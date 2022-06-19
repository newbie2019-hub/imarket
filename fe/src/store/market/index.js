import API from '../base/base'

export default {
  namespaced: true,
  state: {
    categories: [],
    products: { data: [] },
    latest_products: [],
    cart_count: 0,
    cart: [],
    storeInfo: [],
    searchOptions: {
      scrape: true,
      products: true,
      categories: [],
    },
    scrapedRecipes: [],
    searchedProducts: [],
    selectedStore: []
  },
  getters: {

  },
  mutations: {
    SET_SEARCHED_PRODUCTS(state, payload) {
      state.searchedProducts = payload
    },
    SET_SCRAPE(state, payload) {
      state.scrapedRecipes = payload
    },
    SET_SEARCH(state, payload) {
      state.searchOptions = payload
    },
    SET_STORE(state, payload) {
      state.storeInfo = payload
    },
    SET_CATEGORIES(state, data) {
      state.categories = data
    },
    SET_LATEST_PRODUCTS(state, data) {
      state.latest_products = data
    },
    SET_PRODUCTS(state, payload) {
      if (payload.current_page == 1) {
        state.products = payload
      }
      else {
        payload.data.map((data) => {
          state.products.data.push(data)
        })
        state.products.current_page = payload.current_page
        state.products.last_page = payload.last_page
        state.products.first_page_url = payload.first_page_url
        state.products.next_page_url = payload.next_page_url
        state.products.prev_page_url = payload.prev_page_url
        state.products.to = payload.to
        state.products.from = payload.from
      }
    },
    ADD_CART(state, payload) {
      state.cart = payload
    },
    SET_CART_COUNT(state, payload) {
      state.cart_count = payload
    },
    SET_CART(state, payload) {
      state.cart = payload
    },
    SET_SELECTED_STORE(state, payload) {
      state.selectedStore = payload.data
    }
  },
  actions: {
    async getLatestProducts({ commit }) {
      const res = await API.get('market/latest-products').then(res => {
        commit('SET_LATEST_PRODUCTS', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async getProducts({ commit }, payload) {
      // console.log(payload)
      const res = await API.get(`market?page=${ payload }`).then(res => {
        commit('SET_PRODUCTS', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async getCategories({ commit }) {
      const res = await API.get('categories').then(res => {
        commit('SET_CATEGORIES', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async getCartItems({ commit }, payload) {
      const res = await API.get('cart').then(res => {
        commit('SET_CART', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async addToCart({ commit }, payload) {
      const res = await API.post('cart', payload).then(res => {
        commit('ADD_CART', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async cartCount({ commit }, payload) {
      const res = await API.get('cart/count-items', payload).then(res => {
        commit('SET_CART_COUNT', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async deleteCart({ commit }, payload) {
      const res = await API.delete(`cart/${ payload }`).then(res => {
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async removeItem({ commit }, payload) {
      const res = await API.delete(`cart/removeItem/${ payload }`).then(res => {
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async checkout({ commit }, payload) {
      const res = await API.post(`checkout`, payload).then(res => {
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async getSelectedStoreInfo({ commit }, payload) {
      console.log(payload)
      const res = await API.get(`store/${payload}`).then(res => {
        commit('SET_SELECTED_STORE', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async getStoreInfo({ commit }) {
      const res = await API.get('store').then(res => {
        commit('SET_STORE', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async updateStore({ commit }, payload) {
      const res = await API.put(`store/${ payload.id }`, payload).then(res => {
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },

    async searchProducts({ commit }, payload) {
      // console.log(payload)
      const res = await API.get(`market/search?product=${payload.product}&page=${payload.page}`).then(res => {
        commit('SET_SEARCHED_PRODUCTS', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
  },
}