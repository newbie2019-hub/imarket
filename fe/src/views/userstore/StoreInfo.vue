<template>
  <div>
    <div class="mt-2">
      <v-layout column>
        <div class="store-preview">
          <v-img :src="`http://127.0.0.1:8000/images/banners/${storeInfo.banner_image}`" class="store-preview--image"></v-img>
          <div class="upload-image-banner cursor-pointer v-btn">
            <label for="uploadimg" class="cursor-pointer">
              <v-icon color="white">mdi-camera</v-icon>
            </label>
            <input type="file" id="uploadimg" @change="uploadBannerImage" />
          </div>
          <div class="image-overlay"></div>
        </div>
        <v-layout class="store-preview--content">
          <div align-center justify-center class="position-relative">
            <v-avatar color="grey" size="140">
              <img v-if="storeInfo.logo != '' && storeInfo.logo != null" :src="'http://127.0.0.1:8000/images/logos/' + storeInfo.logo" alt="Store Logo" />
              <v-icon x-large color="primary" v-else> mdi-account-circle </v-icon>
            </v-avatar>
            <div class="upload-image-logo cursor-pointer v-btn">
              <label for="uploadimg" class="cursor-pointer">
                <v-icon small color="white">mdi-camera</v-icon>
              </label>
              <input type="file" id="uploadimg" @change="uploadBannerImage" />
            </div>
          </div>
          <v-layout column class="ml-5">
            <h1 class="font-rubik white--text">{{ storeInfo.name }}</h1>
            <p class="white--text">
              <v-icon color="white"> mdi-map-marker</v-icon>
              {{ storeInfo.address }}
            </p>
            <v-row dense>
              <v-col sm="5" md="4" lg="3">
                <p class="mb-0">Week Days</p>
                <p>
                  <small>
                    <v-icon small> mdi-clock </v-icon>
                    {{ storeInfo.week_days_opening | formatTime }}
                  </small>
                  -
                  <small>
                    <v-icon small> mdi-clock </v-icon>
                    {{ storeInfo.week_days_closing | formatTime }}
                  </small>
                </p>
              </v-col>
              <v-col sm="5" md="4" lg="3">
                <p class="mb-0">Week Ends</p>
                <p>
                  <small>
                    <v-icon small> mdi-clock </v-icon>
                    {{ storeInfo.week_end_opening | formatTime }}
                  </small>
                  -
                  <small>
                    <v-icon small> mdi-clock </v-icon>
                    {{ storeInfo.week_end_closing | formatTime }}
                  </small>
                </p>
              </v-col>
            </v-row>
          </v-layout>
        </v-layout>
      </v-layout>
      <v-layout column>
        <p class="font-2x font-weight-bold ml-1 mt-5 mb-0">Store Information</p>
        <p class="grey--text ml-1">Update your store information below</p>
      </v-layout>
      <v-form ref="form" lazy-validation v-model="valid" @submit.prevent="update">
        <v-row dense>
          <v-col lg="6" md="6" sm="6" cols="12">
            <v-text-field prepend-inner-icon="mdi-card-bulleted-outline" v-model="data.name" :rules="required" hide-details="auto" label="Store Name" required outlined></v-text-field>
          </v-col>
          <v-col lg="6" md="6" sm="6" cols="12">
            <v-textarea class="" rows="2" prepend-inner-icon="mdi-map-marker" v-model="data.address" :rules="required" auto-grow hide-details="auto" label="Address" required outlined></v-textarea>
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
          <v-btn type="submit" color="green darken-2" dark depressed :loading="btnLoading">Save Changes</v-btn>
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
  import moment from 'moment';
  export default {
    filters: {
      formatTime(val) {
        if (val) {
          return moment(val, ['hh:mm']).format('hh:mm A');
        }
      },
    },
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
      async uploadBannerImage(event) {
        let formData = new FormData();
        formData.append('banner', event.target.files[0]);
        await API.post(`store/update-banner`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })
          .then((response) => {
            this.toastData(200, { msg: 'Banner image updated successfully!' });
          })
          .catch((error) => {
            console.log({ error });
          });
        await this.$store.dispatch('market/getStoreInfo');
      },
      async uploadImage(event) {
        if (event) {
          let formData = new FormData();
          formData.append('banner', event);
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
          await this.$store.dispatch('market/getStoreInfo');
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
<style>
  
</style>
