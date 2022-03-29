<template>
  <v-form
    v-model="photoForm"
    ref="photoForm"
    @submit.prevent="submitPhoto"
    lazy-validation
    enctype="multipart/form-data"
  >
    <v-container
      :style="{ 'margin-top': $vuetify.breakpoint.smAndDown ? '-68' : '-76' }"
    >
      <v-row justify="center">
        <v-col
          cols="12"
          md="10"
          lg="7"
          xl="5"
        >
          <v-card class="mb-3">
            <v-toolbar flat>
              <v-toolbar-title class="title">
                Upload Photo
              </v-toolbar-title>
            </v-toolbar>

            <v-divider />

            <v-card-text>
              <p>Your photo must be:</p>
              <ul>
                <li><b>2x2 inches</b> in size;</li>
                <li>
                  <b>in JPEG (.jpg)</b> file format <b>not exceeding 1MB</b> in
                  file size;
                </li>
              </ul>
            </v-card-text>

            <v-divider />

            <v-card-text>
              <v-file-input
                v-model="image"
                type="file"
                :rules="rules.image"
                accept="image/jpg"
                prepend-icon="mdi-camera"
                @change="croppie"
                label="Select a photo"
                show-size
              />
            </v-card-text>

            <template v-if="!!image && photoForm">
              <v-card-text>
                <vue-croppie
                  :boundary="{ height: 500 }"
                  :vieport="{ width: 350, height: 350 }"
                  :enable-resize="false"
                  ref="cropper"
                />
              </v-card-text>
            </template>
          </v-card>

          <v-btn
            color="primary"
            type="submit"
            :loading="uploading"
            large
          >
            Upload photo
          </v-btn>
        </v-col>
      </v-row>
    </v-container>

    <v-snackbar
      v-model="snackbar"
      timeout="4000"
      :color="snackbarColor"
      right
      app
    >
      <v-icon
        left
        :color="snackbarIconColor"
      >
        {{ snackbarIcon }}
      </v-icon>
      {{ snackbarText }}
    </v-snackbar>
  </v-form>
</template>
<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  data () {
    return {
      rules: {
        image: [
          (v) => !!v || 'Please select a photo.',
          (v) => !v || v.type === 'image/jpeg' || 'Upload only .jpeg images.',
          (v) =>
            !v || v.size < 1000000 || 'Photo size should be less than 1 MB.'
        ]
      },

      image: null,
      photoForm: null,
      cropped: null,

      snackbar: false,
      snackbarColor: null,
      snackbarText: false,
      snackbarIcon: null,
      snackbarIconColor: null

    }
  },

  computed: {
    ...mapGetters({
      uploading: 'photo/PHOTO_UPLOADING',
      uploaded: 'photo/PHOTO_UPLOADED'
    })
  },

  methods: {
    ...mapActions({
      uploadPhoto: 'photo/UPLOAD_PHOTO'
    }),

    croppie (file) {
      if (file) {
        const reader = new FileReader()

        reader.onload = (e) => {
          this.$refs.cropper.bind({
            url: e.target.result
          })
        }

        reader.readAsDataURL(file)
      }
    },

    submitPhoto () {
      if (this.$refs.photoForm.validate()) {
        var opts = {
          type: 'base64',
          format: 'jpeg'
        }
      }

      this.$refs.cropper.result(opts, (output) => {
        this.cropped = output
      })
    },

    showSnackbar (data) {
      this.snackbar = true

      this.snackbarColor = data?.color
      this.snackbarIcon = data?.icon
      this.snacbarIconColor = data?.iconColor
      this.snackbarText = data?.text
    }
  },

  watch: {
    cropped (croppedImage) {
      if (croppedImage) {
        this.uploadPhoto({
          image: croppedImage
        })
      }
    },

    uploaded (val) {
      if (val) {
        this.showSnackbar({
          color: 'primary',
          icon: 'mdi-check-circle-outline',
          iconColor: '#fff',
          text: 'Your photo has been uploaded.'
        })
      }
    }
  }
}
</script>
