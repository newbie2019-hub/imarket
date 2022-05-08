<template>
  <div>
    <div class="mt-2">
      <v-form ref="form" lazy-validation v-model="valid" @submit.prevent="update">
        <v-layout column>
          <p class="font-2x font-weight-bold ml-1 mt-5 mb-0">Store Information</p>
          <p class="grey--text ml-1">Update your store information below</p>
        </v-layout>
        <v-row dense>
          <v-col lg="6" md="6" sm="6" cols="12">
            <v-text-field prepend-inner-icon="mdi-card-bulleted-outline" v-model="data.name" :rules="required" hide-details="auto" label="Store Name" required outlined></v-text-field>
          </v-col>
          <v-col lg="6" md="6" sm="6" cols="12">
            <v-file-input
              @change="uploadImage"
              accept="image/png, image/jpeg, image/bmp"
              outlined
              prepend-icon=""
              hide-details="auto"
              prepend-inner-icon="mdi-file-image"
              label="Store Banner"
            ></v-file-input>
          </v-col>
          <v-col lg="6" md="6" sm="6" cols="12">
            <v-textarea
              rows="2"
              auto-grow
              class=""
              prepend-inner-icon="mdi-key"
              type="text"
              v-model="data.description"
              hide-details="auto"
              :rules="required"
              label="Store Description"
              required
              outlined
            ></v-textarea>
          </v-col>
          <v-col lg="6" md="6" sm="6" cols="12">
            <v-textarea class="" rows="2" prepend-inner-icon="mdi-map-marker" v-model="data.address" :rules="required" auto-grow hide-details="auto" label="Address" required outlined></v-textarea>
          </v-col>

          <v-col lg="12">
            <p class="mb-2">Week Days Opening</p>
            <v-layout d-flex>
              <v-dialog ref="dialogWeekDaysMorning" v-model="modalWeekDaysMorning" :return-value.sync="data.week_days_opening" persistent width="400px">
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    class=""
                    outlined
                    v-model="data.week_days_opening"
                    label="Opening Hour"
                    prepend-inner-icon="mdi-clock-time-four-outline"
                    readonly
                    :rules="required"
                    hide-details="auto"
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-time-picker ampm-in-title scrollable format="ampm" v-if="modalWeekDaysMorning" v-model="data.week_days_opening" persistent full-width>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="modalWeekDaysMorning = false"> Cancel </v-btn>
                  <v-btn text color="primary" @click="$refs.dialogWeekDaysMorning.save(data.week_days_opening)"> OK </v-btn>
                </v-time-picker>
              </v-dialog>

              <v-dialog ref="dialogWeekDaysAfternoon" v-model="modalWeekDaysAfternoon" :return-value.sync="data.week_days_closing" persistent width="400px">
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    class=""
                    outlined
                    v-model="data.week_days_closing"
                    label="Closing Hour"
                    prepend-inner-icon="mdi-clock-time-four-outline"
                    readonly
                    :rules="required"
                    hide-details="auto"
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-time-picker ampm-in-title scrollable format="ampm" v-if="modalWeekDaysAfternoon" v-model="data.week_days_closing" full-width>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="modalWeekDaysAfternoon = false"> Cancel </v-btn>
                  <v-btn text color="primary" @click="$refs.dialogWeekDaysAfternoon.save(data.week_days_closing)"> OK </v-btn>
                </v-time-picker>
              </v-dialog>
            </v-layout>
          </v-col>
          <v-col lg="12">
            <p class="mb-2">Week Ends Opening</p>
            <v-layout d-flex>
              <v-dialog ref="dialogWeekEndsMorning" v-model="modalWeekEndMorning" :return-value.sync="data.week_end_opening" persistent width="400px">
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    class=""
                    outlined
                    v-model="data.week_end_opening"
                    label="Opening Hour"
                    prepend-inner-icon="mdi-clock-time-four-outline"
                    readonly
                    :rules="required"
                    hide-details="auto"
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-time-picker ampm-in-title scrollable format="ampm" v-if="modalWeekEndMorning" v-model="data.week_end_opening" persistent full-width>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="modalWeekEndMorning = false"> Cancel </v-btn>
                  <v-btn text color="primary" @click="$refs.dialogWeekEndsMorning.save(data.week_end_opening)"> OK </v-btn>
                </v-time-picker>
              </v-dialog>

              <v-dialog ref="dialogWeekendsAfternoon" v-model="modalWeekEndAfternoon" :return-value.sync="data.week_end_closing" persistent width="400px">
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    class=""
                    outlined
                    v-model="data.week_end_closing"
                    label="Closing Hour"
                    hide-details="auto"
                    prepend-inner-icon="mdi-clock-time-four-outline"
                    readonly
                    :rules="required"
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-time-picker ampm-in-title scrollable format="ampm" v-if="modalWeekEndAfternoon" v-model="data.week_end_closing" full-width>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="modalWeekEndAfternoon = false"> Cancel </v-btn>
                  <v-btn text color="primary" @click="$refs.dialogWeekendsAfternoon.save(data.week_end_closing)"> OK </v-btn>
                </v-time-picker>
              </v-dialog>
            </v-layout>
          </v-col>
          <p class="grey--text ml-1 mt-3 font-weight-light">Note: This will be displayed on your store but you can still set the status of your store manually</p>
        </v-row>
        <v-layout justify-end class="mt-5 mb-10">
          <v-btn type="submit" color="green darken-2" dark depressed :loading="btnLoading">Save Update</v-btn>
        </v-layout>
      </v-form>
    </div>
  </div>
</template>
<script>
  import API from '../../store/base/base';
  import { formatCurrency } from '@/assets/js/utilities';
  import { rules } from '@/assets/js/rules';
  import { mapState } from 'vuex';
  export default {
    mixins: [formatCurrency, rules],
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
      valid: true,
      modalWeekDaysMorning: false,
      modalWeekDaysAfternoon: false,
      modalWeekEndMorning: false,
      modalWeekEndAfternoon: false,
      btnLoading: false,
      isLoading: false,
      initialLoading: false,
    }),
    async mounted() {
      await this.$store.dispatch('market/getStoreInfo');
    },
    methods: {
      async uploadImage(event) {
        if (event) {
          let formData = new FormData();
          formData.append('image', event);
          await API.post(`products/uploadImage`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
            .then((response) => {
              this.data.image = response.data;
            })
            .catch((error) => {
              console.log({ error });
            });
        }
      },
      async update() {
        const valid = this.$refs.form.validate();
        if (valid) {
          this.btnLoading = true;
          const { status, data } = await this.$store.dispatch('market/updateStore', this.data);
          this.toastData(status, data);
          await this.$store.dispatch('products/getStoreInfo');
          this.btnLoading = false;
        }
      },
    },
    computed: {
      ...mapState('market', ['storeInfo']),
    },
    watch: {
      storeInfo() {
        this.data = JSON.parse(JSON.stringify(this.storeInfo));
      },
    },
  };
</script>
