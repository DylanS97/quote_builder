<template>
    <div class="width-full h-screen">
        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-medium text-xl text-gray-800 leading-tight">
                    {{ heading }}
                </h2>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-8 pt-8">
            <breadcrumb :crumbs="crumbs" :tags="crumbTags"></breadcrumb>
        </div>
        
        <div class="max-w-7xl mx-auto w-100 mt-8 p-10 bg-white rounded-md">
            <div class="flex">
                
                <detailsTab v-show="!show_products ? true : false"
                            :details="details"
                            :errors="errors"></detailsTab>

                <productsTab v-show="show_products"
                             :product_items="product_items"
                             :addToCart="addToCart">

                    <div class="field-container w-64 h-10 ml-2 mb-2">
                        <input @keyup="getProducts" v-model="search" type="text" id="product-search" placeholder=" " class="field rounded-md">
                        <label class="label bg-gray-100 top-2" for="product-search">Search Products</label>
                    </div>

                </productsTab>
                
                <cart id="cart"
                      :errors="errors"
                      :cart="cart" :subTotal="subTotal"
                      :addToCart="addToCart"
                      :scroll="scroll"
                      :removeFromCart="removeFromCart"></cart>
            </div>
            
            <div class="max-w-7xl mx-auto w-100 mt-8 flex justify-end">
                <router-link :to="{ name: 'quotes' }" v-if="show_products" class="w-32 py-2 bg-red-500 text-white text-center rounded-md mx-8">Cancel</router-link>
                <button v-if="!show_products" @click="toggleShow" class="w-32 py-2 bg-red-500 text-white rounded-md mx-8">Previous</button>
                <button v-if="show_products" @click="toggleShow" class="w-32 py-2 bg-green-500 text-white rounded-md">Continue</button>
                <button v-if="!show_products" @click="createQuote" class="w-32 py-2 bg-green-500 text-white rounded-md">Create</button>
            </div>
        </div>
    </div>
</template>

<script>
import breadcrumb from '../components/BreadcrumbComponent.vue';
import cart from './components/CartComponent.vue';
import detailsTab from './components/DetailsTabComponent.vue';
import productsTab from './components/ProductsTabComponent.vue';
const default_layout = "default";

export default {
    components: {
        cart,
        breadcrumb,
        detailsTab,
        productsTab
    },

    data() {
        return {
            heading:       'Create a Quote',
            product_items: {},
            search:        '',
            cart:          [],
            show_products: true,
            details:       {},
            subTotal:      0,
            errors:        [],
            crumbs:        [
                                'home',
                                'quotes',
                                'quote_create'
            ],
            crumbTags:     [
                                'Home',
                                'Quotes',
                                'Create'
            ],
            scroll:        false
        }
    },

    created() {
        this.getProducts();
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

        // Gets the products.
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

        // Adds overflow scroll to cart when height gets above 544px.
        cartScroll() {
            let height = $('#item-list').height();
            if(height >= 440) {
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
                    this.cart     = data[0];
                    this.subTotal = data[1];

                    this.cartScroll();
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Remove items from cart.
        removeFromCart(id) {
            if (this.cart[id]['quantity'] > 1) {
                this.decrement(id);
            } else {
                this.deleteFromCart(id);
            }
        },

        // Decrease the quantity.
        decrement(id) {
            axios.patch('/cart/' + id, this.cartData(id))
                .then(({data}) => {
                    this.cart     = data[0];
                    this.subTotal = data[1];
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },
        
        // Delete items from the cart.
        deleteFromCart(id) {
            axios.delete('/cart/' + id, { data: { cart: this.cart, sub_total: this.subTotal } })
                .then(({data}) => {
                    this.cart     = data[0];
                    this.subTotal = data[1];

                    this.cartScroll();
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Get the cart data.
        cartData(id) {
            let data          = {};
            data['id']        = id;
            data['cart']      = this.cart;
            data['sub_total'] = this.subTotal;

            return data;
        },

        // Generate the data from createQuote.
        generateData(cart, details) {
            details['sub_total'] = this.subTotal;

            let params        = {};
            params['cart']    = cart;
            params['details'] = details;

            return params;
        },

        // Creates the final quote.
        createQuote() {
            axios.post('/quotes', {
                sub_total:         this.subTotal,
                cart:              this.cart,
                client_first_name: this.details.client_first_name,
                client_last_name:  this.details.client_last_name,
                client_email:      this.details.client_email,
                client_phone:      this.details.client_phone
            })
                .then(({data}) => {
                    this.errors = [];
                    window.location.hash = '#/quotes/' + data.id;
                })
                .catch((e) => {
                    this.errors = e.response.data.errors;
                })
        }
    }
};
</script>