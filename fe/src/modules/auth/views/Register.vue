<template>
  <div>
    <Navbar />
    <v-container class="mt-5">
      <v-row justify="center" align="center" class="flex-md-column-reverse flex-lg-row h-100">
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
              <v-stepper-step editable :complete="step > 2" step="2"> Address & Login </v-stepper-step>
              <v-divider></v-divider>
              <v-stepper-step editable :complete="step > 3" step="3"> Confirm </v-stepper-step>
            </v-stepper-header>

            <v-stepper-items>
              <v-stepper-content step="1">
                <div class="mt-2">
                  <v-text-field prepend-inner-icon="mdi-card-bulleted" hide-details="auto" v-model="data.first_name" :rules="required" label="First Name" required outlined></v-text-field>
                  <v-text-field prepend-inner-icon="mdi-card-bulleted" class="mt-3" hide-details="auto" v-model="data.last_name" :rules="required" label="Last Name" required outlined></v-text-field>
                  <v-text-field
                    prepend-inner-icon="mdi-phone-in-talk"
                    class="mt-3"
                    outlined
                    hide-details="auto"
                    v-model="data.contact_number"
                    :rules="required"
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
                  <v-text-field class="mt-3" prepend-inner-icon="mdi-key" type="password" v-model="data.password" hide-details="auto" :rules="required" label="Password" required outlined></v-text-field>
                  <v-textarea class="mt-3" rows="2" prepend-inner-icon="mdi-map-marker" v-model="data.address" :rules="required" auto-grow hide-details="auto" label="Address" required outlined></v-textarea>
                </div>
                <p class="grey--text mt-3">Note: An email confirmation will be sent upon creating your account. Please also make sure your address is correct.</p>
                <v-btn class="mt-4" color="primary" @click="step = 3"> Continue </v-btn>
              </v-stepper-content>

              <v-stepper-content step="3">
                <p class="text-center">
                  Your account informations will be secured by our servers and no information will be given to anyone. <br /><br />Note: By creating your account you are accepting our
                  <a type="button" href="" class="text-decoration-none">terms and conditions. </a>
                </p>
                <v-btn type="submit" color="primary" block> Create Account </v-btn>
              </v-stepper-content>
            </v-stepper-items>
          </v-stepper>
          <!-- <v-form lazy-validation v-model="valid" class="mt-6">
              <v-text-field v-model="data.first_name" :rules="required" label="First Name" required outlined></v-text-field>
              <v-text-field v-model="data.last_name" :rules="required" label="Last Name" required outlined></v-text-field>
              <v-text-field v-model="data.email" :rules="emailRule" label="Email" required outlined></v-text-field>
              <v-text-field type="password" v-model="data.password" :rules="required" label="Password" required outlined></v-text-field>
              <v-divider class="mb-8 mt-2"></v-divider>
              <v-btn class="mt-5 mb-5" depressed color="orange darken-2" dark large block>Register Account </v-btn>
            </v-form> -->
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
  import Navbar from '@/views/components/Navbar.vue';
  import { rules } from '@/assets/js/rules.js';
  export default {
    components: { Navbar },
    mixins: [rules],
    data: () => ({
      valid: true,
      step: 1,
      data: {
        email: '',
        password: '',
        first_name: '',
        last_name: '',
        contact_number: '',
        gender: '',
      },
    }),
    mounted() {
      console.log(this.$data);
    },
  };
</script>
<style></style>
