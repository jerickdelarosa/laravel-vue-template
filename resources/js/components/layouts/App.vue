<template>
  <!-- MAIN APP -->
  <v-app>
    <v-overlay
      :value="userLoading"
      opacity="1"
      absolute
      color="white"
    >
      <v-progress-circular
        indeterminate
        color="primary"
      />
    </v-overlay>

    <!-- ROUTER VIEW FOR NAVIGATION -->
    <router-view name="navigation" />

    <!-- MAIN -->
    <v-main class="no-transition">
      <!-- ROUTER VIEW FOR CURRENT ROUTE -->
      <router-view />
    </v-main>
  </v-app>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  data () {
    return {
      userLoading: false
    }
  },
  created () {
    /* if (this.token) {
      this.userLoading = true
      this.getUserData()
        .then(() => {
          this.connectToWS()
        })
        .finally(() => {
          this.userLoading = false
        })
    } */
  },
  computed: {
    ...mapGetters({
      token: 'auth/TOKEN',
      user: 'auth/USER_DATA'

    })

    /* connectToWS () {
      window.Echo.private('admin-channel')
        .listen('.update-dashboard', (e) => {
          if (e) {
            this._getDashboardData()
          }
        })
    } */
  }

}
</script>

<style lang="scss">
  .no-transition {
    transition: none !important;
  }
</style>
