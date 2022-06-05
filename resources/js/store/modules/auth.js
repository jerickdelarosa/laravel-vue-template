import api from '../../api/auth'

const state = {
  user: null,
  userLoading: false
}

const getters = {
  TOKEN: () => localStorage.getItem('app-token'),
  USER_DATA: (state) => state.user
}

const mutations = {
  SET_USER_DATA: (state, payload) => { state.user = payload }
}

const actions = {
  AUTHENTICATE: ({ commit }, data) => {
    return api.authenticate(data)
  },

  LOGIN: ({ commit }, token) => {
    localStorage.setItem('app-token', token)
    api.setToken(token)
  },

  LOGOUT: ({ commit }) => {
    commit('SET_USER_DATA', null)
    localStorage.removeItem('app-token')
    api.removeToken()
  },

  GET_USER_DATA: async ({ commit }) => {
    await api.getUserData()
      .then((response) => {
        const user = response?.data?.data
        commit('SET_USER_DATA', user)
      })
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
