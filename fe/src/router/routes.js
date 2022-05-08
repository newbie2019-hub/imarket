import Home from '../views/Home.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: {
      title: 'Home Page'
    }
  },
  {
    path: '/imarket',
    name: 'Market',
    component: () => import(/* webpackChunkName: "imarket" */ '../views/Market.vue'),
    meta: {
      title: 'Market - Make your order and we will deliver it to you right ahead'
    }
  },
  {
    path: '/orders',
    name: 'Orders',
    component: () => import(/* webpackChunkName: "orders" */ '../views/Orders.vue'),
    meta: {
      title: 'Market - Here are your Orders'
    }
  },
  {
    path: '/account-settings',
    name: 'Settings',
    component: () => import(/* webpackChunkName: "imarket" */ '../views/Settings.vue'),
    meta: {
      title: 'IMarket Account Settings'
    }
  },
  {
    path: '/manage-products',
    name: 'Settings',
    component: () => import(/* webpackChunkName: "imarket" */ '../views/ManageProducts.vue'),
    meta: {
      title: 'IMarket Account Settings'
    }
  },
  {
    path: '/cart-checkout',
    name: 'Checkout',
    component: () => import(/* webpackChunkName: "cart-checkout" */ '../views/Cart.vue'),
    meta: {
      title: 'Market - Checkout your items so we can deliver it to you'
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