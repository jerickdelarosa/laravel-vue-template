/* eslint-disable no-unused-vars */
import './bootstrap'

// =============================================================================
// Import VueJS
// =============================================================================
import Vue from 'vue'

// =============================================================================
// Import Vuetify Settings
// =============================================================================
import vuetify from './plugins/vuetify'

// =============================================================================
// Import Router Settings
// =============================================================================
import router from './plugins/router'

// =============================================================================
// Import Vuex Settings
// =============================================================================
import store from './store'

// =============================================================================
// Vue Croppie
// =============================================================================
import VueCroppie from 'vue-croppie'
import 'croppie/croppie.css'

Vue.use(VueCroppie)

// =============================================================================
// Main App Component
// =============================================================================
Vue.component('App', require('./components/layouts/App.vue').default)

// =============================================================================
// Turn off tips on production
// =============================================================================
Vue.config.productionTip = false

// =============================================================================
// Init Vue App
// =============================================================================
const app = new Vue({
  el: '#app',
  vuetify,
  router,
  store
})
