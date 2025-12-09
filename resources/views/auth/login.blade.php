<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - KAI Access</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"
            rel="stylesheet">
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
            .form-floating-label {
                position: absolute;
                top: 50%;
                left: 1rem;
                transform: translateY(-50%);
                transition: all 0.2s ease-out;
                pointer-events: none;
                color: #9CA3AF;
            }

            input:focus~.form-floating-label,
            input:not(:placeholder-shown)~.form-floating-label {
                top: 0;
                font-size: 0.75rem;
                color: #2D3E98;
                background-color: white;
                padding: 0 0.25rem;
            }
        </style>
    </head>

    <body class="font-sans antialiased bg-gray-50 h-screen overflow-hidden">

        <div class="flex w-full h-full">

            <!-- LEFT SIDE: Image/Hero (Hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 relative bg-kai-darkblue items-center justify-center overflow-hidden">
                <!-- Background Image with Overlay -->
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset('storage/assets/background.jpg') }}"
                        class="w-full h-full object-cover opacity-60" alt="KAI Background"
                        onerror="this.style.display='none'">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-kai-darkblue/90 to-kai-blue/80 mix-blend-multiply">
                    </div>
                </div>

                <!-- Content -->
                <div class="relative z-10 text-white text-center px-12">
                    <div class="mb-8 flex justify-center">
                        <div
                            class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center border border-white/20 shadow-xl">
                            <img src="{{ asset('storage/assets/logo.png') }}" class="h-10 w-auto brightness-0 invert"
                                alt="Logo KAI">
                        </div>
                    </div>
                    <h1 class="text-4xl font-bold mb-4">Selamat Datang di KAI Access</h1>
                    <p class="text-lg text-blue-100 font-light max-w-md mx-auto">
                        Platform pemesanan tiket kereta api resmi, mudah, dan terpercaya. Nikmati perjalanan Anda
                        bersama kami.
                    </p>

                    <!-- Decoration -->
                    <div class="mt-12 flex justify-center gap-2">
                        <div class="h-1.5 w-12 bg-kai-orange rounded-full"></div>
                        <div class="h-1.5 w-3 bg-white/50 rounded-full"></div>
                        <div class="h-1.5 w-3 bg-white/50 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white relative">

                <!-- Mobile Background Overlay (Only visible on small screens) -->
                <div class="absolute inset-0 lg:hidden z-0">
                    <div class="absolute inset-0 bg-kai-blue/5"></div>
                </div>

                <div
                    class="w-full max-w-md bg-white lg:bg-transparent p-8 lg:p-0 rounded-2xl shadow-xl lg:shadow-none z-10 relative border lg:border-none border-gray-100">

                    <!-- Mobile Logo -->
                    <div class="lg:hidden flex justify-center mb-8">
                        <img src="{{ asset('storage/assets/logo.png') }}" class="h-12 w-auto" alt="Logo KAI">
                    </div>

                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Masuk Akun</h2>
                        <p class="text-gray-500 text-sm">Silakan masuk menggunakan email dan kata sandi Anda.</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div
                            class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div class="relative">
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username"
                                class="w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-kai-blue/20 focus:border-kai-blue text-gray-800 placeholder-transparent peer transition-all outline-none"
                                placeholder="Email">
                            <label for="email"
                                class="form-floating-label bg-white px-1 peer-focus:text-kai-blue">Email</label>
                            <div class="absolute right-4 top-3.5 text-gray-400">
                                <i class="far fa-envelope"></i>
                            </div>
                            @if ($errors->get('email'))
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                        <!-- Password -->
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password"
                                class="w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-kai-blue/20 focus:border-kai-blue text-gray-800 placeholder-transparent peer transition-all outline-none"
                                placeholder="Kata Sandi">
                            <label for="password"
                                class="form-floating-label bg-white px-1 peer-focus:text-kai-blue">Kata Sandi</label>
                            <div class="absolute right-4 top-3.5 text-gray-400 cursor-pointer hover:text-kai-blue transition"
                                onclick="togglePassword()">
                                <i class="far fa-eye" id="eye-icon"></i>
                            </div>
                            @if ($errors->get('password'))
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $errors->first('password') }}</p>
                            @endif
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                                <div class="relative">
                                    <input id="remember_me" type="checkbox" name="remember" class="sr-only peer">
                                    <div
                                        class="w-5 h-5 border-2 border-gray-300 rounded peer-checked:bg-kai-blue peer-checked:border-kai-blue transition-all">
                                    </div>
                                    <i
                                        class="fas fa-check text-white text-xs absolute top-0.5 left-0.5 opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                                <span class="ml-2 text-sm text-gray-500 group-hover:text-kai-blue transition">Ingat
                                    Saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm font-medium text-kai-orange hover:text-orange-600 transition"
                                    href="{{ route('password.request') }}">
                                    Lupa Kata Sandi?
                                </a>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-kai-blue to-kai-darkblue hover:from-blue-700 hover:to-blue-900 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-300 transform active:scale-95 flex items-center justify-center gap-2">
                            Masuk Sekarang <i class="fas fa-arrow-right"></i>
                        </button>

                        <!-- Divider -->
                        <div class="relative flex py-2 items-center">
                            <div class="flex-grow border-t border-gray-200"></div>
                            <span class="flex-shrink-0 mx-4 text-gray-400 text-xs">Belum punya akun?</span>
                            <div class="flex-grow border-t border-gray-200"></div>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center">
                            <a href="{{ route('register') }}"
                                class="inline-block w-full py-3.5 border-2 border-kai-orange text-kai-orange font-bold rounded-xl hover:bg-orange-50 transition-colors">
                                Daftar Akun Baru
                            </a>
                        </div>
                    </form>

                    <!-- Footer Copyright -->
                    <div class="mt-8 text-center text-xs text-gray-400">
                        &copy; {{ date('Y') }} PT Kereta Api Indonesia (Persero)
                    </div>
                </div>
            </div>
        </div>

        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const eyeIcon = document.getElementById('eye-icon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            }
        </script>
    </body>

</html>
