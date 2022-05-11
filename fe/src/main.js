import Vue from 'vue'
import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify'
import './assets/css/styles.css'
import store from "./store";
import { toast } from './assets/js/utilities'
Vue.config.productionTip = false

import { registerModules } from "./register-modules";
import adminModule from "./modules/admin";
import authModule from "./modules/auth";
import VAnimateCss from 'v-animate-css';

Vue.use(VAnimateCss);

registerModules({
  admin: adminModule,
  auth: authModule,
});

import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

Vue.use(Toast, {
  transition: "Vue-Toastification__bounce",
  maxToasts: 4,
  newestOnTop: true,
  position: "top-left",
  pauseOnHover: false,
  hideProgressBar: true,
  timeout: 3800,
});

Vue.mixin(toast)
new Vue({
  router,
  vuetify,
  store,
  render: h => h(App)
}).$mount('#app')
