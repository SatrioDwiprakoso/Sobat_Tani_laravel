<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobat Tani - Panel Admin</title>
    
    <!-- Vite Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap Icons & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="text-slate-800 antialiased flex h-screen overflow-hidden">

    <!-- Sidebar Kiri -->
    <aside class="w-[280px] bg-white border-r border-slate-100 flex flex-col h-full shrink-0 shadow-sm z-10">
        <!-- Logo -->
        <div class="h-24 flex items-center justify-center px-8">
            <h2 class="text-2xl font-extrabold text-[#1B4D30]">Klinik Tani</h2>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-4">
            <!-- Search Input -->
            <div class="relative mb-8">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" placeholder="Cari data..." 
                       class="w-full bg-slate-50 border border-slate-100 text-sm rounded-2xl pl-11 pr-4 py-3 focus:outline-none focus:border-[#1B4D30] focus:ring-1 focus:ring-[#1B4D30] transition">
            </div>

            <!-- Grid Menu 2x2 dengan Logika Active Route -->
            <div class="grid grid-cols-2 gap-3 mb-8">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex flex-col items-center justify-center p-4 rounded-2xl transition border border-slate-100 
                   {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-br from-[#1B4D30] to-[#2E7D32] text-white shadow-md border-transparent' : 'bg-white text-slate-500 hover:bg-slate-50' }}">
                    <i class="bi bi-grid-1x2-fill text-xl mb-2 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span class="text-[11px] font-semibold">Dashboard</span>
                </a>

                <!-- Konsultasi -->
                <a href="{{ route('admin.konsultasi.index') }}" 
                   class="flex flex-col items-center justify-center p-4 rounded-2xl transition border border-slate-100 
                   {{ request()->routeIs('admin.konsultasi.*') ? 'bg-gradient-to-br from-[#1B4D30] to-[#2E7D32] text-white shadow-md border-transparent' : 'bg-white text-slate-500 hover:bg-slate-50' }}">
                    <i class="bi bi-chat-dots-fill text-xl mb-2 {{ request()->routeIs('admin.konsultasi.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span class="text-[11px] font-semibold">Konsultasi</span>
                </a>

                <!-- Pengaduan -->
                <a href="#" 
                   class="flex flex-col items-center justify-center p-4 rounded-2xl transition border border-slate-100 
                   {{ request()->routeIs('admin.pengaduan.*') ? 'bg-gradient-to-br from-[#1B4D30] to-[#2E7D32] text-white shadow-md border-transparent' : 'bg-white text-slate-500 hover:bg-slate-50' }}">
                    <i class="bi bi-exclamation-triangle-fill text-xl mb-2 {{ request()->routeIs('admin.pengaduan.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span class="text-[11px] font-semibold">Pengaduan</span>
                </a>

                <!-- Survei -->
                <a href="#" 
                   class="flex flex-col items-center justify-center p-4 rounded-2xl transition border border-slate-100 
                   {{ request()->routeIs('admin.survei.*') ? 'bg-gradient-to-br from-[#1B4D30] to-[#2E7D32] text-white shadow-md border-transparent' : 'bg-white text-slate-500 hover:bg-slate-50' }}">
                    <i class="bi bi-star-fill text-xl mb-2 {{ request()->routeIs('admin.survei.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span class="text-[11px] font-semibold">Survei</span>
                </a>
            </div>

            <!-- Label List Menu -->
            <div class="flex items-center justify-between px-2 mb-4">
                <span class="text-[10px] font-extrabold text-slate-400 tracking-wider">MANAJEMEN</span>
                <i class="bi bi-chevron-down text-slate-300 text-[10px]"></i>
            </div>

            <!-- List Menu Bawah -->
            <div class="space-y-1">
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition">
                    <i class="bi bi-people-fill text-slate-400"></i>
                    <span class="text-sm font-medium">Petani</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition">
                    <i class="bi bi-journal-text text-slate-400"></i>
                    <span class="text-sm font-medium">Artikel</span>
                </a>
            </div>
        </div>

        <!-- Profil Admin Bawah -->
        <div class="p-6">
            <div class="bg-slate-900 rounded-2xl p-4 flex items-center justify-between cursor-pointer hover:bg-slate-800 transition">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-full bg-white flex items-center justify-center shrink-0">
                        <span class="text-slate-900 font-bold text-sm">A</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-white text-xs font-bold">Administrator</span>
                        <span class="text-slate-400 text-[10px]">Admin</span>
                    </div>
                </div>
                <i class="bi bi-box-arrow-right text-slate-400 hover:text-white transition"></i>
            </div>
        </div>
    </aside>

    <!-- Konten Kanan -->
    <main class="flex-1 overflow-y-auto p-8 lg:p-12">
        @yield('content')
    </main>

    <!-- Stack Scripts bawaan Laravel/Blade -->
    @stack('scripts')
</body>
</html>