import API from "@/store/base/base.js";

export default {
  namespaced: true,
  state: {
    user: {

    },
    token: localStorage.getItem('auth') || '',
  },
  getters: {

  },
  mutations: {
    SET_AUTH_ACC(state, data) {
      state.userinfo = data.user_info
      state.useraccount = data.user_account
      const bearer_token = localStorage.getItem('auth') || ''
      API.defaults.headers.common['Authorization'] = `Bearer ${ bearer_token }`
    },
    SET_ACC(state, data) {
      state.user = data
      const bearer_token = localStorage.getItem('auth') || ''
      API.defaults.headers.common['Authorization'] = `Bearer ${ bearer_token }`
    },
    SET_USER_ACC(state, data) {
      state.user = data
    },
    SET_USER_TOKEN(state, token) {
      localStorage.setItem('auth', token)
      state.token = token

      const bearer_token = localStorage.getItem('auth') || ''
      API.defaults.headers.common['Authorization'] = `Bearer ${ bearer_token }`
    },
    UNSET_USER(state) {
      localStorage.removeItem('auth');
      localStorage.removeItem('isUser');
      state.token = ''
      state.user = {

      },

        API.defaults.headers.common['Authorization'] = ''
    }
  },
  actions: {
    async login({ commit }, payload) {
      const res = await API.post('/auth/login', payload).then(res => {
        commit('SET_USER_ACC', res.data.user)
        commit('SET_USER_TOKEN', res.data.access_token)

        return res;
      }).catch(err => {
        return err.response;
      })

      return res;
    },
    async register({ commit }, payload) {
      const res = await API.post('/auth/register', payload).then(res => {
        return res;
      }).catch(err => {
        return err.response;
      })

      return res;
    },
    async registerSeller({ commit }, payload) {
      const res = await API.post('/auth/partnership', payload).then(res => {
        return res;
      }).catch(err => {
        return err.response;
      })

      return res;
    },
    async update({ commit }, data) {
      const res = await API.post(`auth/update`, data).then(res => {
        return res;
      }).catch(err => {
        return err.response;
      })

      return res;
    },
    async removeImage({ commit }) {
      const res = await API.post('remove-profile').then(res => {
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async changePassword({ commit }, data) {
      const res = await API.post('auth/update-password', data).then(res => {
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async logout({ commit }) {
      const res = await API.post('auth/logout?token=' + localStorage.getItem('auth')).then(response => {
        commit('UNSET_USER')
        return response
      }).catch(error => {
        return error.response
      });

      return res;
    },
    async checkUser({ commit }) {
      const res = await API.post('auth/me?token=' + localStorage.getItem('auth')).then(res => {
        commit('SET_ACC', res.data)
        return res;
      }).catch(error => {
        commit('UNSET_USER')

        return error.response;
      })

      return res;
    },
  }
};
