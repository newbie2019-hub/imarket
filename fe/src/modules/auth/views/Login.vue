<template>
  <div>
    <Navbar />
    <v-layout class="mt-15"></v-layout>
    <v-container class="mt-15">
      <v-row justify="center" class="h-100">
        <v-col cols="12" sm="11" md="6" lg="6">
          <h1 v-animate-css="'fadeInUp'" class="heading lh-normal circular">Fastest <span class="blue--text">Order</span> & <span class="blue--text">Delivery</span> At Your Doorstep</h1>
          <h2 v-animate-css="{ classes: 'fadeInUp', delay: 450 }" class="font-weight-light">Incredibly fast and convenient delivery at the palm of your hand.</h2>

          <!-- <v-layout d-flex align-center>
            <v-btn v-animate-css="{ classes: 'fadeInUp', delay: 650 }" class="mt-4 rounded-pill btn-glow mr-8" color="orange darken-2" depressed large dark>Order Now</v-btn>
          </v-layout> -->
          <p v-animate-css="{ classes: 'fadeInUp', delay: 850 }" class="mt-5">
            Dont have an account? <router-link href="#" link to="/register" text class="mt-3 text-decoration-wavy">Sign-up</router-link>
          </p>
        </v-col>
        <v-col cols="12" sm="11" md="6" lg="5">
          <v-card v-animate-css="{ classes: 'fadeInUp', delay: 50 }" class="pl-8 pr-8 pt-5 pb-5" :elevation="$vuetify.breakpoint.xs || $vuetify.breakpoint.sm ? 0 : 1">
            <h1>Login</h1>
            <p>Welcome! Please enter your credentials to proceed to your account</p>
            <v-form ref="form" lazy-validation v-model="valid" @submit.prevent="login" class="mt-6">
              <v-text-field prepend-inner-icon="mdi-email" v-model="data.email" :rules="emailRule" label="Email Address" required outlined></v-text-field>
              <v-text-field prepend-inner-icon="mdi-key" type="password" v-model="data.password" :rules="required" label="Password" required outlined></v-text-field>
              <v-divider class="mb-8 mt-2"></v-divider>
              <v-btn type="submit" class="mt-5 mb-5" depressed color="orange darken-2" dark large block :loading="isLoading">Login Account </v-btn>
            </v-form>
          </v-card>
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
      data: {
        email: '',
        password: '',
      },
    }),
    mounted() {
    },
    methods: {
      async login() {
        const valid = this.$refs.form.validate();
        if (valid) {
          this.isLoading = true;
          const { data, status } = await this.$store.dispatch('auth/login', this.data);
          if (status == 200) {
            this.$refs.form.reset();
            this.$router.push('/imarket')
            this.$toast.success(`Welcome back, ${data.user.info.last_name}!`)
          }
          else {
            this.$toast.error('Email or Password is incorrect!')
          }
          this.isLoading = false;
        } else {
          this.$toast.error('Please check your inputs!');
        }
      },
    },
  };
</script>
<style></style>
