<template>
  <div>
    <!-- NAVIGATION DRAWER -->
    <v-navigation-drawer
      clipped
      app
      v-model="navbar"
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

    <!-- APP BAR -->
    <v-app-bar
      clipped-left
      clipped-right
      app
      dark
      color="primary"
    >
      <!-- NAVIGATION ICON -->
      <v-app-bar-nav-icon @click="toggleNav" />

      <!-- TITLE -->
      <v-toolbar-title> App </v-toolbar-title>
    </v-app-bar>
  </div>
</template>

<script>
export default {
  data () {
    return {
      // NAVBAR
      navbar: null,

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
        }
      ]
    }
  },

  methods: {
    // Toggle navigation drawer
    toggleNav () {
      this.navbar = !this.navbar
    }
  }
}
</script>
