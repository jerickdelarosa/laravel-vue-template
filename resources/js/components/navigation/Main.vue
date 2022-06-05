<template>
  <div>
    <!-- APP BAR -->
    <v-app-bar
      clipped-left
      app
      dark
      color="primary"
      flat
    >
      <!-- NAVIGATION ICON -->

      <v-app-bar-nav-icon @click="toggleNav" />

      <!-- TITLE -->
      <v-toolbar-title> App </v-toolbar-title>

      <v-spacer />

      <!-- USER ACCOUNT -->
      <v-menu offset-y>
        <template #activator="{on}">
          <v-btn
            icon
            v-on="on"
          >
            <v-avatar
              color="primary lighten-1"
              size="40"
            >
              <v-img :src="userAvatar" />
            </v-avatar>
          </v-btn>
        </template>

        <v-card width="360">
          <v-list nav>
            <v-list-item two-line>
              <v-list-item-avatar
                size="48"
                color="primary"
                class="primary--text"
              >
                <v-avatar>
                  <v-img :src="userAvatar" />
                </v-avatar>
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title class="font-weight-medium">
                  {{ userName }}
                </v-list-item-title>
                <v-list-item-subtitle>{{ userEmail }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>

            <v-divider class="my-2" />

            <v-list-item @click="logout">
              <v-list-item-icon>
                <v-icon>mdi-logout-variant</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Sign Out</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>
      </v-menu>
    </v-app-bar>

    <!-- NAVIGATION DRAWER -->
    <v-navigation-drawer
      clipped
      app
      v-model="mainNav"
      :width="$vuetify.breakpoint.mdAndDown ? 320 : 256"
    >
      <!-- NAVIGATION LINKS -->
      <v-list dense>
        <template v-for="(nav, i) in navLinks">
          <v-list-item
            active-class="primary--text"
            :key="`nav-link-${i}`"
            link
            exact
            :to="{ name: nav.to }"
          >
            <v-list-item-icon>
              <v-icon>
                {{ nav.icon }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-title>{{ nav.title }}</v-list-item-title>
          </v-list-item>
        </template>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  data () {
    return {
      // NAVBAR
      mainNav: null,

      // NAVIGATION LINKS
      // For icons: https://materialdesignicons.com/
      navLinks: [
        {
          to: 'dashboard',
          title: 'Dashboard',
          icon: 'mdi-view-dashboard-outline'
        },
        {
          to: 'upload.photo',
          title: 'Upload Photo',
          icon: 'mdi-image'
        },
        {
          to: 'upload.document',
          title: 'Upload Document',
          icon: 'mdi-file'
        },
        {
          to: 'test',
          title: 'Websockets Tester',
          icon: 'mdi-account'
        }
      ]
    }
  },

  computed: {
    ...mapGetters({
      user: 'auth/USER_DATA'
    }),

    authenticated () {
      return !!this.user
    },
    userAvatar () {
      return this.user?.avatar
    },
    userName () {
      return this.user?.name
    },
    userEmail () {
      return this.user?.email
    }
  },

  methods: {
    ...mapActions({
      _logout: 'auth/LOGOUT'
    }),
    // Toggle navigation drawer
    toggleNav () {
      this.mainNav = !this.mainNav
    },

    logout () {
      this._logout()
      this.$router.go({
        name: 'auth.login'
      })
    }

  }
}
</script>
