<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Booking Detail - KAI</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="bg-gray-100">

        <main class="container mx-auto px-4 py-6 max-w-6xl">
            <div class="mb-4">
                <a href="javascript:history.back()" class="text-sm underline">Back to Ticket Selection</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- LEFT: Details & Forms -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- TRIP DETAILS -->
                    @php
                        $departureTicket = $ticket;
                        $returnTicket = null;

                        if (request()->has('return_ticket')) {
                            $returnTicket = \App\Models\Ticket::with(['jadwalKereta', 'kereta', 'destinasi'])
                                ->find(request()->get('return_ticket'));
                        }

                        $passengers = request()->get('passengers', 1);

                        // Calculate total WITHOUT admin fee
                        $total = ($departureTicket->harga_tiket * $passengers);
                        if ($returnTicket) {
                            $total += ($returnTicket->harga_tiket * $passengers);
                        }
                    @endphp

                    <div class="bg-white border-2 border-gray-900 p-6">
                        <h2 class="text-lg font-bold uppercase mb-4">TRIP DETAILS</h2>

                        <!-- Departure -->
                        <div class="mb-6">
                            <div class="bg-gray-100 px-3 py-1 inline-block mb-3 text-sm font-bold">
                                DEPARTURE
                            </div>

                            <div class="border-2 border-gray-300 p-4">
                                <div class="flex justify-between mb-3">
                                    <div>
                                        <div class="font-bold">{{ $departureTicket->kereta->nama_kereta }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ $departureTicket->destinasi->destinasi_asal }} -
                                            {{ $departureTicket->destinasi->destinasi_tujuan }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-lg">Rp
                                            {{ number_format($departureTicket->harga_tiket, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">/person</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm pt-3 border-t">
                                    <div>
                                        <div class="text-gray-600 text-xs">DATE</div>
                                        <div class="font-medium">
                                            {{ $departureTicket->jadwalKereta->jadwal_keberangkatan->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 text-xs">TIME</div>
                                        <div class="font-medium">
                                            {{ $departureTicket->jadwalKereta->jadwal_keberangkatan->format('H:i') }}
                                            -
                                            {{ $departureTicket->jadwalKereta->jadwal_sampai->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Return -->
                        @if($returnTicket)
                            <div class="mb-6">
                                <div class="bg-gray-100 px-3 py-1 inline-block mb-3 text-sm font-bold">
                                    RETURN
                                </div>

                                <div class="border-2 border-gray-300 p-4">
                                    <div class="flex justify-between mb-3">
                                        <div>
                                            <div class="font-bold">{{ $returnTicket->kereta->nama_kereta }}</div>
                                            <div class="text-sm text-gray-600">
                                                {{ $returnTicket->destinasi->destinasi_asal }} -
                                                {{ $returnTicket->destinasi->destinasi_tujuan }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-lg">Rp
                                                {{ number_format($returnTicket->harga_tiket, 0, ',', '.') }}
                                            </div>
                                            <div class="text-xs text-gray-500">/person</div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 text-sm pt-3 border-t">
                                        <div>
                                            <div class="text-gray-600 text-xs">DATE</div>
                                            <div class="font-medium">
                                                {{ $returnTicket->jadwalKereta->jadwal_keberangkatan->format('d M Y') }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-gray-600 text-xs">TIME</div>
                                            <div class="font-medium">
                                                {{ $returnTicket->jadwalKereta->jadwal_keberangkatan->format('H:i') }}
                                                -
                                                {{ $returnTicket->jadwalKereta->jadwal_sampai->format('H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Passengers -->
                        <div class="border-t-2 pt-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">TOTAL PASSENGERS</span>
                                <span class="font-bold">{{ $passengers }} PERSON(S)</span>
                            </div>
                        </div>
                    </div>

                    <!-- CONTACT FORM (Guest Only) -->
                    @guest
                        <div class="bg-white border-2 border-gray-900 p-6">
                            <h2 class="text-lg font-bold uppercase mb-2">CONTACT INFORMATION</h2>
                            <p class="text-sm text-gray-600 mb-4">Required for ticket delivery and payment notification</p>

                            <form id="contact-form" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold mb-1">FULL NAME *</label>
                                    <input type="text" name="nama" required
                                        class="w-full px-4 py-2 border-2 border-gray-300 focus:border-gray-900 outline-none"
                                        placeholder="Enter full name">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold mb-1">EMAIL *</label>
                                    <input type="email" name="email" required
                                        class="w-full px-4 py-2 border-2 border-gray-300 focus:border-gray-900 outline-none"
                                        placeholder="example@email.com">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold mb-1">PHONE NUMBER *</label>
                                    <input type="tel" name="phone" required
                                        class="w-full px-4 py-2 border-2 border-gray-300 focus:border-gray-900 outline-none"
                                        placeholder="08xxxxxxxxxx" pattern="[0-9]{10,13}">
                                    <p class="text-xs text-gray-500 mt-1">10-13 digits</p>
                                </div>
                            </form>
                        </div>
                    @endguest
                </div>

                <!-- RIGHT: Summary & Payment -->
                <div class="lg:col-span-1">
                    <div class="bg-white border-2 border-gray-900 p-6 sticky top-6">
                        <h2 class="text-lg font-bold uppercase mb-4">PRICE SUMMARY</h2>

                        <!-- Price Breakdown -->
                        <div class="space-y-2 mb-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Departure ({{ $passengers }}x)</span>
                                <span class="font-mono">Rp
                                    {{ number_format($departureTicket->harga_tiket * $passengers, 0, ',', '.') }}</span>
                            </div>

                            @if($returnTicket)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Return ({{ $passengers }}x)</span>
                                    <span class="font-mono">Rp
                                        {{ number_format($returnTicket->harga_tiket * $passengers, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Total -->
                        <div class="border-t-2 border-gray-900 pt-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="font-bold uppercase">Total</span>
                                <div class="text-right">
                                    <div class="font-bold text-2xl font-mono">Rp
                                        {{ number_format($total, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $passengers }} passenger(s)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Button -->
                        <button onclick="proceedToPayment()" id="payment-btn"
                            class="w-full py-3 bg-blue-600 text-white font-bold hover:bg-blue-700 transition disabled:bg-gray-300">
                            CHOOSE PAYMENT METHOD
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <script>
            function proceedToPayment() {
                @guest
                                // Validate contact form for guests
                        const form = document.getElementById('contact-form');
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    const contactData = {
                        nama: form.querySelector('input[name="nama"]').value,
                        email: form.querySelector('input[name="email"]').value,
                        phone: form.querySelector('input[name="phone"]').value
                    };

                    // Store contact data
                    sessionStorage.setItem('contact_data', JSON.stringify(contactData));
                @endguest

            // Disable button
            const btn = document.getElementById('payment-btn');
                btn.disabled = true;
                btn.textContent = 'PROCESSING...';

                // Store booking data
                const bookingData = {
                    departure_ticket: {{ $departureTicket->ticket_id }},
                    @if($returnTicket)
                        return_ticket: {{ $returnTicket->ticket_id }},
                    @endif
                passengers: {{ $passengers }},
                total: {{ $total }}
            };

            sessionStorage.setItem('booking_data', JSON.stringify(bookingData));

            // Log for development
            console.log('Booking Data:', bookingData);

            // Redirect to payment method selection page (to be created)
            // setTimeout(() => {
            //     window.location.href = '{{ route("tutor_pembayaran") }}?' + new URLSearchParams({
            //         total: {{ $total }},
            //         booking_ref: 'BK' + Date.now()
            //     });
            // }, 500);
        }

            @guest
                // Phone number formatting
                document.addEventListener('DOMContentLoaded', () => {
                    const phoneInput = document.querySelector('input[name="phone"]');
                    if (phoneInput) {
                        phoneInput.addEventListener('input', (e) => {
                            e.target.value = e.target.value.replace(/[^0-9]/g, '');
                        });
                    }
                });
            @endguest
        </script>

        <!-- Debug -->
        <div class="fixed bottom-4 left-4 bg-gray-900 text-white text-xs p-3 font-mono">
            <div>DEP: {{ $departureTicket->ticket_id }}</div>
            @if($returnTicket)
                <div>RET: {{ $returnTicket->ticket_id }}</div>
            @endif
            <div>PAX: {{ $passengers }}</div>
            <div>TOTAL: Rp {{ number_format($total, 0, ',', '.') }}</div>
            <div>USER: {{ Auth::check() ? 'AUTH' : 'GUEST' }}</div>
        </div>
    </body>

</html>