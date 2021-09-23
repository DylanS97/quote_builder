<template>
    <div class="width-full">
        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-medium text-xl text-gray-800 leading-tight">
                    Edit Product - {{ name }}
                </h2>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-8 pt-8">
            <breadcrumb :crumbs="crumbs" :tags="crumbTags"></breadcrumb>
        </div>

        <div class="max-w-7xl mx-auto p-10">
            <form @submit.prevent="updateProduct" class="p-6 bg-white shadow-2xl rounded-md" method="patch">
                <div class="flex mt-4">
                    <div class="flex-1 px-4 mb-4">
                        <div class="field-container h-14 w-100">
                            <input class="field rounded-md font-light" placeholder=" " type="text" id="name" v-model="name">
                            <label class="label bg-white top-4 font-normal" for="name">Product Name</label>
                        </div>
                        <span v-if="errors.name" class="text-red-500">{{ errors.name[0] }}</span>
                    </div>
                    
                    <div class="flex-1 px-4 mb-4">
                        <div class="field-container h-14 w-100">
                            <input class="field rounded-md font-light" type="text" placeholder=" " id="price" v-model="price">
                            <label class="label bg-white top-4 font-normal" for="price">Product Price</label>
                        </div>
                        <span v-if="errors.price" class="text-red-500">{{ errors.price[0] }}</span>
                    </div>
                </div>

                <div class="px-4 my-4">
                    <div class="field-container h-80 w-100">
                        <textarea class="field rounded-md font-light pt-4" placeholder=" " id="description" cols="30" rows="10" v-model="description"></textarea>
                        <label class="label bg-white top-2 font-normal" for="description">Product Description</label>
                    </div>
                    <span v-if="errors.description" class="text-red-500">{{ errors.description[0] }}</span>
                </div>

                <div class="flex justify-end p-4">
                    <button @click="goBack" class="w-32 py-2 bg-red-500 text-white rounded-md mx-8">Cancel</button>
                    <button class="w-32 py-2 bg-green-500 text-white rounded-md ml-8">Save changes</button>
                </div>
            </form>
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
            id:          '',
            name:        '',
            description: '',
            price:       '',
            crumbs:      [
                            'home',
                            'products',
                            'product',
                            'product_edit'
            ],
            crumbTags:   [
                            'home',
                            'products'
            ],
            errors:      []
        }
    },

    created() {
        const url = new URL(window.location.href);
        this.id   = url.hash.match(/(\d+)/)[0];

        axios.get('/products/' + this.id)
            .then(({data}) => {
                this.name        = data.name;
                this.description = data.description;
                this.price       = data.price;
                this.errors      = [];

                this.crumbTags.push(this.name);
                this.crumbTags.push('Edit');
                this.getCrumbs();
            })
            .catch((e) => {
                console.log(e.response.data.message);
            })
    },

    methods: {
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

        // Update the product.
        updateProduct() {
            axios.patch('/products/' + this.id, {
                name:        this.name,
                description: this.description,
                price:       this.price
            })
                .then(() => {
                    this.goBack();
                })
                .catch((e) => {
                    this.errors = e.response.data.errors;
                })
        },

        // Redirect back.
        goBack() {
            window.location.hash = '#/products/' + this.id;
        }
    }
};
</script>