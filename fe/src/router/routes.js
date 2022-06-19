import Home from '../views/Home.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: {
      title: 'Welcome to IMarket - Your one stop shop solution for market products'
    }
  },
  {
    path: '/imarket',
    name: 'Market',
    component: () => import(/* webpackChunkName: "imarket" */ '../views/Market.vue'),
    meta: {
      title: 'IMarket - Make your order and we will deliver it to you right ahead'
    }
  },
  {
    path: '/imarket/product/:id',
    name: 'ProductInfo',
    component: () => import(/* webpackChunkName: "productInfo" */ '../views/ProductInfo.vue'),
    meta: {
      title: ''
    }
  },
  {
    path: '/imarket/store/:id',
    name: 'ProductInfo',
    component: () => import(/* webpackChunkName: "productInfo" */ '../views/StoreMarket.vue'),
    meta: {
      title: 'IMarket - Store Information'
    }
  },
  {
    path: '/imarket/search',
    name: 'ProductInfo',
    component: () => import(/* webpackChunkName: "productInfo" */ '../views/SearchResult.vue'),
    meta: {
      title: 'IMarket - Product Search Results'
    }
  },
  {
    path: '/orders',
    name: 'Orders',
    component: () => import(/* webpackChunkName: "orders" */ '../views/Orders.vue'),
    meta: {
      title: 'Market - Order Section',
      requiresAuth: true
    }
  },
  {
    path: '/account-settings',
    name: 'Settings',
    component: () => import(/* webpackChunkName: "imarket" */ '../views/Settings.vue'),
    meta: {
      title: 'IMarket - User Account Settings',
      requiresAuth: true
    }
  },
  {
    path: '/manage-products',
    name: 'Settings',
    component: () => import(/* webpackChunkName: "imarket" */ '../views/ManageProducts.vue'),
    meta: {
      title: 'IMarket Account Settings',
      requiresAuth: true
    }
  },
  {
    path: '/cart-checkout',
    name: 'Checkout',
    component: () => import(/* webpackChunkName: "cart-checkout" */ '../views/Cart.vue'),
    meta: {
      title: 'Market - Checkout your items so we can deliver it to you',
      requiresAuth: true
    }
  },
  {
    path: '*',
    name: '404 Error Page',
    component: () => import(/* webpackChunkName: "error" */ '../views/Error.vue'),
    meta: {
      title: 'Error! Page Not Found'
    }
  }
]

export default routes;