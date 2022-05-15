import API from '@/store/base/base'

export default {
 namespaced: true,
 state: {
  categories: [],
  archivedCategories: [],
 },
 getters: {

 },
 mutations: {
  SET_CATEGORIES(state, payload) {
   state.categories = payload
  },
  SET_ARCHIVED_CATEGORIES(state, payload) {
   state.archivedCategories = payload
  },
  PERMANENT_REMOVE_CATEGORY(state, payload) {
   state.archivedCategories.map((category, i) => {
    if (category.id == payload.id) {
     state.archivedCategories.splice(i, 1)
    }
   })
  },
  UPDATE_CATEGORY(state, payload) {
   state.categories.map((category, i) => {
    if (category.id == payload.id) {
     state.categories[i].category = payload.category
    }
   })
  },
  REMOVE_CATEGORY(state, payload) {
   state.archivedCategories.push(payload)

   state.categories.map((category, i) => {
    if (category.id == payload.id) {
     state.categories.splice(i, 1)
    }
   })
  },
  RESTORE_CATEGORY(state, payload) {
   state.categories.unshift(payload)

   state.archivedCategories.map((category, i) => {
    if (category.id == payload.id) {
     state.archivedCategories.splice(i, 1)
    }
   })
  }
 },
 actions: {
  async getCategories({ commit }, payload) {
   const res = await API.get(`admin/category?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_CATEGORIES', res.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getArchivedCategories({ commit }, payload) {
   const res = await API.get(`admin/archived-category?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_ARCHIVED_CATEGORIES', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async updateCategory({ commit }, payload) {
   const res = await API.put(`admin/category/${payload.id}`, payload).then(res => {
    commit('UPDATE_CATEGORY', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async deleteCategory({ commit }, payload) {
   const res = await API.delete(`admin/archived-category/${ payload.id }`, payload).then(res => {
    commit('PERMANENT_REMOVE_CATEGORY', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async archiveCategory({ commit }, payload) {
   const res = await API.delete(`admin/category/${ payload.id }`, payload).then(res => {
    commit('REMOVE_CATEGORY', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async restoreCategory({ commit }, payload) {
   const res = await API.put(`admin/archived-category/${ payload.id }`).then(res => {
    commit('RESTORE_CATEGORY', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}