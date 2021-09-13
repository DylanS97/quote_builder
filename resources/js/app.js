require('./bootstrap');

require('alpinejs');

import { defaultTo, split } from 'lodash';
import Vue from 'vue';
import router from './routes';

Vue.use(require('vue-resource'));

Vue.component('pagination', require('./views/components/PaginationComponent.vue').default);

Vue.component('cart', require('./views/components/CartComponent.vue').default);

Vue.component('breadcrumb', require('./views/components/BreadcrumbComponent.vue'));

Vue.component('delete', require('./views/components/DeleteComponent.vue'));

const app = new Vue({
    el: '#app',
    router
});

Vue.filter('ucFirstLetter', function (value) {
    // let up = value.charAt(0).toUpperCase();
    // if (value.charAt(0) === up) {
    //     return value;
    // }
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
