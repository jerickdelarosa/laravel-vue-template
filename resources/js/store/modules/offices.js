import api from '../../api/api'

const state = {
  offices: []

}

const getters = {
  OFFICE_LIST: state => state.offices
}

const mutations = {
  SET_OFFICES: (state, payload) => { state.offices = payload }
}

const actions = {
  GET: async ({ commit }) => {
    return await api.get('/api/job_openings/offices')
      .then((response) => {
        commit('SET_OFFICES', response?.data?.data)
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
