<template>
    <div class="bg-gray-100 rounded-md ml-8 shadow-2xl">
        <div class="p-2 pl-6 mt-6 bg-gray-300 w-64 relative border-t border-b border-gray-600 overflow-hidden">
            <h2 class="text-2xl font-medium">Shopping Cart</h2>
            <div class="title-tag bg-gray-100 h-10 w-10 top-1 left-60"></div>
        </div>
        
        <div id="cart-inner-container">
            <div v-bind:class="{ 'overflow-y-scroll': scroll }" id="item-list" class="p-6">
                <div v-if="errors.cart && cart.length === 0" class="bg-white shadow-lg p-3 my-3 rounded-md text-red-500 text-center">
                    <span>{{ errors.cart[0] }}</span>
                </div>
                <div v-if="cart.length === 0 && !errors.cart" class="text-center">
                    <span class="text-gray-400 text-lg font-medium">No products in cart.</span>
                </div>
                <div class="flex bg-white shadow-lg p-3 my-3 rounded-md" v-for="(item, index) in cart" :key="index">
                    <span @change="console.log('hello')" v-if="item.product_name" class="flex-1 py-2 rounded-l-md w-50">{{ item.product_name | ucFirstLetter }}</span>
                    <span v-if="item.product_price" class="py-2 text-center mr-6">{{ item.product_price | priceFormat }}</span>
                    <span v-if="item.quantity" class="py-2 rounded-r-md pr-4 text-right">
                        <i @click="removeFromCart(index)" class="fas fa-arrow-circle-left cursor-pointer"></i>
                        {{ item.quantity }}
                        <i @click="addToCart(item, index)" class="fas fa-arrow-circle-right cursor-pointer"></i>
                    </span>
                </div>
            </div>

            <div id="total-container" class="px-4 w-full mb-8">
                <div class="flex justify-between text-lg bg-white rounded-md p-4 my-2">
                    <h2 class="font-medium">Sub Total:</h2>
                    <span v-if="subTotal == 0" class="font-medium" v-text="'£0.00'"></span>
                    <span v-if="subTotal" class="font-medium">{{ subTotal | priceFormat }}</span>
                </div>
                <div class="flex justify-between text-lg bg-white rounded-md p-4 my-2">
                    <h2 class="font-medium">VAT (20%):</h2>
                    <span v-if="subTotal == 0" class="font-medium" v-text="'£0.00'"></span>
                    <span v-if="subTotal" class="font-medium">{{ subTotal * 0.2 | priceFormat }}</span>
                </div>
                <div class="flex justify-between text-lg bg-white rounded-md p-4 mt-2">
                    <h2 class="font-medium">Total (+VAT):</h2>
                    <span v-if="subTotal == 0" class="font-medium" v-text="'£0.00'"></span>
                    <span v-if="subTotal" class="font-medium">{{ subTotal * 1.2 | priceFormat }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'cart',

    props: [
        'cart',
        'subTotal',
        'addToCart',
        'removeFromCart',
        'errors',
        'scroll'
    ]
}
</script>