<template>
  <v-form
    v-model="uploadForm"
    ref="uploadForm"
    @submit.prevent="uploadDocument"
    enctype="multipart/form-data"
  >
    <v-container>
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
                Upload Document
              </v-toolbar-title>
            </v-toolbar>
            <v-divider />
            <v-card-text>
              <p>Your document must be:</p>
              <ul>
                <li>
                  <strong>PDF Format</strong> only;
                </li>
                <li>
                  <b>in PDF (.pdf)</b> file format <b>not exceeding 5MB</b> in
                  file size;
                </li>
              </ul>
            </v-card-text>
            <v-divider />
            <!-- <v-list-item two-line>
              <v-list-content>
                <v-list-item-title>{{ document.title }}</v-list-item-title>
                <v-list-item-subtitle>{{ document.subtitle }}</v-list-item-subtitle>
              </v-list-content>
            </v-list-item> -->
            <v-card-text>
              <v-file-input
                v-model="file"
                type="file"
                show-size
                :rules="rules.document"
                accept="application/pdf"
                prepend-icon="mdi-file-pdf"
                label="Upload your document"
              />
            </v-card-text>
          </v-card>
          <v-btn
            type="submit"
            color="primary"
            :loading="uploading"
            large
          >
            Upload Document
          </v-btn>
        </v-col>
      </v-row>
    </v-container>

    <v-snackbar
      v-model="snackbar"
      timeout="-1"
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
import { mapGetters, mapActions } from 'vuex'

export default {
  /* props: {
    document: {
      type: Object
    }
  }, */
  data () {
    return {
      uploadForm: null,
      file: null,

      snackbar: false,
      snackbarColor: null,
      snackbarText: false,
      snackbarIcon: null,
      snackbarIconColor: null,

      rules: {
        document: [
          v => !!v || 'Please select a document.',
          v => !v || v.type === 'application/pdf' || 'Upload only pdf files.',
          v => !v || v.size < 5000000 || 'File size should be less than 5 MB.'
        ]
      }
    }
  },
  computed: {
    ...mapGetters({
      uploading: 'document/DOCUMENT_UPLOADING',
      uploaded: 'document/DOCUMENT_UPLOADED'
    })
  },
  watch: {
    uploaded (val) {
      if (val) {
        this.showSnackbar({
          color: 'primary',
          icon: 'mdi-check-circle-outline',
          iconColor: '#fff',
          text: 'Your document has been uploaded.'
        })
      }
    }
  },
  methods: {
    ...mapActions({
      upload: 'document/UPLOAD_DOCUMENT'
    }),
    uploadDocument () {
      if (this.$refs.uploadForm.validate()) {
        const formData = new FormData()
        formData.append('document', this.file)

        this.upload(formData)

        console.log(this.upload(formData))
      }
    },

    showSnackbar (data) {
      this.snackbar = true

      this.snackbarColor = data?.color
      this.snackbarIcon = data?.icon
      this.snackbarIconColor = data?.iconColor
      this.snackbarText = data?.text
    }
  }
}
</script>
