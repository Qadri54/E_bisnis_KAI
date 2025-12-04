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

        // Query departure tickets
        // Origin -> Destination (e.g., Medan -> Binjai)
        $departure_tickets = Ticket::with(['jadwalKereta', 'kereta', 'destinasi'])
            ->whereHas('destinasi', function($query) use ($validated) {
                $query->where('destinasi_asal', $validated['origin'])
                      ->where('destinasi_tujuan', $validated['destination']);
            })
            ->whereHas('jadwalKereta', function($query) use ($validated) {
                $query->whereDate('jadwal_keberangkatan', $validated['departure_date']);
            })
            ->where('stok_tiket', '>=', $validated['passengers'])
            ->get();

        // Query return tickets if return_date is provided
        // For return: we need to find tickets where:
        // - destinasi_asal = user's selected destination (Binjai)
        // - destinasi_tujuan = user's selected origin (Medan)
        $return_tickets = collect();
        if (!empty($validated['return_date'])) {
            $return_tickets = Ticket::with(['jadwalKereta', 'kereta', 'destinasi'])
                ->whereHas('destinasi', function($query) use ($validated) {
                    // Flip: destination becomes origin, origin becomes destination
                    $query->where('destinasi_asal', $validated['destination'])
                          ->where('destinasi_tujuan', $validated['origin']);
                })
                ->whereHas('jadwalKereta', function($query) use ($validated) {
                    $query->whereDate('jadwal_keberangkatan', $validated['return_date']);
                })
                ->where('stok_tiket', '>=', $validated['passengers'])
                ->get();
        }

        return view('KeretaApi.list_ticket', [
            'departure_tickets' => $departure_tickets,
            'return_tickets' => $return_tickets,
            'search_params' => $validated
        ]);
    }

    public function show_detail_ticket($id_tiket){
        $ticket = Ticket::with(['jadwalKereta', 'kereta', 'destinasi'])
            ->findOrFail($id_tiket);

        return view('KeretaApi.detail_ticket', [
            'ticket' => $ticket
        ]);
    }

    public function show_tutor_pembayaran(Request $request){
        return view('KeretaApi.tutor_pembayaran');
    }
}
