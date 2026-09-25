@extends('layouts.admin')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Rekap Survei SKM</h1>
        <p class="text-sm text-slate-500 font-medium mt-1">Survei Kepuasan Masyarakat</p>
    </div>
    <button class="flex items-center text-slate-600 font-semibold text-sm bg-white px-4 py-2.5 rounded-lg border border-slate-200 shadow-sm hover:bg-slate-50 transition">
        <i class="bi bi-download mr-2"></i> Ekspor Excel
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-8">
    <!-- Card 1: Main Index -->
    <div class="md:col-span-3 bg-forest rounded-2xl p-6 shadow-md flex flex-col items-center justify-center text-center relative overflow-hidden h-72">
        <div class="absolute -right-8 -top-8 h-32 w-32 bg-white opacity-5 rounded-full"></div>
        <div class="absolute -left-6 -bottom-6 h-24 w-24 bg-white opacity-5 rounded-full"></div>
        
        <h3 class="text-white/70 font-semibold text-xs tracking-widest uppercase mb-4 relative z-10">Indeks Kepuasan</h3>
        <div class="text-6xl font-extrabold text-white mb-2 relative z-10">{{ number_format($indeksSKM, 1) }}</div>
        <p class="text-white/70 text-xs font-medium mb-4 relative z-10">dari 5.0</p>
        
        <div class="flex text-amber-400 text-lg mb-4 relative z-10">
            @for($i = 1; $i <= 5; $i++)
                @if($i <= round($indeksSKM))
                    <i class="bi bi-star-fill mx-0.5"></i>
                @else
                    <i class="bi bi-star-fill text-white/20 mx-0.5"></i>
                @endif
            @endfor
        </div>
        
        <p class="text-xs text-white/80 font-medium relative z-10">{{ $totalSurvei }} total responden</p>
    </div>
    
    <!-- Card 2: Rating Distribution -->
    <div class="md:col-span-4 bg-white rounded-2xl p-6 shadow-sm border border-slate-200 h-72 flex flex-col justify-center">
        <h3 class="font-extrabold text-slate-800 mb-6">Distribusi Rating</h3>
        <div class="space-y-3.5 w-full">
            @foreach([5, 4, 3, 2, 1] as $star)
            @php 
                $count = $bintang[$star] ?? 0;
                $percent = $totalSurvei > 0 ? ($count / $totalSurvei) * 100 : 0; 
            @endphp
            <div class="flex items-center text-xs">
                <div class="flex text-amber-400 w-16 shrink-0">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $star) <i class="bi bi-star-fill text-[10px] mr-0.5"></i>
                        @else <i class="bi bi-star-fill text-slate-200 text-[10px] mr-0.5"></i>
                        @endif
                    @endfor
                </div>
                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden mx-3">
                    <div class="bg-mint h-full rounded-full" style="width: 100%">
                        <div class="bg-forest h-full rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                <span class="w-6 text-right text-slate-700 font-bold shrink-0">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Card 3: Aspect Scores -->
    <div class="md:col-span-5 bg-white rounded-2xl p-6 shadow-sm border border-slate-200 h-72 overflow-hidden flex flex-col">
        <h3 class="font-extrabold text-slate-800 mb-6">Skor per Aspek</h3>
        <div class="space-y-4 flex-1 overflow-y-auto pr-2">
            @forelse($skorAspek as $aspek => $skor)
            @php $percentAspek = ($skor / 5) * 100; @endphp
            <div>
                <div class="flex justify-between items-end mb-1.5">
                    <span class="text-xs font-semibold text-slate-500">{{ $aspek }}</span>
                    <span class="text-sm font-extrabold text-slate-800">{{ number_format($skor, 1) }}</span>
                </div>
                <div class="h-2 bg-slate-100 rounded-full overflow-hidden w-5/6">
                    <div class="bg-forest h-full rounded-full" style="width: {{ $percentAspek }}%"></div>
                </div>
            </div>
            @empty
            <div class="h-full flex items-center justify-center text-slate-400 text-sm font-medium text-center">
                Belum ada data kriteria survei. Data aspek akan otomatis muncul sesuai database.
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
        <div>
            <h3 class="font-extrabold text-slate-800 text-lg">Ulasan Responden</h3>
            <p class="text-xs text-slate-400 font-medium mt-1">{{ $totalSurvei }} ulasan</p>
        </div>
        <div class="flex space-x-2">
            <button class="px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-100 flex items-center">
                <i class="bi bi-star-fill text-amber-400 mr-1.5"></i> 5
            </button>
            <button class="px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-100 flex items-center">
                <i class="bi bi-star-fill text-amber-400 mr-1.5"></i> 4
            </button>
            <button class="px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-100 flex items-center">
                <i class="bi bi-star-fill text-amber-400 mr-1.5"></i> 3
            </button>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                    <th class="p-4 pl-6">Responden</th>
                    <th class="p-4">Komoditas</th>
                    <th class="p-4">Rating</th>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4 pr-6">Komentar</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($survei as $item)
                <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                    <td class="p-4 pl-6 align-top">
                        <div class="flex items-center">
                            @php 
                                $colors = ['bg-mint text-forest', 'bg-blue-100 text-blue-700', 'bg-amber-100 text-amber-700', 'bg-red-100 text-red-700', 'bg-purple-100 text-purple-700'];
                                $color = $colors[crc32($item->konsultasi->pengguna->nama_lengkap ?? 'A') % 5];
                            @endphp
                            <div class="h-8 w-8 rounded-full {{ $color }} flex items-center justify-center font-bold text-xs mr-3 shrink-0">
                                {{ substr($item->konsultasi->pengguna->nama_lengkap ?? 'P', 0, 1) }}
                            </div>
                            <span class="font-bold text-slate-800 text-sm whitespace-nowrap">{{ $item->konsultasi->pengguna->nama_lengkap ?? 'Petani' }}</span>
                        </div>
                    </td>
                    <td class="p-4 align-top">
                        <span class="bg-amber-50 text-amber-700 px-2.5 py-1 rounded-md text-[10px] font-bold whitespace-nowrap">{{ $item->konsultasi->komoditas ?? '-' }}</span>
                    </td>
                    <td class="p-4 align-top">
                        <div class="flex text-amber-400 text-[10px]">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $item->rating) <i class="bi bi-star-fill mr-0.5"></i>
                                @else <i class="bi bi-star-fill text-slate-200 mr-0.5"></i>
                                @endif
                            @endfor
                        </div>
                    </td>
                    <td class="p-4 align-top text-slate-400 text-xs font-medium whitespace-nowrap">
                        {{ $item->created_at->format('d M Y') }}
                    </td>
                    <td class="p-4 pr-6 align-top text-slate-500 text-xs leading-relaxed">
                        "{{ $item->catatan_evaluasi ?? 'Tanpa catatan' }}"
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-slate-400 font-medium text-sm">Belum ada survei yang diisi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

