@props([
    'name',
    'title' => 'Form Data',
    'subtitle' => null,
    'show' => false,
    'maxWidth' => 'md',
    'icon' => 'bi-pencil-square'
])

@php
$maxWidthClass = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth] ?? 'sm:max-w-md';
@endphp

<div
    x-data="{ show: @js($show) }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })"
    x-on:open-modal.window="$event.detail === '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail === '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 flex items-center justify-center"
    style="display: {{ $show ? 'flex' : 'none' }};"
    x-cloak>

    <!-- Backdrop Overlay -->
    <div
        x-show="show"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>

    <!-- Modal Window -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="bg-white rounded-lg border border-slate-200 shadow-xl overflow-hidden w-full {{ $maxWidthClass }} relative z-10 my-auto text-left">
        
        <!-- Header -->
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded bg-slate-200 text-slate-800 flex items-center justify-center text-sm font-bold shrink-0">
                    <i class="bi {{ $icon }}"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900 leading-tight">{{ $title }}</h3>
                    @if($subtitle)
                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            <button type="button" x-on:click="show = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-md focus:outline-none">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <!-- Body / Slot -->
        <div>
            {{ $slot }}
        </div>
    </div>
</div>
