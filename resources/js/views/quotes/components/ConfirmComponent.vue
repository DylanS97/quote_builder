
<template>
    <div v-if="email || quote" :class="email === true || quote === true ?  'absolute bottom-10 right-10 bg-green-100 border-2 border-green-200' : 'absolute bottom-10 right-10 bg-red-100 border-2 border-red-200'">
        <i @click="email = null; quote = null" class="fa fa-times float-right cursor-pointer text-xl text-red-400 pt-1 pr-2"></i>
        <div class="p-4 pt-2">
            <i class="fa fa-check block text-3xl text-green-500 text-center py-2"></i>
            <span class="block text-xl font-semibold text-gray-400" v-if="email">Email Sent Successfully</span>
            <span class="block text-xl font-semibold text-gray-400" v-if="email === false">Email Failed To Send</span>
            <span class="block text-xl font-semibold text-gray-400" v-if="quote">Quote Completed!</span>
            <span class="block text-xl font-semibold text-gray-400" v-if="quote === false">Uncompleted Quote</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'confirmModal',

    data() {
        return {
            email: '',
            quote: ''
        }
    },

    props: ['emailSuccess', 'quoteSuccess'],

    computed: {
        email: this.emailSuccess,
        quote: this.quoteSuccess
    },

    methods: {
        text() {
            if (this.email === true || this.email === false) {
                if (this.email === true) {
                    return 'Email Sent Successfully';
                } else if (this.email === false) {
                    return 'Error Sending Email';
                }
            } else if (this.quote === true || this.quote === false) {
                if (this.quote === true) {
                    return 'Quote Completed!';
                } else if (this.quote === false) {
                    return 'Uncompleted Quote!';
                }
            }
        }
    }
}
</script>