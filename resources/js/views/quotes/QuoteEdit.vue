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
                <router-link :to="{ name: 'quote' }" v-if="show_products" class="w-32 py-2 bg-red-500 text-white text-center rounded-md mx-8">Cancel</router-link>
                <button v-if="!show_products" @click="toggleShow" class="w-32 py-2 bg-red-500 text-white rounded-md mx-8">Previous</button>
                <button v-if="show_products" @click="toggleShow" class="w-32 py-2 bg-green-500 text-white rounded-md">Continue</button>
                <button v-if="!show_products" @click="updateQuote" class="w-32 py-2 bg-green-500 text-white rounded-md">Update</button>
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
            id:            null,
            product_items: {},
            search:        '',
            cart:          {},
            index:         0,
            show_products: true,
            details:       {},
            subTotal:      0,
            errors:        [],
            crumbs:        [
                                'home',
                                'quotes',
                                'quote',
                                'quote_edit'
            ],
            crumbTags:     [
                                'home',
                                'quotes'
            ],
            scroll:        false
        }
    },

    created() {
        this.getQuotes();
        this.getProducts();
        this.getCrumbs();
    },

    methods: {
        // Gets ID from URL and gets quote details.
        getQuotes() {
            const url = new URL(window.location.href);
            this.id   = url.hash.match(/(\d+)/)[0];

            axios.get('/quotes/' + this.id)
                .then(({data}) => {
                    data.products.forEach(product => {
                        this.cart[product.product_id] = product;

                        delete this.cart[product.product_id].created_at;
                        delete this.cart[product.product_id].updated_at;
                        delete this.cart[product.product_id].product_id;
                    });

                    this.details  = data;
                    this.subTotal = data.sub_total;

                    setTimeout(() => {
                        this.cartScroll();
                    });
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
                    this.cart     = data[0];
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
                    this.cart     = data[0];
                    this.subTotal = data[1];

                    this.cartScroll();
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Updates the quote.
        updateQuote() {
            axios.patch('/quotes/' + this.id, {
                sub_total:         this.subTotal,
                cart:              this.cart,
                client_first_name: this.details.client_first_name,
                client_last_name:  this.details.client_last_name,
                client_email:      this.details.client_email,
                client_phone:      this.details.client_phone
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