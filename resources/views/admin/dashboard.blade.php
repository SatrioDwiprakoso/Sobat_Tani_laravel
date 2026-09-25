@extends('layouts.admin')

@section('content')
<!-- Header Area -->
<div class="flex justify-between items-center mb-8">
    <div>
        <p class="text-xs text-slate-400 font-medium mb-1 tracking-wide uppercase">Home / Dashboard</p>
        <h1 class="text-3xl font-medium text-slate-800 mb-1">Halo, {{ Session::get('admin_name', 'Administrator') }} 👋</h1>
        <p class="text-sm text-slate-500">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>
    </div>
    <div class="flex space-x-3">
        <button class="h-11 w-11 bg-white border border-slate-100 rounded-2xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition">
            <i class="bi bi-calendar4-week"></i>
        </button>
        <button class="h-11 w-11 bg-white border border-slate-100 rounded-2xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition">
            <i class="bi bi-box-arrow-up"></i>
        </button>
    </div>
</div>

<!-- Top Stats Section (Bento Style) -->
<div class="flex flex-col xl:flex-row gap-6 mb-6">
    
    <!-- 4 Stats Row (Unified Card) -->
    <div class="flex-1 bg-white p-8 rounded-[2rem] border border-slate-100 flex items-center justify-between">
        
        <!-- Stat 1 -->
        <div class="flex flex-col">
            <div class="flex items-center text-slate-400 mb-2 space-x-2">
                <i class="bi bi-file-earmark-text text-xl"></i>
                <span class="text-4xl font-light text-slate-800">{{ $menungguBalasan }}</span>
            </div>
            <p class="text-[13px] font-medium text-slate-500">Konsultasi Baru</p>
        </div>

        <!-- Stat 2 -->
        <div class="flex flex-col">
            <div class="flex items-center text-slate-400 mb-2 space-x-2">
                <i class="bi bi-exclamation-triangle text-xl"></i>
                <span class="text-4xl font-light text-slate-800">{{ $pengaduanAktif }}</span>
            </div>
            <p class="text-[13px] font-medium text-slate-500">Pengaduan Aktif</p>
        </div>

        <!-- Stat 3 (Dinamis dari Database) -->
        <div class="flex flex-col">
            <div class="flex items-center text-slate-400 mb-2 space-x-2">
                <i class="bi bi-people text-xl"></i>
                <span class="text-4xl font-light text-slate-800">{{ $totalPetani }}</span>
            </div>
            <p class="text-[13px] font-medium text-slate-500">Total Petani</p>
        </div>

        <!-- Stat 4 -->
        <div class="flex flex-col">
            <div class="flex items-center text-slate-400 mb-2 space-x-2">
                <i class="bi bi-check2-circle text-xl"></i>
                <span class="text-4xl font-light text-slate-800">{{ $statusSelesai }}</span>
            </div>
            <p class="text-[13px] font-medium text-slate-500">Tiket Selesai</p>
        </div>
        
    </div>

    <!-- Gauge Chart -->
    <div class="w-full xl:w-80 bg-white rounded-[2rem] p-8 border border-slate-100 flex flex-col items-center justify-center relative">
        <div class="relative w-48 h-28 flex items-end justify-center mb-2">
            <svg viewBox="0 0 100 50" class="w-full h-full overflow-visible">
                <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="#F1F5F9" stroke-width="6" stroke-linecap="round"/>
                @php
                    $percentage = $totalSurvei > 0 ? ($indeksSKM / 5) * 100 : 0;
                    $dash = ($percentage / 100) * 125.6;
                @endphp
                <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="#1B4D30" stroke-width="6" stroke-linecap="round" stroke-dasharray="{{ $dash }}, 125.6"/>
            </svg>
            <div class="absolute bottom-2 text-center w-full flex justify-center items-baseline space-x-1">
                <h3 class="text-4xl font-light text-slate-800 leading-none">{{ number_format($indeksSKM, 1) }}</h3>
                <span class="text-sm font-medium text-slate-400">/ 5</span>
            </div>
            <span class="absolute -left-2 bottom-0 text-[11px] font-medium text-slate-400">0</span>
            <span class="absolute -right-2 bottom-0 text-[11px] font-medium text-slate-400">5</span>
        </div>
        <p class="text-[13px] font-medium text-slate-500 mt-2">Indeks Kepuasan SKM</p>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Tickets List -->
    <div class="lg:col-span-1 bg-white rounded-[2rem] p-6 lg:p-8 border border-slate-100 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-3">
                <span class="text-sm font-medium text-slate-600 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 cursor-pointer hover:bg-slate-100 transition">
                    Hari Ini <i class="bi bi-chevron-down ml-1 text-[10px]"></i>
                </span>
                <h3 class="font-medium text-slate-800 text-lg">Tiket Mendesak</h3>
            </div>
            <a href="{{ route('admin.konsultasi.index') }}" class="h-8 w-8 bg-slate-50 border border-slate-100 text-slate-500 rounded-full flex items-center justify-center hover:bg-slate-100 transition">
                <i class="bi bi-three-dots"></i>
            </a>
        </div>
        
        <div class="space-y-4 overflow-y-auto pr-2" style="max-height: 400px;">
            @forelse($aktivitasTerbaru as $item)
            <div class="bg-[#F8FAFC] p-5 rounded-2xl border border-slate-100 hover:border-slate-200 transition">
                <div class="flex justify-between items-start mb-1">
                    <h4 class="font-medium text-slate-800 text-[15px] w-4/5 leading-snug">{{ $item->judul_keluhan }}</h4>
                    <div class="h-7 w-7 rounded-full bg-slate-800 text-white flex items-center justify-center shrink-0">
                        <i class="bi bi-link-45deg"></i>
                    </div>
                </div>
                <p class="text-[13px] text-slate-400 mb-4">{{ $item->komoditas }} &bull; {{ $item->pengguna->nama_lengkap ?? 'Petani Anonim' }}</p>
                
                <div class="flex justify-between items-center mt-2">
                    <span class="bg-white px-3 py-1 rounded-lg text-xs font-medium text-slate-500 border border-slate-100 flex items-center">
                        <i class="bi bi-clock mr-1.5 text-[#1B4D30]"></i> {{ $item->created_at->format('H:i') }}
                    </span>
                    
                    <div class="flex -space-x-2">
                        @if($item->foto)
                        <div class="h-7 w-7 rounded-full border-2 border-[#F8FAFC] overflow-hidden bg-slate-200">
                            <img src="{{ asset('storage/' . $item->foto) }}" class="h-full w-full object-cover">
                        </div>
                        @endif
                        <div class="h-7 w-7 rounded-full border-2 border-[#F8FAFC] bg-slate-300 flex items-center justify-center">
                            <i class="bi bi-person text-slate-500 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-10">
                <div class="inline-flex items-center justify-center h-14 w-14 rounded-full bg-slate-50 mb-3 border border-slate-100">
                    <i class="bi bi-check2-all text-slate-400 text-2xl"></i>
                </div>
                <p class="text-slate-500 text-sm font-medium">Wah, semua tiket udah beres sob!</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Line Chart -->
    <div class="lg:col-span-2 bg-white rounded-[2rem] p-6 lg:p-8 border border-slate-100 flex flex-col">
        <div class="flex justify-between items-start mb-8">
            <div class="flex flex-col">
                <h3 class="font-medium text-slate-800 text-lg mb-1">Tren Tiket Konsultasi</h3>
                <span class="text-sm text-slate-400">Rata-rata tiket masuk per hari</span>
            </div>
            <div class="flex flex-col items-end">
                <div class="flex items-center space-x-2">
                    <span class="text-4xl font-light text-slate-800">{{ $totalKonsultasi }}</span>
                    <button class="h-7 w-7 bg-slate-50 border border-slate-100 text-slate-500 rounded-full flex items-center justify-center">
                        <i class="bi bi-arrow-up-right text-xs"></i>
                    </button>
                </div>
                <span class="text-[13px] font-medium text-slate-400 mt-1">Total Minggu Ini</span>
            </div>
        </div>

        <div class="relative flex-1 w-full min-h-[300px]">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const labelHari = @json($labelHari);
    const trenKakao = @json($trenKakao);
    const trenKelapa = @json($trenKelapa);
    const trenKopi = @json($trenKopi);
    const trenKonsultasi = [];
    for(let i = 0; i < labelHari.length; i++) {
        trenKonsultasi.push(trenKakao[i] + trenKelapa[i] + trenKopi[i]);
    }
    
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: labelHari,
            datasets: [{ 
                label: 'Konsultasi', 
                data: trenKonsultasi, 
                borderColor: '#1B4D30', 
                backgroundColor: 'transparent',
                tension: 0.1, 
                pointRadius: 0, 
                pointHoverRadius: 6,
                pointBackgroundColor: '#1B4D30',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#ffffff',
                    titleColor: '#1e293b',
                    bodyColor: '#1e293b',
                    borderColor: '#f1f5f9',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 12,
                    displayColors: false,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 14, weight: 'medium' },
                    callbacks: {
                        label: function(context) { return context.raw + ' Tiket'; }
                    }
                }
            }, 
            scales: { 
                y: { 
                    display: true, 
                    beginAtZero: true,
                    border: { display: false },
                    grid: { color: '#f8fafc', drawTicks: false },
                    ticks: { color: '#94a3b8', padding: 10, font: {size: 11} }
                }, 
                x: { 
                    border: { display: false },
                    grid: { display: false }, 
                    ticks: { color: '#94a3b8', font: {size: 11, weight: '500'} } 
                } 
            },
            layout: { padding: { left: 0, right: 0, top: 10, bottom: 0 } },
            interaction: { intersect: false, mode: 'index' },
        }
    });
</script>
@endpush