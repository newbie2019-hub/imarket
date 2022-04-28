import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach(async (to, from, next) => {
  document.title = to.meta.title || 'Welcome to IMarket'
  if (to.matched.some((record) => record.meta.requiresLogin) && !localStorage.getItem('auth')) {
    next({ name: 'Login' })
  }
  else if (to.matched.some((record) => record.meta.isAuth) && localStorage.getItem('auth')) {
    next({ name: 'Home' })
  }
  else {
    next();
  }
});

export default router
