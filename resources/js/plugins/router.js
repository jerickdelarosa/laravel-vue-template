import Vue from 'vue'
import VueRouter from 'vue-router'

// =======================================================================
// IMPORT ROUTER COMPONENTS HERE
// =======================================================================

// Login
import AuthLogin from '../components/pages/Auth/Login.vue'

// Dashboard
import Dashboard from '../components/pages/Dashboard.vue'

// Uploading a Photo
import UploadPhoto from '../components/pages/Upload/UploadPhoto.vue'

// Document
import Document from '../components/pages/Document/Document.vue'

// Tester Section
import Tester from '../components/pages/Tester/Tester.vue'

// =======================================================================
// IMPORT ROUTER CHILD COMPONENTS HERE
// =======================================================================

// =======================================================================
// IMPORT NAVIGATION COMPONENTS HERE
// =======================================================================
import MainNavigation from '../components/navigation/Main.vue'

// =======================================================================
// Make Vue use VueRouter
// =======================================================================
Vue.use(VueRouter)

// =======================================================================
// VueRouter Settings
// =======================================================================
const AppName = 'App'

const router = new VueRouter({
  mode: 'history', // Navigation Mode
  base: '/', // Base Route
  scrollBehavior (to, from, savedPosition) {
    return { x: 0, y: 0 } // Scroll Behavior on each navigation change
  },

  // Routes Declaration
  routes: [
    // Dashboard
    {
      path: '/',
      name: 'dashboard',
      components: {
        default: Dashboard,
        navigation: MainNavigation
      },
      meta: {
        title: 'Dashboard',
        auth: true
      }
    },
    // Upload Photo
    {
      path: '/upload-photo',
      name: 'upload.photo',
      components: {
        default: UploadPhoto,
        navigation: MainNavigation
      },
      meta: {
        title: 'Upload Photo',
        auth: true
      }
    },
    // Upload Photo
    {
      path: '/upload-document',
      name: 'upload.document',
      components: {
        default: Document,
        navigation: MainNavigation
      },
      meta: {
        title: 'Upload Document',
        auth: true
      }
    },
    // Tester Section
    {
      path: '/test',
      name: 'test',
      components: {
        default: Tester,
        navigation: MainNavigation
      },
      meta: {
        title: 'Tester',
        auth: true
      }
    },

    // Auth Login
    {
      path: '/login',
      name: 'auth.login',
      component: AuthLogin,
      meta: {
        title: 'Login',
        guest: true
      }
    }

  ]
})

router.beforeEach((to, from, next) => {
  // Auth Token
  const userToken = localStorage.getItem('app-token')

  // Change Title on route change
  document.title = `${to.meta?.title} | ${AppName}`

  if (userToken) {
    if (to.matched.some(record => record.meta?.guest)) {
      next({
        name: 'dashboard'
      })
    }

    next()
  } else {
    if (to.matched.some(record => record.meta?.auth)) {
      next({
        name: 'auth.login'
      })
    }

    next()
  }

  next()
})

// =======================================================================
// Export Settings
// =======================================================================
export default router
