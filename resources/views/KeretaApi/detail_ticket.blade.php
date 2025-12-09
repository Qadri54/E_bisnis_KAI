<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran - KAI Access</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        kai: {
                            blue: '#2D3E98',
                            darkblue: '#1A2C75',
                            orange: '#F26522',
                            light: '#F8F9FA',
                            gray: '#757575',
                            border: '#E0E0E0'
                        }
                    },
                    fontFamily: {
                        sans: ['Roboto', 'sans-serif'],
                    },
                    boxShadow: {
                        'card': '0 4px 15px rgba(0,0,0,0.05)',
                        'sticky': '0 4px 20px rgba(0,0,0,0.08)'
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F5F7FA; }

        /* Smooth Accordion */
        .accordion-content {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }
        .accordion-content.active {
            max-height: 1000px; /* Cukup besar untuk menampung konten */
            opacity: 1;
            padding-bottom: 1.5rem; /* pb-6 equivalent */
        }

        /* Custom Radio */
        input[type="radio"]:checked + div {
            border-color: #F26522;
            background-color: #FFF5F2;
        }
        input[type="radio"]:checked + div .radio-circle {
            border-color: #F26522;
            background-color: #F26522;
            box-shadow: inset 0 0 0 3px white;
        }
    </style>
</head>

