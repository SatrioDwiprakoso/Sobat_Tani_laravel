<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sobat Tani</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: { colors: { forest: '#25633E', mint: '#46A76E', bglight: '#F8FAFC' } }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-bglight h-screen flex items-center justify-center font-sans">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-forest p-8 text-center">
            <i class="bi bi-tree-fill text-5xl text-mint mb-3 inline-block"></i>
            <h2 class="text-2xl font-bold text-white">Sobat Tani</h2>
            <p class="text-mint text-sm mt-1">Portal Admin & Penyuluh Perkebunan</p>
        </div>
        
        <div class="p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-6">Masuk ke Akun Anda</h3>
            
            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email / NIP</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-person text-slate-400"></i>
                        </div>
                        <input type="text" name="email" class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-mint focus:border-transparent text-sm" placeholder="Masukkan email atau NIP" required>
                    </div>
                </div>
                
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-bold text-slate-700">Kata Sandi</label>
                        <a href="#" class="text-xs text-forest font-medium hover:underline">Lupa sandi?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-lock text-slate-400"></i>
                        </div>
                        <input type="password" name="password" class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-mint focus:border-transparent text-sm" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="w-full bg-forest text-white py-3 rounded-lg font-bold hover:bg-forest/90 transition shadow-lg shadow-forest/30 flex justify-center items-center">
                    Masuk Dashboard <i class="bi bi-arrow-right ml-2"></i>
                </button>
            </form>
        </div>
        
        <div class="bg-slate-50 px-8 py-4 text-center border-t border-slate-100">
            <p class="text-xs text-slate-500">© 2026 Dinas Perkebunan. Hak Cipta Dilindungi.</p>
        </div>
    </div>

</body>
@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Akses Ditolak',
        text: '{{ $errors->first() }}',
        confirmButtonColor: '#25633E'
    });
</script>
@endif
</html>

