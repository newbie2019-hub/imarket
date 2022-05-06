import API from '../base/base'

export default {
 namespaced: true,
 state: {
  orders: [],
 },
 getters: {

 },
 mutations: {
  SET_ORDERS(state, payload) {
   if (payload.current_page == 1) {
    state.orders = payload
   }
   else {
    payload.data.map((data) => {
     state.orders.data.push(data)
    })
    state.orders.current_page = payload.current_page
    state.orders.last_page = payload.last_page
    state.orders.first_page_url = payload.first_page_url
    state.orders.next_page_url = payload.next_page_url
    state.orders.prev_page_url = payload.prev_page_url
    state.orders.to = payload.to
    state.orders.from = payload.from
   }
  },

 },
 actions: {
  async getOrders({ commit }, payload) {
   const res = await API.get(`orders?page=${payload}`).then(res => {
    commit('SET_ORDERS', res.data)
    return res;
   }).catch(error => {
    return error.response;
   })

   return res;
  },
 },
}