<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NikelOps Fleet ERP') }} - Sistem Monitoring Armada Tambang</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0F172A;
            background-color: #F8FAFC;
        }
        .badge-status {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-menunggu { background-color: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; }
        .badge-disetujui { background-color: #D1FAE5; color: #065F46; border: 1px solid #6EE7B7; }
        .badge-ditolak { background-color: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }
        .badge-selesai { background-color: #E0E7FF; color: #3730A3; border: 1px solid #A5B4FC; }
    </style>
</head>
<body class="font-sans antialiased h-full bg-slate-50" 
      x-data="{ sidebarOpen: window.innerWidth >= 1024 }"
      @resize.window="if (window.innerWidth >= 1024) sidebarOpen = true">
      
    <div class="min-h-screen bg-slate-50 flex">
        <!-- Sidebar Navigation -->
        @include('layouts.sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-200 ease-in-out" 
             :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-0'">
             
            <!-- Top Navbar -->
            @include('layouts.navigation')

            <!-- Main Content Container -->
            <main class="flex-1 p-5 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-300 text-emerald-900 px-4 py-3 rounded-md shadow-2xs flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-check-circle-fill text-emerald-700 text-base"></i>
                            <span class="font-semibold text-xs sm:text-sm">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-rose-50 border border-rose-300 text-rose-900 px-4 py-3 rounded-md shadow-2xs flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-exclamation-triangle-fill text-rose-700 text-base"></i>
                            <span class="font-semibold text-xs sm:text-sm">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Clean ERP Footer -->
            <footer class="bg-white border-t border-slate-200 py-3.5 mt-auto">
                <div class="max-w-7xl mx-auto px-6 text-center text-xs text-slate-500 font-medium">
                    &copy; {{ date('Y') }} NikelOps ERP Portal &mdash; System Monitoring Pemesanan Kendaraan Operasional Tambang Nikel.
                </div>
            </footer>
        </div>
    </div>

    <!-- Reusable Confirmation Modal -->
    <x-confirm-modal />
</body>
</html>
