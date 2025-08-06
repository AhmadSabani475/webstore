<a class="flex flex-col bg-white group rounded-xl"
    href="{{ route('product', $product->slug) }}">
    <img class="object-cover rounded-md aspect-square" src="{{ $product->cover_url }}" alt="{{ $product->name }}">
    <div class="py-5">
        <h3 class="text-lg font-bold text-gray-800 ">
            {{ $product->name }}
        </h3>
        <span class="text-sm text-gray-500">
            {{ $product->short_description }}
        </span>
        <p class="mt-1 font-semibold text-black ">
            {{ $product->price_formatted }}
        </p>
    </div>
</a>
