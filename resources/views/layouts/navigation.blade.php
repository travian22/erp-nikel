<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-20 shadow-2xs">
    <!-- Left Section: Sidebar Toggle & System Context -->
    <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
        <!-- Sidebar Toggle Button (Mobile & Desktop) -->
        <button @click="sidebarOpen = !sidebarOpen" 
                type="button"
                aria-label="Toggle Navigation Sidebar"
                class="p-2 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
            <i class="bi bi-list text-xl"></i>
        </button>

        <!-- System Title & Portal Context -->
        <div class="flex items-center space-x-2.5 min-w-0">
            <h1 class="font-bold text-xs sm:text-sm text-slate-800 truncate leading-tight">
                Monitoring & Pemesanan Armada Kendaraan Tambang
            </h1>
        </div>
    </div>

    <!-- Right Section: User Profile Dropdown Menu -->
    <div class="flex items-center space-x-3 shrink-0">
        @auth
            <!-- User Role Indicator Badge -->
            <span class="hidden md:inline-flex items-center px-2.5 py-1 rounded text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-300">
                <i class="bi {{ Auth::user()->isAdmin() ? 'bi-shield-lock' : 'bi-person-check' }} me-1.5 text-xs text-slate-500"></i>
                ROLE: {{ strtoupper(Auth::user()->role) }}
            </span>

            <!-- User Profile Dropdown Button -->
            <div x-data="{ open: false }" class="relative">
                <button @click.stop="open = !open" 
                        @keydown.escape.window="open = false"
                        type="button"
                        id="user-menu-button"
                        :aria-expanded="open.toString()"
                        aria-haspopup="true"
                        class="flex items-center space-x-2 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 px-3 py-1.5 rounded-md transition border border-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <i class="bi bi-person-circle text-slate-600 text-sm"></i>
                    <span class="max-w-[120px] sm:max-w-[160px] truncate">{{ Auth::user()->name ?? Auth::user()->username }}</span>
                    <i class="bi bi-chevron-down text-[10px] text-slate-400 ms-1 transition-transform" :class="{'rotate-180': open}"></i>
                </button>

                <!-- Dropdown Content Box: Profile & Logout -->
                <div x-show="open"
                     @click.outside="open = false"
                     x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-1.5 w-52 bg-white rounded-lg shadow-md border border-slate-200 py-1 z-50 divide-y divide-slate-100">

                    <!-- User Information Header -->
                    <div class="px-3.5 py-2 bg-slate-50">
                        <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-slate-500 truncate font-medium">{{ Auth::user()->email }}</p>
                    </div>

                    <!-- Dropdown Links: Profile & Logout -->
                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100 transition">
                            <i class="bi bi-person-gear me-2.5 text-slate-400 text-sm"></i> Profile
                        </a>

                        <form id="logout-form-nav" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="button" 
                                    onclick="confirmAction(document.getElementById('logout-form-nav'), {
                                        title: 'Konfirmasi Keluar (Logout)',
                                        message: 'Apakah Anda yakin ingin keluar dari Portal Sistem ERP NikelOps?',
                                        type: 'warning',
                                        icon: 'bi-box-arrow-right',
                                        confirmText: 'Ya, Keluar Sekarang',
                                        cancelText: 'Batal'
                                    })"
                                    class="w-full text-left flex items-center px-3.5 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                <i class="bi bi-box-arrow-right me-2.5 text-rose-500 text-sm"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Guest Dropdown Menu -->
            <div x-data="{ open: false }" class="relative">
                <button @click.stop="open = !open" 
                        type="button"
                        class="flex items-center space-x-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <i class="bi bi-person-circle text-slate-500 text-sm"></i>
                    <span>Pengunjung</span>
                    <i class="bi bi-chevron-down text-[10px] text-slate-400 ms-1" :class="{'rotate-180': open}"></i>
                </button>

                <div x-show="open" 
                     @click.outside="open = false" 
                     x-cloak
                     class="absolute right-0 mt-1.5 w-40 bg-white rounded-lg shadow-md border border-slate-200 py-1 z-50">
                    <a href="{{ route('login') }}" class="flex items-center px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                        <i class="bi bi-box-arrow-in-right me-2 text-slate-600"></i> Login Masuk
                    </a>
                </div>
            </div>
        @endauth
    </div>
</header>
