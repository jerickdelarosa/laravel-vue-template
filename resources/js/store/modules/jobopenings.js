import api from '../../api/api'

const state = {

  jobs: [],
  offices: [],
  status: [],
  hrmoTypes: [],

  jobDetails: null

}

const getters = {

  OFFICE_LIST: state => state.offices,
  STATUS_LIST: state => state.status,
  HRMO_TYPE_LIST: state => state.hrmoTypes,

  JOBS_LIST: state => state.jobs,
  JOB_DETAILS: state => state.jobDetails

}

const mutations = {
  SET_OFFICE: (state, payload) => (state.offices = payload),
  SET_STATUS: (state, payload) => (state.status = payload),
  SET_HRMO_TYPE: (state, payload) => (state.hrmoTypes = payload),

  SET_JOBS: (state, payload) => { state.jobs = payload }

}

const actions = {

  // Get data for creating New Job Vacancy
  GET_DATA: async ({ commit }) => {
    return await api.get('/api/job_openings/create')
      .then((response) => {
        commit('SET_OFFICE', response?.data?.offices)
        commit('SET_STATUS', response?.data?.status)
        commit('SET_HRMO_TYPE', response?.data?.hrmoTypes)
      })
  },

  // Get list of saved job opening items
  GET: async ({ commit }) => {
    return await api.get('/api/job_openings')
      .then((response) => {
        commit('SET_JOBS', response?.data?.data)
      })
  },

  // Show details of inventory
  EDIT: async ({ commit }, payload) => {
    return await new Promise((resolve, reject) => {
      return api.get(`/api/job_openings/${payload.jo_id}/edit`)
        .then((response) => {
          resolve(response?.data)
        })
        .catch((e) => {
          reject(e.response?.data)
        })
    })
  },

  /* SHOW: async ({ commit }, payload) => {
    return await new Promise((resolve, reject) => {
      return api.get(`/api/inventory/${payload.code}`)
        .then((response) => {
          resolve(true)
          commit('SET_ITEM_DETAILS', response?.data?.data)
        })
        .catch((e) => {
          reject(e.response?.data)
        })
    })
  },

  CLEAR_ITEM_DETAILS: async ({ commit }) => {
    commit('SET_ITEM_DETAILS', null)
  }, */

  // Save Job
  SAVE: async ({ commit }, data) => {
    return await new Promise((resolve, reject) => {
      return api.post('/api/job_openings', data)
        .then((response) => {
          resolve(response?.data)
        })
        .catch((e) => {
          reject(e.response?.data)
        })
    })
  },

  // Save Job
  UPDATE: async ({ commit }, payload) => {
    return await new Promise((resolve, reject) => {
      return api.patch(`/api/job_openings/${payload.jo_id}`, payload)
        .then((response) => {
          resolve(response?.data)
        })
        .catch((e) => {
          reject(e.response?.data)
        })
    })
  },

  // Delete Job
  DELETE: async ({ commit }, payload) => {
    return await new Promise((resolve, reject) => {
      api.delete(`/api/job_openings/${payload.jo_id}`)
        .then((response) => {
          resolve(response?.data)
        })
        .catch((e) => {
          reject(e.response?.data)
        })
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
