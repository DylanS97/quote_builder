<template>
    <div class="width-full">

        <confirmDelete :data="product" v-show="showModal" :deleteData="deleteProduct" :hideDeleteModal="hideDeleteModal"></confirmDelete>
        
        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-normal text-xl text-gray-800 leading-tight">
                    {{ product.name | ucFirstLetter }}
                </h2>
            </div>
        </header>

        <div class="max-w-7xl mx-auto p-10">
            <div class="flex justify-between">
                <div class="">
                    <breadcrumb :crumbs="crumbs" :tags="crumbTags"></breadcrumb>
                </div>
                <div class="flex justify-end">
                    <a title="Delete Product" href="javascript:;" v-on:click="showDeleteModal(product)" class="mx-2 py-2 px-4 bg-red-500 rounded-md">
                        <i class="fas fa-trash-alt text-lg text-white"></i>
                    </a>
                    <router-link title="Edit Images" :to="{ name: 'product_images', params: { id: id } }" class="mx-2 py-2 px-4 bg-blue-500 rounded-md">
                        <i class="fas fa-images text-lg text-white"></i>
                    </router-link>
                    <router-link title="Edit Product" :to="{ name: 'product_edit', params: { id: id } }" class="ml-2 mr-4 py-2 px-4 bg-green-500 rounded-md">
                        <i class="fas fa-edit text-lg text-white"></i>
                    </router-link>
                </div>
            </div>

            <div id="product-det-img-cont" class="min-h-300">
                <div class="prod-details flex-1 shadow-xl bg-white rounded-md m-3 ml-0 p-4 pl-0 relative">
                    <div class="p-2 mb-4 bg-gray-300 pl-4 w-52 relative border-t border-b border-gray-600 overflow-hidden">
                        <h1 class="text-lg font-normal">Details</h1>
                        <div class="title-tag bg-white h-9 w-9 top-1 left-48"></div>
                    </div>
                    <div class="flex flex-col justify-evenly h-full absolute top-0 left-0 w-full px-4">
                        <div class="flex justify-between py-2">
                            <h2 class="font-normal">Product Name:</h2>
                            <span class="font-light">{{ product.name | ucFirstLetter }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <h2 class="font-normal">Product Price:</h2>
                            <span class="font-light">{{ product.price | priceFormat }}</span>
                        </div>
                    </div>
                </div>

                <div id="prod-imgs-cont" class="flex-1 shadow-xl bg-white rounded-md m-3 mr-0 p-4 pl-0">
                    <div class="p-2 mb-4 bg-gray-300 pl-4 w-52 relative border-t border-b border-gray-600 overflow-hidden">
                        <h1 class="text-lg font-normal">Images</h1>
                        <div class="title-tag bg-white h-9 w-9 top-1 left-48"></div>
                    </div>
                    <div class="flex">
                        <div id="img-cont" class="flex-1 mx-auto pl-4" style="width: 75%;">
                            <img @load="getHeight" v-if="imageTotal === 0" id="image-preview" class="w-full h-auto" src="/storage/images/ph.jpg">
                            <img @load="getHeight" v-if="imageTotal > 0" id="image-preview" class="w-full h-auto" :src="'/storage/product_images/' + product.images[0].source" :alt="product.images[0].alt">
                        </div>
                        <div id="image-scroll" v-if="imageTotal > 1" class="overflow-y-scroll px-2 h-50" style="width: 25%;">
                            <div class="" v-for="(image, index) in product.images" :key="index">
                                <img v-if="index >= 1" class="cursor-pointer my-1" @click="imageSwap" :src="'/storage/product_images/' + image.source" :alt="image.alt">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="prod-desc-cont" class="w-100 shadow-xl bg-white rounded-md mt-3 p-4 pl-0">
                <div class="p-2 mb-6 bg-gray-300 pl-4 w-56 relative border-t border-b border-gray-600 overflow-hidden">
                    <h1 class="text-lg font-normal">Description</h1>
                    <div class="title-tag bg-white h-9 w-9 top-1 left-52"></div>
                </div>
                <div class="pl-4">
                    <p class="font-light">{{ product.description }}</p>
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
            id:         '',
            product:    [],
            form:       [],
            imageTotal: 0,
            crumbs:     [
                            'home',
                            'products',
                            'product'
            ],
            crumbTags:  [
                            'home',
                            'products'
            ],
            showModal:  false
        }
    },

    created() {
        const url = new URL(window.location.href);
        this.id   = url.hash.match(/(\d+)/)[0];

        axios('/products/' + this.id)
            .then(({data}) => {
                this.product    = data;
                this.imageTotal = this.count(data.images);

                this.crumbTags.push(data['name']);
                this.getCrumbs();
            })
            .catch(e => {
                console.log(e.response.data.message);
                window.location.hash = '#/products/404'
            })
            
    },

    methods: {
        // Get the length of a given object.
        count(data) {
            return data.length;
        },

        // Get the crumb links text.
        getCrumbs() {
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

        // Get the height for scrollable image div to the same height as the preview image.
        getHeight() {
            let height = $('#image-preview').css('height');

            $('#image-scroll').height(height);
        },

        // Swaps image in scroll div with preview image.
        imageSwap(e) {
            let clicked = e.target.attributes.src.value;
            let current = $('#image-preview').attr('src');

            $('#image-preview').attr('src', clicked);
            $(e.target).attr('src', current);
        },

        // Hide the delete confirmation modal.
        hideDeleteModal() {
            this.showModal = false;
        },
        
        // Delete the product.
        deleteProduct(id) {
            axios.delete('/products/' + id)
                .then(() => {
                    window.location.href = '#/products';
                })
        }
    }
};

</script>