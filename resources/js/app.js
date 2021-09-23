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

const app = new Vue({
    el: '#app',

    router
});

Vue.filter('ucFirstLetter', function (value) {
    if (value) {
        return value.charAt(0).toUpperCase() + value.slice(1);
    }
    return null;
});

Vue.filter('dateFormat', function (value) {
    if (value) {
        let date = new Date(value);

        const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
        let formatted_date = date.toLocaleDateString('en-GB', options);

        let formatted_time = date.toLocaleTimeString('en-GB');

        return formatted_time + ' on ' + formatted_date;
    }
});

Vue.filter('priceFormat', function (value) {
    if (value) {
        return value.toLocaleString("en-GB", {
            style: "currency",
            currency: "GBP",
            currencyDisplay: 'symbol',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        })
    }
});

// Vue.mixin({
//     methods: {
//         // Display the delete confirmation modal.
//         showDeleteModal(data) {
//             this.showModal = true;
//             this.current = data;
//         },

//         // Hide the delete confirmation modal.
//         hideDeleteModal() {
//             this.showModal = false;
//         }
//     }
// });