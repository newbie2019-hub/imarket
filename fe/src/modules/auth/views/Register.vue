<template>
  <div>
    <Navbar />
    <v-layout class="mt-15"></v-layout>
    <v-container class="mt-15">
      <v-row justify="center" class="h-100">
        <v-col cols="12" sm="11" md="6" lg="6">
          <h1 v-animate-css="'fadeInUp'" class="heading lh-normal circular">Fastest <span class="blue--text">Order</span> & <span class="blue--text">Delivery</span> At Your Doorstep</h1>
          <h2 v-animate-css="{ classes: 'fadeInUp', delay: 450 }" class="font-weight-light mt-2">Welcome User! We need some of your information to complete your registration</h2>
          <p v-animate-css="{ classes: 'fadeInUp', delay: 850 }" class="mt-5">Already have an account? <router-link to="/login" class="mt-3 text-decoration-wavy">Login</router-link></p>
        </v-col>
        <v-col cols="12" sm="11" md="6" lg="6">
          <v-stepper alt-labels v-model="step" elevation="0" non-linear>
            <v-stepper-header class="elevation-0 pa-0">
              <v-stepper-step editable :complete="step > 1" step="1"> Personal Info </v-stepper-step>
              <v-divider></v-divider>
              <v-stepper-step editable :complete="step > 2" step="2"> Continuation </v-stepper-step>
              <v-divider></v-divider>
              <v-stepper-step editable :complete="step > 3" step="3"> Confirm </v-stepper-step>
            </v-stepper-header>

            <v-stepper-items>
              <v-form ref="form" lazy-validation v-model="valid" @submit.prevent="register">
                <v-stepper-content step="1">
                  <div class="mt-2">
                    <v-text-field v-model="data.first_name" prepend-inner-icon="mdi-card-bulleted" hide-details="auto" :rules="required" label="First Name" required outlined></v-text-field>
                    <v-text-field v-model="data.last_name" prepend-inner-icon="mdi-card-bulleted" class="mt-3" hide-details="auto" :rules="required" label="Last Name" required outlined></v-text-field>
                    <v-text-field
                      prepend-inner-icon="mdi-phone-in-talk"
                      class="mt-3"
                      outlined
                      hide-details="auto"
                      v-model="data.contact_number"
                      :rules="minChar"
                      label="Contact Number"
                      type="text"
                      required
                    ></v-text-field>
                    <v-select prepend-inner-icon="mdi-card-bulleted" :items="gender" class="mt-4" v-model="data.gender" :rules="required" outlined label="Gender" hide-details="auto"></v-select>
                  </div>
                  <v-btn class="mt-5" color="primary" @click="step = 2"> Continue </v-btn>
                </v-stepper-content>

                <v-stepper-content step="2">
                  <div class="mt-2">
                    <v-text-field prepend-inner-icon="mdi-email" v-model="data.email" :rules="emailRule" hide-details="auto" label="Email" required outlined></v-text-field>
                    <v-text-field
                      class="mt-3"
                      prepend-inner-icon="mdi-key"
                      type="password"
                      v-model="data.password"
                      hide-details="auto"
                      :rules="required"
                      label="Password"
                      required
                      outlined
                    ></v-text-field>
                    <v-textarea
                      class="mt-3"
                      rows="2"
                      prepend-inner-icon="mdi-map-marker"
                      v-model="data.address"
                      :rules="required"
                      @click.prevent="addressDialog = true"
                      auto-grow
                      hide-details="auto"
                      label="Address"
                      readonly
                      required
                      outlined
                    ></v-textarea>
                    <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="data.birthday" transition="scale-transition" offset-y min-width="auto">
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          class="mt-4"
                          hide-details="auto"
                          outlined
                          v-model="data.birthday"
                          :rules="required"
                          label="Date of Birth"
                          prepend-inner-icon="mdi-calendar"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker v-model="date" min="1970-01-01" :max="maxBirthDate" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="menu = false"> Cancel </v-btn>
                        <v-btn text color="primary" @click="$refs.menu.save(date)"> OK </v-btn>
                      </v-date-picker>
                    </v-menu>
                  </div>
                  <p class="grey--text mt-3">Note: An email confirmation will be sent upon creating your account. Please also make sure your address is correct.</p>
                  <v-btn class="mt-4" color="primary" @click="step = 3"> Continue </v-btn>
                </v-stepper-content>

                <v-stepper-content step="3">
                  <p class="text-center">
                    Your account informations will be secured by our servers and no information will be given to anyone. <br /><br />Note: By creating your account you are accepting our
                    <a type="button" href="" class="text-decoration-none">terms and conditions. </a>
                  </p>
                  <v-btn type="submit" color="primary" block :loading="isLoading"> Create Account </v-btn>
                </v-stepper-content>
              </v-form>
            </v-stepper-items>
          </v-stepper>
        </v-col>
      </v-row>
    </v-container>

    <v-dialog v-model="addressDialog" max-width="620">
      <v-card class="pl-4 pr-4">
        <v-card-title class="text-h5 pb-0 pt-5"> Select Address </v-card-title>
        <h4 class="font-weight-light ml-6 mr-5 mb-2">What's your exact location?</h4>
        <label class="">
          <gmap-autocomplete
            ref="gmapAutoComplete"
            class="gmap-auto-complete"
            @place_changed="initMarker"
            :options="{ fields: ['geometry', 'formatted_address', 'address_components'] }"
          ></gmap-autocomplete>
        </label>
        <v-container>
          <v-row class="ml-4 mr-4 mt-1 mb-1 border-orange position-relative" style="height: 400px">
            <gmap-map @click="addLocationMarker" class="rounded-xl" :zoom="20" :options="{ mapTypeControl: false, streetViewControl: false }" :center="center" style="width: 100%; height: 100%">
              <gmap-marker :position="center"></gmap-marker>
            </gmap-map>
            <v-btn class="btn-setAddress" color="primary darken-2" large @click="addressDialog = false"> SET ADDRESS </v-btn>
          </v-row>
        </v-container>
        <v-card-actions class="pb-5">
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import Navbar from '@/views/components/Navbar.vue';
  import { rules } from '@/assets/js/rules.js';
  import { birthdate } from '@/assets/js/utilities.js';
  import { gmapApi } from 'vue2-google-maps';

  export default {
    components: { Navbar },
    mixins: [rules, birthdate],
    data: () => ({
      valid: true,
      step: 1,
      menu: false,
      date: null,
      addressDialog: false,
      btnLoading: false,
      data: {
        email: '',
        password: '',
        first_name: '',
        last_name: '',
        contact_number: '',
        gender: '',
        birthday: '',
        address: '',
        lat: '',
        lng: '',
        street_number: '',
        route: '',
        locality: '',
        country: '',
        adminstrative_area_level_2: '',
        adminstrative_area_level_1: '',
      },
      center: {
        lat: 12.8797,
        lng: 121.774,
      },
      locPlaces: [],
      existingPlace: null,
    }),
    mounted() {
      this.maxDate();
      this.locateGeoLocation();
    },
    computed: {
      google: gmapApi,
    },
    methods: {
      codeAddress() {
        const geocoder = new this.google.maps.Geocoder();
        var address = this.center;
        const formatted_address = null;
        // console.log(geocoder)
        geocoder.geocode({ 'location': address }, (results, status) => {
          // console.log(status)
          if (status == 'OK') {
            console.log(results[0]);
            this.data.address = results[0].formatted_address;
            this.$refs.gmapAutoComplete.$el.value = results[0].formatted_address;
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      },
      initMarker(loc) {
        const marker = {
          lat: loc.geometry.location.lat(),
          lng: loc.geometry.location.lng(),
        };
        // console.log(loc)
        // console.log(this.codeAddress());
        if (loc.address_components.length > 5) {
          this.data.street_number = loc.address_components[0].long_name;
          this.data.route = loc.address_components[1].long_name;
          this.data.locality = loc.address_components[2].long_name;
          this.data.adminstrative_area_level_2 = loc.address_components[3].long_name;
          this.data.adminstrative_area_level_1 = loc.address_components[4].long_name;
          this.data.country = loc.address_components[5].long_name;
          this.data.lng = marker.lng;
          this.data.lat = marker.lat;
          this.data.address = loc.formatted_address;
        } else {
          this.data.route = loc.address_components[0].long_name;
          this.data.locality = loc.address_components[1].long_name;
          this.data.adminstrative_area_level_2 = loc.address_components[2].long_name;
          this.data.adminstrative_area_level_1 = loc.address_components[3].long_name;
          this.data.country = loc.address_components[4].long_name;
          this.data.lng = marker.lng;
          this.data.lat = marker.lat;
          this.data.address = loc.formatted_address;
        }
        this.center = marker;
      },
      addLocationMarker(e) {
        const marker = {
          lat: e.latLng.lat(),
          lng: e.latLng.lng(),
        };

        this.codeAddress();
        this.center = marker;
      },
      locateGeoLocation: function () {
        navigator.geolocation.getCurrentPosition((res) => {
          this.center = {
            lat: res.coords.latitude,
            lng: res.coords.longitude,
          };
        });
      },
      async setAddress() {},
      async register() {
        const valid = this.$refs.form.validate();

        if (valid) {
          this.isLoading = true;
          const { data, status } = await this.$store.dispatch('auth/register', this.data);
          this.toastData(status, data);
          if (status == 200) {
            this.$refs.form.reset();
            this.step = 1;
            setTimeout(() => {
              this.$toast.success('Please check your email for confirmation!');
            }, 1200);
          }
          this.isLoading = false;
        } else {
          this.$toast.error('Please make sure your inputs are correct.');
        }
      },
    },
  };
</script>
<style>
 
</style>
