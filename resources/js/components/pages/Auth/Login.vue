<template>
  <v-container fill-height>
    <v-row
      justify="center"
      align="center"
    >
      <v-col
        cols="12"
        md="8"
        lg="6"
        xl="4"
      >
        <v-card outlined>
          <v-progress-linear
            indeterminate
            color="primary"
            absolute
            top
            :active="authenticating"
          />

          <v-overlay
            :value="authenticating"
            absolute
            class="mt-1"
            color="white"
          />

          <v-card-text class="text-center">
            <v-img
              src="/assets/images/logos/bulsu.png"
              width="96"
              class="mx-auto mb-3"
            />
            <p class="headline black--text mb-2 font-weight-medium">
              Laravel/Socialite App
            </p>
            <span class="title black--text font-weight-regular">You need to have an Email Account to use the system.</span>
          </v-card-text>

          <v-card-text>
            <v-btn
              @click="loginGoogle"
              color="primary"
              block
              x-large
              depressed
              class="mt-3"
            >
              Log in with your BulSU Account
            </v-btn>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>

import { mapActions } from 'vuex'

export default {

  data () {
    return {
      rules: {

        required: v => !!v || 'This field is required.',
        valid_string: v => !v || /^[\w\r\n &+/Ññ.,-]*$/.test(v) || 'This field must contain valid characters.',
        email: v => /^[\w.]+@bulsu\.edu\.ph$/.test(v) || 'Please enter a valid BulSU Email Account'
      },
      loginForm: true,
      authenticating: false
    }
  },
  computed: {
    hasError () {
      return !!this.loginError
    }
  },
  methods: {
    ...mapActions({
      authenticate: 'auth/AUTHENTICATE',
      login: 'auth/LOGIN',
      getUserData: 'auth/GET_USER_DATA'
    }),
    loginGoogle () {
      this.$auth.authenticate('google').then(response => {
        this.socialLogin(response)
      }).catch(err => {
        console.log({ err: err })
      })
    },
    socialLogin (response) {
      this.authenticating = true
      this.$http.post('/auth/login', response)
        .then(response => {
          this.login(response?.data?.token)
          this.getUserData()
            .then(() => {
              this.$router.go({
                name: 'dashboard'
              })
            })
        })
        .catch(e => {
          console.log(e.response)
        })
    }
  }
}
</script>
