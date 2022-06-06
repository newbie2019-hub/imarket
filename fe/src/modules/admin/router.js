import Index from './views/Index'
import AccountLogs from './views/subroutes/AccountLogs'
import Dashboard from './views/subroutes/Dashboard'
import Product from './views/subroutes/Product'
import Store from './views/subroutes/Store'
import User from './views/subroutes/User'
import Partner from './views/subroutes/Partner'
import Orders from './views/subroutes/Orders'
import Purchase from './views/subroutes/Purchase'
import Riders from './views/subroutes/Riders'
import Settings from './views/subroutes/Settings'
import Categories from './views/subroutes/Categories'

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
      path: "account-logs",
      component: AccountLogs,
      meta: {
        title: 'IMarket - Account Logs'
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
      path: "categories",
      component: Categories,
      meta: {
        title: 'IMarket - Categories Management'
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
      path: "account-settings",
      component: Settings
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