<body class="font-sans text-gray-700 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-40 bg-white shadow-sm h-[72px]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ asset('storage/assets/logo.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/e/e0/Kereta_Api_Indonesia_logo.svg/2560px-Kereta_Api_Indonesia_logo.svg.png'" class="h-8 w-auto" alt="KAI Logo">
                <div class="h-8 w-px bg-gray-200 hidden md:block"></div>
                <div class="hidden md:flex flex-col">
                    <span class="font-bold text-lg text-kai-darkblue leading-none">Pembayaran</span>
                    <span class="text-[10px] text-gray-500 mt-0.5">Selesaikan transaksi Anda</span>
                </div>
            </div>

            <!-- Kode Booking Badge -->
            <div class="bg-blue-50 border border-blue-100 px-4 py-1.5 rounded-full flex items-center gap-2">
                <span class="text-xs text-gray-500 uppercase font-bold">Kode Booking</span>
                <span class="text-sm font-bold text-kai-blue font-mono tracking-wider">{{ $booking->kode_booking }}</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8 mt-[72px] max-w-6xl">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- LEFT COLUMN: Payment Methods & Timer -->
            <div class="lg:col-span-2 space-y-6">

                <!-- TIMER ALERT -->
                <div class="bg-gradient-to-r from-orange-50 to-white border border-orange-100 rounded-xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-kai-orange"></div>

                    <div class="flex items-center gap-4 z-10">
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-kai-orange shrink-0 animate-pulse">
                            <i class="fas fa-stopwatch text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Sisa Waktu Pembayaran</h3>
                            <p class="text-xs text-gray-500">Selesaikan sebelum <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($booking->created_at)->addHour()->format('H:i') }} WIB</span></p>
                        </div>
                    </div>

                    <div class="text-3xl font-black text-kai-orange font-mono z-10 tabular-nums" id="countdown">
                        00:59:59
                    </div>
                </div>

                <!-- PAYMENT METHODS CARD -->
                <div class="bg-white rounded-xl shadow-card overflow-hidden border border-gray-100">
                    <div class="bg-kai-blue px-6 py-4 border-b border-blue-800">
                        <h2 class="text-white font-bold text-lg flex items-center gap-3">
                            <i class="fas fa-wallet"></i> Metode Pembayaran
                        </h2>
                    </div>

                    <form id="payment-form" action="{{ route('checkout_process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">
                        <input type="hidden" name="action" value="pay"> <!-- Default action -->

                        <!-- QRIS -->
                        <div class="border-b border-gray-100">
                            <button type="button" onclick="toggleAccordion('qris')" class="w-full px-6 py-5 flex items-center justify-between hover:bg-gray-50 transition group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 group-hover:text-kai-blue group-hover:bg-blue-50 transition">
                                        <i class="fas fa-qrcode text-xl"></i>
                                    </div>
                                    <span class="font-bold text-gray-700 group-hover:text-kai-blue text-lg">QRIS</span>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" id="icon-qris"></i>
                            </button>
                            <div id="content-qris" class="accordion-content bg-gray-50 px-6">
                                <label class="relative cursor-pointer block group">
                                    <input type="radio" name="payment_method" value="qris" class="peer sr-only">
                                    <div class="p-4 bg-white border border-gray-200 rounded-xl flex items-center justify-between transition-all hover:border-kai-orange/50">
                                        <div class="flex items-center gap-4">
                                            <div class="radio-circle w-5 h-5 rounded-full border-2 border-gray-300 transition-colors"></div>
                                            <div>
                                                <span class="font-bold text-gray-800 block">QRIS (Instant)</span>
                                                <span class="text-xs text-gray-500">Gopay, OVO, Dana, LinkAja, ShopeePay</span>
                                            </div>
                                        </div>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1200px-Logo_QRIS.svg.png" class="h-6 w-auto" alt="QRIS">
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Virtual Account -->
                        <div class="border-b border-gray-100">
                            <button type="button" onclick="toggleAccordion('va')" class="w-full px-6 py-5 flex items-center justify-between hover:bg-gray-50 transition group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 group-hover:text-kai-blue group-hover:bg-blue-50 transition">
                                        <i class="fas fa-university text-xl"></i>
                                    </div>
                                    <span class="font-bold text-gray-700 group-hover:text-kai-blue text-lg">Virtual Account</span>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" id="icon-va"></i>
                            </button>
                            <div id="content-va" class="accordion-content bg-gray-50 px-6 space-y-3">

                                <label class="relative cursor-pointer block">
                                    <input type="radio" name="payment_method" value="mandiri" class="peer sr-only">
                                    <div class="p-4 bg-white border border-gray-200 rounded-xl flex items-center justify-between transition-all hover:border-kai-orange/50">
                                        <div class="flex items-center gap-4">
                                            <div class="radio-circle w-5 h-5 rounded-full border-2 border-gray-300 transition-colors"></div>
                                            <span class="font-bold text-gray-800">Mandiri Virtual Account</span>
                                        </div>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/2560px-Bank_Mandiri_logo_2016.svg.png" class="h-4 w-auto" alt="Mandiri">
                                    </div>
                                </label>

                                <label class="relative cursor-pointer block">
                                    <input type="radio" name="payment_method" value="bni" class="peer sr-only">
                                    <div class="p-4 bg-white border border-gray-200 rounded-xl flex items-center justify-between transition-all hover:border-kai-orange/50">
                                        <div class="flex items-center gap-4">
                                            <div class="radio-circle w-5 h-5 rounded-full border-2 border-gray-300 transition-colors"></div>
                                            <span class="font-bold text-gray-800">BNI Virtual Account</span>
                                        </div>
                                        <img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png" class="h-4 w-auto" alt="BNI">
                                    </div>
                                </label>

                                <label class="relative cursor-pointer block">
                                    <input type="radio" name="payment_method" value="bri" class="peer sr-only">
                                    <div class="p-4 bg-white border border-gray-200 rounded-xl flex items-center justify-between transition-all hover:border-kai-orange/50">
                                        <div class="flex items-center gap-4">
                                            <div class="radio-circle w-5 h-5 rounded-full border-2 border-gray-300 transition-colors"></div>
                                            <span class="font-bold text-gray-800">BRI Virtual Account</span>
                                        </div>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/1280px-BANK_BRI_logo.svg.png" class="h-4 w-auto" alt="BRI">
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- E-Wallet -->
                        <div>
                            <button type="button" onclick="toggleAccordion('ewallet')" class="w-full px-6 py-5 flex items-center justify-between hover:bg-gray-50 transition group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 group-hover:text-kai-blue group-hover:bg-blue-50 transition">
                                        <i class="fas fa-mobile-alt text-xl"></i>
                                    </div>
                                    <span class="font-bold text-gray-700 group-hover:text-kai-blue text-lg">E-Wallet</span>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" id="icon-ewallet"></i>
                            </button>
                            <div id="content-ewallet" class="accordion-content bg-gray-50 px-6">
                                <label class="relative cursor-pointer block">
                                    <input type="radio" name="payment_method" value="linkaja" class="peer sr-only">
                                    <div class="p-4 bg-white border border-gray-200 rounded-xl flex items-center justify-between transition-all hover:border-kai-orange/50">
                                        <div class="flex items-center gap-4">
                                            <div class="radio-circle w-5 h-5 rounded-full border-2 border-gray-300 transition-colors"></div>
                                            <span class="font-bold text-gray-800">LinkAja</span>
                                        </div>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/LinkAja.svg/1200px-LinkAja.svg.png" class="h-6 w-auto" alt="LinkAja">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Cancel Button -->
                <form id="cancel-form" action="{{ route('checkout_process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">
                    <input type="hidden" name="action" value="cancel">

                    <button type="submit" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')" class="w-full py-4 text-red-500 font-bold text-sm bg-red-50 hover:bg-red-100 rounded-xl transition border border-red-100 flex items-center justify-center gap-2">
                        <i class="far fa-trash-alt"></i> Batalkan Pesanan
                    </button>
                </form>

            </div>

            <!-- RIGHT COLUMN: Sticky Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-4">

                    <div class="bg-white rounded-xl shadow-sticky overflow-hidden border border-gray-100">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="font-bold text-lg text-gray-800">Rincian Pesanan</h2>
                        </div>

                        <div class="p-5">
                            <!-- Train Details -->
                            <div class="flex items-start gap-4 mb-6">
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-kai-blue shrink-0">
                                    <i class="fas fa-train text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800">{{ $booking->ticket->kereta->nama_kereta }}</h3>
                                    <div class="text-xs text-gray-500 mt-1 bg-gray-100 px-2 py-0.5 rounded inline-block">Ekonomi (C)</div>
                                </div>
                            </div>

                            <!-- Route Visual -->
                            <div class="relative pl-4 space-y-6 mb-6">
                                <div class="absolute left-[5px] top-2 bottom-2 w-0.5 bg-gray-200"></div>

                                <div class="relative">
                                    <div class="absolute -left-[15px] top-1.5 w-2.5 h-2.5 rounded-full bg-kai-blue ring-4 ring-white"></div>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold mb-0.5">Keberangkatan</p>
                                    <p class="font-bold text-gray-800 text-lg leading-none">{{ \Carbon\Carbon::parse($booking->ticket->jadwalKereta->jadwal_keberangkatan)->format('H:i') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($booking->ticket->jadwalKereta->jadwal_keberangkatan)->format('d M Y') }}</p>
                                    <p class="text-sm font-medium text-kai-darkblue mt-1">{{ $booking->ticket->destinasi->destinasi_asal }}</p>
                                </div>

                                <div class="relative">
                                    <div class="absolute -left-[15px] top-1.5 w-2.5 h-2.5 rounded-full bg-kai-orange ring-4 ring-white"></div>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold mb-0.5">Tiba</p>
                                    <p class="font-bold text-gray-800 text-lg leading-none">{{ \Carbon\Carbon::parse($booking->ticket->jadwalKereta->jadwal_sampai)->format('H:i') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($booking->ticket->jadwalKereta->jadwal_sampai)->format('d M Y') }}</p>
                                    <p class="text-sm font-medium text-kai-darkblue mt-1">{{ $booking->ticket->destinasi->destinasi_tujuan }}</p>
                                </div>
                            </div>

                            <!-- Cost Breakdown -->
                            <div class="bg-blue-50/50 rounded-lg p-4 space-y-3 mb-6 border border-blue-100">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Harga Tiket</span>
                                    <span>Rp {{ number_format($booking->ticket->harga_tiket, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Jumlah Penumpang</span>
                                    <span>{{ $booking->banyak_tiket }} Orang</span>
                                </div>
                                <div class="border-t border-dashed border-blue-200 my-2"></div>
                                <div class="flex justify-between items-end">
                                    <span class="font-bold text-gray-800">Total Tagihan</span>
                                    <span class="font-bold text-2xl text-kai-orange">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Pay Button -->
                            <button onclick="submitPayment()" class="w-full bg-gradient-to-r from-kai-orange to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-orange-500/40 transition-all duration-300 transform active:scale-[0.98] flex items-center justify-center gap-2 group">
                                <i class="fas fa-lock text-sm group-hover:animate-pulse"></i> Bayar Sekarang
                            </button>
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="text-[10px] text-gray-400">
                            <i class="fas fa-shield-alt mr-1"></i> Pembayaran Anda dijamin aman
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <script>
        // Timer Logic
        // Calculate remaining time based on server time vs created_at + 1 hour
        const endTime = new Date("{{ \Carbon\Carbon::parse($booking->created_at)->addHour()->toIso8601String() }}").getTime();

        function updateTimer() {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                document.getElementById("countdown").innerHTML = "00:00:00";
                handleTimeout();
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("countdown").innerHTML =
                (hours < 10 ? "0" + hours : hours) + ":" +
                (minutes < 10 ? "0" + minutes : minutes) + ":" +
                (seconds < 10 ? "0" + seconds : seconds);
        }

        setInterval(updateTimer, 1000);
        updateTimer(); // Initial call

        function handleTimeout() {
            fetch('{{ route("checkout_process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    booking_id: {{ $booking->booking_id }},
                    action: 'timeout'
                })
            }).then(res => {
                if (res.ok) {
                    alert('Waktu pembayaran habis! Pesanan dibatalkan otomatis.');
                    window.location.href = "{{ url('/') }}";
                }
            });
        }

        // Accordion Logic
        function toggleAccordion(id) {
            // Close active ones
            document.querySelectorAll('.accordion-content').forEach(el => {
                if (el.id !== `content-${id}`) {
                    el.classList.remove('active');
                    // Reset styling of other buttons if needed
                }
            });

            // Rotate icons back
            document.querySelectorAll('.fa-chevron-down').forEach(icon => {
                if (icon.id !== `icon-${id}`) icon.style.transform = 'rotate(0deg)';
            });

            const content = document.getElementById(`content-${id}`);
            const icon = document.getElementById(`icon-${id}`);

            content.classList.toggle('active');
            icon.style.transform = content.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
        }

        // Payment Submission
        function submitPayment() {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

            if (!selectedPayment) {
                alert('Silakan pilih metode pembayaran terlebih dahulu.');
                // Highlight accordion section visual hint could be added here
                return;
            }

            if(confirm('Lanjutkan pembayaran sebesar Rp {{ number_format($booking->total_harga, 0, ',', '.') }}?')) {
                document.getElementById('payment-form').submit();
            }
        }
    </script>
</body>
</html>
