@extends('layouts.admin')

@section('content')

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Manajemen Pengguna</h1>
        <p class="text-sm text-slate-500 font-medium mt-1">Daftar petani, penyuluh, dan admin terdaftar</p>
    </div>
        <div class="flex gap-3">
        <div class="flex items-center bg-white border border-slate-200 px-4 py-2 rounded-xl w-full md:w-72 shadow-sm">
            <i class="bi bi-search text-slate-400"></i>
            <input type="text" placeholder="Cari pengguna..." class="bg-transparent border-none outline-none ml-3 w-full text-sm placeholder-slate-400">
        </div>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-forest text-white px-4 py-2 rounded-xl font-bold hover:bg-forest/90 transition shadow-sm flex items-center">
            <i class="bi bi-plus-lg mr-2"></i> Tambah
        </button>
    </div>
</div>



<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                                        <th class="p-4 pl-6">Pengguna</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Peran</th>
                    <th class="p-4">Terdaftar</th>
                    <th class="p-4 pr-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($pengguna as $user)
                <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                    <td class="p-4 pl-6 align-middle">
                        <div class="flex items-center">
                            @php 
                                $colors = ['bg-mint text-forest', 'bg-blue-100 text-blue-700', 'bg-amber-100 text-amber-700', 'bg-red-100 text-red-700', 'bg-purple-100 text-purple-700'];
                                $color = $colors[crc32($user->nama_lengkap ?? 'A') % 5];
                            @endphp
                            <div class="h-10 w-10 rounded-full {{ $color }} flex items-center justify-center font-bold mr-4 shrink-0 border border-slate-100">
                                {{ substr($user->nama_lengkap ?? "A", 0, 1) }}
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 text-sm block">{{ $user->nama_lengkap ?? "Tanpa Nama" }}</span>
                                <span class="text-xs text-slate-400 font-medium">{{ $user->nomor_telepon ?? '-' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 align-middle text-slate-600 font-medium">{{ $user->email }}</td>
                    <td class="p-4 align-middle">
                        @if($user->peran == "admin")
                            <span class="bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide">Admin</span>
                        @elseif($user->peran == "penyuluh")
                            <span class="bg-mint text-forest px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide">Penyuluh</span>
                        @else
                            <span class="bg-amber-50 text-amber-700 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide">Petani</span>
                        @endif
                    </td>
                                        <td class="p-4 align-middle text-slate-500 font-medium">{{ $user->created_at->format("d M Y") }}</td>
                    <td class="p-4 pr-6 align-middle text-right">
                        <button onclick="editPengguna('{{ $user->id }}', '{{ addslashes($user->nama_lengkap) }}', '{{ addslashes($user->email) }}', '{{ $user->peran }}')" class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition inline-flex items-center justify-center mr-2 shadow-sm">
                            <i class="bi bi-pencil-fill text-xs"></i>
                        </button>
                        <button onclick="confirmDelete('{{ $user->id }}')" class="h-8 w-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition inline-flex items-center justify-center shadow-sm">
                            <i class="bi bi-trash3-fill text-xs"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-12 text-slate-400 font-medium">Belum ada pengguna terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- Modal Tambah -->
<div id="modalTambah" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-lg text-slate-800">Tambah Pengguna</h3>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('admin.pengguna.store') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email</label>
                    <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Peran</label>
                    <select name="peran" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                        <option value="penyuluh">Penyuluh</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Kata Sandi</label>
                    <input type="password" name="kata_sandi" required minlength="6" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-forest hover:bg-forest/90 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Edit -->
<div id="modalEdit" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-lg text-slate-800">Edit Pengguna</h3>
            <button onclick="document.getElementById('modalEdit').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="formEdit" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
                    <input type="text" id="edit_nama" name="nama_lengkap" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email</label>
                    <input type="email" id="edit_email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Peran</label>
                    <select id="edit_peran" name="peran" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                        <option value="petani">Petani</option>
                        <option value="penyuluh">Penyuluh</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Kata Sandi Baru <span class="text-[10px] normal-case text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="kata_sandi" minlength="6" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
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
    function editPengguna(id, nama, email, peran) {
        document.getElementById('formEdit').action = '/admin/pengguna/' + id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_peran').value = peran;
        document.getElementById('modalEdit').classList.remove('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Pengguna?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('formDelete');
                form.action = '/admin/pengguna/' + id;
                form.submit();
            }
        });
    }
</script>
@endsection




