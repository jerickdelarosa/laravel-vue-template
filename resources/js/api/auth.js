import api from './api'

export default {
  setToken: (token) => {
    api.setAuthorizationToken(token)
  },

  removeToken: () => {
    api.removeAuthorizationToken()
  },

  redirectToGoogle: () => {
    return api.get('/api/auth/google')
  },

  authenticate (data) {
    return api.post('/api/auth/login', data)
  },

  getUserData () {
    return api.get('/api/auth/user')
  }
}
