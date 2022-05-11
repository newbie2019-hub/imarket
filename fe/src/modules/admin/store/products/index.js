import API from '@/store/base/base'

export default {
 namespaced: true,
 state: {
  products: [],
  archivedProducts: [],
 },
 getters: {
 },
 mutations: {
  SET_PRODUCTS(state, payload) {
   state.products = payload
  },
  SET_ARCHIVED_PRODUCTS(state, payload) {
   state.archivedProducts = payload
  },
  PERMANENT_REMOVE_PRODUCT(state, payload) {
   state.archivedProducts.map((product, i) => {
    if (product.id == payload.id) {
     state.archivedProducts.splice(i, 1)
    }
   })
  },
  REMOVE_PRODUCT(state, payload) {
   state.archivedProducts.push(payload)

   state.products.map((product, i) => {
    if (product.id == payload.id) {
     state.products.splice(i, 1)
    }
   })
  },
  RESTORE_STORE(state, payload) {
   state.products.push(payload)

   state.archivedProducts.map((product, i) => {
    if (product.id == payload.id) {
     state.archivedProducts.splice(i, 1)
    }
   })
  }
 },
 actions: {
  async getProducts({ commit }, payload) {
   const res = await API.get(`admin/products?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_PRODUCTS', res.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getArchivedProducts({ commit }, payload) {
   const res = await API.get(`admin/archived-products?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_ARCHIVED_PRODUCTS', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async deleteProduct({ commit }, payload) {
   const res = await API.delete(`admin/archived-products/${ payload.id }`, payload).then(res => {
    commit('PERMANENT_REMOVE_PRODUCT', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async archiveProduct({ commit }, payload) {
   const res = await API.delete(`admin/products/${ payload.id }`, payload).then(res => {
    commit('REMOVE_PRODUCT', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async restoreProduct({ commit }, payload) {
   const res = await API.put(`admin/archived-products/${ payload.id }`).then(res => {
    commit('RESTORE_PRODUCT', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}