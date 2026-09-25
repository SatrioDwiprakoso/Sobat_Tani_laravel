@extends('layouts.admin')

@section('content')

@php
    $totalSemua = $pengaduan->count();
    $totalBaru = $pengaduan->where('status', 'menunggu')->count();
    $totalDiproses = $pengaduan->where('status', 'diproses')->count();
    $totalSelesai = $pengaduan->where('status', 'selesai')->count();
@endphp

<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pengaduan Kebun</h1>
    <p class="text-sm text-slate-500 font-medium mt-2">{{ $totalSemua }} total Â· {{ $totalBaru }} baru menunggu tindak lanjut</p>
</div>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div class="flex space-x-2">
        <a href="#" class="bg-forest text-white px-4 py-2 rounded-full text-sm font-semibold flex items-center">
            Semua <span class="ml-2 bg-white/20 px-2 py-0.5 rounded-full text-[10px]">{{ $totalSemua }}</span>
        </a>
        <a href="#" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-full text-sm font-semibold hover:bg-slate-50 flex items-center transition">
            Baru <span class="ml-2 bg-slate-100 px-2 py-0.5 rounded-full text-[10px]">{{ $totalBaru }}</span>
        </a>
        <a href="#" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-full text-sm font-semibold hover:bg-slate-50 flex items-center transition">
            Diproses <span class="ml-2 bg-slate-100 px-2 py-0.5 rounded-full text-[10px]">{{ $totalDiproses }}</span>
        </a>
        <a href="#" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-full text-sm font-semibold hover:bg-slate-50 flex items-center transition">
            Selesai <span class="ml-2 bg-slate-100 px-2 py-0.5 rounded-full text-[10px]">{{ $totalSelesai }}</span>
        </a>
    </div>
    
    <div class="flex items-center bg-white border border-slate-200 px-4 py-2 rounded-xl w-full md:w-72 shadow-sm">
        <i class="bi bi-search text-slate-400"></i>
        <input type="text" placeholder="Cari pelapor atau kategori..." class="bg-transparent border-none outline-none ml-3 w-full text-sm placeholder-slate-400">
    </div>
</div>

<div class="space-y-4">
    @forelse($pengaduan as $item)
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between hover:border-forest/30 transition">
        
        <div class="flex items-center md:w-64 mb-4 md:mb-0 shrink-0">
            <div class="h-12 w-12 rounded-full bg-mint text-forest flex items-center justify-center font-bold text-lg mr-4 border border-forest/10">
                {{ substr($item->pengguna->nama_lengkap ?? ($item->subjek ?? 'P'), 0, 1) }}
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm">{{ $item->pengguna->nama_lengkap ?? 'Pelapor' }}</h4>
                <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $item->pengguna->no_hp ?? '08xx-xxxx-xxxx' }}</p>
            </div>
        </div>

        <div class="md:w-32 flex flex-col items-start mr-4 shrink-0">
            <span class="bg-amber-50 text-amber-700 px-2.5 py-1 rounded text-[10px] font-bold mb-1.5 whitespace-nowrap">{{ $item->kategori ?? 'Umum' }}</span>
            <span class="bg-mint text-forest px-2.5 py-1 rounded text-[10px] font-bold">ADU-00{{ $item->id }}</span>
        </div>
        
        <div class="flex-1 pr-6 mb-4 md:mb-0">
            <p class="text-sm text-slate-500 line-clamp-2 leading-relaxed">{{ $item->deskripsi ?? 'Deskripsi tidak tersedia.' }}</p>
        </div>
        
        <div class="flex items-center justify-between md:justify-end md:w-56 shrink-0">
            <div class="text-right mr-6">
                @if($item->status == 'menunggu')
                    <div class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide inline-block mb-1">Baru</div>
                @elseif($item->status == 'diproses')
                    <div class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide inline-block mb-1">Diproses</div>
                @else
                    <div class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide inline-block mb-1">Selesai</div>
                @endif
                <p class="text-xs text-slate-400 font-medium">{{ $item->created_at->format('d M Y') }}</p>
            </div>
            
            <a href="{{ route('admin.pengaduan.show', $item->id) }}" class="bg-white border border-slate-200 text-slate-600 hover:text-forest hover:border-forest/30 text-xs font-bold px-4 py-2.5 rounded-lg transition whitespace-nowrap shadow-sm">
                Tindak Lanjut
            </a>
        </div>
    </div>
    @empty
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center">
        <p class="text-slate-500 font-medium">Belum ada pengaduan masyarakat yang terdaftar.</p>
    </div>
    @endforelse
</div>
@endsection

