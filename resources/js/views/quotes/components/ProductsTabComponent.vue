<template>
    <div id="products-tab" class="flex-1 flex flex-col p-3 height-100% shadow-2xl bg-gray-100 rounded-md mx-w-800px">
        <slot></slot>

        <div class="product-tab-products flex-1 overflow-y-scroll">
            <div v-if="product_items.length === 0" class="text-center p-6">
                <span class="text-2xl text-gray-400">No products to display</span>
            </div>

            <div id="products-grid" class="grid small-grid grid-cols-3">
                <div class="product p-2 rounded-md m-2 shadow-lg bg-white" 
                     v-for="(product, index) in product_items" :key="index">
                    <div class="rounded-t-md overflow-hidden shadow-lg">
                        <img class="mx-auto" v-if="product.images.length === 0"
                            src="storage/images/ph.jpg" 
                            alt="Placeholder">
                        <img class="mx-auto" v-if="product.images.length > 0"
                            :src="'storage/product_images/' + product.images[0].source" 
                            :alt="product.images[0].alt">
                    </div>
                    <div class="flex justify-between py-4">
                        <div>
                            <span class="max-w-100 overflow-hidden product-name">{{ product.name | ucFirstLetter }}</span>
                        </div>
                        <div>
                            <span class="text-right" style="height: min-content;">{{ product.price | priceFormat }}</span>
                        </div>
                    </div>

                    <button @click="addToCart(product)" class="py-1 px-2 bg-green-500 rounded-md text-white text-sm float-right">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'productTab',

    props: [
        'product_items',
        'addToCart'
    ]
}
</script>