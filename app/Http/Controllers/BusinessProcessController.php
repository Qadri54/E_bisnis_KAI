<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; // Pastikan model Booking ada
use App\Models\Ticket;  // Pastikan model Ticket ada
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;


class BusinessProcessController extends Controller {

    public function checkout_process(Request $request) {
        // 1. Validasi Request dari Form
        $request->validate([
            // 'booking_id' => 'required|exists:booking,booking_id',
            'action' => 'required|in:pay,cancel,timeout',
            // Jika action 'pay', wajib ada payment_method
            // 'payment_method' => 'required_if:action,pay|string'
        ]);

        // dd($request->all());

        // 2. Ambil Data Booking
        // dd($request->booking_id);
        $booking = Booking::with(['ticket.kereta', 'ticket.destinasi', 'user'])
            ->findOrFail($request->booking_id);

        // --- SKENARIO 1: USER KLIK "BAYAR SEKARANG" ---
        if ($request->action == 'pay') {

            // A. Update Status menjadi PAID (Lunas)
            // Di sistem nyata, ini biasanya dilakukan via Callback Payment Gateway (Midtrans/Xendit)
            // Tapi untuk simulasi, kita langsung update jadi paid.
            $booking->update([
                'status' => 'success',
                'updated_at' => now()
            ]);
            
            $booking->update(['status' => 'success']);
            return view('KeretaApi.resi_pdf', compact('booking'))->with('success', 'Pembayaran berhasil! Silakan unduh E-Ticket Anda.');


            // B. Generate PDF Resi / E-Ticket
            // Pastikan view 'KeretaApi.resi_pdf' sudah Anda buat (seperti di percakapan sebelumnya)
            $pdf = Pdf::loadView('KeretaApi.resi_pdf', ['booking' => $booking]);
            $pdf->setPaper('A4', 'portrait');
            // $pdf->setOptions([
            //     'isRemoteEnabled' => true,
            //     'chroot' => public_path()
            // ]);

            // C. Download PDF otomatis ke browser user
            return $pdf->download('E-Ticket-KAI-' . $booking->kode_booking . '.pdf');
        }

        // --- SKENARIO 2: USER KLIK "BATALKAN PESANAN" ---
        if ($request->action == 'cancel') {
            $booking->update(['status' => 'canceled']);
            return redirect('/')->with('error', 'Pesanan berhasil dibatalkan.');
        }

        // --- SKENARIO 3: TIMER HABIS (AJAX) ---
        if ($request->action == 'timeout') {
            $booking->update(['status' => 'time_out']);
            return response()->json(['status' => 'success', 'message' => 'Booking expired']);
        }
    }

    public function booking_process(Request $request) {
        // 1. Validasi Input
        $request->validate([
            'departure_ticket' => 'required|exists:ticket,ticket_id',
            'passengers' => 'required|integer|min:1',
        ]);

        // 2. Ambil Data Tiket untuk Hitung Harga
        $ticket = Ticket::findOrFail($request->departure_ticket);

        // Hitung Total Harga
        $totalPrice = $ticket->harga_tiket * $request->passengers;

        // 3. Simpan ke Database (Tabel Booking)
        // Menggunakan User ID yang sedang login
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticket->ticket_id,
            'banyak_tiket' => $request->passengers,
            'status' => 'waiting', // Default status sesuai request
            'total_harga' => $totalPrice,
            'kode_booking' => 'KB-' . strtoupper(Str::random(6)), // Generate kode unik
            'tanggal_booking' => now(),
        ]);

        // Jika ada tiket pulang (return_ticket), logikanya bisa ditambahkan di sini
        // dengan membuat row booking kedua.

        // 4. Return View Pembayaran dengan membawa data booking_id (atau object booking)
        return view('KeretaApi.pembayaran', [
            'booking' => $booking,
            'ticket' => $ticket
        ]);
    }
}
