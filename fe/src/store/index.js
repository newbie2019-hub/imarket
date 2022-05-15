import Vue from "vue";
import Vuex from "vuex";

import market from './market'
import orders from './orders'
import products from './products'

import adminUser from '../modules/admin/store/user/index'
import adminStore from '../modules/admin/store/stores/'
import adminProduct from '../modules/admin/store/products/'
import adminDashboard from '../modules/admin/store/dashboard/'
import adminCategory from '../modules/admin/store/category/'

Vue.use(Vuex);

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    market, orders, products, adminUser, adminStore, adminProduct, adminDashboard, adminCategory
  }
});



