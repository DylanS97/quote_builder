<template>
    <div class="width-full">
        <div v-if="emailSuccess" class="absolute bottom-10 right-10 bg-green-100 border-2 border-green-200">
            <i @click="emailSuccess = null" class="fa fa-times float-right cursor-pointer text-xl text-red-400 pt-1 pr-2"></i>
            <div class="p-4 pt-2">
                <i class="fa fa-check block text-3xl text-green-500 text-center py-2"></i>
                <span class="block text-xl font-semibold text-gray-400">Email Sent Successfully</span>
            </div>
        </div>

        <div v-if="emailSuccess === false" class="absolute bottom-10 right-10 bg-red-100 border-2 border-red-200">
            <i @click="emailSuccess = null" class="fa fa-times float-right cursor-pointer text-xl text-red-400 pt-1 pr-2"></i>
            <div class="p-4 pt-2">
                <i class="fa fa-times block text-3xl text-red-500 text-center py-2"></i>
                <span class="block text-xl font-semibold text-gray-400">Error Sending Email</span>
            </div>
        </div>

        <div v-if="quoteCompleted" class="absolute bottom-10 right-10 bg-green-100 border-2 border-green-200">
            <i @click="quoteCompleted = null" class="fa fa-times float-right cursor-pointer text-xl text-red-400 pt-1 pr-2"></i>
            <div class="p-4 pt-2">
                <i class="fa fa-check block text-3xl text-green-500 text-center py-2"></i>
                <span class="block text-xl font-semibold text-gray-400">Quote Completed!</span>
            </div>
        </div>

        <div v-if="quoteCompleted === false" class="absolute bottom-10 right-10 bg-red-100 border-2 border-red-200">
            <i @click="quoteCompleted = null" class="fa fa-times float-right cursor-pointer text-xl text-red-400 pt-1 pr-2"></i>
            <div class="p-4 pt-2">
                <i class="fa fa-times block text-3xl text-red-500 text-center py-2"></i>
                <span class="block text-xl font-semibold text-gray-400">Quote Uncompleted!</span>
            </div>
        </div>
        
        <confirmDelete :data="current"
                       v-show="showModal"
                       :deleteData="deleteQuote"
                       :hideDeleteModal="hideDeleteModal"></confirmDelete>

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
                <a title="Complete Quote" v-if="!quote.completed" @click.prevent="toggleComplete" class="py-2 px-4 bg-green-500 rounded-md cursor-pointer">
                    <i class="fas fa-check text-2xl text-white"></i>
                </a>
                <a title="Incomplete Quote" v-if="quote.completed" @click.prevent="toggleComplete" class="py-2 px-4 bg-red-500 rounded-md cursor-pointer">
                    <i class="fas fa-times text-2xl text-white"></i>
                </a>
                <div class="flex justify-end">
                    <button @click="mailQuote" :title="'Email Quote #' + quote.id" class="py-2 px-4 bg-blue-500 rounded-md mx-3">
                        <i v-if="mailing != true" class="fas fa-envelope text-white text-2xl"></i>
                        <div v-if="mailing" class="loader"></div>
                    </button>
                    <router-link :title="'Edit Quote #' + quote.id" :to="{ name: 'quote_edit', params: { id: quote.id } }" class="py-2 px-4 pr-3 bg-green-500 rounded-md mx-3">
                        <i class="fas fa-edit text-white text-2xl"></i>
                    </router-link>
                    <button @click="showDeleteModal(quote)" title="Delete Quote" class="py-2 px-4 bg-red-500 rounded-md ml-3">
                        <i class="far fa-trash-alt text-white text-2xl"></i>
                    </button>
                </div>
            </div>

            <div class="shadow-2xl bg-gray-50 w-100 p-10">
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
                            <th class="p-4 pl-14 text-left font-medium">Name</th>
                            <th class="p-4 font-medium">Quantity</th>
                            <th class="p-4 pr-14 text-right font-medium">Price</th>
                        </tr>
                        <tr v-for="(product, index) in quote.products" :key="index" class="p-2">
                            <td class="p-4 pl-16 font-light"><span class="block w-full">{{ product.product_name | ucFirstLetter }}</span></td>
                            <td class="p-4 text-center font-light">{{ product.quantity }}</td>
                            <td class="p-4 pr-16 text-right font-light">{{ product.product_price | priceFormat }}</td>
                        </tr>
                        <tr class="px-2 py-6 border-t border-gray-300">
                            <th class="text-left p-4 px-14 font-medium" colspan="2">Sub Total</th>
                            <td class="text-right p-4 pr-14 font-light">{{ quote.sub_total | priceFormat }}</td>
                        </tr>
                        <tr class="px-2 py-6 border-t border-gray-300">
                            <th class="text-left p-4 px-14 font-medium" colspan="2">VAT</th>
                            <td class="text-right p-4 pr-14 font-light">{{ quote.sub_total_vat | priceFormat }}</td>
                        </tr>
                        <tr class="px-2 py-6 border-t border-gray-300">
                            <th class="text-left p-4 px-14 font-medium" colspan="2">Total</th>
                            <td class="text-right p-4 pr-14 font-light">{{ quote.total | priceFormat }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import breadcrumb from '../components/BreadcrumbComponent.vue';
import confirmDelete from '../components/DeleteComponent.vue';
const default_layout = "default";

export default {
    components: { breadcrumb, confirmDelete },
    data() {
        return {
            id:           '',
            quote:        [],
            total:        '',
            crumbs:       [
                               'home',
                               'quotes',
                               'quote'
            ],
            crumbTags:    [
                               'home',
                               'quotes'
            ],
            showModal:    false,
            current:      null,
            mailing:      false,
            emailSuccess: null,
            quoteCompleted: null
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
            this.id   = url.hash.match(/(\d+)/)[0];

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

        // Toggle the completed column of a quote.
        toggleComplete() {
            axios.patch('/quote-completed/' + this.id)
                .then(({data}) => {
                    this.quote = data;
                    if (data.completed === 1) {
                        this.quoteCompleted = true;
                    } else {
                        this.quoteCompleted = false;
                    }

                    setTimeout(() => {
                        this.quoteCompleted = null;
                    }, 5000);
                })
                .catch(e => {
                    console.log(e.response.data.message);
                })
        },

        // Email the quote to Mailtrap.
        mailQuote() {
            this.mailing = true;

            axios.get('/mail-quote/' + this.id, {
                'quote': this.quote
            })
                .then(({data}) => {
                    this.mailing = false;
                    this.displaySuccess();
                })
                .catch(e => {
                    this.displayFailed();
                    console.log(e);
                })
        },

        // Display success message after emailing.
        displaySuccess() {
            this.emailSuccess = true;

            setTimeout(() => {
                this.emailSuccess = null;
            }, 3000);
        },

        // Display failed message for emailing.
        displayFailed() {
            this.emailSuccess = false;

            setTimeout(() => {
                this.emailSuccess = null;
            }, 3000);
        },

        // Hide the delete confirmation modal.
        hideDeleteModal() {
            this.showModal = false;
        }
    }
};
</script>