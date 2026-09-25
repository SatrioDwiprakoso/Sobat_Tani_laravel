@extends('layouts.admin')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Manajemen Artikel</h1>
        <p class="text-sm text-slate-500 font-medium mt-1">Kelola artikel panduan dan berita untuk petani</p>
    </div>
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-forest text-white px-4 py-2 rounded-xl font-bold hover:bg-forest/90 transition shadow-sm flex items-center">
        <i class="bi bi-plus-lg mr-2"></i> Tulis Artikel
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                    <th class="p-4 pl-6 w-1/2">Judul Artikel</th>
                    <th class="p-4">Penulis</th>
                    <th class="p-4">Terbit</th>
                    <th class="p-4 pr-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($artikels as $artikel)
                <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                    <td class="p-4 pl-6 align-middle">
                        <div class="flex items-center">
                            @if($artikel->gambar_url)
                                <img src="{{ $artikel->gambar_url }}" alt="Cover" class="w-12 h-12 object-cover rounded-lg mr-4 shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-mint text-forest flex items-center justify-center mr-4 shrink-0"><i class="bi bi-image"></i></div>
                            @endif
                            <div>
                                <span class="font-bold text-slate-800 text-sm block line-clamp-1">{{ $artikel->judul }}</span>
                                <span class="text-xs text-slate-400 font-medium line-clamp-1 mt-0.5">{{ $artikel->ringkasan }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 align-middle text-slate-600 font-medium">{{ $artikel->penulis }}</td>
                    <td class="p-4 align-middle text-slate-500 font-medium">{{ $artikel->created_at->format('d M Y') }}</td>
                    <td class="p-4 pr-6 align-middle text-right">
                        <button onclick="editArtikel(this)" data-id="{{ $artikel->id }}" data-judul="{{ $artikel->judul }}" data-gambar="{{ $artikel->gambar_url }}" data-isi="{{ $artikel->isi }}" class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition inline-flex items-center justify-center mr-2 shadow-sm">
                            <i class="bi bi-pencil-fill text-xs"></i>
                        </button>
                        <button onclick="confirmDelete('{{ $artikel->id }}')" class="h-8 w-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition inline-flex items-center justify-center shadow-sm">
                            <i class="bi bi-trash3-fill text-xs"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-12 text-slate-400 font-medium">Belum ada artikel.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modalTambah" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
            <h3 class="font-bold text-lg text-slate-800">Tulis Artikel Baru</h3>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('admin.artikel.store') }}" method="POST" class="p-6 overflow-y-auto">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul Artikel</label>
                    <input type="text" name="judul" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">URL Gambar Sampul (Opsional)</label>
                    <input type="url" name="gambar_url" placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Isi Artikel</label>
                    <textarea name="isi" required rows="8" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-forest hover:bg-forest/90 transition shadow-sm">Terbitkan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
            <h3 class="font-bold text-lg text-slate-800">Edit Artikel</h3>
            <button onclick="document.getElementById('modalEdit').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="formEdit" method="POST" class="p-6 overflow-y-auto">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul Artikel</label>
                    <input type="text" id="edit_judul" name="judul" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">URL Gambar Sampul (Opsional)</label>
                    <input type="url" id="edit_gambar" name="gambar_url" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Isi Artikel</label>
                    <textarea id="edit_isi" name="isi" required rows="8" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<form id="formDelete" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function editArtikel(btn) {
        var id = btn.getAttribute('data-id');
        var judul = btn.getAttribute('data-judul');
        var gambar = btn.getAttribute('data-gambar');
        var isi = btn.getAttribute('data-isi');
        document.getElementById('formEdit').action = '/admin/artikel/' + id;
        document.getElementById('edit_judul').value = judul;
        document.getElementById('edit_gambar').value = gambar;
        document.getElementById('edit_isi').value = isi;
        document.getElementById('modalEdit').classList.remove('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Artikel?',
            text: "Artikel ini akan hilang dari aplikasi!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('formDelete');
                form.action = '/admin/artikel/' + id;
                form.submit();
            }
        });
    }
</script>
@endsection
