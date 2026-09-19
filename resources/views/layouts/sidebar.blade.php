<!-- Desktop & Mobile Sidebar Container -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       x-cloak
       class="-translate-x-full fixed inset-y-0 left-0 w-64 bg-slate-900 border-r border-slate-800 text-slate-300 z-40 transform transition-transform duration-200 ease-in-out flex flex-col justify-between shadow-xl">
    
    <div>
        <!-- Sidebar Brand Logo Header -->
        <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800 bg-slate-950/80">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 focus:outline-none">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 border border-indigo-500 flex items-center justify-center text-white shadow-xs">
                    <i class="bi bi-truck-front-fill text-base text-white"></i>
                </div>
                <div>
                    <span class="font-bold text-sm tracking-tight text-white block leading-tight">NikelOps <span class="text-indigo-400 font-semibold">ERP</span></span>
                    <span class="block text-[10px] font-medium text-slate-400 tracking-wider uppercase">PT Nikel Mining Fleet</span>
                </div>
            </a>
            
            <!-- Mobile Close Button -->
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-md focus:outline-none">
                <i class="bi bi-x-lg text-base"></i>
            </button>
        </div>

        <!-- Sidebar Navigation Items -->
        <nav class="p-3 space-y-1 overflow-y-auto max-h-[calc(100vh-8rem)]">
            <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Navigasi Utama
            </div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-3 px-3 py-2 rounded-md text-xs font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white font-bold border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                <i class="bi bi-speedometer2 text-sm {{ request()->routeIs('dashboard') ? 'text-indigo-400' : '' }}"></i>
                <span>Dashboard Analytics</span>
            </a>

            <!-- Admin Master Data Section -->
            @if(Auth::user()->isAdmin())
                <div class="pt-3 px-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    Data Master
                </div>

                <a href="{{ route('vehicles.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-md text-xs font-semibold transition-all {{ request()->routeIs('vehicles.*') ? 'bg-slate-800 text-white font-bold border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="bi bi-car-front text-sm {{ request()->routeIs('vehicles.*') ? 'text-indigo-400' : '' }}"></i>
                    <span>Armada Kendaraan</span>
                </a>

                <a href="{{ route('drivers.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-md text-xs font-semibold transition-all {{ request()->routeIs('drivers.*') ? 'bg-slate-800 text-white font-bold border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="bi bi-person-badge text-sm {{ request()->routeIs('drivers.*') ? 'text-indigo-400' : '' }}"></i>
                    <span>Driver Tambang</span>
                </a>

                <a href="{{ route('rental-companies.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-md text-xs font-semibold transition-all {{ request()->routeIs('rental-companies.*') ? 'bg-slate-800 text-white font-bold border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="bi bi-building text-sm {{ request()->routeIs('rental-companies.*') ? 'text-indigo-400' : '' }}"></i>
                    <span>Perusahaan Rental</span>
                </a>

                <a href="{{ route('locations.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-md text-xs font-semibold transition-all {{ request()->routeIs('locations.*') ? 'bg-slate-800 text-white font-bold border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="bi bi-geo-alt text-sm {{ request()->routeIs('locations.*') ? 'text-indigo-400' : '' }}"></i>
                    <span>Lokasi & Site Pool</span>
                </a>

                <a href="{{ route('categories.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-md text-xs font-semibold transition-all {{ request()->routeIs('categories.*') ? 'bg-slate-800 text-white font-bold border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="bi bi-tags text-sm {{ request()->routeIs('categories.*') ? 'text-indigo-400' : '' }}"></i>
                    <span>Kategori Kendaraan</span>
                </a>

                <a href="{{ route('users.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-md text-xs font-semibold transition-all {{ request()->routeIs('users.*') ? 'bg-slate-800 text-white font-bold border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="bi bi-people text-sm {{ request()->routeIs('users.*') ? 'text-indigo-400' : '' }}"></i>
                    <span>Manajemen User</span>
                </a>
            @endif

            <!-- Operations / Pemesanan Section -->
            <div class="pt-3 px-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Operasional Fleet
            </div>

            <a href="{{ route('bookings.index') }}" 
               class="flex items-center space-x-3 px-3 py-2 rounded-md text-xs font-semibold transition-all {{ request()->routeIs('bookings.*') ? 'bg-slate-800 text-white font-bold border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                <i class="bi bi-journal-check text-sm {{ request()->routeIs('bookings.*') ? 'text-indigo-400' : '' }}"></i>
                <span>Pemesanan Kendaraan</span>
            </a>

            <!-- Approver 2-Level Section -->
            @if(Auth::user()->isApprover())
                @php
                    $pendingApprovalsCount = \App\Models\BookingApproval::where('approver_id', Auth::id())
                        ->where('status', 'menunggu')
                        ->count();
                @endphp
                <a href="{{ route('approvals.index') }}" 
                   class="flex items-center justify-between px-3 py-2 rounded-md text-xs font-semibold transition-all {{ request()->routeIs('approvals.*') ? 'bg-slate-800 text-white font-bold border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <div class="flex items-center space-x-3">
                        <i class="bi bi-shield-check text-sm {{ request()->routeIs('approvals.*') ? 'text-indigo-400' : '' }}"></i>
                        <span>Persetujuan 2-Level</span>
                    </div>
                    @if($pendingApprovalsCount > 0)
                        <span class="bg-amber-500 text-slate-950 font-bold text-[10px] px-1.5 py-0.2 rounded">{{ $pendingApprovalsCount }}</span>
                    @endif
                </a>
            @endif

            <!-- Reports & Audit Log Section (Admin) -->
            @if(Auth::user()->isAdmin())
                <div class="pt-3 px-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    Laporan & Governance
                </div>

                <a href="{{ route('reports.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-md text-xs font-semibold transition-all {{ request()->routeIs('reports.*') ? 'bg-slate-800 text-white font-bold border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="bi bi-file-earmark-excel text-sm {{ request()->routeIs('reports.*') ? 'text-indigo-400' : '' }}"></i>
                    <span>Export Laporan Excel</span>
                </a>

                <a href="{{ route('logs.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-md text-xs font-semibold transition-all {{ request()->routeIs('logs.*') ? 'bg-slate-800 text-white font-bold border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="bi bi-clock-history text-sm {{ request()->routeIs('logs.*') ? 'text-indigo-400' : '' }}"></i>
                    <span>Audit Trail Log</span>
                </a>
            @endif
        </nav>
    </div>

    <!-- Sidebar Footer / Active User Banner -->
    <div class="p-3 border-t border-slate-800 bg-slate-950/60">
        <div class="flex items-center space-x-3">
            <div class="w-7 h-7 rounded bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-300 font-bold text-xs">
                <i class="bi bi-person"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name ?? Auth::user()->username }}</p>
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">{{ Auth::user()->role }}</p>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Backdrop Overlay -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false" 
     x-cloak
     x-transition:enter="transition-opacity ease-linear duration-200" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" 
     x-transition:leave="transition-opacity ease-linear duration-200" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0" 
     class="fixed inset-0 bg-slate-900/50 z-30 lg:hidden"></div>
