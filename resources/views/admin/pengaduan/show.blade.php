@extends("layouts.admin")

@section("content")
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Tindak Lanjut Pengaduan</h1>
    </div>
    <a href="{{ route("admin.pengaduan.index") }}" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-50 flex items-center">
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl custom-shadow border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800">Detail Laporan</h3>
            </div>
            <div class="p-6">
                <div>
                    <h5 class="text-sm font-bold text-slate-800 mb-2">Deskripsi Pengaduan</h5>
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-100 text-sm text-slate-700 leading-relaxed">
                        {{ $pengaduan->deskripsi }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl custom-shadow border border-slate-100 overflow-hidden h-full">
            <div class="p-6">
                <form action="{{ route("admin.pengaduan.status", $pengaduan->id) }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Status Saat Ini</label>
                        <select name="status" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 bg-white">
                            <option value="menunggu" {{ $pengaduan->status == "menunggu" ? "selected" : "" }}>Menunggu</option>
                            <option value="diproses" {{ $pengaduan->status == "diproses" ? "selected" : "" }}>Diproses</option>
                            <option value="selesai" {{ $pengaduan->status == "selesai" ? "selected" : "" }}>Selesai</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-forest text-white py-3 rounded-lg font-bold text-sm hover:bg-forest/90">
                        Perbarui Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
