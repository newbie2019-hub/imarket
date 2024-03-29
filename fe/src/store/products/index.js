import API from '../base/base'

export default {
 namespaced: true,
 state: {
  products: [],
  productSelected: [],
  relatedCategoryProduct: [],
  moreProducts: [],
 },
 getters: {

 },
 mutations: {
  SET_SELETED_PRODUCT(state, payload) {
   state.productSelected = payload.product
   state.relatedCategoryProduct = payload.relatedProduct
   state.moreProducts = payload.moreProducts
  },
  SET_PRODUCTS(state, payload) {
   state.products = payload
  },
  APPEND_PRODUCT(state, payload){
   state.products.unshift(payload)
  }
 },
 actions: {
  async getProduct({ commit }, payload) {
   const res = await API.get(`products/${payload}`).then(res => {
    commit('SET_SELETED_PRODUCT', res.data)
    return res;
   }).catch(error => {
    return error.response;
   })

   return res;
  },
  async getProducts({ commit }) {
   const res = await API.get(`products`).then(res => {
    commit('SET_PRODUCTS', res.data)
    return res;
   }).catch(error => {
    return error.response;
   })

   return res;
  },
  async updateProductQuantity({ commit }, payload) {
   const res = await API.put(`cart/${payload.cart_id}/product/${payload.product.id}`, payload).then(res => {
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