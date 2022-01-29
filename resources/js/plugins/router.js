import Vue from 'vue'
import VueRouter from 'vue-router'

// =======================================================================
// IMPORT ROUTER COMPONENTS HERE
// =======================================================================
// Dashboard
import Dashboard from '../components/pages/Dashboard.vue'

// Pages
import JobOpenings from '../components/pages/JobOpenings/JobOpenings.vue'

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
const AppName = 'BulSU'

const router = new VueRouter({
  mode: 'history', // Navigation Mode
  base: '/', // Base Route
  scrollBehavior (to, from, savedPosition) {
    return { x: 0, y: 0 } // Scroll Behavior on each navigation change
  },

  // Routes Declaration
  routes: [
    // Job Posting
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

    // Job Posting
    {
      path: '/job-openings',
      name: 'job-openings',
      components: {
        default: JobOpenings,
        navigation: MainNavigation
      },
      meta: {
        title: 'Job Openings',
        auth: true
      }
    }

  ]
})

router.beforeEach((to, from, next) => {
  // Change Title on route change
  document.title = `${to.meta?.title} | ${AppName}`
  next()
})

// =======================================================================
// Export Settings
// =======================================================================
export default router
