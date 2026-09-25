@extends("layouts.admin")

@section("content")
<div class="mb-6 flex items-center justify-between">
    <div>
        <div class="flex items-center text-sm text-slate-500 mb-1">
            <a href="{{ route("admin.dashboard") }}" class="hover:text-forest">Dashboard</a>
            <i class="bi bi-chevron-right mx-2 text-xs"></i>
            <a href="{{ route("admin.konsultasi.index") }}" class="hover:text-forest">Konsultasi Tanaman</a>
            <i class="bi bi-chevron-right mx-2 text-xs"></i>
            <span class="text-slate-800 font-medium">Tiket #{{ $konsultasi->id ?? "1042" }}</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Detail & Tindak Lanjut Konsultasi</h1>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route("admin.konsultasi.index") }}" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-50 flex items-center">
            <i class="bi bi-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <div class="lg:col-span-5 space-y-6">
        <div class="bg-white rounded-xl custom-shadow border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800">Informasi Petani & Laporan</h3>
                <span class="px-2.5 py-1 bg-amber/10 text-amber border border-amber/20 rounded-md text-xs font-bold">Menunggu</span>
            </div>
            <div class="p-5">
                <div class="flex items-center mb-6">
                    <div class="h-12 w-12 rounded-full bg-forest text-white flex items-center justify-center font-bold text-lg mr-4">
                        {{ substr($konsultasi->pengguna->nama_lengkap ?? "B", 0, 1) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-lg">{{ $konsultasi->pengguna->nama_lengkap ?? "Budi Santoso" }}</h4>
                        <p class="text-sm text-slate-500"><i class="bi bi-people mr-1"></i> KT. Makmur Jaya | <i class="bi bi-telephone mr-1 ml-2"></i> 0812-3456-7890</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <p class="text-xs text-slate-500 mb-1">Komoditas</p>
                        <p class="font-bold text-forest flex items-center"><i class="bi bi-tag-fill mr-1"></i> {{ $konsultasi->komoditas ?? "Kakao" }}</p>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <p class="text-xs text-slate-500 mb-1">Tanggal Masuk</p>
                        <p class="font-semibold text-slate-800">{{ isset($konsultasi) ? $konsultasi->created_at->format("d M Y, H:i") : "12 Sep 2026, 09:42" }}</p>
                    </div>
                </div>
                <div class="mb-6">
                                                            <h5 class="text-sm font-bold text-slate-800 mb-2">Foto Gejala / Penyakit</h5>
                    <div class="relative rounded-lg overflow-hidden border border-slate-200 h-48 bg-slate-100 flex items-center justify-center">
                        @if($konsultasi->foto_tanaman)
                            <img src="{{ asset('storage/' . $konsultasi->foto_tanaman) }}" alt="Foto Gejala" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition" onclick="openImageModal(this.src)">
                        @else
                            <i class="bi bi-image text-4xl text-slate-300"></i>
                        @endif
                    </div>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-slate-800 mb-2">Deskripsi Laporan Petani</h5>
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-100 text-sm text-slate-700 leading-relaxed italic">
                        "{{ $konsultasi->deskripsi ?? "Daun kakao saya tiba-tiba menguning..." }}"
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="lg:col-span-7">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col" style="height: calc(100vh - 180px); min-height: 500px;">
            <div class="p-4 border-b border-slate-100 bg-white flex justify-between items-center z-10 shadow-sm">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-forest text-white flex items-center justify-center font-bold mr-3 shadow-sm">
                        {{ substr($konsultasi->pengguna->nama_lengkap ?? "B", 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Thread Diskusi: {{ $konsultasi->pengguna->nama_lengkap }}</h3>
                        <p class="text-[11px] text-slate-500 font-medium">Topik: {{ $konsultasi->judul_keluhan }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500 uppercase">Status:</span>
                    @if($konsultasi->status == 'menunggu')
                        <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded text-xs font-bold">Menunggu</span>
                    @elseif($konsultasi->status == 'dijawab')
                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-bold">Dijawab</span>
                    @else
                        <span class="bg-mint text-forest px-2 py-0.5 rounded text-xs font-bold">Selesai</span>
                    @endif
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6 bg-slate-50/50 space-y-6">
                <!-- Pesan Awal (Deskripsi) -->
                <div class="flex justify-start">
                    <div class="max-w-[80%]">
                        <div class="flex items-center mb-1">
                            <span class="font-bold text-xs text-slate-700 mr-2">{{ $konsultasi->pengguna->nama_lengkap }} (Petani)</span>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $konsultasi->created_at->format('d M, H:i') }}</span>
                        </div>
                        <div class="bg-white border border-slate-200 text-slate-700 p-3.5 rounded-2xl rounded-tl-none shadow-sm text-sm leading-relaxed">
                            <p class="font-bold mb-1 border-b border-slate-100 pb-1">{{ $konsultasi->judul_keluhan }}</p>
                            {{ $konsultasi->deskripsi_gejala }}
                        </div>
                    </div>
                </div>

                <!-- Backward Compatibility Old Tanggapan -->
                @if($konsultasi->tanggapan_ahli && $konsultasi->pesan->isEmpty())
                <div class="flex justify-end">
                    <div class="max-w-[80%]">
                        <div class="flex items-center justify-end mb-1">
                            <span class="text-[10px] text-slate-400 font-medium mr-2">{{ $konsultasi->updated_at->format('d M, H:i') }}</span>
                            <span class="font-bold text-xs text-forest">Sobat Tani Admin</span>
                        </div>
                        <div class="bg-forest text-white p-3.5 rounded-2xl rounded-tr-none shadow-sm text-sm leading-relaxed">
                            {{ $konsultasi->tanggapan_ahli }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Riwayat Pesan -->
                @foreach($konsultasi->pesan as $p)
                    @if($p->pengguna_id == $konsultasi->pengguna_id)
                        <!-- Pesan dari Petani -->
                        <div class="flex justify-start">
                            <div class="max-w-[80%]">
                                <div class="flex items-center mb-1">
                                    <span class="font-bold text-xs text-slate-700 mr-2">{{ $p->pengguna->nama_lengkap ?? 'Petani' }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $p->created_at->format('d M, H:i') }}</span>
                                </div>
                                                                  <div class="bg-white border border-slate-200 text-slate-700 p-3.5 rounded-2xl rounded-tl-none shadow-sm text-sm leading-relaxed">
                                      {{ $p->pesan }}
                                      @if($p->foto)
                                          <div class="mt-2 rounded-lg overflow-hidden border border-slate-200">
                                              <img src="{{ asset('storage/' . $p->foto) }}" alt="Foto Balasan" class="max-w-full h-auto cursor-pointer hover:opacity-90 transition" onclick="openImageModal(this.src)">
                                          </div>
                                      @endif
                                  </div>
                            </div>
                        </div>
                    @else
                        <!-- Pesan dari Penyuluh/Admin -->
                        <div class="flex justify-end">
                            <div class="max-w-[80%]">
                                <div class="flex items-center justify-end mb-1">
                                    <span class="text-[10px] text-slate-400 font-medium mr-2">{{ $p->created_at->format('d M, H:i') }}</span>
                                    <span class="font-bold text-xs text-forest">{{ $p->pengguna->nama_lengkap ?? 'Penyuluh' }} @if($p->pengguna->peran == 'admin')(Admin)@endif</span>
                                </div>
                                                                  <div class="bg-forest text-white p-3.5 rounded-2xl rounded-tr-none shadow-sm text-sm leading-relaxed">
                                      {{ $p->pesan }}
                                      @if($p->foto)
                                          <div class="mt-2 rounded-lg overflow-hidden border border-slate-200/20">
                                              <img src="{{ asset('storage/' . $p->foto) }}" alt="Foto Balasan" class="max-w-full h-auto cursor-pointer hover:opacity-90 transition" onclick="openImageModal(this.src)">
                                          </div>
                                      @endif
                                  </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Input Balasan -->
            <div class="p-4 bg-white border-t border-slate-100 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.02)]">
                <form action="{{ route('admin.konsultasi.reply', $konsultasi->id) }}" method="POST">
                    @csrf
                    <div class="flex gap-3">
                        <input type="text" name="tanggapan_ahli" placeholder="Ketik balasan Anda..." class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest transition" required autocomplete="off">
                        <button type="submit" class="bg-forest text-white px-5 py-3 rounded-xl font-bold hover:bg-forest/90 transition shadow-sm flex items-center justify-center h-[46px] w-[46px] md:w-auto">
                            <i class="bi bi-send-fill md:mr-2"></i> <span class="hidden md:inline">Kirim</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

