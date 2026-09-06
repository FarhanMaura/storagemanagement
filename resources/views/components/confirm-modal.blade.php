<!-- Custom Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 z-[9999] hidden">
    <!-- Backdrop with blur -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="confirmBackdrop"></div>
    
    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div id="confirmBox" class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-md w-full transform scale-95 opacity-0 transition-all duration-300">
            <!-- Icon Header -->
            <div class="flex justify-center pt-8 pb-4">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center animate-bounce-slow shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>

            <!-- Content -->
            <div class="px-8 pb-6 text-center">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3" id="confirmTitle">
                    Konfirmasi Aksi
                </h3>
                <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed" id="confirmMessage">
                    Apakah Anda yakin ingin melanjutkan?
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 px-8 pb-8">
                <button id="confirmCancel" 
                        class="flex-1 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-xl font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200 hover:scale-105 active:scale-95">
                    Batal
                </button>
                <button id="confirmOk" 
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:from-red-600 hover:to-red-700 transition-all duration-200 hover:scale-105 active:scale-95 shadow-lg">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-slow {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    .animate-bounce-slow {
        animation: bounce-slow 2s infinite;
    }

    /* Prevent body scroll when modal is open */
    body.modal-open {
        overflow: hidden;
    }
</style>

<script>
    // Custom Confirm Dialog
    window.customConfirm = function(message, title = 'Konfirmasi Aksi') {
        return new Promise((resolve) => {
            const modal = document.getElementById('confirmModal');
            const backdrop = document.getElementById('confirmBackdrop');
            const box = document.getElementById('confirmBox');
            const titleEl = document.getElementById('confirmTitle');
            const messageEl = document.getElementById('confirmMessage');
            const cancelBtn = document.getElementById('confirmCancel');
            const okBtn = document.getElementById('confirmOk');

            // Set content
            titleEl.textContent = title;
            messageEl.textContent = message;

            // Show modal
            modal.classList.remove('hidden');
            document.body.classList.add('modal-open');

            // Trigger animations
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                box.classList.remove('scale-95', 'opacity-0');
                box.classList.add('scale-100', 'opacity-100');
            }, 10);

            // Handle buttons
            const handleCancel = () => {
                closeModal();
                resolve(false);
            };

            const handleOk = () => {
                closeModal();
                resolve(true);
            };

            const closeModal = () => {
                backdrop.classList.remove('opacity-100');
                backdrop.classList.add('opacity-0');
                box.classList.remove('scale-100', 'opacity-100');
                box.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('modal-open');
                }, 300);

                // Remove event listeners
                cancelBtn.removeEventListener('click', handleCancel);
                okBtn.removeEventListener('click', handleOk);
                backdrop.removeEventListener('click', handleCancel);
            };

            // Add event listeners
            cancelBtn.addEventListener('click', handleCancel);
            okBtn.addEventListener('click', handleOk);
            backdrop.addEventListener('click', handleCancel);

            // ESC key to cancel
            const handleEsc = (e) => {
                if (e.key === 'Escape') {
                    handleCancel();
                    document.removeEventListener('keydown', handleEsc);
                }
            };
            document.addEventListener('keydown', handleEsc);
        });
    };

    // Override default confirm for forms with data-confirm attribute
    document.addEventListener('DOMContentLoaded', function() {
        // Handle forms with onclick confirm
        document.addEventListener('click', async function(e) {
            const target = e.target.closest('[onclick*="confirm"]');
            if (target) {
                e.preventDefault();
                e.stopPropagation();

                // Extract confirm message from onclick
                const onclickAttr = target.getAttribute('onclick');
                const match = onclickAttr.match(/confirm\(['"](.+?)['"]\)/);
                
                if (match) {
                    const message = match[1];
                    const confirmed = await customConfirm(message);
                    
                    if (confirmed) {
                        // Remove onclick to prevent loop
                        const originalOnclick = target.getAttribute('onclick');
                        target.removeAttribute('onclick');
                        
                        // If it's a form submit button, submit the form
                        if (target.type === 'submit') {
                            target.closest('form').submit();
                        } else {
                            // Re-execute the onclick without confirm
                            const cleanOnclick = originalOnclick.replace(/return confirm\(['"].+?['"]\)\s*&&\s*/, '');
                            eval(cleanOnclick);
                        }
                    }
                }
            }
        }, true);
    });
</script>
