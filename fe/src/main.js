import Vue from 'vue'
import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify'

Vue.config.productionTip = false

import { registerModules } from "./register-modules";
import adminModule from "./modules/admin";
import cartModule from "./modules/cart";
import authModule from "./modules/auth";
import userModule from "./modules/user";

registerModules({
  admin: adminModule,
  cart: cartModule,
  user: userModule,
  auth: authModule,
});

new Vue({
  router,
  vuetify,
  render: h => h(App)
}).$mount('#app')
