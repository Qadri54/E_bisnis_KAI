<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List Ticket - KAI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .ticket-selected { 
            border: 3px solid #2563eb;
            background: #eff6ff;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            inset: 0;
            background: rgba(0,0,0,0.7);
        }
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 {{ !empty($search_params['return_date']) ? 'md:grid-cols-2' : '' }} gap-6">
            
            <!-- DEPARTURE -->
            <section id="departure-section">
                <div class="bg-white border-2 border-gray-900 p-4 mb-4">
                    <h2 class="text-lg font-bold uppercase">
                        DEPARTURE ({{ $departure_tickets->count() }})
                    </h2>
                </div>

                @if($departure_tickets->count() > 0)
                    <div class="space-y-3">
                        @foreach($departure_tickets as $ticket)
                            <div class="ticket-card bg-white border-2 border-gray-300 p-4 cursor-pointer hover:border-gray-900 transition"
                                 data-ticket-id="{{ $ticket->ticket_id }}"
                                 data-ticket-type="departure"
                                 data-ticket-price="{{ $ticket->harga_tiket }}"
                                 onclick="selectTicket(this, 'departure')">
                                
                                <div class="flex justify-between mb-3">
                                    <div>
                                        <div class="font-bold text-base">{{ $ticket->kereta->nama_kereta }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ $ticket->destinasi->destinasi_asal }} - {{ $ticket->destinasi->destinasi_tujuan }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-lg">Rp {{ number_format($ticket->harga_tiket, 0, ',', '.') }}</div>
                                        <div class="text-xs text-gray-500">/person</div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center text-sm pt-3 border-t">
                                    <div>
                                        {{ $ticket->jadwalKereta->jadwal_keberangkatan->format('H:i') }}
                                        -
                                        {{ $ticket->jadwalKereta->jadwal_sampai->format('H:i') }}
                                    </div>
                                    <div class="bg-green-100 px-2 py-1 text-xs font-mono">
                                        {{ $ticket->stok_tiket }} SEATS
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white border-2 border-gray-300 p-8 text-center">
                        <div class="text-4xl mb-2">×</div>
                        <div class="font-bold mb-1">NO TICKETS AVAILABLE</div>
                        <div class="text-sm text-gray-600 mb-4">No trains found for selected date</div>
                        <a href="{{ route('home') }}" class="inline-block px-4 py-2 bg-gray-900 text-white">
                            Search Again
                        </a>
                    </div>
                @endif
            </section>

            <!-- RETURN -->
            @if(!empty($search_params['return_date']))
                <section id="return-section" class="{{ $return_tickets->count() > 0 && $departure_tickets->count() > 0 ? 'opacity-40' : '' }}">
                    <div class="bg-white border-2 border-gray-900 p-4 mb-4">
                        <h2 class="text-lg font-bold uppercase">
                            RETURN ({{ $return_tickets->count() }})
                        </h2>
                        @if($departure_tickets->count() > 0 && $return_tickets->count() > 0)
                            <p class="text-xs mt-1" id="return-instruction">
                                Select departure first
                            </p>
                        @endif
                    </div>

                    @if($return_tickets->count() > 0)
                        <div class="space-y-3">
                            @foreach($return_tickets as $ticket)
                                <div class="ticket-card bg-white border-2 border-gray-300 p-4 cursor-pointer hover:border-gray-900 transition"
                                     data-ticket-id="{{ $ticket->ticket_id }}"
                                     data-ticket-type="return"
                                     data-ticket-price="{{ $ticket->harga_tiket }}"
                                     onclick="selectTicket(this, 'return')">
                                    
                                    <div class="flex justify-between mb-3">
                                        <div>
                                            <div class="font-bold text-base">{{ $ticket->kereta->nama_kereta }}</div>
                                            <div class="text-sm text-gray-600">
                                                {{ $ticket->destinasi->destinasi_asal }} - {{ $ticket->destinasi->destinasi_tujuan }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-lg">Rp {{ number_format($ticket->harga_tiket, 0, ',', '.') }}</div>
                                            <div class="text-xs text-gray-500">/person</div>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center text-sm pt-3 border-t">
                                        <div>
                                            {{ $ticket->jadwalKereta->jadwal_keberangkatan->format('H:i') }}
                                            -
                                            {{ $ticket->jadwalKereta->jadwal_sampai->format('H:i') }}
                                        </div>
                                        <div class="bg-green-100 px-2 py-1 text-xs font-mono">
                                            {{ $ticket->stok_tiket }} SEATS
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white border-2 border-gray-300 p-8 text-center">
                            <div class="text-4xl mb-2">×</div>
                            <div class="font-bold mb-1">NO RETURN TICKETS</div>
                            <div class="text-sm text-gray-600 mb-4">No trains found for return date</div>
                            <a href="{{ route('home') }}" class="inline-block px-4 py-2 bg-gray-900 text-white">
                                Search Again
                            </a>
                        </div>
                    @endif
                </section>
            @endif
        </div>

        <!-- Continue Button -->
        <div id="continue-btn" class="hidden fixed bottom-6 right-6">
            <button onclick="showLoginModal()" 
                    class="px-6 py-4 bg-blue-600 text-white font-bold shadow-lg hover:bg-blue-700 transition">
                CONTINUE | <span id="total-price" class="font-mono"></span>
            </button>
        </div>
    </main>

    <!-- Modal -->
    <div id="login-modal" class="modal">
        <div class="bg-white border-4 border-gray-900 max-w-md w-11/12 p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-1">PROCEED TO BOOKING</h2>
                <p class="text-sm text-gray-600">Choose how to continue</p>
            </div>

            <!-- Summary -->
            <div class="bg-gray-100 p-4 mb-6 border-2 border-gray-300">
                <div class="flex justify-between mb-1">
                    <span class="text-sm">Total Payment:</span>
                    <span class="font-bold font-mono" id="modal-total">Rp 0</span>
                </div>
                <div class="text-xs text-gray-600">
                    <span id="modal-passengers">{{ $search_params['passengers'] }}</span> passengers | 
                    <span id="modal-tickets-info">One-way</span>
                </div>
            </div>

            <!-- Options -->
            <div class="space-y-3">
                <button onclick="continueAsGuest()" 
                        class="w-full p-4 border-2 border-gray-900 hover:bg-gray-100 transition text-left">
                    <div class="font-bold mb-1">GUEST CHECKOUT</div>
                    <div class="text-xs text-gray-600">Continue without account</div>
                </button>

                <button onclick="goToLogin()" 
                        class="w-full p-4 bg-blue-600 text-white hover:bg-blue-700 transition text-left">
                    <div class="font-bold mb-1">LOGIN / REGISTER</div>
                </button>
            </div>

            <button onclick="closeModal()" class="w-full mt-4 py-2 text-sm underline">
                Cancel
            </button>

    <script>
        let selected = { departure: null, return: null };

        function selectTicket(el, type) {
            const id = el.getAttribute('data-ticket-id');
            const section = document.getElementById(`${type}-section`);
            
            section.querySelectorAll('.ticket-card').forEach(card => {
                card.classList.remove('ticket-selected');
            });
            
            el.classList.add('ticket-selected');
            selected[type] = id;
            
            if (type === 'departure') {
                const returnSection = document.getElementById('return-section');
                if (returnSection) {
                    returnSection.classList.remove('opacity-40');
                    const instruction = document.getElementById('return-instruction');
                    if (instruction) {
                        instruction.textContent = 'Now select return ticket';
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
                
                document.getElementById('total-price').textContent = 
                    `Rp ${total.toLocaleString('id-ID')}`;
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
            if (deptCard) {
                total += parseInt(deptCard.getAttribute('data-ticket-price')) * passengers;
            }
            
            if (selected.return) {
                const retCard = document.querySelector(`[data-ticket-id="${selected.return}"]`);
                if (retCard) {
                    total += parseInt(retCard.getAttribute('data-ticket-price')) * passengers;
                }
            }
            
            document.getElementById('modal-total').textContent = 
                `Rp ${total.toLocaleString('id-ID')}`;
            document.getElementById('modal-passengers').textContent = passengers;
            document.getElementById('modal-tickets-info').textContent = 
                selected.return ? 'Round trip' : 'One-way';
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
            
            window.location.href = '{{ route("login") }}?redirect=' + 
                encodeURIComponent(window.location.href);
        }

        function proceedToBooking() {
            const params = new URLSearchParams({
                departure_ticket: selected.departure,
                passengers: {{ $search_params['passengers'] }}
            });
            
            if (selected.return) {
                params.append('return_ticket', selected.return);
            }
            
            window.location.href = `/detail-ticket/${selected.departure}?${params}`;
        }

        document.getElementById('login-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        document.addEventListener('DOMContentLoaded', () => {
            const deptTickets = document.querySelectorAll('[data-ticket-type="departure"]');
            if (deptTickets.length === 1) {
                selectTicket(deptTickets[0], 'departure');
            }

            @auth
                const pendingBooking = sessionStorage.getItem('pending_booking');
                if (pendingBooking) {
                    const data = JSON.parse(pendingBooking);
                    if (data.departure) {
                        const deptCard = document.querySelector(`[data-ticket-id="${data.departure}"]`);
                        if (deptCard) selectTicket(deptCard, 'departure');
                    }
                    if (data.return) {
                        const retCard = document.querySelector(`[data-ticket-id="${data.return}"]`);
                        if (retCard) selectTicket(retCard, 'return');
                    }
                    sessionStorage.removeItem('pending_booking');
                }
            @endauth
        });
    </script>

    <!-- Debug -->
    <div class="fixed bottom-4 left-4 bg-gray-900 text-white text-xs p-3 font-mono">
        <div>DEP: {{ $departure_tickets->count() }}</div>
        <div>RET: {{ $return_tickets->count() }}</div>
        <div>AUTH: {{ Auth::check() ? 'YES' : 'NO' }}</div>
        <div>{{ $search_params['departure_date'] }} 
            @if(!empty($search_params['return_date']))
                > {{ $search_params['return_date'] }}
            @endif
        </div>
    </div>
</body>
</html>