<template>
    <div class="width-full">
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

        <div class="max-w-7xl mx-auto p-10 pb-0 flex justify-between">
            <div class="">
                <select @change="getSelected($event)" name="pagination" id="pagination" class="rounded-md cursor-pointer">
                    <option v-for="size in pageSizes" :key="size" :value="size">
                        {{ size }}
                    </option>
                </select>
            </div>
            <div class="flex">
                <div class="field-container w-64 h-full ml-2 mb-2">
                    <input @keyup="getResults" v-model="search" type="text" id="product-search" placeholder=" " class="field rounded-md">
                    <label class="label bg-gray-100 top-2" for="product-search">Search</label>
                </div>
                <router-link :to="{ name: 'quote_create' }" class="py-1 px-3 bg-green-500 rounded-md ml-3">
                    <i class="far fa-plus-square text-2xl text-white"></i>
                </router-link>
            </div>
        </div>

        <div class="max-w-7xl mx-auto p-10">
            <div v-if="processed === false">
                <span class="text-green-400">Processing...</span>
            </div>
            <table v-if="data_length" class="w-full bg-white">
                <thead>
                    <tr>
                        <th @click="changeSort('id')" class="border border-gray-300 p-2 cursor-pointer">
                            <span class="font-medium">ID</span>
                            <i v-if="sort_field === 'id' && sort_direction === 'DESC'" class="fas fa-caret-down ml-2"></i>
                            <i v-if="sort_field === 'id' && sort_direction === 'ASC'" class="fas fa-caret-up ml-2"></i>
                        </th>
                        <th @click="changeSort('client_first_name')" class="border border-gray-300 p-2 cursor-pointer">
                            <span class="font-medium">Client Name</span>
                            <i v-if="sort_field === 'client_first_name' && sort_direction === 'DESC'" class="fas fa-caret-down ml-2"></i>
                            <i v-if="sort_field === 'client_first_name' && sort_direction === 'ASC'" class="fas fa-caret-up ml-2"></i>
                        </th>
                        <th @click="changeSort('total')" class="border border-gray-300 p-2 cursor-pointer">
                            <span class="font-medium">Total Price</span>
                            <i v-if="sort_field === 'total' && sort_direction === 'DESC'" class="fas fa-caret-down ml-2"></i>
                            <i v-if="sort_field === 'total' && sort_direction === 'ASC'" class="fas fa-caret-up ml-2"></i>
                        </th>
                        <th @click="changeSort('created_at')" class="border border-gray-300 p-2 cursor-pointer">
                            <span class="font-medium">Created at</span>
                            <i v-if="sort_field === 'created_at' && sort_direction === 'DESC'" class="fas fa-caret-down ml-2"></i>
                            <i v-if="sort_field === 'created_at' && sort_direction === 'ASC'" class="fas fa-caret-up ml-2"></i>
                        </th>
                        <th @click="changeSort('updated_at')" class="border border-gray-300 p-2 cursor-pointer">
                            <span class="font-medium">updated at</span>
                            <i v-if="sort_field === 'updated_at' && sort_direction === 'DESC'" class="fas fa-caret-down ml-2"></i>
                            <i v-if="sort_field === 'updated_at' && sort_direction === 'ASC'" class="fas fa-caret-up ml-2"></i>
                        </th>
                        <th @click="changeSort('completed')" class="border border-gray-300 p-2 cursor-pointer">
                            <span class="font-medium">Completed</span>
                            <i v-if="sort_field === 'completed' && sort_direction === 'DESC'" class="fas fa-caret-down ml-2"></i>
                            <i v-if="sort_field === 'completed' && sort_direction === 'ASC'" class="fas fa-caret-up ml-2"></i>
                        </th>
                        <th class="border border-gray-300 p-2"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(quote, index) in quote_list.data" :key="index">
                        <td class="text-center font-light border border-gray-300 p-1">
                            <router-link :to="{ name: 'quote', params: {id: quote.id} }" class="hover:underline">
                                {{ quote.id }}
                            </router-link>
                        </td>
                        <td class="text-center font-light border border-gray-300 p-1">
                            {{ quote.client_first_name | ucFirstLetter }} {{ quote.client_last_name | ucFirstLetter }}
                        </td>
                        <td class="text-center font-light border border-gray-300 p-1">
                            {{ quote.total * 1.2 | priceFormat }}
                        </td>
                        <td class="text-center font-light border border-gray-300 p-1">
                            {{ quote.created_at | dateFormat }}
                        </td>
                        <td class="text-center font-light border border-gray-300 p-1">
                            {{ quote.updated_at | dateFormat }}
                        </td>
                        <td class="text-center font-light border border-gray-300 p-1">
                            {{ quote.completed }}
                        </td>
                        <td class="text-center border border-gray-300 p-1">
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
            
            <div v-if="!data_length && processed === true" class="text-center w-full pt-10">
                <span class="py-2 text-xl font-bold text-gray-400">No database enteries</span>
            </div>

            <pagination 
                v-if="data_length"
                :meta="meta" 
                v-on:pagination="getResults" 
                :data="quote_list" 
                @pagination-change-page="getResults"
                class="pt-10">
            </pagination>
        </div>
    </div>
</template>

<script>
const default_layout = "default";
import breadcrumb from './components/BreadcrumbComponent.vue';
import confirmDelete from './components/DeleteComponent.vue';

export default {
    components: { breadcrumb, confirmDelete },

    data() {
        return {
            heading: 'Quotes',
            quote_list: [],
            data_length: 0,
            meta: {},
            pageSize: 10,
            pageSizes: [10, 25, 50, 100, 250, 500],
            search: "",
            sort_field: 'created_at',
            sort_direction: 'DESC',
            processed: false,
            crumbs: [
                'home',
                'quotes'
            ],
            crumbTags: [],
            showModal: false,
            current: null
        }
    },

    created() {
        this.getResults();

        this.getCrumbs();
    },

    methods: {
        // Get the request parameters for the quotes.
        getRequestParams(search, pageSize, sort_field) {
            let params = {};

            if (search) {
                params['search'] = search;
            }
            if (pageSize) {
                params['size'] = pageSize;
            }
            if (sort_field) {
                params['sort_field'] = sort_field;
                params['sort_direction'] = this.sort_direction;
            }

            return params;
        },

        // Get the quotes list depending on the parameters provided.
        getResults(page) {
            axios.get('/quotes?page=' + page, {
                'params': this.getRequestParams(this.search, this.pageSize, this.sort_field)
            })
                .then(({data}) => {
                    this.quote_list = data;
                    this.meta = data.meta;
                    this.pageSize = data.meta.per_page;
                    this.data_length = this.quote_list.data.length;
                    this.processed = true;
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Get the crumb links text.
        getCrumbs() {
            axios.post('/crumbs', {
                crumbs: this.crumbs
            })
                .then(({data}) => {
                    this.crumbTags = data;
                })
                .catch((e) => {
                    console.log(e.response.data);
                })
        },

        // Get the selected paginate value.
        getSelected(e) {
            this.pageSize = e.target.value;
            this.getResults();
        },

        // Change the sort field / direction on table columns.
        changeSort(field) {
            if (this.sort_field != field) {
                this.sort_field = field;
                if (field === 'created_at' || field === 'updated_at') {
                    this.sort_direction = 'DESC';
                } else {
                    this.sort_direction = 'ASC'
                }
            } else {
                this.sort_direction === 'DESC' ? this.sort_direction = 'ASC' : this.sort_direction = 'DESC'
            }

            this.getResults();
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