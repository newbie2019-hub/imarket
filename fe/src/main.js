import Vue from 'vue'
import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify'
import './assets/css/styles.css'
import store from "./store";
import { toast } from './assets/js/utilities'
import * as VueGoogleMaps from 'vue2-google-maps'
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

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyCchv0AxfcsTctlyOBnx0EniydEdGUYsYg',
    libraries: 'places, geocoding',
    region: 'PH'
  },
})

new Vue({
  router,
  vuetify,
  store,
  render: h => h(App)
}).$mount('#app')
