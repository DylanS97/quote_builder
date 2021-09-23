require('./bootstrap');

require('alpinejs');

import { defaultTo, mixin, now, split } from 'lodash';
import Vue from 'vue';
import router from './routes';

Vue.use(require('vue-resource'));

Vue.component('pagination', require('./views/components/PaginationComponent.vue').default);

Vue.component('cart', require('./views/quotes/components/CartComponent.vue').default);

Vue.component('breadcrumb', require('./views/components/BreadcrumbComponent.vue').default);

Vue.component('delete', require('./views/components/DeleteComponent.vue'));

Vue.component('details-tab', require('./views/quotes/components/DetailsTabComponent.vue'));

Vue.component('products-tab', require('./views/quotes/components/ProductsTabComponent.vue'));

Vue.mixin({
    methods: {
        // Display the delete confirmation modal.
        showDeleteModal(data) {
            this.showModal = true;
            this.current = data;
        },
        
        // Get selected paginate value.
        getSelectedPaginate(e) {
            this.pageSize = e.target.value;
            this.getResults();
        },

        // Gets the products.
        getProducts() {
            axios.get('/products', {
                'params': this.getRequestParams(this.search)
            })
                .then(({data}) => {
                    this.product_items = data;
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Get the request parameters for retrieving the products.
        getRequestParams(search = null, pageSize = null, filter = null, sort_field = null, sort_direction = null) {
            let params = {};

            if (search) {
                params['search'] = search;
            }
            if (pageSize) {
                params['size']   = pageSize;
            }
            if (filter) {
                params['filter'] = filter;
            }
            if (sort_field) {
                params['sort_field']     = sort_field;
                params['sort_direction'] = sort_direction;
            }

            return params;
        },

        // Delete the quote.
        deleteQuote(id) {
            axios.delete('/quotes/' + id)
                .then(() => {
                    if (window.location.hash === '#/quotes/' + id) {
                        window.location.hash = '#/quotes';
                    } else {
                        this.hideDeleteModal();
                        this.getResults();
                    }
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                })
        },

        // Adds overflow scroll to cart when height gets above 544px.
        cartScroll() {
            let height = $('#item-list').height();
            if(height >= 440) {
                this.scroll = true;
            } else {
                this.scroll = false;
            }
        },

        // Toggle between products and details.
        toggleShow() {
            if (this.show_products) {
                this.show_products = false;
            } else {
                this.show_products = true;
            }
        },

        // Get the cart data.
        cartData(id) {
            let data          = {};
            data['id']        = id;
            data['cart']      = this.cart;
            data['sub_total'] = this.subTotal;

            return data;
        },

        // Generate the data from createQuote.
        generateData(cart, details) {
            details['sub_total'] = this.subTotal;

            let params        = {};
            params['cart']    = cart;
            params['details'] = details;

            return params;
        },
    },
    filters: {
        ucFirstLetter(value) {
            if (value) {
                return value.charAt(0).toUpperCase() + value.slice(1);
            }
            return null;
        },

        priceFormat(value) {
            if (value) {
                return value.toLocaleString("en-GB", {
                    style: "currency",
                    currency: "GBP",
                    currencyDisplay: 'symbol',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                })
            }
        },

        dateFormat(value) {
            if (value) {
                let date = new Date(value);
        
                const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
                let formatted_date = date.toLocaleDateString('en-GB', options);
        
                let formatted_time = date.toLocaleTimeString('en-GB');
        
                return formatted_time + ' on ' + formatted_date;
            }
        },
    }
});

const app = new Vue({
    el: '#app',

    router
});