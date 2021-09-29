<template>
    <nav id="pagination-cont" aria-label="Page navigation example" class="flex justify-between mt-4">
        <div class="flex flex-col justify-center border border-gray-250 px-2 bg-white rounded-md">
            <span class="text-blue-400">Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} entries</span>
        </div>
        <ul class="flex">
            <li>
                <a title="Start" @click.prevent="changePage(meta.first_page)"
                    class="cursor-pointer px-4 py-1 relative block bg-white border border-gray-250 text-blue-400 hover:bg-gray-100 rounded-l-md"
                    :class="{'disabled':meta.current_page === 1}">
                    <i class="fas fa-angle-double-left"></i>
                </a>
            </li>
            <li>
                <a title="Previous page" @click.prevent="changePage(meta.current_page-1)"
                    class="cursor-pointer px-4 py-1 relative block bg-white border border-gray-250 text-blue-400 hover:bg-gray-100"
                    :class="{'disabled':meta.current_page === 1}">
                    <i class="fas fa-angle-left"></i>
                </a>
            </li>
            <li :class="{'active':meta.current_page === p}" v-for="(p, index) in meta.last_page" :key="index">
                <a v-if="paginationCheck(p) == true" @click.prevent="changePage(p)"
                    class="cursor-pointer px-4 py-1 relative block bg-white border border-gray-250 text-blue-400 hover:bg-gray-100">
                    {{ p }}
                </a>
            </li>
            <li>
                <a title="Next page" @click.prevent="changePage(meta.current_page+1)" 
                    class="cursor-pointer px-4 py-1 relative block bg-white border border-gray-250 text-blue-400 hover:bg-gray-100" 
                    :class="{'disabled':meta.current_page === meta.last_page}">
                    <i class="fas fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a title="End" @click.prevent="changePage(meta.last_page)"
                    class="cursor-pointer px-4 py-1 relative block bg-white border border-gray-250 text-blue-400 hover:bg-gray-100 rounded-r-md"
                    :class="{'disabled':meta.current_page === meta.last_page}">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    props: ['meta'],

    methods: {
        changePage(page) {
            if(page <= 0 || page > this.meta.last_page) {
                return;
            }
            this.$emit('pagination', page)
        },

        paginationCheck(p) {
            if (this.meta.current_page === this.meta.last_page) {
                if (p >= (this.meta.current_page - 4)) {
                    return true;
                }
            } else if (this.meta.current_page === (this.meta.last_page - 1)) {
                if (p >= (this.meta.current_page - 3)) {
                    return true;
                }
            } else if (this.meta.current_page >= 3) {
                if (p <= (this.meta.current_page + 2) && p >= (this.meta.current_page - 2)) {
                    return true;
                }
            } else if (this.meta.current_page == 2) {
                if (p === 1 || p <= this.meta.current_page + 3) {
                    return true;
                }
            } else if (this.meta.current_page === 1) {
                if (p <= 5) {
                    return true;
                }
            }

            // if (p >= (this.meta.current_page - 2) && p <= (this.meta.current_page + 2)) { return true }

            return false;
        }
    }
}
</script>