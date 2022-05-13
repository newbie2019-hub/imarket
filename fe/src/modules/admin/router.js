import Index from './views/Index'
import Dashboard from './views/subroutes/Dashboard'
import Product from './views/subroutes/Product'
import Store from './views/subroutes/Store'
import User from './views/subroutes/User'
import Partner from './views/subroutes/Partner'
import Orders from './views/subroutes/Orders'
import Purchase from './views/subroutes/Purchase'
import Riders from './views/subroutes/Riders'

const moduleRoute = {
  path: "/admin",
  component: Index,
  meta: {
    requiresAuth: true,
    title: 'IMarket - Welcome Back Admin'
  },
  children: [
    {
      path: "dashboard",
      component: Dashboard,
      meta: {
        title: 'IMarket - Dashboard'
      }
    },
    {
      path: "users",
      component: User,
      meta: {
        title: 'IMarket - Users Management'
      }
    },
    {
      path: "products",
      component: Product,
      meta: {
        title: 'IMarket - Products Management'
      }
    },
    {
      path: "stores",
      component: Store,
      meta: {
        title: 'IMarket - Stores Management'
      }
    },
    {
      path: "riders",
      component: Riders,
      meta: {
        title: 'IMarket - Riders Management'
      }
    },
    {
      path: "partners",
      component: Partner
    },
    {
      path: "orders",
      component: Orders,
      meta: {
        title: 'IMarket - Orders Management'
      }
    },
    {
      path: "purchase-history",
      component: Purchase
    },
    {
      path: '/',
      redirect: '/admin/dashboard'
    }
  ]
};

export default router => {
  router.addRoutes([moduleRoute]);
};
