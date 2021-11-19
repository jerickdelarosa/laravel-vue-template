import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

// ==============================
// Make Vue uses Vuetify
// ==============================
Vue.use(Vuetify)

// ==============================
// Vuetify Settings
// ==============================
const opts = {
  theme: {
    dark: false,
    themes: {
      light: {
        primary: '#c4223a',
        secondary: '#226666',
        accent: '#CB824E',

        info: '#216666',
        success: '#70854a',
        warning: '#ed7512',
        error: '#f44336'
      }
    }
  }
}

// ==============================
// Export Settings
// ==============================
export default new Vuetify(opts)
