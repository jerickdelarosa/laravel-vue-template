import api from '../../api/api'

const state = {
  documents: null,
  documentsLoading: false,
  documentUploading: false,
  documentUploaded: false
}

const getters = {
  DOCUMENTS: state => state.documents,
  DOCUMENTS_LOADING: state => state.documentsLoading,
  DOCUMENT_UPLOADING: state => state.documentUploading,
  DOCUMENT_UPLOADED: state => state.documentUploaded
}

const mutations = {
  SET_DOCUMENTS: (state, data) => (state.documents = data),
  SET_DOCUMENTS_LOADING: (state, data) => (state.documentsLoading = data),
  SET_DOCUMENT_UPLOADING: (state, data) => (state.documentUploading = data),
  SET_DOCUMENT_UPLOADED: (state, data) => (state.documentUploaded = data)
}

const actions = {

  GET_DOCUMENTS: async ({ commit }) => {
    commit('SET_DOCUMENTS_LOADING', true)
    const response = await api.get('api/user/document')
    commit('SET_DOCUMENTS', response.data?.uploaded)
    commit('SET_DOCUMENTS_LOADING', false)
  },

  UPLOAD_DOCUMENT: async ({ commit }, data) => {
    commit('SET_DOCUMENT_UPLOADING', true)
    commit('SET_DOCUMENT_UPLOADED', false)

    try {
      const response = await api.post('api/user/document', data)
      commit('SET_DOCUMENT_UPLOADED', response.data.status)
    } catch (e) {
      commit('SET_DOCUMENT_UPLOADED', false)
    } finally {
      commit('SET_DOCUMENT_UPLOADING', false)
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
