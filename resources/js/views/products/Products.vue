<template>
    <div id="products-page" class="width-full">
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

        <div class="filters-search-cont max-w-7xl mx-auto p-10 pb-0">
            <div class="filter-sort-cont">
                <div class="mr-3">
                    <select @change="getSelectedPaginate($event)" name="pagination" id="pagination" class="rounded-md cursor-pointer">
                        <option v-for="size in pageSizes" :key="size" :value="size">
                            {{ size }}
                        </option>
                    </select>
                </div>

                <div class="">
                    <select @change="getSelectedFilter($event)" name="category" id="category" class="rounded-md">
                        <option value="" :selected="!filter" disabled>-- Select Category --</option>
                        <option value="DESC">Latest to Oldest</option>
                        <option value="ASC">Oldest to Latest</option>
                        <option value="">No filter</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between">
                <div class="field-container w-64 h-full mb-2">
                    <input @keyup="getResults" v-model="search" type="text" id="product-search" placeholder=" " class="field rounded-md">
                    <label class="label bg-gray-100 top-2" for="product-search">Search</label>
                </div>
                <router-link title="Create a Product" :to="{ name: 'product_create' }" class="py-1 px-3 bg-green-500 rounded-md ml-3">
                    <i class="far fa-plus-square text-white text-2xl"></i>
                </router-link>
            </div>
        </div>

        <div v-if="processed === false" class="max-w-7xl px-10 mx-auto pt-10">
            <span class="text-green-400">Processing...</span>
        </div>
        
        <div class="max-w-7xl p-10 mx-auto grid small-grid grid-cols-5" style="grid-gap: 30px;">
            <router-link :to="{ name: 'product', params: { id: product.id } }" 
                         class="product p-2 full-shadow rounded-md" 
                         v-for="(product, index) in product_items" :key="index">
                <div class="rounded-t-md overflow-hidden shadow-lg">
                    <img class="mx-auto" v-if="product.images[0]" :src="'storage/product_images/' + product.images[0].source" 
                         :alt="product.images[0].alt">
                    <img class="mx-auto" v-if="!product.images[0]" src="storage/images/ph.jpg" 
                         alt="Placeholder">
                </div>
                <div class="flex justify-between py-4">
                    <div class="flex-1 pr-4">
                        <span class="max-w-100 overflow-hidden product-name">{{ product.name | ucFirstLetter }}</span>
                    </div>
                    <div>
                        <span class="text-right" style="height: min-content;">{{ product.price | priceFormat }}</span>
                    </div>
                </div>
            </router-link>
        </div>

        <div v-if="!data_length && processed === true" class="max-w-7xl px-10 mx-auto text-center">
            <span class="text-xl font-bold text-gray-400">No database enteries</span>
        </div>

        <div class="max-w-7xl px-10 pb-10 mx-auto">
            <pagination 
                :meta="meta" 
                v-if="data_length"
                v-on:pagination="getResults" 
                :data="product_items" 
                @pagination-change-page="getResults">
            </pagination>
        </div>
    </div>
</template>

<script>
import breadcrumb from '../components/BreadcrumbComponent.vue';
const default_layout = "default";

export default {
    components: { breadcrumb },
    data() {
        return {
            heading:       'Products',
            product_items: [],
            data_length:   0,
            image:         '',
            meta:          {},
            pageSize:      10,
            search:        '',
            pageSizes:     [10, 25, 50, 100, 250, 500],
            filter:        '',
            processed:     false,
            crumbs:        [
                                'home',
                                'products'
            ],
            crumbTags:     [
                                'Home',
                                'Products'
            ]
        }
    },

    created() {
        this.getResults();
    },

    methods: {
        // Get the products.
        getResults(page) {
            axios.get('/products?page=' + page, {
                'params': this.getRequestParams(this.search, this.pageSize, this.filter)
            })
                .then(({data}) => {
                    this.product_items = data.data;
                    this.meta          = data.meta;
                    this.pageSize      = data.meta.per_page;
                    this.data_length   = this.product_items.length;
                    this.processed     = true;
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Get selected filter value.
        getSelectedFilter(e) {
            this.filter = e.target.value;
            this.getResults();
        }
    }
};

</script>