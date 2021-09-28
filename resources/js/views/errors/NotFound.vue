<template>
    <div class="contents">
        <div class="max-w-7xl mx-auto">
            <div class="p-4 w-96 mx-auto mt-28">
                <h1 class="text-8xl text-center mb-5">404</h1>
                <p class="text-2xl text-center mb-5">Page Not Found</p>
                <div class="w-min mx-auto">
                    <a class="px-4 py-2 mx-auto rounded-md bg-blue-500 text-white" :href="back()">Back</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
const default_layout = "default";

export default {
    data() {
        return {
            //
        }
    },

    methods: {
        back() {
            return window.location.hash.replace('/404', '');
        }
    }
};
</script>