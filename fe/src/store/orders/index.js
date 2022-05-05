import API from '../base/base'

export default {
 namespaced: true,
 state: {
  orders: [],
 },
 getters: {

 },
 mutations: {
  SET_ORDERS(state, data) {
   state.orders = data
  },

 },
 actions: {
  async getOrders({ commit }) {
   const res = await API.get('orders').then(res => {
    commit('SET_ORDERS', res.data)
    return res;
   }).catch(error => {
    return error.response;
   })

   return res;
  },
 },
}