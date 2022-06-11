import API from '@/store/base/base'

export default {
 namespaced: true,
 state: {
  stores: [],
  archivedStores: [],
 },
 getters: {
  GET_USERS(state) {
   return state.stores;
  }
 },
 mutations: {
  SET_STORES(state, payload) {
   state.stores = payload
  },
  SET_ARCHIVED_STORES(state, payload) {
   state.archivedStores = payload
  },
  PERMANENT_REMOVE_STORE(state, payload) {
   state.archivedStores.map((user, i) => {
    if (user.id == payload.id) {
     state.archivedStores.splice(i, 1)
    }
   })
  },
  REMOVE_STORE(state, payload) {
   state.archivedStores.push(payload)

   state.stores.map((user, i) => {
    if (user.id == payload.id) {
     state.stores.splice(i, 1)
    }
   })
  },
  RESTORE_STORE(state, payload) {
   state.stores.push(payload)

   state.archivedStores.map((user, i) => {
    if (user.id == payload.id) {
     state.archivedStores.splice(i, 1)
    }
   })
  },
  APPROVE_STORE(state, payload) {
   console.log(payload)
   state.stores.map((store, i) => {
    if(store.id == payload.id){
     state.stores[i].status = 'Approved'
    }
   })
  },
 },
 actions: {
  async approveStore({ commit }, payload) {
   const res = await API.put(`admin/store/${payload.id}?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('APPROVE_STORE', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getStores({ commit }, payload) {
   const res = await API.get(`admin/stores?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_STORES', res.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getArchivedStores({ commit }, payload) {
   const res = await API.get(`admin/archived-stores?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_ARCHIVED_STORES', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async deleteStore({ commit }, payload) {
   const res = await API.delete(`admin/archived-stores/${ payload.id }`, payload).then(res => {
    commit('PERMANENT_REMOVE_STORE', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async archiveStore({ commit }, payload) {
   const res = await API.delete(`admin/stores/${ payload.id }`, payload).then(res => {
    commit('REMOVE_STORE', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async restoreStore({ commit }, payload) {
   const res = await API.put(`admin/archived-stores/${ payload.id }`).then(res => {
    commit('RESTORE_STORE', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}