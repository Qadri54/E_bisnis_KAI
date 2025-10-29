<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Destinasi;
use App\Models\JadwalKereta;
use App\Models\Kereta;

class RoutingPageController extends Controller
{
    public function index(){
        // Get unique origin and destination stations
        $origins = Destinasi::select('destinasi_asal')
            ->distinct()
            ->orderBy('destinasi_asal')
            ->pluck('destinasi_asal');
        
        $destinations = Destinasi::select('destinasi_tujuan')
            ->distinct()
            ->orderBy('destinasi_tujuan')
            ->pluck('destinasi_tujuan');

        return view('KeretaApi.home_page', compact('origins', 'destinations'));
    }

    public function show_list_ticket(Request $request){
        // Validate the incoming request
        $validated = $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'passengers' => 'required|integer|min:1|max:8'
        ]);

        // Query tickets based on origin, destination, and departure date
        $tickets = Ticket::with(['jadwalKereta', 'kereta', 'destinasi'])
            ->whereHas('destinasi', function($query) use ($validated) {
                $query->where('destinasi_asal', $validated['origin'])
                      ->where('destinasi_tujuan', $validated['destination']);
            })
            ->whereHas('jadwalKereta', function($query) use ($validated) {
                $query->whereDate('jadwal_keberangkatan', $validated['departure_date']);
            })
            ->where('stok_tiket', '>=', $validated['passengers'])
            ->get();

        return view('KeretaApi.list_ticket', [
            'tickets' => $tickets,
            'search_params' => $validated
        ]);
    }

    public function show_detail_ticket($id_tiket){
        // Fetch ticket details with relations
        $ticket = Ticket::with(['jadwalKereta', 'kereta', 'destinasi'])
            ->findOrFail($id_tiket);

        return view('detail_ticket', [
            'ticket' => $ticket
        ]);
    }

    public function show_tutor_pembayaran(Request $request){
        return view('tutor_pembayaran');
    }
}