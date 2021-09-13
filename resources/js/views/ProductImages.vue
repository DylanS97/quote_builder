<template>
    <div class="width-full">
        <confirmDelete :data="current" v-show="showModal" :deleteData="deleteImage" :hideDeleteModal="hideDeleteModal"></confirmDelete>
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

        <div class="flex max-w-7xl mx-auto p-10">
            <form @submit.prevent="imageUpload" class="flex-1 p-4 pl-0 mr-4 bg-white rounded-md" action="javascript:;" method="post" enctype="multipart/form-data">
                <header class="mb-4">
                    <div class="p-2 mb-4 bg-gray-300 pl-4 w-52 relative border-t border-b border-gray-600 overflow-hidden">
                        <h1 class="text-lg font-normal">Add Image</h1>
                        <div class="title-tag bg-white h-9 w-9 top-1 left-48"></div>
                    </div>
                </header>
                <div class="flex-1 mb-4 pl-4 w-100">
                    <div>
                        <input class="my-4" id="image-input" type="file" accept="image/*" name="source" @change="imageSelected">
                        <span v-if="errors.source" class="block text-red-500">{{ errors.source[0] }}</span>
                        <span v-if="errors.source" class="block text-red-500">{{ errors.source[1] }}</span>
                    </div>
                </div>
                <div class="flex-1 my-8 pl-4 w-100">
                    <div class="field-container h-12">
                        <input class="field rounded-md" id="alt" type="text" v-model="alt" placeholder=" ">
                        <label class="label bg-white top-3" for="alt">Alt</label>
                        <span v-if="errors.alt && alt === ''" class="text-red-500">{{ errors.alt[0] }}</span>
                    </div>
                </div>
                <button class="flex-1 float-right px-4 py-3 bg-green-500 rounded-md text-white">Add Image</button>
            </form>

            <div class="flex-1 ml-4 rounded-md overflow-hidden">
                <img class="rounded-md" id="preview-image" src="storage/images/ph.jpg" alt="Image Preview">
            </div>
        </div>

        <div class="max-w-7xl mx-auto p-10">
            <div v-if="images.length === 0" class="w-100 bg-white p-4 rounded-md text-center">
                <span class="text-2xl font-medium text-gray-400">No images for product.</span>
            </div>
            <div v-if="images.length > 0" class="w-100 grid grid-cols-4 bg-white p-4 rounded-md" style="grid-gap: 30px;">
                <div class="relative" v-for="(image, index) in images" :key="index">
                    <img class="p-2" :src="'storage/product_images/' + image.source" :alt="image.alt">
                    <form @submit.prevent="showDeleteModal(image)" class="">
                        <button class="absolute py-2 px-4 bg-red-500 top-0 right-0 text-white">
                            <i class="fas fa-trash-alt text-lg text-white"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto flex justify-end px-2">
            <button @click="done" class="w-32 py-2 bg-green-500 text-white rounded-md mx-8">Done</button>
        </div>
    </div>
</template>

<script>
import breadcrumb from './components/BreadcrumbComponent.vue';
import confirmDelete from './components/DeleteComponent.vue';
const default_layout = "default";

export default {
    components: { breadcrumb, confirmDelete },
    data() {
        return {
            heading: 'Edit Images',
            id: '',
            images: {},
            source: null,
            alt: '',
            crumbs: [
                'home',
                'products',
                'product',
                'product_images'
            ],
            crumbTags: [
                'home',
                'products'
            ],
            errors: [],
            showModal: false,
            current: null
        }
    },

    created() {
        const url = new URL(window.location.href);
        this.id = url.hash.match(/(\d+)/)[0];

        this.getImages();
    },

    methods: {
        // Get the product images.
        getImages() {
            axios.get('/products/' + this.id)
                .then(({data}) => {
                    this.images = data['images'];
                    this.crumbTags.push(data['name']);
                    this.crumbTags.push('images');

                    this.getCrumbs();
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
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

        // Preciew uploaded image.
        imageSelected(e) {
            this.source = e.target.files[0];

            let reader = new FileReader();
            reader.readAsDataURL(this.source);
            reader.onload = e => {
                $('#preview-image').attr('src', e.target.result);
            };
        },

        // Upload the image.
        imageUpload() {
            let data = new FormData;
            data.append('source', this.source);
            data.append('alt', this.alt);

            axios.post(this.id + '/images/create', data)
                .then(() => {
                    this.alt = '';
                    this.getImages();
                    this.errors = [];
                })
                .catch(e => {
                    if (e.response) {
                        this.errors = e.response.data.errors;
                    }
                })
        },

        // Display the delete confirmation modal.
        showDeleteModal(image) {
            this.showModal = true;
            this.current = image;
        },

        // Hide the delete confirmation modal.
        hideDeleteModal() {
            this.showModal = false;
        },

        // Delete an image.
        deleteImage(id) {
            axios.delete('product_images/' + id)
                .then(() => {
                    this.showModal = false;
                    this.getImages();
                })
        },

        // Navigate back to product view.
        done() {
            window.location.hash = '#/products/' + this.id;
        }
    }

};

</script>