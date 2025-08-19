<div
    x-data="{ show: false, message: '', type: 'info' }"
    x-show="show"
    x-transition
    x-init="
        window.addEventListener('toast-magic', event => {
            message = event.detail.message;
            type = event.detail.type || 'info';
            show = true;
            setTimeout(() => show = false, 4000);
        });
    "
    class="fixed top-6 right-6 z-50"
>
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        :class="{
            'bg-green-600': type === 'success',
            'bg-red-600': type === 'error',
            'bg-yellow-600': type === 'warning',
            'bg-blue-600': type === 'info',
        }"
        class="text-white px-4 py-2 rounded shadow-lg w-72"
    >
        <p x-text="message"></p>
    </div>
</div>
