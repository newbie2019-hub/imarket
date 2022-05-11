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
  requiresAuth: true
 },
 children: [
   {
     path: "dashboard",
     component: Dashboard,
     meta: {}
   },
   {
     path: "users",
     component: User
   },
   {
     path: "products",
     component: Product
   },
   {
     path: "stores",
     component: Store
   },
   {
     path: "riders",
     component: Riders
   },
   {
     path: "partners",
     component: Partner
   },
   {
     path: "orders",
     component: Orders
   },
   {
     path: "purchase-history",
     component: Purchase
   },
 ]
};

export default router => {
 router.addRoutes([moduleRoute]);
};
