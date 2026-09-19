<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold text-slate-900 leading-tight">Manajemen Akun Pengguna</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola pengguna sistem ERP (Admin, Approver Level 1 & 2, Pegawai Pemohon)</p>
        </div>
        <button type="button" 
                x-data="" 
                x-on:click.prevent="$dispatch('open-modal', 'create-user-modal')" 
                class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow-2xs transition space-x-1.5 shrink-0 cursor-pointer">
            <i class="bi bi-person-plus text-xs"></i>
            <span>Tambah Pengguna Baru</span>
        </button>
    </div>

    <div class="space-y-5">
        <!-- Filter Bar -->
        <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-2xs">
            <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-center gap-3 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, username, email..." class="text-xs rounded-md border-slate-300 w-64 focus:border-slate-500 focus:ring-slate-500">
                <select name="role" class="text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin (Pengelola)</option>
                    <option value="approver" {{ request('role') === 'approver' ? 'selected' : '' }}>Approver (Penyetuju)</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User (Pegawai)</option>
                </select>
                <button type="submit" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow-2xs transition flex items-center space-x-1">
                    <i class="bi bi-funnel text-xs"></i>
                    <span>Filter</span>
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('users.index') }}" class="px-3 py-2 text-slate-600 hover:text-slate-900 text-xs font-semibold">Reset</a>
                @endif
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                            <th class="py-3 px-4 whitespace-nowrap">Pengguna</th>
                            <th class="py-3 px-4 whitespace-nowrap">Username</th>
                            <th class="py-3 px-4 whitespace-nowrap">Email</th>
                            <th class="py-3 px-4 whitespace-nowrap">Role / Hak Akses</th>
                            <th class="py-3 px-4 whitespace-nowrap">Status Akun</th>
                            <th class="py-3 px-4 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs font-medium text-slate-700 bg-white">
                        @forelse($users as $u)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-xs shrink-0">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-tight">{{ $u->name }}</span>
                                            @if($u->employee)
                                                <span class="text-[10px] text-slate-500 font-normal block">{{ $u->employee->position }} - {{ $u->employee->department }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-semibold text-slate-800 whitespace-nowrap">{{ $u->username }}</td>
                                <td class="py-3.5 px-4 whitespace-nowrap">{{ $u->email }}</td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($u->isAdmin())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <i class="bi bi-shield-lock me-1"></i> ADMIN
                                        </span>
                                    @elseif($u->isApprover())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            <i class="bi bi-shield-check me-1"></i> APPROVER
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            <i class="bi bi-person me-1"></i> USER
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($u->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center space-x-1.5">
                                        <button type="button" 
                                                x-data="" 
                                                x-on:click.prevent="$dispatch('open-modal', 'edit-user-{{ $u->id }}')" 
                                                class="inline-flex items-center px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold rounded-md border border-amber-300 transition space-x-1">
                                            <i class="bi bi-pencil-square text-xs"></i>
                                            <span>Edit</span>
                                        </button>

                                        @if(Auth::id() !== $u->id)
                                            <form id="delete-user-form-{{ $u->id }}" action="{{ route('users.destroy', $u) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        onclick="confirmAction(document.getElementById('delete-user-form-{{ $u->id }}'), {
                                                            title: 'Hapus Akun Pengguna',
                                                            message: 'Apakah Anda yakin ingin menghapus akun {{ addslashes($u->name) }} ({{ $u->username }})?',
                                                            type: 'danger',
                                                            confirmText: 'Ya, Hapus Pengguna'
                                                        })" 
                                                        class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-md border border-rose-200 transition space-x-1">
                                                    <i class="bi bi-trash text-xs"></i>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <!-- Edit User Modal -->
                                    <x-form-modal name="edit-user-{{ $u->id }}" title="Edit Pengguna" subtitle="Perbarui data akun {{ $u->name }}" icon="bi-pencil-square" :show="old('user_id') == $u->id">
                                        <form method="POST" action="{{ route('users.update', $u) }}" class="p-5 space-y-4">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="user_id" value="{{ $u->id }}">

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                                                <input type="text" name="name" value="{{ old('user_id') == $u->id ? old('name') : $u->name }}" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                                                @if(old('user_id') == $u->id)
                                                    @error('name') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 mb-1">Username *</label>
                                                    <input type="text" name="username" value="{{ old('user_id') == $u->id ? old('username') : $u->username }}" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                                                    @if(old('user_id') == $u->id)
                                                        @error('username') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                                                    @endif
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 mb-1">Role / Hak Akses *</label>
                                                    <select name="role" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                                                        @php $curRole = old('user_id') == $u->id ? old('role') : $u->role; @endphp
                                                        <option value="admin" {{ $curRole === 'admin' ? 'selected' : '' }}>Admin (Pengelola System)</option>
                                                        <option value="approver" {{ $curRole === 'approver' ? 'selected' : '' }}>Approver (Penyetuju Booking)</option>
                                                        <option value="user" {{ $curRole === 'user' ? 'selected' : '' }}>User (Pegawai Pemohon)</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Email *</label>
                                                <input type="email" name="email" value="{{ old('user_id') == $u->id ? old('email') : $u->email }}" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                                                @if(old('user_id') == $u->id)
                                                    @error('email') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                                                @endif
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Password Baru (Kosongkan jika tidak diubah)</label>
                                                <input type="password" name="password" class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Minimal 6 karakter...">
                                                @if(old('user_id') == $u->id)
                                                    @error('password') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                                                @endif
                                            </div>

                                            <div class="flex items-center space-x-2 pt-2">
                                                <input type="checkbox" id="is_active_{{ $u->id }}" name="is_active" value="1" {{ (old('user_id') == $u->id ? old('is_active') : $u->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="is_active_{{ $u->id }}" class="text-xs font-bold text-slate-700">Akun Aktif</label>
                                            </div>

                                            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-200">
                                                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold rounded shadow-2xs">Batal</button>
                                                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded shadow-2xs">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </x-form-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-500">Belum ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create User Modal -->
    <x-form-modal name="create-user-modal" title="Tambah Pengguna Baru" subtitle="Input data akun pengguna sistem NikelOps ERP" icon="bi-person-plus" :show="$errors->any() && !old('user_id')">
        <form method="POST" action="{{ route('users.store') }}" class="p-5 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Contoh: Budi Santoso">
                @if(!old('user_id'))
                    @error('name') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Username *</label>
                    <input type="text" name="username" value="{{ old('username') }}" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Contoh: budi_santoso">
                    @if(!old('user_id'))
                        @error('username') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Role / Hak Akses *</label>
                    <select name="role" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User (Pegawai Pemohon)</option>
                        <option value="approver" {{ old('role') === 'approver' ? 'selected' : '' }}>Approver (Penyetuju Booking)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Pengelola System)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Contoh: budi@nikel.co.id">
                @if(!old('user_id'))
                    @error('email') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Password *</label>
                <input type="password" name="password" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Minimal 6 karakter...">
                @if(!old('user_id'))
                    @error('password') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                @endif
            </div>

            <div class="flex items-center space-x-2 pt-2">
                <input type="checkbox" id="create_is_active" name="is_active" value="1" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="create_is_active" class="text-xs font-bold text-slate-700">Akun Langsung Aktif</label>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-200">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold rounded shadow-2xs">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded shadow-2xs">Simpan Pengguna Baru</button>
            </div>
        </form>
    </x-form-modal>
</x-app-layout>
