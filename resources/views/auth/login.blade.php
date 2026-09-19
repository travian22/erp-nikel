<x-guest-layout>
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <!-- Minimalist ERP Portal Header -->
        <div class="bg-slate-900 p-6 text-center border-b border-slate-800">
            <div class="w-10 h-10 rounded bg-slate-800 border border-slate-700 mx-auto flex items-center justify-center text-white mb-2.5">
                <i class="bi bi-truck-front-fill text-xl"></i>
            </div>
            <h1 class="text-lg font-bold text-white tracking-tight">
                NikelOps <span class="text-slate-400 font-normal">ERP</span> Portal
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Sistem Pemesanan & Monitoring Kendaraan Operasional Tambang
            </p>
        </div>

        <!-- Form Body -->
        <div class="p-6 space-y-5">
            <!-- Session Status Alert -->
            <x-auth-session-status class="mb-2" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Username / Email Field -->
                <div>
                    <label for="login" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Username / Email *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-person text-sm"></i>
                        </div>
                        <input id="login" 
                               type="text" 
                               name="login" 
                               value="{{ old('login') }}" 
                               required 
                               autofocus 
                               autocomplete="username"
                               class="w-full pl-9 pr-3 py-2 text-xs rounded border border-slate-300 focus:border-slate-500 focus:ring-1 focus:ring-slate-500 text-slate-900 placeholder-slate-400"
                               placeholder="Masukkan username atau email">
                    </div>
                    <x-input-error :messages="$errors->get('login')" class="mt-1.5 text-xs text-rose-600 font-medium" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600 font-medium" />
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Password *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-lock text-sm"></i>
                        </div>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               class="w-full pl-9 pr-3 py-2 text-xs rounded border border-slate-300 focus:border-slate-500 focus:ring-1 focus:ring-slate-500 text-slate-900"
                               placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-600 font-medium" />
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-slate-900 focus:ring-slate-500">
                        <span class="ms-2 text-xs font-medium text-slate-600">Ingat Akun Saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded shadow-2xs transition flex items-center justify-center space-x-2">
                        <i class="bi bi-box-arrow-in-right text-sm"></i>
                        <span>Masuk ke System ERP</span>
                    </button>
                </div>
            </form>

            <!-- Quick Auto-Fill Login Helper for Testing -->
            <div class="pt-4 border-t border-slate-200">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2 text-center">
                    Akses Pengujian (1-Click Auto Fill)
                </span>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" 
                            onclick="fillLogin('admin', 'password')" 
                            class="px-2 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[11px] font-semibold border border-slate-300 transition text-center">
                        Admin
                    </button>
                    <button type="button" 
                            onclick="fillLogin('approver1', 'password')" 
                            class="px-2 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[11px] font-semibold border border-slate-300 transition text-center">
                        Appr L1
                    </button>
                    <button type="button" 
                            onclick="fillLogin('approver2', 'password')" 
                            class="px-2 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[11px] font-semibold border border-slate-200 transition text-center">
                        Appr L2
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(username, password) {
            document.getElementById('login').value = username;
            document.getElementById('password').value = password;
        }
    </script>
</x-guest-layout>
