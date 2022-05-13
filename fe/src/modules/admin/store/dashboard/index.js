import API from '@/store/base/base'

export default {
 namespaced: true,
 state: {
  latestProducts: [],
 },
 getters: {
 },
 mutations: {
  SET_PRODUCTS(state, payload) {
   state.latestProducts = payload
  },

 },
 actions: {
  async getDashboardSummary({ commit }, payload) {
   const res = await API.get(`admin/dashboard?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_PRODUCTS', res.data.latestProducts)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}