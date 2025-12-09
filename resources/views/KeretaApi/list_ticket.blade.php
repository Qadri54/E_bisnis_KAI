<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pilih Tiket - KAI Access</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"
            rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Tailwind CSS (CDN) -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Custom Config (Sama dengan Welcome) -->
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
                                gray: '#BDBDBD'
                            }
                        },
                        fontFamily: {
                            sans: ['Roboto', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
        <style>
            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: #2D3E98;
                border-radius: 4px;
            }

            .ticket-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* Selected State Styling */
            .ticket-selected {
                border: 2px solid #F26522 !important;
                /* KAI Orange */
                background-color: #fff7ed !important;
                /* Orange-50 */
                box-shadow: 0 4px 6px -1px rgba(242, 101, 34, 0.1), 0 2px 4px -1px rgba(242, 101, 34, 0.06);
                transform: translateY(-2px);
            }

            /* Checkmark icon for selected ticket */
            .ticket-selected .select-indicator {
                background-color: #F26522;
                border-color: #F26522;
                color: white;
            }

            .modal {
                display: none;
                transition: opacity 0.3s ease;
            }

            .modal.active {
                display: flex;
                animation: fadeIn 0.2s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }
        </style>
    </head>

    <body class="font-sans text-gray-700 bg-gray-50 flex flex-col min-h-screen">

        <!-- Navbar -->
        <nav class="fixed top-0 w-full z-40 bg-white shadow-sm transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-[72px] items-center">
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <a href="{{ url('/') }}" class="flex items-center gap-3">
                            <img class="h-10 w-auto" src="{{ asset('storage/assets/logo.png') }}"
                                onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/e/e0/Kereta_Api_Indonesia_logo.svg/2560px-Kereta_Api_Indonesia_logo.svg.png'"
                                alt="KAI Logo">
                        </a>
                    </div>
                    <div class="hidden md:flex items-center space-x-1">
                        @auth
                            <span class="text-sm font-medium text-gray-600">Hi, {{ Auth::user()->name }}</span>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-kai-blue hover:text-kai-orange font-bold text-sm uppercase px-4 py-2 transition">Masuk</a>
                            <a href="{{ route('register') }}"
                                class="ml-2 px-6 py-2 rounded-full border-2 border-kai-blue text-kai-blue hover:bg-kai-blue hover:text-white transition duration-300 font-bold text-sm uppercase">Daftar</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Search Summary Header (Blue Bar) -->
        <div class="pt-[72px]">
            <div class="bg-gradient-to-r from-kai-darkblue to-kai-blue text-white shadow-md">
                <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <div class="flex items-center gap-3 text-lg font-bold">
                                <span>{{ $search_params['origin'] ?? 'Origin' }}</span>
                                <i class="fas fa-arrow-right text-kai-orange text-sm"></i>
                                <span>{{ $search_params['destination'] ?? 'Destination' }}</span>
                            </div>
                            <div class="text-sm text-gray-200 mt-1 flex items-center gap-4">
                                <span><i class="far fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::parse($search_params['departure_date'])->format('D, d M Y') }}</span>
                                <span><i class="fas fa-user mr-1"></i> {{ $search_params['passengers'] }}
                                    Penumpang</span>
                            </div>
                        </div>
                        <a href="{{ route('home') }}"
                            class="text-xs bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full transition">
                            Ubah Pencarian
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-8 max-w-7xl">
            <div class="grid grid-cols-1 {{ !empty($search_params['return_date']) ? 'lg:grid-cols-2' : '' }} gap-8">

                <!-- DEPARTURE SECTION -->
                <section id="departure-section" class="flex flex-col gap-4">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-xl font-bold text-kai-darkblue flex items-center gap-2">
                            <span
                                class="bg-kai-blue text-white w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
                            Pergi
                        </h2>
                        <span class="text-sm text-gray-500 font-medium">{{ $departure_tickets->count() }} Pilihan
                            Tiket</span>
                    </div>

                    @if($departure_tickets->count() > 0)
                        <div class="space-y-4">
                            @foreach($departure_tickets as $ticket)
                                <div class="ticket-card relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 cursor-pointer group"
                                    data-ticket-id="{{ $ticket->ticket_id }}" data-ticket-type="departure"
                                    data-ticket-price="{{ $ticket->harga_tiket }}" onclick="selectTicket(this, 'departure')">

                                    <div class="p-5">
                                        <div class="flex justify-between items-start mb-4">
                                            <!-- Train Info -->
                                            <div class="flex items-center gap-3">
                                                <div class="text-2xl text-kai-orange"><i class="fas fa-train"></i></div>
                                                <div>
                                                    <div class="font-bold text-gray-800 text-lg">
                                                        {{ $ticket->kereta->nama_kereta }}</div>
                                                    <div
                                                        class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded inline-block">
                                                        Ekonomi</div>
                                                </div>
                                            </div>
                                            <!-- Price -->
                                            <div class="text-right">
                                                <div class="font-bold text-kai-orange text-xl">Rp
                                                    {{ number_format($ticket->harga_tiket, 0, ',', '.') }}</div>
                                                <div class="text-xs text-gray-400">/org</div>
                                            </div>
                                        </div>

                                        <!-- Schedule Info -->
                                        <div class="flex items-center justify-between text-sm mb-4">
                                            <div class="text-center">
                                                <div class="font-bold text-lg text-gray-800">
                                                    {{ $ticket->jadwalKereta->jadwal_keberangkatan->format('H:i') }}</div>
                                                <div class="text-xs text-gray-500">{{ $ticket->destinasi->destinasi_asal }}
                                                </div>
                                            </div>

                                            <div class="flex-1 px-4 flex flex-col items-center">
                                                <div class="text-xs text-gray-400 mb-1">
                                                    {{ $ticket->jadwalKereta->jadwal_keberangkatan->diff($ticket->jadwalKereta->jadwal_sampai)->format('%hj %im') }}
                                                </div>
                                                <div class="w-full h-px bg-gray-300 relative">
                                                    <div class="absolute -top-1 right-0 text-gray-300"><i
                                                            class="fas fa-chevron-right text-xs"></i></div>
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <div class="font-bold text-lg text-gray-800">
                                                    {{ $ticket->jadwalKereta->jadwal_sampai->format('H:i') }}</div>
                                                <div class="text-xs text-gray-500">{{ $ticket->destinasi->destinasi_tujuan }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Footer Card -->
                                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                            <div
                                                class="text-xs {{ $ticket->stok_tiket < 20 ? 'text-red-500' : 'text-green-600' }} font-medium flex items-center gap-1">
                                                <i class="fas fa-chair"></i> Tersedia {{ $ticket->stok_tiket }} Kursi
                                            </div>
                                            <!-- Selection Indicator Circle -->
                                            <div
                                                class="select-indicator w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center text-xs transition-colors">
                                                <i class="fas fa-check opacity-0 group-[.ticket-selected]:opacity-100"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-xl border border-gray-200 p-10 text-center shadow-sm">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-2xl">
                                <i class="fas fa-train-slash"></i>
                            </div>
                            <h3 class="font-bold text-gray-800 mb-2">Tiket Tidak Ditemukan</h3>
                            <p class="text-sm text-gray-500 mb-6">Tidak ada jadwal kereta tersedia untuk rute dan tanggal
                                ini.</p>
                            <a href="{{ route('home') }}"
                                class="inline-block px-6 py-2 bg-kai-blue text-white rounded-full text-sm font-medium hover:bg-kai-darkblue transition">
                                Cari Tanggal Lain
                            </a>
                        </div>
                    @endif
                </section>

                <!-- RETURN SECTION (Conditional) -->
                @if(!empty($search_params['return_date']))
                    <section id="return-section"
                        class="flex flex-col gap-4 {{ $return_tickets->count() > 0 && $departure_tickets->count() > 0 ? 'opacity-50 pointer-events-none grayscale' : '' }} transition-all duration-300">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-xl font-bold text-kai-darkblue flex items-center gap-2">
                                <span
                                    class="bg-kai-blue text-white w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
                                Pulang
                            </h2>
                            <span class="text-sm text-gray-500 font-medium">{{ $return_tickets->count() }} Pilihan
                                Tiket</span>
                        </div>

                        @if($departure_tickets->count() > 0 && $return_tickets->count() > 0)
                            <div id="return-instruction"
                                class="bg-blue-50 text-blue-700 text-sm p-3 rounded-lg border border-blue-100 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i> Silakan pilih tiket keberangkatan terlebih dahulu.
                            </div>
                        @endif

                        @if($return_tickets->count() > 0)
                            <div class="space-y-4">
                                @foreach($return_tickets as $ticket)
                                    <div class="ticket-card relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 cursor-pointer group"
                                        data-ticket-id="{{ $ticket->ticket_id }}" data-ticket-type="return"
                                        data-ticket-price="{{ $ticket->harga_tiket }}" onclick="selectTicket(this, 'return')">

                                        <div class="p-5">
                                            <div class="flex justify-between items-start mb-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="text-2xl text-kai-orange"><i class="fas fa-train"></i></div>
                                                    <div>
                                                        <div class="font-bold text-gray-800 text-lg">
                                                            {{ $ticket->kereta->nama_kereta }}</div>
                                                        <div
                                                            class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded inline-block">
                                                            Ekonomi</div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="font-bold text-kai-orange text-xl">Rp
                                                        {{ number_format($ticket->harga_tiket, 0, ',', '.') }}</div>
                                                    <div class="text-xs text-gray-400">/org</div>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between text-sm mb-4">
                                                <div class="text-center">
                                                    <div class="font-bold text-lg text-gray-800">
                                                        {{ $ticket->jadwalKereta->jadwal_keberangkatan->format('H:i') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $ticket->destinasi->destinasi_asal }}
                                                    </div>
                                                </div>
                                                <div class="flex-1 px-4 flex flex-col items-center">
                                                    <div class="text-xs text-gray-400 mb-1">
                                                        {{ $ticket->jadwalKereta->jadwal_keberangkatan->diff($ticket->jadwalKereta->jadwal_sampai)->format('%hj %im') }}
                                                    </div>
                                                    <div class="w-full h-px bg-gray-300 relative">
                                                        <div class="absolute -top-1 right-0 text-gray-300"><i
                                                                class="fas fa-chevron-right text-xs"></i></div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="font-bold text-lg text-gray-800">
                                                        {{ $ticket->jadwalKereta->jadwal_sampai->format('H:i') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $ticket->destinasi->destinasi_tujuan }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                                <div
                                                    class="text-xs {{ $ticket->stok_tiket < 20 ? 'text-red-500' : 'text-green-600' }} font-medium flex items-center gap-1">
                                                    <i class="fas fa-chair"></i> Tersedia {{ $ticket->stok_tiket }} Kursi
                                                </div>
                                                <div
                                                    class="select-indicator w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center text-xs transition-colors">
                                                    <i class="fas fa-check opacity-0 group-[.ticket-selected]:opacity-100"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- No Return Ticket UI -->
                            <div class="bg-white rounded-xl border border-gray-200 p-10 text-center shadow-sm">
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-2xl">
                                    <i class="fas fa-train-slash"></i>
                                </div>
                                <h3 class="font-bold text-gray-800 mb-2">Tiket Pulang Tidak Ditemukan</h3>
                                <a href="{{ route('home') }}" class="text-kai-blue font-bold text-sm underline">Cari Ulang</a>
                            </div>
                        @endif
                    </section>
                @endif

            </div>
        </main>

        <!-- Floating Continue Button -->
        <div id="continue-btn"
            class="hidden fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-4 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] z-30 animate-slide-up">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div>
                    <div class="text-xs text-gray-500 uppercase tracking-wide">Total Estimasi</div>
                    <div id="total-price" class="text-2xl font-bold text-kai-orange">Rp 0</div>
                </div>
                <button onclick="showLoginModal()"
                    class="bg-gradient-to-r from-kai-orange to-orange-500 hover:from-orange-600 hover:to-orange-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-transform active:scale-95">
                    Lanjutkan Pemesanan <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Login/Register Modal Option -->
        <div id="login-modal"
            class="modal fixed inset-0 z-50 bg-black/60 backdrop-blur-sm items-center justify-center p-4">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-100">
                <!-- Modal Header -->
                <div class="bg-kai-light p-6 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Lanjutkan Pemesanan</h2>
                        <p class="text-sm text-gray-500 mt-1">Pilih metode untuk melanjutkan</p>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Booking Summary inside Modal -->
                <div class="p-6 bg-blue-50/50 border-b border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Total Pembayaran</span>
                        <span class="font-bold text-kai-orange text-lg" id="modal-total">Rp 0</span>
                    </div>
                    <div class="text-xs text-gray-500 flex gap-2">
                        <span class="bg-white px-2 py-1 rounded border border-gray-200"><i class="fas fa-user mr-1"></i>
                            <span id="modal-passengers">{{ $search_params['passengers'] }}</span> Penumpang</span>
                        <span class="bg-white px-2 py-1 rounded border border-gray-200"><i
                                class="fas fa-ticket-alt mr-1"></i> <span id="modal-tickets-info">Sekali
                                Jalan</span></span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="p-6 space-y-4">
                    <button onclick="goToLogin()"
                        class="w-full bg-kai-blue hover:bg-kai-darkblue text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-3 shadow-md group">
                        <i class="fas fa-sign-in-alt group-hover:translate-x-1 transition"></i> Login / Daftar Akun
                    </button>

                    <div class="relative flex py-1 items-center">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="flex-shrink-0 mx-4 text-gray-400 text-xs">ATAU</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    <button onclick="continueAsGuest()"
                        class="w-full bg-white border-2 border-gray-200 hover:border-kai-orange hover:text-kai-orange text-gray-600 font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-3">
                        <i class="fas fa-user-clock"></i> Lanjut sebagai Tamu
                    </button>
                </div>
            </div>
        </div>

        <!-- Javascript Logic (Sama Persis dengan sebelumnya, hanya penyesuaian kelas CSS) -->
        <script>
            let selected = { departure: null, return: null };

            function selectTicket(el, type) {
                const id = el.getAttribute('data-ticket-id');
                const section = document.getElementById(`${type}-section`);

                // Remove selected state from siblings
                section.querySelectorAll('.ticket-card').forEach(card => {
                    card.classList.remove('ticket-selected');
                    // Reset checkmark icon visual if needed via JS (though CSS handles it mostly)
                });

                // Add selected state
                el.classList.add('ticket-selected');
                selected[type] = id;

                // Handle Return Section visibility/instruction
                if (type === 'departure') {
                    const returnSection = document.getElementById('return-section');
                    if (returnSection) {
                        returnSection.classList.remove('opacity-50', 'pointer-events-none', 'grayscale');
                        const instruction = document.getElementById('return-instruction');
                        if (instruction) {
                            instruction.className = 'bg-green-50 text-green-700 text-sm p-3 rounded-lg border border-green-100 flex items-center gap-2 animate-pulse';
                            instruction.innerHTML = '<i class="fas fa-check-circle"></i> Tiket pergi terpilih. Silakan pilih tiket pulang.';
                        }
                    }
                }

                updateContinueButton();
            }

            function updateContinueButton() {
                const hasReturn = document.getElementById('return-section') !== null;
                const btn = document.getElementById('continue-btn');

                if (selected.departure && (!hasReturn || selected.return)) {
                    btn.classList.remove('hidden');

                    let total = 0;
                    const passengers = {{ $search_params['passengers'] }};

                    const deptCard = document.querySelector(`[data-ticket-id="${selected.departure}"]`);
                    if (deptCard) {
                        total += parseInt(deptCard.getAttribute('data-ticket-price')) * passengers;
                    }

                    if (selected.return) {
                        const retCard = document.querySelector(`[data-ticket-id="${selected.return}"]`);
                        if (retCard) {
                            total += parseInt(retCard.getAttribute('data-ticket-price')) * passengers;
                        }
                    }

                    document.getElementById('total-price').textContent = `Rp ${total.toLocaleString('id-ID')}`;
                } else {
                    btn.classList.add('hidden');
                }
            }

            function showLoginModal() {
                @auth
                    proceedToBooking();
                @else
                    const modal = document.getElementById('login-modal');
                    modal.classList.add('active');
                    updateModalContent();
                    document.body.style.overflow = 'hidden';
                @endauth
        }

            function updateModalContent() {
                const passengers = {{ $search_params['passengers'] }};
                let total = 0;
                const deptCard = document.querySelector(`[data-ticket-id="${selected.departure}"]`);
                if (deptCard) total += parseInt(deptCard.getAttribute('data-ticket-price')) * passengers;

                if (selected.return) {
                    const retCard = document.querySelector(`[data-ticket-id="${selected.return}"]`);
                    if (retCard) total += parseInt(retCard.getAttribute('data-ticket-price')) * passengers;
                }

                document.getElementById('modal-total').textContent = `Rp ${total.toLocaleString('id-ID')}`;
                document.getElementById('modal-passengers').textContent = passengers;
                document.getElementById('modal-tickets-info').textContent = selected.return ? 'Pulang Pergi' : 'Sekali Jalan';
            }

            function closeModal() {
                document.getElementById('login-modal').classList.remove('active');
                document.body.style.overflow = '';
            }

            function continueAsGuest() {
                proceedToBooking();
            }

            function goToLogin() {
                sessionStorage.setItem('pending_booking', JSON.stringify({
                    departure: selected.departure,
                    return: selected.return,
                    passengers: {{ $search_params['passengers'] }},
                    search_params: @json($search_params)
                }));

                // Redirect logic (as before)
                window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(window.location.href);
            }

            function proceedToBooking() {
                const params = new URLSearchParams({
                    departure_ticket: selected.departure,
                    passengers: {{ $search_params['passengers'] }}
            });

                if (selected.return) {
                    params.append('return_ticket', selected.return);
                }

                window.location.href = `/detail-Ticket/${selected.departure}?${params}`;
            }

            // Close modal when clicking outside
            document.getElementById('login-modal')?.addEventListener('click', function (e) {
                if (e.target === this) closeModal();
            });
        </script>
    </body>

</html>