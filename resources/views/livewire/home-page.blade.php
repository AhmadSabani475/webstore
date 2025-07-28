<div>
        <div class="container mx-auto max-w-[85rem] w-full">
            <div class="mt-10">
                <x-product-sections :products="$feature_product" title="Feature Product" :url="route('product-catalog')" />
                <x-featured-icon />
                <x-product-sections :products="$latest_product" title="Latest Products" :url="route('product-catalog')" />
            </div>
        </div>
</div>
