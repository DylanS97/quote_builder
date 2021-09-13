<template>
    <div class="width-full">
        <confirmDelete :data="current" v-show="showModal" :deleteData="deleteQuote" :hideDeleteModal="hideDeleteModal"></confirmDelete>
        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-medium text-xl text-gray-800 leading-tight">
                    {{ quote.client_first_name | ucFirstLetter }} {{ quote.client_last_name | ucFirstLetter }}'s Quote
                </h2>
            </div>
        </header>

        <div class="max-w-3xl mx-auto px-8 pt-8">
            <breadcrumb :crumbs="crumbs" :tags="crumbTags"></breadcrumb>
        </div>

        <div class="max-w-3xl mx-auto p-10">
            <div class="flex justify-between mb-8">
                <a title="Complete Quote" v-if="!quote.completed" @click.prevent="completeQuote" class="py-2 px-4 bg-green-500 rounded-md cursor-pointer">
                    <i class="fas fa-check text-2xl text-white"></i>
                </a>
                <a title="Undo Complete Quote" v-if="quote.completed" @click.prevent="incompleteQuote" class="py-2 px-4 bg-red-500 rounded-md cursor-pointer">
                    <i class="fas fa-times text-2xl text-white"></i>
                </a>
                <div class="flex justify-end">
                    <a :title="'Email Quote #' + quote.id" href="#" class="py-2 px-4 bg-blue-500 rounded-md mx-3">
                        <i class="fas fa-envelope text-white text-2xl"></i>
                    </a>
                    <router-link :title="'Edit Quote #' + quote.id" :to="{ name: 'quote_edit', params: { id: quote.id } }" class="py-2 px-4 bg-green-500 rounded-md mx-3">
                        <i class="fas fa-edit text-white text-2xl"></i>
                    </router-link>
                    <button @click="showDeleteModal(quote)" class="py-2 px-4 bg-red-500 rounded-md ml-3">
                        <i class="far fa-trash-alt text-white text-2xl"></i>
                    </button>
                </div>
            </div>
            <div class="shadow-2xl bg-gray-50 w-100 p-10">
                <div class="">
                </div>
                <div class="w-100 p-5 border-8 border-gray-400 bg-white">
                    <h1 class="text-xl font-medium py-5 mb-5 border-b border-gray-300">Quote Details</h1>
                    <div class="flex justify-between p-2">
                        <h2>Quote ID:</h2>
                        <span class="font-light">{{ quote.id }}</span>
                    </div>
                    <div class="flex justify-between p-2">
                        <h2>Client Name:</h2>
                        <span class="font-light">{{ quote.client_first_name | ucFirstLetter }} {{ quote.client_last_name | ucFirstLetter }}</span>
                    </div>
                    <div class="flex justify-between p-2">
                        <h2>Client Mobile:</h2>
                        <span class="font-light">{{ quote.client_phone }}</span>
                    </div>
                    <div class="flex justify-between p-2">
                        <h2>Client Email:</h2>
                        <span class="font-light">{{ quote.client_email }}</span>
                    </div>
                </div>
                <hr class="mt-4">
                <div class="w-100 p-5 border-8 border-gray-400 bg-white">
                    <h1 class="text-xl font-medium py-5 mb-5 border-b border-gray-300">Products</h1>
                    <table class="w-full border border-gray-300">
                        <tr class="px-2 border-b border-gray-300">
                            <th class="p-4 font-medium">Name</th>
                            <th class="p-4 font-medium">Quantity</th>
                            <th class="p-4 font-medium">Price</th>
                        </tr>
                        <tr v-for="(product, index) in quote.products" :key="index" class="p-2">
                            <td class="p-4 text-center font-light">{{ product.product_name | ucFirstLetter }}</td>
                            <td class="p-4 text-center font-light">{{ product.quantity }}</td>
                            <td class="p-4 text-center font-light">{{ product.product_price | priceFormat }}</td>
                        </tr>
                        <tr class="px-2 py-6 border-t border-gray-300">
                            <th class="text-left p-4 px-16 font-medium" colspan="2">Sub Total</th>
                            <td class="text-center p-4 font-light">{{ quote.sub_total | priceFormat }}</td>
                        </tr>
                        <tr class="px-2 py-6 border-t border-gray-300">
                            <th class="text-left p-4 px-16 font-medium" colspan="2">VAT</th>
                            <td class="text-center p-4 font-light">{{ quote.sub_total_vat | priceFormat }}</td>
                        </tr>
                        <tr class="px-2 py-6 border-t border-gray-300">
                            <th class="text-left p-4 px-16 font-medium" colspan="2">Total</th>
                            <td class="text-center p-4 font-light">{{ quote.total | priceFormat }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import confirmDelete from './components/DeleteComponent.vue';
import breadcrumb from './components/BreadcrumbComponent.vue';
const default_layout = "default";

export default {
    components: { breadcrumb, confirmDelete },
    data() {
        return {
            id: '',
            quote: [],
            total: '',
            crumbs: [
                'home',
                'quotes',
                'quote'
            ],
            crumbTags: [
                'home',
                'quotes'
            ],
            showModal: false,
            current: null
        }
    },

    created() {
        this.getQuote();

        this.getCrumbs();
    },

    methods: {
        // Gets the quote.
        getQuote() {
            const url = new URL(window.location.href);
            this.id = url.hash.match(/(\d+)/)[0];
            axios.get('/quotes/' + this.id)
                .then(({data}) => {
                    this.quote = data;
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Get the crumb links text.
        getCrumbs() {
            this.crumbTags.push('#' + this.id);
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

        // Completes the quote when tick button is clicked.
        completeQuote() {
            axios.patch('/quotes/' + this.id, {
                'completed': 1
            })
                .then(({data}) => {
                    this.quote = data;
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Sets quote to incompleted when X button is clicked.
        incompleteQuote() {
            axios.patch('/quotes/' + this.id, {
                'completed': 0
            })
                .then(({data}) => {
                    this.quote = data;
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Display the delete confirmation modal.
        showDeleteModal(quote) {
            this.showModal = true;
            this.current = quote;
        },

        // Hide the delete confirmation modal.
        hideDeleteModal() {
            this.showModal = false;
        },

        // Delete the quote.
        deleteQuote(id) {
            axios.delete('/quotes/' + id)
                .then(() => {
                    window.location.hash = '#/quotes';
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        }
    }
};
</script>