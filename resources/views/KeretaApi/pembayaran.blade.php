<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Pembayaran - KAI Access</title>
        <!-- Fonts & Tailwind -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"
            rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: { extend: { colors: { kai: { blue: '#2D3E98', orange: '#F26522' } } } }
            }
        </script>
        <style>
            .accordion-content {
                transition: all 0.3s ease;
                max-height: 0;
                overflow: hidden;
                opacity: 0;
            }

            .accordion-content.active {
                max-height: 500px;
                opacity: 1;
            }

            input:checked+div {
                border-color: #F26522;
                background-color: #FFF5F2;
            }
        </style>
    </head>

    <body class="bg-gray-100 font-sans text-gray-700">

        <!-- Navbar -->
        <nav class="bg-white shadow-sm h-[72px] fixed w-full top-0 z-50 flex items-center px-4 lg:px-8">
            <div class="max-w-6xl w-full mx-auto flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('storage/assets/logo.png') }}" class="h-8 w-auto" alt="Logo">
                    <span class="font-bold text-kai-blue text-lg border-l pl-4 border-gray-300">Pembayaran</span>
                </div>
                <div class="bg-blue-50 px-3 py-1 rounded text-kai-blue font-mono font-bold text-sm">
                    {{ $booking->kode_booking }}
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-6xl mx-auto px-4 py-8 mt-20 flex flex-col lg:flex-row gap-8">

            <!-- LEFT: Payment Methods -->
            <div class="flex-1 space-y-6">
                <!-- Timer -->
                <div
                    class="bg-gradient-to-r from-orange-50 to-white border border-orange-200 p-4 rounded-xl flex justify-between items-center shadow-sm">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-kai-orange animate-pulse">
                            <i class="fas fa-stopwatch text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">Sisa Waktu</p>
                            <p class="text-sm">Selesaikan sebelum
                                {{ \Carbon\Carbon::parse($booking->created_at)->addHour()->format('H:i') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-2xl font-mono font-bold text-kai-orange" id="countdown">00:59:59</div>
                </div>

                <!-- Payment Form Wrapper -->
                <form id="payment-form" action="{{ route('checkout_process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">
                    <input type="hidden" name="action" value="pay"> <!-- Action PAY -->

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-kai-blue px-6 py-4">
                            <h2 class="text-white font-bold flex items-center gap-2"><i class="fas fa-wallet"></i> Pilih
                                Metode Pembayaran</h2>
                        </div>

                        <!-- QRIS -->
                        <div class="border-b">
                            <button type="button" onclick="toggleAccordion('qris')"
                                class="w-full p-5 flex justify-between items-center hover:bg-gray-50">
                                <span class="font-bold text-gray-700 flex items-center gap-3"><i
                                        class="fas fa-qrcode text-gray-400"></i> QRIS</span>
                                <i class="fas fa-chevron-down text-gray-400" id="icon-qris"></i>
                            </button>
                            <div id="content-qris" class="accordion-content bg-gray-50 px-6 pb-2">
                                <label class="block mb-4 cursor-pointer">
                                    <input type="radio" name="payment_method" value="qris" class="peer sr-only">
                                    <div
                                        class="p-4 bg-white border rounded-xl flex justify-between items-center peer-checked:ring-1 peer-checked:ring-kai-orange">
                                        <div><span class="font-bold block">QRIS</span><span
                                                class="text-xs text-gray-500">Scan via Gopay/OVO/Dana</span></div>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1200px-Logo_QRIS.svg.png"
                                            class="h-6">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- RIGHT: Summary & Pay Button -->
            <div class="lg:w-96">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 sticky top-24 overflow-hidden">
                    <div class="p-5 border-b bg-gray-50">
                        <h3 class="font-bold text-gray-800">Rincian Pembayaran</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Harga</span>
                            <span class="font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Biaya Layanan</span>
                            <span class="font-bold">Gratis</span>
                        </div>
                        <div class="border-t pt-4 flex justify-between items-end">
                            <span class="font-bold text-gray-800">Total Tagihan</span>
                            <span class="font-bold text-2xl text-kai-orange">Rp
                                {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                        </div>

                        <!-- TOMBOL BAYAR SEKARANG -->
                        <!-- Menggunakan onclick JS untuk submit form di sebelah kiri -->
                        <button onclick="submitPayment()"
                            class="w-full bg-kai-orange hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl shadow-md transition transform active:scale-95 flex justify-center items-center gap-2">
                            <i class="fas fa-lock"></i> Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>

        </main>

        <script>
            // Submit Logic
            function submitPayment() {
                // 1. Cek apakah ada radio button yang dipilih di dalam form #payment-form
                const form = document.getElementById('payment-form');
                const selected = form.querySelector('input[name="payment_method"]:checked');

                if (!selected) {
                    alert('Silakan pilih metode pembayaran terlebih dahulu!');

                    // Optional: Scroll ke bagian metode pembayaran untuk memberi tahu user
                    document.querySelector('.accordion-content').parentElement.scrollIntoView({ behavior: 'smooth' });
                    return;
                }

                // 2. Konfirmasi & Submit
                if (confirm('Lanjutkan pembayaran sebesar Rp {{ number_format($booking->total_harga, 0, ',', '.') }}?')) {
                    const btn = document.getElementById('btn-pay');
                    // btn.disabled = false;
                    // btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses...';

                    form.submit();
                }
            }

            // Accordion Logic
            function toggleAccordion(id) {
                document.querySelectorAll('.accordion-content').forEach(el => el.classList.remove('active'));
                document.getElementById('content-' + id).classList.toggle('active');
            }

            // Timer Logic (Simple)
            let time = 3600;
            setInterval(() => {
                const h = Math.floor(time / 3600).toString().padStart(2, '0');
                const m = Math.floor((time % 3600) / 60).toString().padStart(2, '0');
                const s = (time % 60).toString().padStart(2, '0');
                document.getElementById('countdown').innerText = `${h}:${m}:${s}`;
                if (time > 0) time--;
            }, 1000);
        </script>
    </body>

</html>
