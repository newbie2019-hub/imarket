<template>
  <div>
    <v-dialog v-model="dialog" max-width="640">
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
            <gmap-map
              ref="mapRef"
              @click="addLocationMarker"
              class="rounded-xl"
              :zoom="9"
              :options="{ mapTypeControl: false, streetViewControl: false }"
              :center="center"
              style="width: 100%; height: 100%"
            >
              <gmap-marker :position="center"></gmap-marker>
            </gmap-map>
            <v-btn class="btn-setAddress" depressed color="primary darken-2" large @click="setUserAddress"> SET ADDRESS </v-btn>
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
  import { mapState } from 'vuex';
  import { gmapApi } from 'vue2-google-maps';
  export default {
    data: () => ({
      data: { lat: '', lng: '', street_number: '', route: '', locality: '', country: '', adminstrative_area_level_2: '', adminstrative_area_level_1: '', address: '' },
      center: {
        lat: 12.8797,
        lng: 121.774,
      },
      settingAddress: false,
    }),
    props: {
      showModal: Boolean,
    },
    async mounted() {
      await this.locateGeoLocation()
      this.showAddressPicker()
      setTimeout(async () => {
        await this.calculateDistanceMatrix()
      },250)
    },
    methods: {
      close() {
        this.$emit('close');
      },
      showAddressPicker() {
        if (this.user.address.lat != null && this.user.address.lat != '') {
          this.center.lat = this.user.address.lat && parseFloat(this.user.address.lat);
          this.center.lng = this.user.address.lng && parseFloat(this.user.address.lng);
        }
        // this.$refs.gmapAutoComplete.$el.value = this.data.address && this.data.address;
      },
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
      async setUserAddress() {
        this.settingAddress = true;
        const { status, data } = await this.$store.dispatch('auth/updateAddress', this.data);
        await this.$store.dispatch('auth/checkUser');
        this.toastData(status, data);
        this.settingAddress = false;
        this.calculateDistanceMatrix()
        this.close();
      },
      async calculateDistanceMatrix() {
        if (this.user.address.lat == null) {
          return this.$toast.warning('Please set your address');
        }
        const service = new this.google.maps.DistanceMatrixService();

        const marketLocation = {
          lat: 11.15972681865006,
          lng: 124.98905099855318,
        };
        const userAddress = {
          lat: parseFloat(this.user.address.lat),
          lng: parseFloat(this.user.address.lng),
        };
        const request = {
          origins: [userAddress, marketLocation],
          destinations: [this.user.address.formatted_address, 'F. Montejo St., Palo, Leyte'],
          travelMode: this.google.maps.TravelMode.DRIVING,
          unitSystem: this.google.maps.UnitSystem.METRIC,
          avoidHighways: false,
          avoidTolls: false,
        };

        service.getDistanceMatrix(request).then((response) => {
          // console.log(response);
          this.$store.commit('auth/SET_MATRIX', response);
        });
      },
    },
    components: {},
    computed: {
      google: gmapApi,
      ...mapState('auth', ['user']),
      dialog: {
        get() {
          return this.showModal;
        },
        set(value) {
          if (!value) {
            this.$emit('close');
          }
        },
      },
    },
    watch: {
      async google() {
        await this.calculateDistanceMatrix();
      },
    },
  };
</script>
