<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>KAI Access - Pemesanan Tiket Kereta Api</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"
            rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Tailwind CSS (CDN) -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Custom Config -->
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            kai: {
                                blue: '#2D3E98',      /* Warna Biru Utama KAI */
                                darkblue: '#1A2C75',  /* Biru Gelap untuk Gradient */
                                orange: '#F26522',    /* Warna Oranye Aksen KAI */
                                light: '#F8F9FA',
                                gray: '#BDBDBD'
                            }
                        },
                        fontFamily: {
                            sans: ['Roboto', 'sans-serif'],
                        },
                        boxShadow: {
                            'kai': '0 4px 20px rgba(45, 62, 152, 0.15)',
                            'card': '0 10px 40px -10px rgba(0,0,0,0.2)'
                        }
                    }
                }
            }
        </script>
        <style>
            html {
                scroll-behavior: smooth;
            }

            select {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                background-image: none;
            }

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

            .tab-active {
                color: #2D3E98;
                border-bottom: 3px solid #F26522;
                background-color: white;
            }

            .tab-inactive {
                color: #757575;
                background-color: #F8F9FA;
                border-bottom: 1px solid #E0E0E0;
            }

            .bg-pattern {
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%232d3e98' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
        </style>
    </head>

    <body class="font-sans text-gray-700 antialiased bg-gray-50 flex flex-col min-h-screen">

        <!-- Navbar -->
        <nav class="fixed top-0 w-full z-50 bg-white shadow-sm transition-all duration-300" id="navbar">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-[72px] items-center">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <a href="{{ url('/') }}" class="flex items-center gap-3">
                            <img class="h-10 w-auto" src="{{ asset('storage/assets/logo.png') }}"
                                onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/e/e0/Kereta_Api_Indonesia_logo.svg/2560px-Kereta_Api_Indonesia_logo.svg.png'"
                                alt="KAI Logo">
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-1">
                        @if (Route::has('login'))
                            @auth
                                <div class="h-4 w-px bg-gray-300 mx-2"></div>
                                <span class="text-sm font-medium text-gray-600">Hi, {{ Auth::user()->name ?? 'User' }}</span>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-kai-blue hover:text-kai-orange font-bold text-sm uppercase px-4 py-2 transition">Masuk</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="ml-2 px-6 py-2 rounded-full border-2 border-kai-blue text-kai-blue hover:bg-kai-blue hover:text-white transition duration-300 font-bold text-sm uppercase">
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="flex md:hidden">
                        <button type="button" class="text-kai-blue hover:text-kai-orange p-2">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="relative flex-grow flex items-center justify-center pt-[72px] bg-pattern">

            <!-- Background with Overlay -->
            <div class="absolute inset-0 z-0 overflow-hidden h-3/4 lg:h-full">
                <img src="{{ asset('storage/assets/background.jpg') }}"
                    class="w-full h-full object-cover object-center transform scale-105" alt="Background Train"
                    onerror="this.style.display='none'; this.parentElement.classList.add('bg-gradient-to-br', 'from-kai-blue', 'to-kai-darkblue');">
                <!-- Specific Gradient Overlay to match KAI style -->
                <div class="absolute inset-0 bg-gradient-to-r from-kai-darkblue/90 via-kai-blue/80 to-transparent">
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
            </div>

            <div
                class="relative z-10 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-start justify-between py-12 lg:py-20 gap-10">

                <!-- Left Side: Tagline -->
                <div class="w-full lg:w-1/2 text-white pt-10 lg:pt-20 animate-fade-in-up">
                    <h1 class="text-4xl lg:text-[3.5rem] font-black leading-tight mb-6 drop-shadow-md">
                        Pemesanan Tiket<br>
                        <span class="text-kai-orange">Kereta Api</span> Indonesia
                    </h1>
                    <p class="text-lg text-gray-100 max-w-xl mb-8 font-light leading-relaxed">
                        Nikmati perjalanan yang aman, nyaman, dan sehat dengan memesan tiket kereta api secara online di
                        KAI Access.
                    </p>

                    <!-- Features -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div
                            class="flex items-center gap-3 bg-white/10 backdrop-blur-sm p-3 rounded-lg border border-white/10 hover:bg-white/20 transition">
                            <i class="fas fa-ticket-alt text-kai-orange text-xl"></i>
                            <span class="text-sm font-medium">Bebas Antri</span>
                        </div>
                        <div
                            class="flex items-center gap-3 bg-white/10 backdrop-blur-sm p-3 rounded-lg border border-white/10 hover:bg-white/20 transition">
                            <i class="fas fa-shield-virus text-kai-orange text-xl"></i>
                            <span class="text-sm font-medium">Protokol Sehat</span>
                        </div>
                        <div
                            class="flex items-center gap-3 bg-white/10 backdrop-blur-sm p-3 rounded-lg border border-white/10 hover:bg-white/20 transition">
                            <i class="fas fa-clock text-kai-orange text-xl"></i>
                            <span class="text-sm font-medium">Jadwal Realtime</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Booking Widget Card -->
                <div class="w-full lg:w-[480px] mt-6 lg:mt-0">
                    <div class="bg-white rounded-xl shadow-card overflow-hidden">

                        <!-- Tabs -->
                        <div class="flex border-b border-gray-100">
                            <button
                                class="flex-1 py-4 text-center font-bold text-sm uppercase tracking-wide tab-active transition-all">
                                <i class="fas fa-train mr-2 text-lg"></i> Antar Kota
                            </button>
                            <button
                                class="flex-1 py-4 text-center font-bold text-sm uppercase tracking-wide tab-inactive hover:bg-gray-100 transition-all cursor-not-allowed opacity-70">
                                <i class="fas fa-subway mr-2 text-lg"></i> Lokal
                            </button>
                        </div>

                        <!-- Form Body -->
                        <form action="{{ route('list_ticket') }}" method="GET" class="p-6 lg:p-8 space-y-6">
                            @csrf

                            <!-- Rute Section (Origin & Destination) -->
                            <div class="relative flex flex-col gap-5">
                                <!-- Origin -->
                                <div class="relative group">
                                    <label for="origin"
                                        class="block text-xs font-bold text-gray-400 uppercase mb-1">Stasiun
                                        Asal</label>
                                    <div
                                        class="flex items-center border-b border-gray-300 group-focus-within:border-kai-orange group-focus-within:shadow-[0_1px_0_0_#F26522] transition-all pb-1">
                                        <div class="w-8 flex justify-center text-kai-blue">
                                            <i class="fas fa-train-subway text-xl transform -scale-x-100"></i>
                                        </div>
                                        <select id="origin" name="origin" required
                                            class="w-full py-2 px-3 bg-transparent font-bold text-gray-800 focus:outline-none cursor-pointer text-base">
                                            <option value="">Pilih Stasiun</option>
                                            @foreach($origins as $origin)
                                                <option value="{{ $origin }}">{{ $origin }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Swap Button (Absolute Positioned) -->
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10">
                                    <button type="button" id="swapBtn"
                                        class="bg-white border border-gray-200 shadow-sm rounded-full p-2 text-kai-blue hover:text-kai-orange hover:border-kai-orange hover:rotate-180 transition-all duration-300">
                                        <i class="fas fa-exchange-alt transform rotate-90"></i>
                                    </button>
                                </div>

                                <!-- Destination -->
                                <div class="relative group">
                                    <label for="destination"
                                        class="block text-xs font-bold text-gray-400 uppercase mb-1">Stasiun
                                        Tujuan</label>
                                    <div
                                        class="flex items-center border-b border-gray-300 group-focus-within:border-kai-orange group-focus-within:shadow-[0_1px_0_0_#F26522] transition-all pb-1">
                                        <div class="w-8 flex justify-center text-kai-blue">
                                            <i class="fas fa-train-subway text-xl"></i>
                                        </div>
                                        <select id="destination" name="destination" required
                                            class="w-full py-2 px-3 bg-transparent font-bold text-gray-800 focus:outline-none cursor-pointer text-base">
                                            <option value="">Pilih Stasiun</option>
                                            @foreach($destinations as $destination)
                                                <option value="{{ $destination }}">{{ $destination }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Date & Passenger Row -->
                            <div class="flex gap-6">
                                <!-- Departure Date -->
                                <div class="w-3/5 group">
                                    <label for="departure_date"
                                        class="block text-xs font-bold text-gray-400 uppercase mb-1">Tanggal
                                        Berangkat</label>
                                    <div
                                        class="flex items-center border-b border-gray-300 group-focus-within:border-kai-orange group-focus-within:shadow-[0_1px_0_0_#F26522] transition-all pb-1">
                                        <div class="w-8 flex justify-center text-kai-blue">
                                            <i class="far fa-calendar-alt text-xl"></i>
                                        </div>
                                        <input type="date" id="departure_date" name="departure_date"
                                            min="{{ date('Y-m-d') }}"
                                            max="{{ \Carbon\Carbon::now()->addDays(6)->format('Y-m-d') }}" required
                                            class="w-full py-2 px-3 bg-transparent font-bold text-gray-800 focus:outline-none cursor-pointer placeholder-gray-400 text-base">
                                    </div>
                                </div>

                                <!-- Passengers -->
                                <div class="w-2/5 group">
                                    <label for="passengers"
                                        class="block text-xs font-bold text-gray-400 uppercase mb-1">Penumpang</label>
                                    <div
                                        class="flex items-center border-b border-gray-300 group-focus-within:border-kai-orange group-focus-within:shadow-[0_1px_0_0_#F26522] transition-all pb-1">
                                        <div class="w-8 flex justify-center text-kai-blue">
                                            <i class="fas fa-users text-xl"></i>
                                        </div>
                                        <input type="number" id="passengers" name="passengers" min="1" max="8" value="1"
                                            required
                                            class="w-full py-2 px-3 bg-transparent font-bold text-gray-800 focus:outline-none text-base">
                                        <span class="text-xs text-gray-500 font-medium">Orang</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-kai-orange to-orange-500 hover:from-orange-600 hover:to-orange-700 text-white font-bold text-lg py-3.5 rounded-lg shadow-lg hover:shadow-orange-500/40 transition-all duration-300 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                Cari & Pesan Tiket
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-8 relative z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <span>&copy; {{ date('Y') }} PT Kereta Api Indonesia (Persero)</span>
                    </div>
                    <div class="flex gap-6 text-2xl text-gray-400">
                        <a href="#" class="hover:text-kai-blue transition"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="hover:text-kai-blue transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="hover:text-kai-blue transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="hover:text-kai-blue transition"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script>
            // Navbar Scrolled Effect
            window.addEventListener('scroll', function () {
                const navbar = document.getElementById('navbar');
                if (window.scrollY > 20) {
                    navbar.classList.add('shadow-md');
                } else {
                    navbar.classList.remove('shadow-md');
                }
            });

            // Swap Logic
            document.getElementById('swapBtn').addEventListener('click', function () {
                const origin = document.getElementById('origin');
                const destination = document.getElementById('destination');

                // Swap values
                const tempVal = origin.value;
                origin.value = destination.value;
                destination.value = tempVal;

                // Trigger change events to update disabled states
                origin.dispatchEvent(new Event('change'));
                destination.dispatchEvent(new Event('change'));
            });

            // Constraint Logic (Same as before)
            const originSelect = document.getElementById('origin');
            const destinationSelect = document.getElementById('destination');

            function updateOptions(changedSelect, otherSelect) {
                const selectedVal = changedSelect.value;
                Array.from(otherSelect.options).forEach(option => {
                    if (option.value === selectedVal && option.value !== '') {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            }

            originSelect.addEventListener('change', () => updateOptions(originSelect, destinationSelect));
            destinationSelect.addEventListener('change', () => updateOptions(destinationSelect, originSelect));

            // Date Constraints
            const departureInput = document.getElementById('departure_date');
            departureInput.addEventListener('change', function () {
                // Logic for return date if implemented later
            });
        </script>
    </body>

</html>