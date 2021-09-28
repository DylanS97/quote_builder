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
        components: require('./views/quotes/Quotes')
    },
    {
        path: '/quotes/404',
        name: 'quote_not_found',
        components: require('./views/errors/NotFound')
    },
    {
        path: '/quotes/:id',
        name: 'quote',
        components: require('./views/quotes/Quote')
    },
    {
        path: '/quote/create',
        name: 'quote_create',
        components: require('./views/quotes/QuoteCreate')
    },
    {
        path: '/quotes/:id/edit',
        name: 'quote_edit',
        components: require('./views/quotes/QuoteEdit')
    },
    {
        path: '/products',
        name: 'products',
        components: require('./views/products/Products')
    },
    {
        path: '/products/404',
        name: 'product_not_found',
        components: require('./views/errors/NotFound')
    },
    {
        path: '/products/:id',
        name: 'product',
        components: require('./views/products/Product')
    },
    {
        path: '/product/create',
        name: 'product_create',
        components: require('./views/products/ProductCreate')
    },
    {
        path: '/products/:id/edit',
        name: 'product_edit',
        components: require('./views/products/ProductEdit')
    },
    {
        path: '/products/:id/images',
        name: 'product_images',
        components: require('./views/products/ProductImages')
    }
];

export default new VueRouter({
    routes
});