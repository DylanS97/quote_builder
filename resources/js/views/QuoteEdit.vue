<template>
    <div class="width-full h-screen">
        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-medium text-xl text-gray-800 leading-tight">
                    Edit {{ details.client_first_name | ucFirstLetter }} {{ details.client_last_name | ucFirstLetter }}'s Quote
                </h2>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-8 pt-8">
            <breadcrumb :crumbs="crumbs" :tags="crumbTags"></breadcrumb>
        </div>
        
        <div class="max-w-7xl mx-auto w-100 mt-8 p-10 bg-white rounded-md">
            <div class="flex">
                <div v-show="!show_products ? true : false" class="flex-1 rounded-md bg-gray-100 p-10 pl-0 pt-6">
                    <div class="p-2 mb-8 bg-gray-300 pl-10 w-92 relative border-t border-b border-gray-600 overflow-hidden">
                        <h2 class="text-2xl font-medium">Add Customer Details</h2>
                        <div class="title-tag bg-gray-100 h-10 w-10 top-1 left-88"></div>
                    </div>
                    <div class="flex justify-between bg-white rounded-md mb-4 ml-10">
                        <div class="p-4">
                            <div class="field-container w-80 h-14">
                                <input class="field rounded-md" type="text" id="first_name" placeholder=" " v-model="details['client_first_name']">
                                <label for="first_name" class="label bg-white top-4">First Name: <span class="text-red-500">*</span></label>
                            </div>
                            <span v-if="errors.client_first_name" class="text-red-500">{{ errors.client_first_name[0] }}</span>
                        </div>
                        <div class="p-4">
                            <div class="field-container w-80 h-14">
                                <input class="field rounded-md" type="text" placeholder=" " id="last_name" v-model="details['client_last_name']">
                                <label for="last_name" class="label bg-white top-4">Last Name: <span class="text-red-500">*</span></label>
                            </div>
                            <span v-if="errors.client_last_name" class="text-red-500">{{ errors.client_last_name[0] }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between bg-white rounded-md ml-10">
                        <div class="p-4">
                            <div class="field-container w-80 h-14">
                                <input class="field rounded-md" type="text" placeholder=" " id="phone" v-model="details['client_phone']">
                                <label for="phone" class="label bg-white top-4">Contact No: <span class="text-red-500">*</span></label>
                            </div>
                            <span v-if="errors.client_phone" class="text-red-500">{{ errors.client_phone[0] }}</span>
                        </div>
                        <div class="p-4">
                            <div class="field-container w-80 h-14">
                                <input class="field rounded-md" type="text" placeholder=" " id="email" v-model="details['client_email']">
                                <label for="email" class="label bg-white top-4">Email: <span class="text-red-500">*</span></label>
                            </div>
                            <span v-if="errors.client_email" class="text-red-500">{{ errors.client_email[0] }}</span>
                        </div>
                    </div>
                </div>

                <div v-show="show_products" class="flex-1 flex flex-col p-3 height-100% shadow-2xl bg-gray-100 rounded-md mx-w-800px">
                    <div class="field-container w-64 h-10 ml-2 mb-2">
                        <input @keyup="getProducts" v-model="search" type="text" id="product-search" placeholder=" " class="field rounded-md">
                        <label class="label bg-gray-100 top-2" for="product-search">Search Products</label>
                    </div>
                    <div class="flex-1 overflow-y-scroll">
                        <div v-if="product_items.length === 0" class="text-center p-6">
                            <span class="text-2xl text-gray-400">No products to display</span>
                        </div>
                        <div class="grid grid-cols-3">
                            <div
                            class="product p-2 rounded-md m-2 shadow-lg bg-white" 
                            v-for="(product, index) in product_items" :key="index">
                                <div class="rounded-t-md overflow-hidden shadow-lg">
                                    <img v-if="product.images.length === 0"
                                        src="storage/images/ph.jpg" 
                                        alt="Placeholder">
                                    <img v-if="product.images.length > 0"
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
                        </div><!-- /Product list -->
                    </div>
                </div>
                
                <cart id="cart"
                      :errors="errors"
                      :cart="cart" :subTotal="subTotal"
                      :addToCart="addToCart"
                      :scroll="scroll"
                      :removeFromCart="removeFromCart"></cart>
            </div>
            
            <div class="max-w-7xl mx-auto w-100 mt-8 flex justify-end">
                <router-link :to="{ name: 'quote' }" v-if="show_products" class="w-32 py-2 bg-red-500 text-white text-center rounded-md mx-8">Cancel</router-link>
                <button v-if="!show_products" @click="toggleShow" class="w-32 py-2 bg-red-500 text-white rounded-md mx-8">Previous</button>
                <button v-if="show_products" @click="toggleShow" class="w-32 py-2 bg-green-500 text-white rounded-md">Continue</button>
                <button v-if="!show_products" @click="updateQuote" class="w-32 py-2 bg-green-500 text-white rounded-md">Update</button>
            </div>
        </div>
    </div>
</template>

<script>
const default_layout = "default";
import breadcrumb from './components/BreadcrumbComponent.vue';
import cart from './components/CartComponent.vue';

export default {
    components: {
        cart,
        breadcrumb
    },

    data() {
        return {
            id: null,
            product_items: {},
            search: '',
            cart: {},
            index: 0,
            show_products: true,
            details: {},
            subTotal: 0,
            errors: [],
            crumbs: [
                'home',
                'quotes',
                'quote',
                'quote_edit'
            ],
            crumbTags: [
                'home',
                'quotes'
            ],
            scroll: false
        }
    },

    created() {
        this.getQuotes();
        this.getProducts();
        this.getCrumbs();
    },

    methods: {
        // Get the request parameters for retrieving the products.
        getRequestParams(search) {
            let params = {};

            if (search) {
                params['search'] = search;
            }

            return params;
        },

        // Gets ID from URL and gets quote details.
        getQuotes() {
            const url = new URL(window.location.href);
            this.id = url.hash.match(/(\d+)/)[0];

            axios.get('/quotes/' + this.id)
                .then(({data}) => {
                    data.products.forEach(product => {
                        this.cart[product.product_id] = product;
                        delete this.cart[product.product_id].created_at;
                        delete this.cart[product.product_id].updated_at;
                        delete this.cart[product.product_id].product_id;
                        this.cartScroll();
                    });

                    this.details = data;

                    this.subTotal = data.sub_total;
                })
        },

        // Get list of products.
        getProducts() {
            axios.get('/products', {
                'params': this.getRequestParams(this.search)
            })
                .then(({data}) => {
                    this.product_items = data;
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Get the crumb text.
        getCrumbs() {
            this.crumbTags.push('#' + this.id);
            this.crumbTags.push('edit');

            axios.post('/crumbs', {
                crumbs: this.crumbTags
            })
                .then(({data}) => {
                    this.crumbTags = data;
                })
                .catch((e) => {
                    console.log(e.response.data);
                })
        },

        // Adds overflow scroll to cart when height gets above 544px.
        cartScroll() {
            let height = $('#item-list').height();
            if(height > 440) {
                this.scroll = true;
            } else {
                this.scroll = false;
            }
        },

        // Toggle between products and details.
        toggleShow() {
            if (this.show_products) {
                this.show_products = false;
            } else {
                this.show_products = true;
            }
        },

        // Adds item to cart.
        addToCart(product, id = null) {
            axios.post('/cart', this.cartData(id ? id : product.id))
                .then(({data}) => {
                    this.cart = data[0];
                    this.subTotal = data[1];
                    // this.cart.length++;
                    this.cartScroll();
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Decrement or Remove.
        removeFromCart(id) {
            if (this.cart[id]['quantity'] > 1) {
                this.decrease(id);
            } else {
                this.deleteFromCart(id);
            }
        },

        // Decrement the quantity.
        decrease(id) {
            axios.patch('/cart/' + id, this.cartData(id))
                .then(({data}) => {
                    this.cart = data[0];
                    this.subTotal = data[1];
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },
        
        // Delete item from the shopping cart.
        deleteFromCart(id) {
            axios.delete('/cart/' + id, { data: { cart: this.cart, sub_total: this.subTotal } })
                .then(({data}) => {
                    console.log(data[1]);
                    this.cart = data[0];
                    this.subTotal = data[1];
                    this.cartScroll();
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Get the cart data.
        cartData(id) {
            let data = {};

            data['id'] = id;
            data['cart'] = this.cart;
            data['sub_total'] = this.subTotal;

            return data;
        },

        // Generate the data from updateQuote.
        generateData(cart, details) {
            details['sub_total'] = this.subTotal;

            let params = {};

            params['cart'] = cart;
            params['details'] = details;

            return params;
        },

        // Updates the quote.
        updateQuote() {
            axios.patch('/quotes/' + this.id, {
                sub_total: this.subTotal,
                cart: this.cart,
                client_first_name: this.details.client_first_name,
                client_last_name: this.details.client_last_name,
                client_email: this.details.client_email,
                client_phone: this.details.client_phone
            })
                .then(() => {
                    this.errors = [];
                    window.location.hash = '#/quotes/' + this.id;
                })
                .catch((e) => {
                    this.errors = e.response.data.errors;
                })
        }
    }
};
</script>