<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <button  wire:click="remove()" class="text-red-500 flex items-center cursor-pointer">Hapus</button>
    <div wire:loading
        class="animate-spin inline-block size-6 border-3 border-current border-t-transparent text-blue-500 rounded-full"
        role="status" aria-label="loading">
        <span class="sr-only">Loading...</span>
    </div>
</div>
