/* eslint-disable no-unused-vars */
import './bootstrap'

// =============================================================================
// Import VueJS
// =============================================================================
import Vue from 'vue'
import axios from 'axios'

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

import VueAxios from 'vue-axios'
import VueSocialAuth from 'vue-social-auth'
import VueHtml2Canvas from 'vue-html2canvas'

Vue.use(VueCroppie)

Vue.use(VueHtml2Canvas)

Vue.use(VueAxios, axios)

Vue.use(VueSocialAuth, {
  providers: {
    google: {
      clientId: '1043315466899-qtnl2p5tf1n8g47flt6ndi8p7deptqrn.apps.googleusercontent.com',
      clientSecret: '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',
      redirectUri: 'http://app-local.bulsu.edu.ph/auth/login'
    }
  }
})

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
