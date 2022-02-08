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

          <v-form
            ref="jobPostingForm"
            v-model="jobPostingForm"
            @submit.prevent="submitForm"
          >
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
                          Summary
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
                        :error="hasPositionError"
                        :error-messages="positionError"
                        outlined
                        clearable
                      />

                      <!-- Campus -->
                      <v-autocomplete
                        v-model="iCampus"
                        :items="campuses"
                        item-value="campus_name"
                        item-text="campus_name"
                        label="Campus *"
                        :rules="[rules.required]"
                        :error="hasCampusError"
                        :error-messages="campusError"
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
                        item-value="id"
                        item-text="email"
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
                        label="Job Qualification Education"
                        auto-grow
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
                        label="Job Duties"
                        auto-grow
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
                        type="number"
                        outlined
                      />

                      <!-- Job Salary Value -->
                      <v-text-field
                        v-model="iSalaryValue"
                        clearable
                        clear-icon="mdi-close"
                        label="Job Salary Value"
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
                            label="Job Deadline *"
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

                      <!-- Job Link-->
                      <v-text-field
                        v-model="iLinks"
                        clearable
                        clear-icon="mdi-close"
                        label="Appy Link"
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
                          <tr>
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
                              {{ this.iSalaryGrade }}
                            </td>
                          </tr>
                          <tr>
                            <td class="font-weight-bold">
                              Salary Value:
                            </td>
                            <td class="font-weight-regular">
                              {{ this.iSalaryValue !== null ? '₱'+this.iSalaryValue : '' }}
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
                          <tr>
                            <td class="font-weight-bold">
                              Apply Link:
                            </td>
                            <td class="font-weight-regular">
                              {{ this.iLinks }}
                            </td>
                          </tr>
                        </tbody>
                      </v-simple-table>

                      <!-- ACTIONS -->
                      <v-layout justify-end>
                        <v-btn
                          color="primary"
                          type="submit"
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
          </v-form>
        </v-container>
      </v-card-text>
    </v-card>

    <!-- ERROR DIALOG -->
    <v-dialog
      v-model="submitErrorDialog"
      max-width="390"
      persistent
    >
      <v-card>
        <v-toolbar
          color="primary"
          dark
          dense
        >
          <v-icon
            dark
            large
          >
            mdi-alert-octagon-outline
          </v-icon>
        </v-toolbar>
        <v-card-text>
          <div class="pt-5">
            <span class="text-subtitle-2 font-weight-bold">Please make sure all the data are correct.</span>
          </div>
        </v-card-text>
        <v-card-actions class="pt-0">
          <v-spacer />
          <v-btn
            color="grey darken-1"
            text
            @click="submitErrorDialog = false"
          >
            Close
          </v-btn>
          <v-spacer />
        </v-card-actions>
      </v-card>
    </v-dialog>
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
      type: Number,
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
      jobPostingForm: null,

      saving: false,

      jobPostingErrors: null,

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
      iLinks: null,

      steps: 4,
      e1: 1,

      submitErrorDialog: false

    }
  },
  computed: {
    ...mapGetters({
      offices: 'jobopenings/OFFICE_LIST',
      status: 'jobopenings/STATUS_LIST',
      hrmoTypes: 'jobopenings/HRMO_TYPE_LIST',
      campuses: 'jobopenings/CAMPUS_LIST'
    }),
    hasSelectedItem () {
      return !!this.itemId
    },
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

    positionError () {
      return this.jobPostingErrors?.itemId
    },
    hasPositionError () {
      return !!this.positionError
    },
    campusError () {
      return this.jobPostingErrors?.itemId
    },
    hasCampusError () {
      return !!this.campusError
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
          jo_id: this.itemId
        }).then((response) => {
          const selectedItem = response?.data
          console.log(selectedItem)

          this.itemName = selectedItem?.jo_position

          // Step 1
          this.iDatePosted = selectedItem?.jo_date_posted // Date Format
          this.iPosition = selectedItem?.jo_position
          this.iCampus = selectedItem?.jo_campus
          this.iOffice = selectedItem?.jo_office
          this.iVacancyCount = selectedItem?.jo_vacancy_count
          this.iHrmoType = selectedItem?.jo_hr_type

          // Step 2
          this.iJobStatus = selectedItem?.jo_status
          this.iEducation = selectedItem?.jo_qualifications_education
          this.iExperience = selectedItem?.jo_qualifications_experience
          this.iTraining = selectedItem?.jo_qualifications_training
          this.iEligibility = selectedItem?.jo_qualifications_eligibility
          this.iOther = selectedItem?.jo_qualifications_other
          this.iDuties = selectedItem?.jo_duties

          // Step 3
          this.iItemNumber = selectedItem?.jo_item_number
          this.iSalaryGrade = selectedItem?.jo_salary_grade
          this.iSalaryValue = selectedItem?.jo_salary_value
          this.iDeadline = selectedItem?.jo_deadline
          this.iDateFilled = selectedItem?.jo_date_filled
          this.iDateUnpost = selectedItem?.jo_hidden_at
          this.iAttachment = selectedItem?.attachment
          this.iLinks = selectedItem?.links
          this.iRemark = selectedItem?.jo_remarks
          this.iVisibility = selectedItem?.jo_visibility
          this.iShowDetails = selectedItem?.show_details
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
      if (this.$refs.jobPostingForm.validate()) {
        if (this.hasSelectedItem) {
          this.updateItem()
        } else {
          this.saveItem()
        }
      } else {
        this.submitErrorDialog = true
      }
    },
    resetFields () {
      setTimeout(() => {
        this.iDatePosted = null
        this.iPosition = null
        this.iCampus = null
        this.iOffice = null
        this.iVacancyCount = null
        this.iHrmoType = null

        this.iJobStatus = null
        this.iEducation = null
        this.iExperience = null
        this.iTraining = null
        this.iEligibility = null
        this.iOther = null
        this.iDuties = null

        this.iItemNumber = null
        this.iSalaryGrade = null
        this.iSalaryValue = null
        this.iDeadline = null
        this.iDateFilled = null
        this.iAttachment = null
        this.iDateUnpost = null
        this.iRemark = false
        this.iVisibility = true
        this.iShowDetails = true
        this.iLinks = null

        this.jobPostingErrors = null
        this.e1 = 1
        this.submitErrorDialog = false

        this.$refs.jobPostingForm.resetValidation()
      }, 500)
    },

    // Save New Item
    saveItem () {
      this.saving = true

      this._saveItem({
        jo_date_posted: this.iDatePosted,
        jo_position: this.iPosition,
        jo_campus: this.iCampus,
        jo_office: this.iOffice,
        jo_vacancy_count: this.iVacancyCount,
        jo_status: this.iJobStatus,
        jo_hr_type: this.iHrmoType,
        jo_qualifications_education: this.iEducation,
        jo_qualifications_experience: this.iExperience,
        jo_qualifications_training: this.iTraining,
        jo_qualifications_eligibility: this.iEligibility,
        jo_qualifications_other: this.iOther,
        jo_duties: this.iDuties,
        jo_item_number: this.iItemNumber,
        jo_salary_grade: this.iSalaryGrade,
        jo_salary_value: this.iSalaryValue,
        jo_deadline: this.iDeadline,
        jo_remarks: this.iRemark,
        jo_visibility: this.iVisibility,
        jo_date_filled: this.iDateFilled,
        jo_hidden_at: this.iDateUnpost,
        attachment: this.iAttachment,
        show_details: this.iShowDetails,
        links: this.iLinks
      })
        .catch((e) => {
          this.jobPostingErrors = e.errors
        })
        .then((response) => {
          if (response?.created) {
            this.closeDialog(true)
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
        jo_id: this.itemId,
        jo_date_posted: this.iDatePosted,
        jo_position: this.iPosition,
        jo_campus: this.iCampus,
        jo_office: this.iOffice,
        jo_vacancy_count: this.iVacancyCount,
        jo_status: this.iJobStatus,
        jo_hr_type: this.iHrmoType,
        jo_qualifications_education: this.iEducation,
        jo_qualifications_experience: this.iExperience,
        jo_qualifications_training: this.iTraining,
        jo_qualifications_eligibility: this.iEligibility,
        jo_qualifications_other: this.iOther,
        jo_duties: this.iDuties,
        jo_item_number: this.iItemNumber,
        jo_salary_grade: this.iSalaryGrade,
        jo_salary_value: this.iSalaryValue,
        jo_deadline: this.iDeadline,
        jo_remarks: this.iRemark,
        jo_visibility: this.iVisibility,
        jo_date_filled: this.iDateFilled,
        jo_hidden_at: this.iDateUnpost,
        attachment: this.iAttachment,
        show_details: this.iShowDetails,
        links: this.iLinks
      })
        .catch((e) => {
          this.jobPostingErrors = e.errors
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
