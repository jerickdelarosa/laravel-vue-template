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
                New Vacant Job
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

          <v-row justify="center">
            <v-col
              cols="12"
              md="10"
              lg="8"
              xl="8"
            >
              <!-- STEPPER -->
              <v-stepper v-model="e1">
                <v-stepper-header>
                  <template v-for="n in steps">
                    <v-stepper-step
                      :key="`${n}-step`"
                      :complete="e1 > n"
                      :step="n"
                      editable
                      edit-icon="mdi-check"
                    >
                      <template v-if="n == steps">
                        Finalize
                      </template>

                      <template v-else>
                        Step {{ n }}
                      </template>
                    </v-stepper-step>

                    <v-divider
                      v-if="n !== steps"
                      :key="n"
                    />
                  </template>
                </v-stepper-header>

                <v-stepper-items>
                  <!-- STEP 1 -->
                  <v-stepper-content
                    key="step-1"
                    step="1"
                  >
                    <!-- Date Posted -->
                    <v-dialog
                      ref="datePostedDialog"
                      v-model="datePostedDialog"
                      :return-value.sync="iDatePosted"
                      persistent
                      width="290px"
                    >
                      <template #activator="{ on, attrs }">
                        <v-text-field
                          class="mt-3"
                          outlined
                          label="Date Posted *"
                          :value="datePostedFormatted"
                          clearable
                          @click:clear="iDatePosted = null"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                          :rules="[rules.required]"
                          required
                          append-icon="mdi-calendar"
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
                          @click="datePostedDialog = false"
                        >
                          Cancel
                        </v-btn>
                        <v-btn
                          text
                          color="primary"
                          @click="$refs.datePostedDialog.save(iDatePosted)"
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

                    <!-- Campus -->
                    <v-autocomplete
                      v-model="iCampus"
                      :items="campuses"
                      item-value="name"
                      item-text="name"
                      label="Campus *"
                      :rules="[rules.required]"
                      required
                      outlined
                      clearable
                    />

                    <!-- Office -->
                    <v-autocomplete
                      v-model="iOffice"
                      :items="offices"
                      item-value="office_id"
                      item-text="office_name"
                      label="Office"
                      outlined
                      clearable
                    />

                    <!-- Vacancy Count -->
                    <v-text-field
                      v-model="iVacancyCount"
                      label="Vacancy Count *"
                      :rules="[rules.required, rules.number_min]"
                      type="number"
                      required
                      outlined
                      clearable
                    />

                    <!-- HRMO Type -->
                    <v-autocomplete
                      v-model="iHrmoType"
                      :items="hrmoTypes"
                      item-value="email"
                      item-text="name"
                      label="HRMO type *"
                      :rules="[rules.required]"
                      required
                      outlined
                      clearable
                    />

                    <!-- ACTIONS -->
                    <v-layout justify-end>
                      <v-btn
                        color="primary"
                        @click="e1 = 2"
                      >
                        Continue
                      </v-btn>

                      <v-btn
                        class="ml-2"
                        @click="closeDialog(false)"
                        text
                      >
                        Close
                      </v-btn>
                    </v-layout>
                  </v-stepper-content>

                  <!-- STEP 2 -->
                  <v-stepper-content
                    key="step-2"
                    step="2"
                  >
                    <!-- Job Status -->
                    <v-autocomplete
                      class="mt-2"
                      v-model="iJobStatus"
                      :items="status"
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
                      label="Job Qualification Education *"
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
                      outlined
                    />

                    <!-- Job Qualifications - Training -->
                    <v-textarea
                      v-model="iTraining"
                      clearable
                      clear-icon="mdi-close"
                      label="Job Qualification Training"
                      auto-grow
                      outlined
                    />

                    <!-- Job Qualifications - Eligibility  -->
                    <v-textarea
                      v-model="iEligibility"
                      clearable
                      clear-icon="mdi-close"
                      label="Job Qualification Eligibility"
                      auto-grow
                      outlined
                    />

                    <!-- Job Qualifications - Other  -->
                    <v-textarea
                      v-model="iOther"
                      clearable
                      clear-icon="mdi-close"
                      label="Job Qualification Other"
                      auto-grow
                      outlined
                    />

                    <!-- Job Duties -->
                    <v-textarea
                      v-model="iDuties"
                      clearable
                      clear-icon="mdi-close"
                      label="Job Duties *"
                      auto-grow
                      required
                      outlined
                    />

                    <!-- ACTIONS -->
                    <v-layout justify-end>
                      <v-btn
                        color="primary"
                        @click="e1 = 3"
                      >
                        Continue
                      </v-btn>

                      <v-btn
                        class="ml-2"
                        @click="e1 = 1"
                        text
                      >
                        Back
                      </v-btn>
                    </v-layout>
                  </v-stepper-content>

                  <!-- STEP 3 -->
                  <v-stepper-content
                    key="step-3"
                    step="3"
                  >
                    <!-- Job Item Number -->
                    <v-text-field
                      class="mt-3"
                      v-model="iItemNumber"
                      label="Job Item Number"
                      outlined
                      clear-icon="mdi-close"
                      clearable
                    />

                    <!-- Job Salary Grade -->
                    <v-text-field
                      v-model="iSalaryGrade"
                      label="Job Salary Grade"
                      :rules="[rules.number_min]"
                      type="number"
                      outlined
                    />

                    <!-- Job Salary Value -->
                    <v-text-field
                      v-model="iSalaryValue"
                      clearable
                      clear-icon="mdi-close"
                      label="Job Salary Value"
                      :rules="[rules.number_min]"
                      type="number"
                      outlined
                    />

                    <!-- Job Deadline -->
                    <v-dialog
                      ref="dateDeadlineDialog"
                      v-model="dateDeadlineDialog"
                      :return-value.sync="iDeadline"
                      persistent
                      width="290px"
                    >
                      <template #activator="{ on, attrs }">
                        <v-text-field
                          outlined
                          label="Job Deadline"
                          :value="dateDeadlineFormatted"
                          clearable
                          @click:clear="iDeadline = null"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                          :rules="[rules.required]"
                          required
                          append-icon="mdi-calendar"
                        />
                      </template>

                      <v-date-picker
                        v-model="iDeadline"
                        scrollable
                        ref="dateDeadlinePicker"
                        clearable
                      >
                        <v-spacer />
                        <v-btn
                          text
                          color="primary"
                          @click="dateDeadlineDialog = false"
                        >
                          Cancel
                        </v-btn>
                        <v-btn
                          text
                          color="primary"
                          @click="$refs.dateDeadlineDialog.save(iDeadline)"
                        >
                          OK
                        </v-btn>
                      </v-date-picker>
                    </v-dialog>

                    <!-- Date Filled -->
                    <v-dialog
                      ref="dateFilledDialog"
                      v-model="dateFilledDialog"
                      :return-value.sync="iDateFilled"
                      persistent
                      width="290px"
                    >
                      <template #activator="{ on, attrs }">
                        <v-text-field
                          outlined
                          label="Job Date Filled"
                          :value="dateFilledFormatted"
                          clearable
                          @click:clear="iDateFilled = null"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                          required
                          append-icon="mdi-calendar-check"
                        />
                      </template>

                      <v-date-picker
                        v-model="iDateFilled"
                        scrollable
                        ref="dateFilledPicker"
                        clearable
                      >
                        <v-spacer />
                        <v-btn
                          text
                          color="primary"
                          @click="dateFilledDialog = false"
                        >
                          Cancel
                        </v-btn>
                        <v-btn
                          text
                          color="primary"
                          @click="$refs.dateFilledDialog.save(iDateFilled)"
                        >
                          OK
                        </v-btn>
                      </v-date-picker>
                    </v-dialog>

                    <!-- Job Hidden @ {Date} -->
                    <v-dialog
                      ref="dateUnpostDialog"
                      v-model="dateUnpostDialog"
                      :return-value.sync="iDateUnpost"
                      persistent
                      width="290px"
                    >
                      <template #activator="{ on, attrs }">
                        <v-text-field
                          outlined
                          label="Job Unpost Date"
                          :value="dateUnpostFormatted"
                          clearable
                          @click:clear="iDateUnpost = null"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                          required
                          append-icon="mdi-briefcase-off-outline"
                        />
                      </template>

                      <v-date-picker
                        v-model="iDateUnpost"
                        scrollable
                        ref="dateUnpostPicker"
                        clearable
                      >
                        <v-spacer />
                        <v-btn
                          text
                          color="primary"
                          @click="dateUnpostDialog = false"
                        >
                          Cancel
                        </v-btn>
                        <v-btn
                          text
                          color="primary"
                          @click="$refs.dateUnpostDialog.save(iDateUnpost)"
                        >
                          OK
                        </v-btn>
                      </v-date-picker>
                    </v-dialog>

                    <!-- File Attachment -->
                    <v-text-field
                      v-model="iAttachment"
                      clearable
                      clear-icon="mdi-close"
                      label="Attachment Name"
                      :rules="[rules.valid_string]"
                      outlined
                    />

                    <!-- Job Visibility -->
                    <v-switch
                      class="my-0 ml-3"
                      v-model="iRemark"
                      :label="`Job Remark: ${iRemark ? 'True' : 'False'}`"
                      color="primary"
                      hide-details
                    />

                    <!-- Job Visibility -->
                    <v-switch
                      class="my-0 ml-3"
                      v-model="iVisibility"
                      :label="`Job Visibility: ${iVisibility ? 'True' : 'False'}`"
                      color="primary"
                      hide-details
                    />

                    <!-- Show Job Details -->
                    <v-switch
                      class="my-0 ml-3"
                      v-model="iShowDetails"
                      :label="`Show Job details: ${iShowDetails ? 'True' : 'False'}`"
                      color="primary"
                      hide-details
                    />

                    <!-- ACTIONS -->
                    <v-layout
                      class="pt-5"
                      justify-end
                    >
                      <v-btn
                        color="primary"
                        @click="e1 = 4"
                      >
                        Continue
                      </v-btn>

                      <v-btn
                        class="ml-2"
                        @click="e1 = 2"
                        text
                      >
                        Back
                      </v-btn>
                    </v-layout>
                  </v-stepper-content>

                  <!-- STEP 4 -->
                  <v-stepper-content
                    key="step-4"
                    step="4"
                  >
                    <!-- DESCRIPTION TABLE -->
                    <v-simple-table
                      class="mb-5"
                      dense
                    >
                      <thead>
                        <tr>
                          <th
                            class="text-subtitle-2 font-weight-bold primary--text"
                            width="250"
                          >
                            Description
                          </th>
                          <th class="text-left" />
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="font-weight-bold">
                            Position:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iPosition }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Campus:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iCampus }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            College / Office:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iOffice }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            No. of Vacancies:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iVacancyCount }}
                          </td>
                        </tr>
                        <tr v-show="this.iItemNumber !== null">
                          <td class="font-weight-bold">
                            Job Item Number:
                          </td>
                          <td
                            class="font-weight-regular"
                          >
                            {{ this.iItemNumber }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Employment Type:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iJobStatus }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Duties:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iDuties }}
                          </td>
                        </tr>
                      </tbody>
                    </v-simple-table>

                    <!-- QUALIFICATIONS TABLE -->
                    <v-simple-table
                      class="mb-5"
                      dense
                    >
                      <thead>
                        <tr>
                          <th
                            class="text-subtitle-2 font-weight-bold primary--text"
                            width="250"
                          >
                            Qualifications
                          </th>
                          <th class="text-left" />
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="font-weight-bold">
                            Education:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iEducation !== null ? this.iEducation : 'None required' }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Experience:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iExperience !== null ? this.iExperience : 'None required' }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Training:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iTraining !== null ? this.iTraining : 'None required' }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Eligibility:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iEligibility !== null ? this.iEligibility : 'None required' }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Other Qualifications:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iOther }}
                          </td>
                        </tr>
                      </tbody>
                    </v-simple-table>

                    <!-- REMARKS TABLE -->
                    <v-simple-table
                      class="mb-5"
                      dense
                    >
                      <thead>
                        <tr>
                          <th
                            class="text-subtitle-2 font-weight-bold primary--text"
                            width="250"
                          >
                            Remarks
                          </th>
                          <th class="text-left" />
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="font-weight-bold">
                            Date Posted:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iDatePosted }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Deadline of Application:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iDeadline }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Remarks:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iRemark ? 'Filled' : 'Unfilled' }}
                          </td>
                        </tr>
                      </tbody>
                    </v-simple-table>

                    <!-- OTHERS TABLE -->
                    <v-simple-table
                      class="mb-5"
                      dense
                    >
                      <thead>
                        <tr>
                          <th
                            class="text-subtitle-2 font-weight-bold primary--text"
                            width="250"
                          >
                            Others
                          </th>
                          <th class="text-left" />
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="font-weight-bold">
                            HRMO Type:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iHrmoType }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Salary Grade:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iSalaryGrade !== null ? this.iSalaryGrade : 'N/A' }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Salary Value:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iSalaryValue !== null ? '₱'+this.iSalaryValue : 'N/A' }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Date Filled:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iDateFilled }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Date Unpost:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iDateUnpost }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Visibility:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iVisibility ? 'True' : 'False' }}
                          </td>
                        </tr>
                        <tr>
                          <td class="font-weight-bold">
                            Show Details:
                          </td>
                          <td class="font-weight-regular">
                            {{ this.iShowDetails ? 'True' : 'False' }}
                          </td>
                        </tr>
                      </tbody>
                    </v-simple-table>

                    <!-- ACTIONS -->
                    <v-layout justify-end>
                      <v-btn
                        color="primary"
                        @click="e1 = 4"
                      >
                        Submit
                      </v-btn>

                      <v-btn
                        class="ml-2"
                        @click="e1 = 3"
                        text
                      >
                        Back
                      </v-btn>
                    </v-layout>
                  </v-stepper-content>
                </v-stepper-items>
              </v-stepper>
            </v-col>
          </v-row>
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

      saving: false,

      iId: null,
      datePostedDialog: false,
      iDatePosted: null,
      iPosition: null,
      iCampus: null,
      iOffice: null,
      iVacancyCount: null,
      iHrmoType: null,

      iJobStatus: null,
      iEducation: null,
      iExperience: null,
      iTraining: null,
      iEligibility: null,
      iOther: null,
      iDuties: null,

      iItemNumber: null,
      iSalaryGrade: null,
      iSalaryValue: null,
      dateDeadlineDialog: false,
      iDeadline: null,
      dateFilledDialog: false,
      iDateFilled: null,
      dateUnpostDialog: false,
      iAttachment: null,
      iDateUnpost: null,
      iRemark: false,
      iVisibility: true,
      iShowDetails: true,

      steps: 4,
      e1: 1,

      campuses: [
        {
          id: 1,
          name: 'Malolos Campus'
        },
        {
          id: 2,
          name: 'Bustos Campus'
        },
        {
          id: 3,
          name: 'Meneses Campus'
        },
        {
          id: 4,
          name: 'Sarmiento Campus'
        },
        {
          id: 5,
          name: 'Hagonoy Campus'
        }
      ]

    }
  },
  computed: {
    ...mapGetters({
      offices: 'jobopenings/OFFICE_LIST',
      status: 'jobopenings/STATUS_LIST',
      hrmoTypes: 'jobopenings/HRMO_TYPE_LIST'
    }),
    dateDeadlineFormatted () {
      return this.iDeadline ? moment(this.iDeadline).format('YYYY-MM-DD') : ''
    },

    datePostedFormatted () {
      return this.iDatePosted ? moment(this.iDatePosted).format('YYYY-MM-DD') : ''
    },

    dateFilledFormatted () {
      return this.iDateFilled ? moment(this.iDateFilled).format('YYYY-MM-DD') : ''
    },

    dateUnpostFormatted () {
      return this.iDateUnpost ? moment(this.iDateUnpost).format('YYYY-MM-DD') : ''
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
    }

  },

  methods: {
    ...mapActions({
      _getData: 'jobopenings/GET_DATA',
      _editItem: 'jobopenings/EDIT',
      _saveItem: 'jobopenings/SAVE',
      _updateItem: 'jobopenings/UPDATE'
    }),
    async getData () {
      this.formLoading = true
      await this._getData()

      if (this.hasSelectedItem) {
        await this._editItem({
          id: this.itemId
        }).then((response) => {
          /* const selectedItem = response?.data */
          /*
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
          this.iWarrantyLength = selectedItem?.warranty_length?.id */
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
