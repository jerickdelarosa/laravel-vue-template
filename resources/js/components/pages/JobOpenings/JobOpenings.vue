<template>
  <v-container fluid>
    <v-card outlined>
      <v-toolbar flat>
        <v-toolbar-title
          class=" text-subtitle-1 font-weight-bold"
        >
          Job Openings
        </v-toolbar-title>

        <v-spacer />

        <!-- REFRESH BUTTON -->
        <v-tooltip bottom>
          <template #activator="{ on: refreshTooltip }">
            <v-btn
              @click="getJobs()"
              icon
              v-on="refreshTooltip"
            >
              <v-icon> mdi-refresh </v-icon>
            </v-btn>
          </template>
          <span>Refresh</span>
        </v-tooltip>

        <!-- ADD MEASUREMENT BUTTON -->
        <v-tooltip bottom>
          <template #activator="{ on: newItemTooltip }">
            <v-btn
              icon
              v-on="newItemTooltip"
              @click="openItemDialog()"
            >
              <v-icon> mdi-plus </v-icon>
            </v-btn>
          </template>
          <span>Add New Item</span>
        </v-tooltip>
      </v-toolbar>

      <v-divider />

      <v-toolbar flat>
        <v-text-field
          v-model="search"
          prepend-icon="mdi-magnify"
          label="Search Job.."
          clearable
          hide-details
          solo
          flat
        />
      </v-toolbar>
      <v-divider />

      <!-- DATA TABLE -->
      <v-data-table
        class="inventory-table"
        :headers="headers"
        :footer-props="footer"
        :items="jobs"
        :search="search"
        :loading="loading"
        @click:row="viewItem"
      >
        <!-- JOB STATUS-->
        <template #[`item.jo_visibility`]="{ item }">
          <v-chip
            small
            dark
            label
            :color="item.jo_visibility ? 'green' : 'grey'"
          >
            <v-icon
              small
              left
            >
              mdi-{{ item.jo_visibility ? "check" : "close" }}
            </v-icon>
            {{ item.jo_visibility ? "Active" : "Inactive" }}
          </v-chip>
        </template>

        <!--  ITEM ACTIONS -->
        <template #[`item.actions`]="{ item }">
          <v-tooltip bottom>
            <template #activator="{ on: editTooltip }">
              <v-btn
                icon
                small
                @click.stop="editItem(item)"
                v-on="editTooltip"
              >
                <v-icon small>
                  mdi-pencil
                </v-icon>
              </v-btn>
            </template>

            <span>Edit Item</span>
          </v-tooltip>

          <v-tooltip bottom>
            <template #activator="{ on: deleteTooltip }">
              <v-btn
                icon
                small
                v-on="deleteTooltip"
                @click.stop="deleteItem(item)"
              >
                <v-icon small>
                  mdi-delete
                </v-icon>
              </v-btn>
            </template>

            <span>Delete Item</span>
          </v-tooltip>
        </template>
      </v-data-table>
    </v-card>

    <!-- INVENTORY FORM -->
    <job-posting-form-dialog
      :visible="jobPostingDialog"
      :item-id="selectedItem"
      @close="closeItemDialog"
    />

    <!-- DELETE INVENTORY DIALOG -->
    <v-dialog
      v-model="deleteDialog"
      persistent
      width="290"
    >
      <v-card>
        <v-card-title>Delete Item</v-card-title>
        <v-card-text> Are you sure you want to delete this item? </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            text
            @click="closeDelete"
            :disabled="deleting"
          >
            Cancel
          </v-btn>
          <v-btn
            text
            color="red"
            @click="confirmDelete"
            :loading="deleting"
          >
            Delete
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import JobPostingFormDialog from '../../ui/dialogs/JobOpenings/JobPostingForm.vue'

import { mapActions, mapGetters } from 'vuex'
export default {
  components: {
    JobPostingFormDialog
  },
  beforeRouteEnter (to, from, next) {
    next((vm) => {
      vm.getJobs()
    })
  },
  data () {
    return {
      rules: {
        required: (v) => !!v || 'This is a required field.',
        valid_string: (v) =>
          !v ||
          /^[\w\r\n Ññ.,-]*$/.test(v) ||
          'This field must contain valid characters.'
      },

      jobPostingDialog: false,

      deleteDialog: false,

      deleting: false,
      loading: false,
      jobPostingLoading: false,

      iJobId: null,

      selectedItem: null,

      search: null,

      headers: [
        {
          value: 'jo_visibility',
          text: 'Status',
          filterable: false,
          width: 150
        },
        {
          value: 'jo_date_posted',
          text: 'Date Posted',
          filterable: false,
          sortable: false,
          width: 150
        },
        {
          value: 'jo_position',
          text: 'Position',
          filterable: false,
          sortable: false,
          width: 300
        },
        {
          value: 'jo_vacancy_count',
          text: 'Vacancy Count',
          width: 150

        },
        {
          value: 'jo_campus',
          text: 'Campus',
          cellClass: ['text-wrap'],
          width: 150
        },
        {
          value: 'jo_office',
          text: 'Office',
          width: 150
        },
        {
          value: 'jo_status',
          text: 'Status',
          width: 150
        },
        {
          value: 'actions',
          text: '',
          sortable: false,
          align: 'end',
          width: 150
        }
      ],
      // Datatable Footer
      footer: {
        'show-first-last-page': true,
        'items-per-page-options': [10, 20, 50, -1]
      }
    }
  },
  computed: {
    ...mapGetters({
      jobs: 'jobopenings/JOBS_LIST'
    })
  },
  watch: {

  },
  methods: {
    ...mapActions({
      _getJobs: 'jobopenings/GET',
      _deleteJob: 'jobopenings/DELETE'
    }),

    getJobs () {
      this.loading = true
      this._getJobs()
        .finally(() => {
          this.loading = false
        })
    },

    // Create Item
    openItemDialog () {
      this.jobPostingDialog = true
    },

    closeItemDialog (mustReload) {
      this.jobPostingDialog = false
      this.selectedItem = null

      if (mustReload) {
        this.getJobs()
      }
    },

    // View Item Details
    viewItem (item) {
      /* this.$router.push({
        name: 'main.inventory.view.overview',
        params: {
          item: item?.id
        },
        query: {
          ref: 'inventory'
        }
      }) */
    },

    // SHOW EDIT DIALOG FOR SELECTED CATEGORY
    editItem (item) {
      this.selectedItem = item?.jo_id
      this.jobPostingDialog = true
    },

    // DELETE
    deleteItem (item) {
      this.iJobId = item?.jo_id
      this.deleteDialog = true
    },

    closeDelete () {
      this.deleteDialog = false
      this.iJobId = null
    },
    confirmDelete () {
      this.deleting = true
      this._deleteJob({
        jo_id: this.iJobId
      })
        .then((response) => {
          if (response?.deleted) {
            this.getJobs()
            this.closeDelete()
          }
        })
        .finally(() => {
          this.deleting = false
        })
    }
  }
}
</script>

<style lang="scss">
  .title-skeleton-loader {
    .v-skeleton-loader__list-item-two-line {
      background: transparent !important;
    }
  }

  .inventory-table {
    .v-data-table__wrapper {
      table {
        tbody {
          tr {
            td {
              cursor: pointer !important;
            }
          }
        }
      }
    }
  }
</style>
