<template>
  <v-dialog
    fullscreen
    :value="visible"
    scrollable
    persistent
  >
    <v-card>
      <v-card-title class="pa-0 font-weight-normal">
        <v-toolbar
          flat
          color="primary"
          dark
        >
          <template v-if="formLoading">
            <v-row>
              <v-col
                cols="6"
                md="8"
                lg="5"
              >
                <v-skeleton-loader
                  dark
                  class="title-skeleton-loader"
                  type="list-item-two-line"
                />
              </v-col>
            </v-row>
          </template>

          <template v-else>
            <v-btn
              icon
              :disabled="saving"
              @click="closeDialog(false)"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <template v-if="hasSelectedItem">
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Edit Item</v-list-item-title>
                  <v-list-item-subtitle>{{ itemName }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </template>
            <template v-else>
              <v-toolbar-title>
                Add New Job
              </v-toolbar-title>
            </template>
          </template>
          <v-progress-linear
            indeterminate
            :active="saving"
            color="white"
            absolute
            bottom
          />
        </v-toolbar>
      </v-card-title>
      <v-divider />

      <v-card-text id="form-container">
        <v-container>
          <v-overlay
            :value="formLoading || saving"
            style="margin-top: 64px;"
            color="white"
            :opacity="formLoading ? 1 : 0.5"
          >
            <template v-if="formLoading">
              <v-progress-circular
                indeterminate
                color="primary"
              />
            </template>
          </v-overlay>
          <v-form
            ref="inventoryForm"
            v-model="inventoryForm"
            @submit.prevent="submitForm"
          >
            <v-row justify="center">
              <v-col
                cols="12"
                md="10"
                lg="7"
                xl="5"
              >
                <v-card outlined>
                  <v-toolbar
                    flat
                    color="grey lighten-4"
                  >
                    <v-toolbar-title class="title">
                      Job Details
                    </v-toolbar-title>
                  </v-toolbar>
                  <v-divider />
                  <v-card-text>
                    <!-- Date Posted -->
                    <v-dialog
                      ref="dateDialog"
                      v-model="dateDialog"
                      :return-value.sync="iDatePosted"
                      persistent
                      width="290px"
                    >
                      <template #activator="{ on, attrs }">
                        <v-text-field
                          outlined
                          label="Date Posted *"
                          :value="dateFormatted"
                          clearable
                          @click:clear="iDatePosted = null"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                          :rules="[rules.required]"
                          required
                        />
                      </template>

                      <v-date-picker
                        v-model="iDatePosted"
                        scrollable
                        ref="datePostedPicker"
                        clearable
                      >
                        <v-spacer />
                        <v-btn
                          text
                          color="primary"
                          @click="dateDialog = false"
                        >
                          Cancel
                        </v-btn>
                        <v-btn
                          text
                          color="primary"
                          @click="$refs.dateDialog.save(iDatePosted)"
                        >
                          OK
                        </v-btn>
                      </v-date-picker>
                    </v-dialog>

                    <!-- Job Position -->
                    <v-text-field
                      v-model="iPosition"
                      label="Position *"
                      :rules="[rules.required]"
                      outlined
                      clearable
                    />

                    <!-- Campus and Office -->
                    <v-row>
                      <v-col
                        cols="6"
                        class="pb-0"
                      >
                        <v-autocomplete
                          v-model="iCampus"
                          :items="campuses"
                          item-value="id"
                          item-text="name"
                          label="Campus *"
                          :rules="[rules.required]"
                          required
                          outlined
                          clearable
                        />
                      </v-col>

                      <v-col
                        cols="6"
                        class="pb-0"
                      >
                        <v-autocomplete
                          v-model="iOffice"
                          :items="offices"
                          item-value="id"
                          item-text="name"
                          label="Office"
                          required
                          outlined
                          clearable
                        />
                      </v-col>
                    </v-row>

                    <!-- Vacancy Count and HRMO Type -->
                    <v-row>
                      <v-col
                        cols="6"
                        class="pt-0"
                      >
                        <v-text-field
                          v-model="iVacancyCount"
                          label="Vacancy Count *"
                          :rules="[rules.required, rules.number_min]"
                          type="number"
                          required
                          outlined
                          clearable
                        />
                      </v-col>

                      <v-col
                        cols="6"
                        class="pt-0"
                      >
                        <v-autocomplete
                          v-model="iHrmoTypes"
                          :items="hrmo_types"
                          item-value="id"
                          item-text="name"
                          label="HRMO type *"
                          :rules="[rules.required]"
                          required
                          outlined
                          clearable
                        />
                      </v-col>
                    </v-row>

                    <!-- Job Status -->
                    <v-autocomplete
                      v-model="iJobStatus"
                      :items="job_status"
                      item-value="status_id"
                      item-text="status_name"
                      label="Job Status *"
                      :rules="[rules.required]"
                      required
                      outlined
                      clearable
                    />

                    <!-- Job Qualifications - Education -->
                    <v-textarea
                      v-model="iEducation"
                      clearable
                      clear-icon="mdi-close"
                      label="Job Qualification Education"
                      auto-grow
                      required
                      outlined
                    />

                    <!-- Job Qualifications - Experience -->
                    <v-textarea
                      v-model="iExperience"
                      clearable
                      clear-icon="mdi-close"
                      label="Job Qualification Experience"
                      auto-grow
                      required
                      outlined
                    />

                    <!-- Job Qualifications - Training -->
                    <v-textarea
                      v-model="iTraining"
                      clearable
                      clear-icon="mdi-close"
                      label="Job Qualification Training"
                      auto-grow
                      required
                      outlined
                    />

                    <!-- Job Qualifications - Eligibility  -->
                    <v-textarea
                      v-model="iEligibility"
                      clearable
                      clear-icon="mdi-close"
                      label="Job Qualification Eligibility"
                      auto-grow
                      required
                      outlined
                    />

                    <!-- Job Qualifications - Other  -->
                    <v-textarea
                      v-model="iOther"
                      clearable
                      clear-icon="mdi-close"
                      label="Job Qualification Other"
                      auto-grow
                      required
                      outlined
                    />

                    <!-- Job Duties -->
                    <v-textarea
                      v-model="iDuties"
                      clearable
                      clear-icon="mdi-close"
                      label="Job Duties"
                      auto-grow
                      required
                      outlined
                    />

                    <!-- Source of Fund -->
                    <v-autocomplete
                      v-model="iFund"
                      :items="funds"
                      item-text="name"
                      item-value="id"
                      label="Source of Fund *"
                      :rules="[rules.required]"
                      required
                      outlined
                      clearable
                    />

                    <!-- Amount -->
                    <v-text-field
                      v-model="iAmount"
                      label="Item Amount *"
                      :rules="[rules.required, rules.number_min]"
                      required
                      outlined
                      type="number"
                      clearable
                    />

                    <!-- Count and Unit -->
                    <v-row>
                      <v-col
                        cols="6"
                        class="pb-0"
                      >
                        <v-text-field
                          v-model="iQuantity"
                          label="Quantity *"
                          :rules="[rules.required, rules.number_min]"
                          type="number"
                          required
                          outlined
                          clearable
                        />
                      </v-col>

                      <v-col
                        cols="6"
                        class="pb-0"
                      >
                        <v-autocomplete
                          v-model="iUnit"
                          :items="measurements"
                          item-value="id"
                          item-text="unit"
                          label="Unit of Measurement *"
                          :rules="[rules.required]"
                          required
                          outlined
                          clearable
                        />
                      </v-col>
                    </v-row>

                    <!-- Warranty -->
                    <v-row>
                      <v-col
                        cols="6"
                        class="py-0"
                      >
                        <v-text-field
                          v-model="iWarrantyDuration"
                          label="Warranty Duration *"
                          type="number"
                          :rules="hasWarrantyLength ? [rules.required, rules.number_min] : []"
                          :required="hasWarrantyLength"
                          outlined
                          clearable
                        />
                      </v-col>
                      <v-col
                        cols="6"
                        class="py-0"
                      >
                        <v-autocomplete
                          v-model="iWarrantyLength"
                          :items="warrantyLengths"
                          label="Warranty Length *"
                          item-value="id"
                          item-text="name"
                          :rules="hasWarrantyDuration ? [rules.required] : []"
                          :required="hasWarrantyDuration"
                          outlined
                          clearable
                        />
                      </v-col>
                    </v-row>
                  </v-card-text>
                </v-card>
              </v-col>
            </v-row>

            <v-row justify="center">
              <v-col
                class=" text-right"
                cols="12"
                md="10"
                lg="7"
                xl="5"
              >
                <v-btn
                  @click="closeDialog(false)"
                  depressed
                  large
                >
                  Cancel
                </v-btn>

                <v-btn
                  color="primary"
                  depressed
                  large
                  type="submit"
                >
                  Save Job
                </v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-container>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import moment from 'moment'

import { mapActions, mapGetters } from 'vuex'

export default {
  props: {
    visible: {
      type: Boolean,
      default: false
    },
    itemId: {
      type: String,
      required: false,
      default: null
    }
  },
  data () {
    return {
      rules: {
        required: (v) => !!v || 'This is a required field.',
        valid_string: (v) =>
          !v ||
          /^[\w\r\n Ññ.,-]*$/.test(v) ||
          'This field must contain valid characters.',
        number_min: (v) => v > 0 || 'This field must be greater than 0.'
      },

      formLoading: false,
      userRoleForm: null,

      saving: false,
      userRoleErrors: null,

      dataDialog: false,
      iDatePosted: null,

      campuses: [
        {
          id: 1,
          name: 'Malolos'
        },
        {
          id: 2,
          name: 'Bustos'
        },
        {
          id: 3,
          name: 'Meneses'
        },
        {
          id: 4,
          name: 'Sarmiento'
        },
        {
          id: 5,
          name: 'Hagonoy'
        }
      ],

      offices: [],

      hrmo_types: [
        {
          id: 1,
          name: 'Central',
          email: 'chrmo.recruitment@bulsu.edu.ph'
        },
        {
          id: 2,
          name: 'Main',
          email: 'hrmo.main@bulsu.edu.ph'
        },
        {
          id: 3,
          name: 'External',
          email: 'hrmo.external@bulsu.edu.ph'
        }
      ]

    }
  },
  computed: {
    ...mapGetters({
      funds: 'jobpostings/FUND_LIST',
      locations: 'jobpostings/LOCATION_LIST',
      measurements: 'jobpostings/MEASUREMENT_LIST',
      offices: 'jobpostings/OFFICE_LIST',
      categories: 'jobpostings/CATEGORY_LIST',
      warrantyLengths: 'jobpostings/WARRANTY_LENGTH_LIST',
      _subcategories: 'jobpostings/SUBCATEGORY_LIST'
    }),

    datePostedError () {
      return this.jobPostingErrors?.id
    },
    hasDatePostedError () {
      return !!this.datePostedError
    },
    dateFormatted () {
      return this.iDatePosted ? moment(this.iDatePosted).format('YYYY-MM-DD') : ''
    },

    hasWarrantyDuration () {
      return !!this.iWarrantyDuration
    },
    hasWarrantyLength () {
      return !!this.iWarrantyLength
    },
    subcategories () {
      return this.iCategory?.subcategories
    },
    hasSubcategories () {
      return this.subcategories?.length > 0
    },
    deliveredByError () {
      return this.inventoryErrors?.code
    },
    hasDeliveredByError () {
      return !!this.deliveredByError
    },

    descriptionError () {
      return this.inventoryErrors?.code
    },
    hasDescriptionError () {
      return !!this.descriptionError
    },
    warrantyError () {
      return this.inventoryErrors?.code
    },
    hasWarrantyError () {
      return !!this.warrantyError
    },
    hasSelectedItem () {
      return !!this.itemId
    }

  },
  watch: {
    visible (isVisible) {
      if (isVisible) {
        this.getData()
      } else {
        this.resetFields()
      }
    },

    iCategory (newVal, oldVal) {
      if (!this.hasSelectedItem) {
        this.iSubcategory = null
      } else {
        if (oldVal) {
          this.iSubcategory = null
        }
      }
    }

  },

  methods: {
    ...mapActions({
      _getData: 'jobpostings/GET_DATA',
      _editItem: 'jobpostings/EDIT',
      _saveItem: 'jobpostings/SAVE',
      _updateItem: 'jobpostings/UPDATE'
    }),
    async getData () {
      this.formLoading = true
      await this._getData()

      if (this.hasSelectedItem) {
        await this._editItem({
          code: this.itemId
        }).then((response) => {
          const selectedItem = response?.data

          this.itemName = selectedItem?.article

          this.iPoNum = selectedItem?.po_number
          this.iDateAcquired = selectedItem?.acquired_at

          this.iArticle = selectedItem?.article
          this.iDescription = selectedItem?.description

          this.iCategory = this.categories?.find((category) => {
            return category.id === selectedItem?.category?.id
          })
          this.iSubcategory = selectedItem?.subcategory?.id

          this.iFund = selectedItem?.fund?.id

          this.iAmount = selectedItem?.amount

          this.iQuantity = selectedItem?.quantity
          this.iUnit = selectedItem?.measurement?.id

          this.iWarrantyDuration = selectedItem?.warranty_duration
          this.iWarrantyLength = selectedItem?.warranty_length?.id
        })
      }

      document.getElementById('form-container').scrollTop = 0
      this.formLoading = false
    },
    closeDialog (refreshItems) {
      this.$emit('close', refreshItems)
    },
    // Submit Item Form
    submitForm () {
      if (this.$refs.inventoryForm.validate()) {
        if (this.hasSelectedItem) {
          this.updateItem()
        } else {
          this.saveItem()
        }
      }
    },
    resetFields () {
      setTimeout(() => {
        this.iPoNum = null
        this.iArticle = null
        this.iDescription = null
        this.iCategory = null
        this.iSubcategory = null
        this.iFund = null
        this.iAmount = null
        this.iQuantity = null
        this.iUnit = null
        this.iWarrantyDuration = null
        this.iWarrantyLength = null
        this.iDateAcquired = null

        this.$refs.inventoryForm.resetValidation()
      }, 500)
    },

    // Save New Item
    saveItem () {
      this.saving = true

      this._saveItem({
        acquired_at: this.iDateAcquired,
        po_number: this.iPoNum,
        article: this.iArticle,
        description: this.iDescription,
        category_id: this.iCategory?.id,
        subcategory_id: this.iSubcategory,
        fund_id: this.iFund,
        amount: this.iAmount,
        quantity: this.iQuantity,
        measurement_id: this.iUnit,
        warranty_duration: this.iWarrantyDuration,
        warranty_length_id: this.iWarrantyLength
      })
        .catch((e) => {
          this.inventoryErrors = e.errors
        })
        .then((response) => {
          if (response?.created) {
            this.closeDialog(true)
            this.$router.push({
              name: 'main.inventory.view.overview',
              params: {
                item: response.code
              }
            })
          }
        })
        .finally(() => {
          this.saving = false
        })
    },

    // Update Selected Item
    updateItem () {
      this.saving = true

      this._updateItem({
        id: this.itemId,
        acquired_at: this.iDateAcquired,
        po_number: this.iPoNum,
        article: this.iArticle,
        description: this.iDescription,
        category_id: this.iCategory?.id,
        subcategory_id: this.iSubcategory,
        fund_id: this.iFund,
        amount: this.iAmount,
        quantity: this.iQuantity,
        measurement_id: this.iUnit,
        warranty_duration: this.iWarrantyDuration,
        warranty_length_id: this.iWarrantyLength
      })
        .catch((e) => {
          this.inventoryErrors = e.errors
        })
        .then((response) => {
          if (response?.updated) {
            this.closeDialog(true)
          }
        })
        .finally(() => {
          this.saving = false
        })
    }
  }
}
</script>
