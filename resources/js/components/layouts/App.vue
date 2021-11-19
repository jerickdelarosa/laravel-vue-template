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
export default {
  data () {
    return {
      userLoading: false
    }
  }

}
</script>

<style lang="scss">
  .no-transition {
    transition: none !important;
  }
</style>
