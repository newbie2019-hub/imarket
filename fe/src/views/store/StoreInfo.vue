<template>
  <div>
    <div class="mt-2">
      <v-text-field prepend-inner-icon="mdi-card-bulleted-outline" v-model="data.store_name" :rules="required" hide-details="auto" label="Store Name" required outlined></v-text-field>
      <v-text-field
        class="mt-3"
        prepend-inner-icon="mdi-key"
        type="text"
        v-model="data.store_description"
        hide-details="auto"
        :rules="required"
        label="Store Description"
        required
        outlined
      ></v-text-field>
      <v-textarea class="mt-3" rows="2" prepend-inner-icon="mdi-map-marker" v-model="data.store_address" :rules="required" auto-grow hide-details="auto" label="Address" required outlined></v-textarea>

      <p class="mb-0 mt-5">Week Days Opening</p>
      <v-layout d-flex>
        <v-dialog ref="dialogWeekDaysMorning" v-model="modalWeekDaysMorning" :return-value.sync="data.week_days_time_am" persistent width="400px">
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              class="mt-1"
              outlined
              v-model="data.week_days_time_am"
              label="Opening Hour"
              prepend-inner-icon="mdi-clock-time-four-outline"
              readonly
              :rules="required"
              hide-details="auto"
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>
          <v-time-picker ampm-in-title scrollable format="ampm" v-if="modalWeekDaysMorning" v-model="data.week_days_time_am" persistent full-width>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="modalWeekDaysMorning = false"> Cancel </v-btn>
            <v-btn text color="primary" @click="$refs.dialogWeekDaysMorning.save(data.week_days_time_am)"> OK </v-btn>
          </v-time-picker>
        </v-dialog>

        <v-dialog ref="dialogWeekDaysAfternoon" v-model="modalWeekDaysAfternoon" :return-value.sync="data.week_days_time_pm" persistent width="400px">
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              class="mt-1"
              outlined
              v-model="data.week_days_time_pm"
              label="Closing Hour"
              prepend-inner-icon="mdi-clock-time-four-outline"
              readonly
              :rules="required"
              hide-details="auto"
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>
          <v-time-picker ampm-in-title scrollable format="ampm" v-if="modalWeekDaysAfternoon" v-model="data.week_days_time_pm" full-width>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="modalWeekDaysAfternoon = false"> Cancel </v-btn>
            <v-btn text color="primary" @click="$refs.dialogWeekDaysAfternoon.save(data.week_days_time_pm)"> OK </v-btn>
          </v-time-picker>
        </v-dialog>
      </v-layout>

      <p class="mb-0 mt-2">Week Ends Opening</p>
      <v-layout d-flex>
        <v-dialog ref="dialogWeekEndsMorning" v-model="modalWeekEndMorning" :return-value.sync="data.week_ends_time_am" persistent width="400px">
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              class="mt-1"
              outlined
              v-model="data.week_ends_time_am"
              label="Opening Hour"
              prepend-inner-icon="mdi-clock-time-four-outline"
              readonly
              :rules="required"
              hide-details="auto"
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>
          <v-time-picker ampm-in-title scrollable format="ampm" v-if="modalWeekEndMorning" v-model="data.week_ends_time_am" persistent full-width>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="modalWeekEndMorning = false"> Cancel </v-btn>
            <v-btn text color="primary" @click="$refs.dialogWeekEndsMorning.save(data.week_ends_time_am)"> OK </v-btn>
          </v-time-picker>
        </v-dialog>

        <v-dialog ref="dialogWeekendsAfternoon" v-model="modalWeekEndAfternoon" :return-value.sync="data.week_ends_time_pm" persistent width="400px">
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              class="mt-1"
              outlined
              v-model="data.week_ends_time_pm"
              label="Closing Hour"
              hide-details="auto"
              prepend-inner-icon="mdi-clock-time-four-outline"
              readonly
              :rules="required"
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>
          <v-time-picker ampm-in-title scrollable format="ampm" v-if="modalWeekEndAfternoon" v-model="data.week_ends_time_pm" full-width>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="modalWeekEndAfternoon = false"> Cancel </v-btn>
            <v-btn text color="primary" @click="$refs.dialogWeekendsAfternoon.save(data.week_ends_time_pm)"> OK </v-btn>
          </v-time-picker>
        </v-dialog>
      </v-layout>
    </div>
    <p class="grey--text mt-3 font-weight-light">Note: This will be displayed on your store but you can still set the status of your store manually</p>
  </div>
</template>
<script>
  export default {
    data: () => ({
      data: {
        store_name: '',
        store_description: '',
        store_address: '',
        birthday: '',
        week_days_time_am: '',
        week_days_time_pm: '',
        week_ends_time_am: '',
        week_ends_time_pm: '',
      },
      modalWeekDaysMorning: false,
      modalWeekDaysAfternoon: false,
      modalWeekEndMorning: false,
      modalWeekEndAfternoon: false,
    }),
  };
</script>
