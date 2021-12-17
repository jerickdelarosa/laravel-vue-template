import api from '../../api/api'

const state = {
  funds: [],
  categories: [],
  subcategories: [],
  offices: [],
  locations: [],
  measurements: [],
  warrantyLengths: [],

  inventories: [],

  itemDetails: null,

  itemLogs: []
}

const getters = {
  FUND_LIST: state => state.funds,
  LOCATION_LIST: state => state.locations,
  MEASUREMENT_LIST: state => state.measurements,
  CATEGORY_LIST: state => state.categories,
  OFFICE_LIST: state => state.offices,
  SUBCATEGORY_LIST: state => state.subcategories,
  WARRANTY_LENGTH_LIST: state => state.warrantyLengths,

  LIST: state => state.inventories,
  ITEM_DETAILS: state => state.itemDetails

}

const mutations = {
  SET_FUND: (state, payload) => { state.funds = payload },
  SET_LOCATION: (state, payload) => { state.locations = payload },
  SET_MEASUREMENT: (state, payload) => { state.measurements = payload },
  SET_CATEGORY: (state, payload) => { state.categories = payload },
  SET_OFFICE: (state, payload) => { state.offices = payload },
  SET_WARRANTY_LENGTHS: (state, payload) => { state.warrantyLengths = payload },

  SET_INVENTORIES: (state, payload) => { state.inventories = payload },

  SET_ITEM_DETAILS: (state, payload) => { state.itemDetails = payload }

}

const actions = {
  // Get data for creating New Inventory Item
  GET_DATA: async ({ commit }) => {
    return await api.get('/api/inventory/create')
      .then((response) => {
        commit('SET_FUND', response?.data.funds)
        commit('SET_LOCATION', response?.data.locations)
        commit('SET_MEASUREMENT', response?.data.measurements)
        commit('SET_CATEGORY', response?.data.categories)
        commit('SET_OFFICE', response?.data.offices)
        commit('SET_WARRANTY_LENGTHS', response?.data.warranty_lengths)
      })
  },
  // Get list of saved inventory items
  GET: async ({ commit }) => {
    return await api.get('/api/inventory')
      .then((response) => {
        commit('SET_INVENTORIES', response?.data?.data)
      })
  },

  // Show details of inventory
  EDIT: async ({ commit }, payload) => {
    return await new Promise((resolve, reject) => {
      return api.get(`/api/inventory/${payload.code}/edit`)
        .then((response) => {
          resolve(response?.data)
        })
        .catch((e) => {
          reject(e.response?.data)
        })
    })
  },

  SHOW: async ({ commit }, payload) => {
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

  SHOW_LOG_DETAILS: async ({ commit }, payload) => {
    return await new Promise((resolve, reject) => {
      return api.get(`/api/inventory/${payload.code}/logs/${payload.log_id}`)
        .then((response) => {
          resolve(response?.data)
        })
        .catch((e) => {
          reject(e.response?.data)
        })
    })
  },

  CLEAR_ITEM_DETAILS: async ({ commit }) => {
    commit('SET_ITEM_DETAILS', null)
  },

  // Save item
  SAVE: async ({ commit }, data) => {
    return await new Promise((resolve, reject) => {
      return api.post('/api/inventory', data)
        .then((response) => {
          resolve(response?.data)
        })
        .catch((e) => {
          reject(e.response?.data)
        })
    })
  },

  // Save item
  UPDATE: async ({ commit }, payload) => {
    return await new Promise((resolve, reject) => {
      return api.patch(`/api/inventory/${payload.id}`, payload)
        .then((response) => {
          resolve(response?.data)
        })
        .catch((e) => {
          reject(e.response?.data)
        })
    })
  },

  // Delete Item
  DELETE: async ({ commit }, payload) => {
    return await new Promise((resolve, reject) => {
      api.delete(`/api/inventory/${payload.id}`)
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
