<!-- Reusable Clean ERP Confirmation Modal Component -->
<div x-data="{
        isOpen: false,
        title: 'Konfirmasi Tindakan',
        message: 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
        type: 'danger',
        icon: '',
        confirmText: 'Ya, Lanjutkan',
        cancelText: 'Batal',
        requiresInput: false,
        inputLabel: 'Catatan / Alasan',
        inputPlaceholder: 'Masukkan alasan...',
        inputValue: '',
        onConfirmCallback: null,

        show(detail) {
            this.title = detail.title || 'Konfirmasi Tindakan';
            this.message = detail.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            this.type = detail.type || 'danger';
            this.icon = detail.icon || (this.type === 'danger' ? 'bi-exclamation-triangle-fill' : (this.type === 'success' ? 'bi-check-circle-fill' : (this.type === 'warning' ? 'bi-box-arrow-right' : 'bi-info-circle-fill')));
            this.confirmText = detail.confirmText || 'Ya, Lanjutkan';
            this.cancelText = detail.cancelText || 'Batal';
            this.requiresInput = detail.requiresInput || false;
            this.inputLabel = detail.inputLabel || 'Catatan / Alasan';
            this.inputPlaceholder = detail.inputPlaceholder || 'Masukkan alasan...';
            this.inputValue = detail.inputValue || '';
            this.onConfirmCallback = detail.onConfirm || null;
            this.isOpen = true;
        },

        close() {
            this.isOpen = false;
        },

        confirm() {
            if (this.requiresInput && !this.inputValue.trim()) {
                alert('Silakan isi ' + this.inputLabel.toLowerCase() + ' terlebih dahulu.');
                return;
            }
            const cb = this.onConfirmCallback;
            const val = this.inputValue;
            this.close();
            if (typeof cb === 'function') {
                cb(val);
            }
        }
    }"
    @open-confirm-modal.window="show($event.detail)"
    x-cloak>

    <!-- Modal Backdrop Overlay -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4">

        <!-- Modal Dialog Container -->
        <div x-show="isOpen"
             @click.outside="close()"
             @keydown.escape.window="close()"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="bg-white rounded-lg border border-slate-200 shadow-xl max-w-md w-full overflow-hidden text-left">
             
            <!-- Header Section -->
            <div class="px-5 py-4 border-b flex items-center justify-between"
                 :class="{
                     'bg-red-50 border-red-100': type === 'danger',
                     'bg-emerald-50 border-emerald-100': type === 'success',
                     'bg-amber-50 border-amber-100': type === 'warning',
                     'bg-blue-50 border-blue-100': type === 'info'
                 }">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded flex items-center justify-center text-sm font-bold shrink-0"
                         :class="{
                             'bg-red-100 text-red-600': type === 'danger',
                             'bg-emerald-100 text-emerald-600': type === 'success',
                             'bg-amber-100 text-amber-700': type === 'warning',
                             'bg-blue-100 text-blue-600': type === 'info'
                         }">
                        <i class="bi" :class="icon"></i>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900" x-text="title"></h3>
                </div>
                <button type="button" @click="close()" class="text-slate-400 hover:text-slate-600 p-1 rounded-md focus:outline-none">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>

            <!-- Body Section -->
            <div class="p-5 space-y-4">
                <p class="text-xs text-slate-600 leading-relaxed font-medium" x-text="message"></p>

                <!-- Optional Textarea Input (For Rejection Reason, Notes, etc.) -->
                <template x-if="requiresInput">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5" x-text="inputLabel"></label>
                        <textarea x-model="inputValue" 
                                  rows="3" 
                                  class="w-full text-xs rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500 p-2.5 text-slate-900"
                                  :placeholder="inputPlaceholder"></textarea>
                    </div>
                </template>
            </div>

            <!-- Footer Section -->
            <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end space-x-2">
                <button type="button" @click="close()" class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold rounded shadow-2xs transition">
                    <span x-text="cancelText"></span>
                </button>

                <button type="button" @click="confirm()" 
                        class="px-4 py-2 text-xs font-semibold rounded shadow-2xs transition flex items-center space-x-1.5"
                        :class="{
                            'bg-red-600 hover:bg-red-700 text-white': type === 'danger',
                            'bg-emerald-600 hover:bg-emerald-700 text-white': type === 'success',
                            'bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold': type === 'warning',
                            'bg-blue-600 hover:bg-blue-700 text-white': type === 'info'
                        }">
                    <span x-text="confirmText"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    window.confirmAction = function(formOrCallback, options = {}) {
        const detail = {
            title: options.title || 'Konfirmasi Tindakan',
            message: options.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
            type: options.type || 'danger',
            icon: options.icon,
            confirmText: options.confirmText || 'Ya, Lanjutkan',
            cancelText: options.cancelText || 'Batal',
            requiresInput: options.requiresInput || false,
            inputLabel: options.inputLabel || 'Catatan / Alasan',
            inputPlaceholder: options.inputPlaceholder || 'Masukkan alasan...',
            inputValue: options.inputValue || '',
            onConfirm: function(inputValue) {
                if (options.inputName && formOrCallback instanceof HTMLFormElement) {
                    let hiddenInput = formOrCallback.querySelector(`[name="${options.inputName}"]`);
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = options.inputName;
                        formOrCallback.appendChild(hiddenInput);
                    }
                    hiddenInput.value = inputValue;
                }
                
                if (typeof formOrCallback === 'function') {
                    formOrCallback(inputValue);
                } else if (formOrCallback instanceof HTMLFormElement) {
                    formOrCallback.submit();
                }
            }
        };

        window.dispatchEvent(new CustomEvent('open-confirm-modal', { detail }));
    };
</script>
