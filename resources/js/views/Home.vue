<template>
    <div class="contents">
        
        <confirmDelete :data="current" v-show="showModal" :deleteData="deleteQuote" :hideDeleteModal="hideDeleteModal"></confirmDelete>
        
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

        <div class="max-w-7xl px-10 mx-auto">
            <div>
                <div class="relative z-20 w-200">
                    <h1 class="text-md font-medium px-6 py-4 my-6 rounded-2xl bg-white border border-gray-2500 w-full text-center">
                        Recently Created
                    </h1>
                    <div class="absolute arrow box-30px bg-white border-r border-b border-gray-250"></div>
                </div>
            </div>

            <div class="pb-8">
                <div class="rounded-md full-shadow p-6">
                    <div v-if="processed === false" class="">
                        <span class="text-green-400">Processing...</span>
                    </div>

                    <table v-if="data_length" class="w-full bg-white">
                        <thead>
                            <tr>
                                <th class="border border-gray-300 font-medium p-2">ID</th>
                                <th class="border border-gray-300 font-medium p-2">Client Name</th>
                                <th class="border border-gray-300 font-medium p-2">Total Price</th>
                                <th class="border border-gray-300 font-medium p-2">Created At</th>
                                <th class="border border-gray-300 font-medium p-2">Completed</th>
                                <th class="border border-gray-300 font-medium p-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(quote, index) in quote_list" :key="index">
                                <td class="text-center font-light border border-gray-300 p-1">
                                    <router-link :to="'/quotes/' + quote.id" class="hover:underline">{{ quote.id }}</router-link>
                                </td>
                                <td class="text-center font-light border border-gray-300 p-1">{{ quote.client_first_name | ucFirstLetter }} {{ quote.client_last_name | ucFirstLetter }}</td>
                                <td class="text-center font-light border border-gray-300 p-1">{{ quote.total * 1.2 | priceFormat }}</td>
                                <td class="text-center font-light border border-gray-300 p-1">{{ quote.created_at | dateFormat }}</td>
                                <td class="text-center font-light border border-gray-300 p-1" v-text="quote.completed"></td>
                                <td class="text-center font-light border border-gray-300 p-1">
                                    <router-link :to="{ name: 'quote', params: { id: quote.id } }">
                                        <i class="fas fa-eye mr-2 text-yellow-400"></i>
                                    </router-link>
                                    <router-link :to="{ name: 'quote_edit', params: { id: quote.id } }">
                                        <i class="fas fa-edit mx-2 text-green-500"></i>
                                    </router-link>
                                    <button @click="showDeleteModal(quote)">
                                        <i class="far fa-trash-alt ml-2 text-red-500"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="!data_length && processed === true" class="w-full text-center">
                        <span class="text-xl text-gray-400 font-bold">No database enteries</span>
                    </div>
                </div>
            </div>

            <div class="flex">
                <router-link :to="{ name: 'quote_create' }" class="py-8 pr-8 flex-1">
                    <div class="rounded-md full-shadow p-6 py-10 text-center text-xl">
                        <div class="p-4">
                            <i class="fas fa-plus text-5xl text-green-500"></i>
                        </div>
                        <div class="p-4">
                            <span>Create a Quote</span>
                        </div>
                    </div>
                </router-link>

                <router-link :to="{ name: 'products' }" class="py-8 pl-8 flex-1">
                    <div class="rounded-md full-shadow p-6 py-10 text-center text-xl">
                        <div class="p-4">
                            <i class="fas fa-box-open text-5xl text-brown"></i>
                        </div>
                        <div class="p-4">
                            <span>View Products</span>
                        </div>
                    </div>
                </router-link>
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
            heading:     'Home',
            quote_list:  [],
            data_length: 0,
            processed:   false,
            crumbs:      [
                              'home'
            ],
            crumbTags:   [
                              'Home'
            ],
            showModal:   false,
            current:     null
        }
    },

    created() {
        this.getResults();
    },

    methods: {
        // Get the 5 most recently created quotes.
        getResults() {
            axios.get('/quotes')
                .then(({data}) => {
                    this.quote_list  = data
                    this.data_length = this.quote_list.length;
                    this.processed   = true;
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Display the delete confirmation modal.
        showDeleteModal(quote) {
            this.showModal = true;
            this.current   = quote;
        },

        // Hide the delete confirmation modal.
        hideDeleteModal() {
            this.showModal = false;
        },

        // Delete a quote.
        deleteQuote(id) {
            axios.delete('/quotes/' + id)
                .then(() => {
                    this.hideDeleteModal();
                    this.getResults();
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        }
    }
};
</script>