<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name') }}</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fuente -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: #020617;
        }

        .glass {
            backdrop-filter: blur(18px);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glow {
            box-shadow:
                0 0 40px rgba(59, 130, 246, .25),
                0 0 100px rgba(168, 85, 247, .18);
        }

        .bg-grid {
            background-image:
                linear-gradient(rgba(255, 255, 255, .03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .blob {
            position: absolute;
            border-radius: 999999px;
            filter: blur(120px);
            opacity: .35;
        }

        .blob-1 {
            width: 500px;
            height: 500px;
            background: #2563eb;
            top: -120px;
            left: -120px;
        }

        .blob-2 {
            width: 450px;
            height: 450px;
            background: #9333ea;
            bottom: -120px;
            right: -120px;
        }

        .blob-3 {
            width: 350px;
            height: 350px;
            background: #06b6d4;
            top: 40%;
            left: 40%;
        }

        .btn-hover {
            transition: .35s ease;
        }

        .btn-hover:hover {
            transform: translateY(-4px) scale(1.02);
        }

        .title-glow {
            text-shadow:
                0 0 20px rgba(59, 130, 246, .7),
                0 0 50px rgba(168, 85, 247, .4);
        }
    </style>
</head>

<body class="min-h-screen text-white relative overflow-x-hidden">

    <!-- Fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">

        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>

        <div class="absolute inset-0 bg-grid"></div>

    </div>

    <!-- Contenedor -->
    <div class="relative z-10 min-h-screen flex flex-col">

        <!-- Navbar -->
        <header class="w-full px-6 lg:px-12 py-6">

            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">

                <div class="text-center md:text-left">

                    <h1 class="text-3xl lg:text-4xl font-black title-glow">
                        {{ config('app.name') }}
                    </h1>

                    <p class="text-slate-400 mt-2 text-sm lg:text-base">
                        Plataforma Inteligente de Vacunación Veterinaria
                    </p>

                </div>

                <div class="flex flex-wrap justify-center gap-4">

                    <a href="{{ url('/system/login') }}"
                        class="glass px-6 py-3 rounded-2xl hover:bg-blue-500/20 btn-hover">

                        Sistema Principal

                    </a>

                    <a href="{{ url('/admin/login') }}"
                        class="px-6 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 shadow-2xl btn-hover">

                        Administración Veterinarias

                    </a>

                </div>

            </div>

        </header>

        <!-- Hero -->
        <main class="flex-1 flex items-center px-6 lg:px-12 py-10">

            <div class="max-w-7xl mx-auto w-full grid lg:grid-cols-2 gap-14 items-center">

                <!-- Texto -->
                <div class="text-center lg:text-left">

                    <div class="inline-flex items-center gap-2 glass px-4 py-2 rounded-full mb-8">

                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>

                        <span class="text-sm text-slate-300">
                            Tecnología médica veterinaria avanzada
                        </span>

                    </div>

                    <h2 class="text-4xl sm:text-5xl lg:text-7xl font-black leading-tight mb-8 title-glow">

                        Sistema Futurista de

                        <span
                            class="bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-500 bg-clip-text text-transparent">
                            Vacunación Animal
                        </span>

                    </h2>

                    <p class="text-slate-300 text-base lg:text-lg leading-relaxed max-w-2xl mx-auto lg:mx-0 mb-10">

                        Administra campañas de vacunación, veterinarias,
                        mascotas, historiales clínicos y seguimiento sanitario
                        desde una plataforma moderna, segura y completamente digital.

                        <br><br>

                        Diseñado para ofrecer una experiencia visual futurista,
                        rápida y profesional para clínicas veterinarias y centros médicos.

                    </p>

                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-5">

                        <a href="{{ url('/system/login') }}"
                            class="px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 font-semibold text-lg shadow-2xl btn-hover text-center">

                            Ingresar al Sistema

                        </a>

                        <a href="{{ url('/admin/login') }}"
                            class="glass px-8 py-4 rounded-2xl font-semibold text-lg btn-hover text-center">

                            Administración Veterinarias

                        </a>

                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 mt-14">

                        <div class="glass rounded-3xl p-5 text-center">

                            <h3 class="text-3xl font-black text-cyan-400">
                                24/7
                            </h3>

                            <p class="text-slate-400 text-sm mt-1">
                                Monitoreo
                            </p>

                        </div>

                        <div class="glass rounded-3xl p-5 text-center">

                            <h3 class="text-3xl font-black text-purple-400">
                                Cloud
                            </h3>

                            <p class="text-slate-400 text-sm mt-1">
                                Sistema
                            </p>

                        </div>

                        <div class="glass rounded-3xl p-5 text-center">

                            <h3 class="text-3xl font-black text-emerald-400">
                                IA
                            </h3>

                            <p class="text-slate-400 text-sm mt-1">
                                Gestión
                            </p>

                        </div>

                    </div>

                </div>

                <!-- Panel -->
                <div class="relative flex justify-center">

                    <div class="glass glow rounded-[35px] p-8 w-full max-w-xl">

                        <div class="flex items-center justify-between mb-8">

                            <div>

                                <p class="text-slate-400 text-sm">
                                    Sistema Central
                                </p>

                                <h3 class="text-3xl font-bold mt-1">
                                    Dashboard Médico
                                </h3>

                            </div>

                            <div
                                class="w-16 h-16 rounded-3xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center text-3xl shadow-2xl">

                                🩺

                            </div>

                        </div>

                        <div class="space-y-5">

                            <div class="glass rounded-2xl p-5">

                                <h4 class="font-semibold text-lg">
                                    Control de Vacunas
                                </h4>

                                <p class="text-slate-400 text-sm mt-1">
                                    Seguimiento automatizado
                                </p>

                            </div>

                            <div class="glass rounded-2xl p-5">

                                <h4 class="font-semibold text-lg">
                                    Gestión Veterinarias
                                </h4>

                                <p class="text-slate-400 text-sm mt-1">
                                    Administración inteligente
                                </p>

                            </div>

                            <div class="glass rounded-2xl p-5">

                                <h4 class="font-semibold text-lg">
                                    Historial Clínico
                                </h4>

                                <p class="text-slate-400 text-sm mt-1">
                                    Información centralizada
                                </p>

                            </div>

                        </div>

                        <!-- Estado -->
                        <div class="mt-8 rounded-3xl overflow-hidden border border-white/10">

                            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 p-5">

                                <h4 class="font-bold text-xl">
                                    Estado del Sistema
                                </h4>

                                <p class="text-white/70 text-sm mt-1">
                                    Plataforma activa y operativa
                                </p>

                            </div>

                            <div class="p-6 bg-black/20">

                                <div class="flex justify-between mb-4">

                                    <span class="text-slate-300">
                                        Rendimiento
                                    </span>

                                    <span class="text-emerald-400 font-bold">
                                        92%
                                    </span>

                                </div>

                                <div class="w-full h-3 bg-white/10 rounded-full overflow-hidden">

                                    <div class="h-full w-[92%] bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full">
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </main>

        <!-- Footer -->
        <footer class="py-6 text-center text-slate-500 text-sm">

            © {{ date('Y') }}
            {{ config('app.name') }}

            · Sistema avanzado de vacunación veterinaria

        </footer>

    </div>

</body>

</html>
