import Vue from "vue";
import Vuex from "vuex";

import market from './market'
import orders from './orders'
import products from './products'

Vue.use(Vuex);

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    market, orders,products
  }
});

