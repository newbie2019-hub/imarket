import API from '../base/base'

export default {
 namespaced: true,
 state: {
  products: [],
 },
 getters: {

 },
 mutations: {
  SET_PRODUCTS(state, payload) {
   state.products = payload
  },
  APPEND_PRODUCT(state, payload){
   state.products.unshift(payload)
  }
 },
 actions: {
  async getProducts({ commit }) {
   const res = await API.get(`products`).then(res => {
    commit('SET_PRODUCTS', res.data)
    return res;
   }).catch(error => {
    return error.response;
   })

   return res;
  },
  async updateProduct({ commit }, payload) {
   const res = await API.put(`products/${payload.id}`, payload).then(res => {
    return res;
   }).catch(error => {
    return error.response;
   })

   return res;
  },
  async saveProduct({ commit }, payload) {
   const res = await API.post(`products`, payload).then(res => {
    commit('APPEND_PRODUCT', res.data.data)
    return res;
   }).catch(error => {
    return error.response;
   })

   return res;
  },
  async deleteProduct({ commit }, payload) {
   const res = await API.delete(`products/${payload}`).then(res => {

    return res;
   }).catch(error => {
    return error.response;
   })

   return res;
  },
 },
}