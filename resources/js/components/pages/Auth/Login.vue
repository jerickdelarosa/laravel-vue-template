<template>
  <v-container full-height>
    <v-row
      justify="center"
      align="center"
    >
      <v-col
        cols="24"
        md="8"
        lg="6"
        xl="4"
      >
        <v-card outlined>
          <v-progress-linear
            indeterminate
            color="primary"
            absolutetop
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
              class="mx-auto md-3"
            />

            <p class="headline black--text mb--2 font-weight-medium">
              Login
            </p>

            <span class="title black--text font-weight-regular">
              You need to have an Email to use the system.
            </span>
          </v-card-text>

          <v-card-text>
            <v-btn
              color="primary"
              block
              x-large
              depressed
              class="mt-3"
            >
              Log in with your Email Account
            </v-btn>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
