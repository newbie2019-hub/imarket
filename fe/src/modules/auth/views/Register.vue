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
                      auto-grow
                      hide-details="auto"
                      label="Address"
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
  </div>
</template>
<script>
  import Navbar from '@/views/components/Navbar.vue';
  import { rules } from '@/assets/js/rules.js';
  import { birthdate } from '@/assets/js/utilities.js';
  export default {
    components: { Navbar },
    mixins: [rules, birthdate],
    data: () => ({
      valid: true,
      step: 1,
      menu: false,
      date: null,
      data: {
        email: '',
        password: '',
        first_name: '',
        last_name: '',
        contact_number: '',
        gender: '',
        birthday: '',
      },
     
    }),
    mounted() {
      this.maxDate();
    },
    methods: {
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
              this.$toast.success('Please check your email for confirmation!')
            }, 1200)
          }
          this.isLoading = false
        } else {
          this.$toast.error('Please make sure your inputs are correct.');
        }
      },
    },
  };
</script>
<style></style>
