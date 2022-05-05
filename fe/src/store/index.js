import Vue from "vue";
import Vuex from "vuex";

import market from './market'
import orders from './orders'

Vue.use(Vuex);

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    market, orders
  }
});

