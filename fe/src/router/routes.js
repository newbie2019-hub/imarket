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
   path: '*',
   name: '404 Error Page',
   component: () => import(/* webpackChunkName: "error" */ '../views/Error.vue'),
   meta: {
    title: 'Error! Page Not Found'
   }
 }
]

export default routes;