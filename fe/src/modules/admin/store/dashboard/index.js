import API from '@/store/base/base'

export default {
 namespaced: true,
 state: {
  latestProducts: [],
  dashboardSummary: {
   productCount: 0,
   storeCount: 0,
   categoryCount: 0,
   todaysOrderCount: 0,
   userCount: 0,
  }
 },
 getters: {
 },
 mutations: {
  SET_PRODUCTS(state, payload) {
   // 'productsCount' => $productCount,
   // 'storeCount' => $storeCount,
   // 'categCount' => $categCount,
   // 'todaysOrderCount' => $todaysOrder,
   // 'userCount' => $userCount
   state.dashboardSummary.productCount = payload.productsCount
   state.dashboardSummary.storeCount = payload.storeCount
   state.dashboardSummary.todaysOrderCount = payload.todaysOrderCount
   state.dashboardSummary.categCount = payload.categoryCount
   state.dashboardSummary.userCount = payload.userCount
   state.latestProducts = payload.latestProducts
  },

 },
 actions: {
  async getDashboardSummary({ commit }, payload) {
   const res = await API.get(`admin/dashboard?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_PRODUCTS', res.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}