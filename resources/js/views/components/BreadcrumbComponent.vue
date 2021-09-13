<template>
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul class="inline-block min-w-min border border-gray-250 px-2 bg-white rounded-md">
            <li class="inline-block" :class="{ crumb_links: !isLast(index) }" v-for="(crumb, index) in crumbs" :key="index">
                <router-link class="text-blue-400 p-2" :class="{ is_last: isLast(index) }" :to="{ name: crumb }" >
                    {{ tags[index] }}
                </router-link>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: 'breadcrumb',
    
    props: {
        crumbs: {
            type: Array,
            required: true
        },

        tags: {
            type: Array,
            required: true
        }
    },

    methods: {
        isLast(index) {
            return index === this.crumbs.length - 1;
        },

        selected(crumb) {
            this.$emit('selected', crumb);
        }
    }
}
</script>