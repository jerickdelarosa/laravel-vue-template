import api from '../../api/api.js'

const state = {
  photoUploading: false,
  photoUploaded: false
}

const getters = {
  PHOTO_UPLOADING: state => state.photoUploading,
  PHOTO_UPLOADED: state => state.photoUploaded
}

const mutations = {
  SET_PHOTO_UPLOADING: (state, data) => (state.photoUploading = data),
  SET_PHOTO_UPLOADED: (state, data) => (state.photoUploaded = data)
}

const actions = {

  UPLOAD_PHOTO: async ({ commit }, data) => {
    commit('SET_PHOTO_UPLOADING', true)
    commit('SET_PHOTO_UPLOADED', false)

    try {
      const response = await api.post('api/user/photo', data)
      commit('SET_PHOTO_UPLOADED', response.data.status)
    } catch (e) {
      commit('SET_PHOTO_UPLOADED', false)
    } finally {
      commit('SET_PHOTO_UPLOADING', false)
    }
  }

}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
