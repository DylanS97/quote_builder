import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'home',
        components: require('./views/Home')
    },
    {
        path: '/quotes',
        name: 'quotes',
        components: require('./views/Quotes')
    },
    {
        path: '/quotes/:id',
        name: 'quote',
        components: require('./views/Quote')
    },
    {
        path: '/quote/create',
        name: 'quote_create',
        components: require('./views/QuoteCreate')
    },
    {
        path: '/quotes/:id/edit',
        name: 'quote_edit',
        components: require('./views/QuoteEdit')
    },
    {
        path: '/products',
        name: 'products',
        components: require('./views/Products')
    },
    {
        path: '/products/:id',
        name: 'product',
        components: require('./views/Product')
    },
    {
        path: '/product/create',
        name: 'product_create',
        components: require('./views/ProductCreate')
    },
    {
        path: '/products/:id/edit',
        name: 'product_edit',
        components: require('./views/ProductEdit')
    },
    {
        path: '/products/:id/images',
        name: 'product_images',
        components: require('./views/ProductImages')
    },
];

export default new VueRouter({
    routes
});