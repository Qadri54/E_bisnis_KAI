<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Hasil Pencarian</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen">


    <!-- Results -->
    <main class="container mx-auto px-6 py-8">
        @if($tickets->count() > 0)
            <div class="mb-6">
                <h2 class="text-2xl font-bold dark:text-[#EDEDEC]">Tiket Tersedia ({{ $tickets->count() }})</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">Pilih kereta yang sesuai dengan perjalanan Anda</p>
            </div>

            <div class="space-y-4">
                @foreach($tickets as $ticket)
                    <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-6 hover:shadow-lg transition-shadow">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Train Info -->
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold dark:text-[#EDEDEC] mb-2">
                                    {{ $ticket->kereta->nama_kereta }}
                                </h3>
                                <div class="flex items-center gap-6 text-sm">
                                    <div>
                                        <p class="text-[#706f6c] dark:text-[#A1A09A]">Berangkat</p>
                                        <p class="font-medium dark:text-[#EDEDEC]">
                                            {{ $ticket->jadwalKereta->jadwal_keberangkatan->format('H:i') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="h-px bg-[#e3e3e0] dark:bg-[#3E3E3A] w-12"></div>
                                        <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[#706f6c] dark:text-[#A1A09A]">Tiba</p>
                                        <p class="font-medium dark:text-[#EDEDEC]">
                                            {{ $ticket->jadwalKereta->jadwal_sampai->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center gap-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ $ticket->stok_tiket }} kursi tersisa
                                    </span>
                                </div>
                            </div>

                            <!-- Route Info -->
                            <div class="flex-1 lg:text-center">
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Rute</p>
                                <p class="font-medium dark:text-[#EDEDEC]">
                                    {{ $ticket->destinasi->destinasi_asal }} → {{ $ticket->destinasi->destinasi_tujuan }}
                                </p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">
                                    Durasi: 
                                    @php
                                        $duration = $ticket->jadwalKereta->jadwal_keberangkatan->diff($ticket->jadwalKereta->jadwal_sampai);
                                    @endphp
                                    {{ $duration->h }}j {{ $duration->i }}m
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Results -->
            <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-xl font-semibold dark:text-[#EDEDEC] mb-2">Tidak Ada Tiket Tersedia</h3>
                <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6">
                    Maaf, tidak ada kereta yang tersedia untuk rute dan tanggal yang Anda pilih.
                </p>
                <a href="{{ route('home') }}" 
                   class="inline-block px-6 py-3 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1b1b18] rounded-sm font-medium hover:bg-black dark:hover:bg-white transition-all duration-300">
                    Cari Lagi
                </a>
            </div>
        @endif
    </main>
</body>
</html>