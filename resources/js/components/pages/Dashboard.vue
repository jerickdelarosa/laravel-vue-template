<template>
  <div>
    <v-container>
      <v-sheet height="64">
        <v-toolbar
          flat
        >
          <v-toolbar-title v-if="$refs.calendar">
            {{ $refs.calendar.title }}
          </v-toolbar-title>
        </v-toolbar>
      </v-sheet>
      <v-sheet
        class="mx-5"
        height="500"
      >
        <v-calendar
          ref="calendar"
          v-model="focus"
          color="primary"
          :events="events"
          @change="fetchEvents"
        />
      </v-sheet>
    </v-container>
  </div>
</template>
<script>
export default {
  data: () => ({
    focus: '',
    events: []
  }),
  mounted () {
    this.$refs.calendar.checkChange()
  },
  methods: {
    getEventColor (event) {
      return event.color
    },
    setToday () {
      this.focus = ''
    },
    fetchEvents () {
      const events = []
      this.events = events
    }
  }
}
</script>
