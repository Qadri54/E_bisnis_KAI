<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoutingPageController extends Controller
{
    public function index(){
        return view('home_page');
    }

    public function show_list_ticket($stasiun_awal, $stasiun_akhir, $tanggal_berangkat, $banyak_penumpang = 1){
        // buat logika untuk menampilkan list tiket berdasarkan parameter
        return view('list_ticket');
    }

    public function show_detail_ticket($id_tiket){
        // buat logika untuk menampilkan detail tiket berdasarkan id_tiket
        return view('detail_ticket');
    }

    public function show_tutor_pembayaran(Request $request){
        return view('tutor_pembayaran');
    }
}
