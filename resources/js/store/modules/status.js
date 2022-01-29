import api from '../../api/api'

const state = {
  status: []

}

const getters = {
  STATUS_LIST: state => state.status
}

const mutations = {
  SET_STATUS: (state, payload) => { state.status = payload }
}

const actions = {
  GET: async ({ commit }) => {
    return await api.get('/api/job_openings/status')
      .then((response) => {
        commit('SET_STATUS', response?.data?.data)
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
