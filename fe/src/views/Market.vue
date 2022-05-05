<template>
  <div>
    <Navbar />
    <v-container class="">
      <v-layout column class="mb-8 lh-small">
        <h1 v-if="user.info" class="mb-2">Hi, {{ user.info && user.info.first_name }} {{ user.info && user.info.last_name }}!</h1>
        <h1 v-else class="mb-2">Hi, User!</h1>
        <h2 class="grey--text font-weight-light">I-Market is here to serve you.</h2>
      </v-layout>
      <v-row justify="center" align="center">
        <v-col cols="12" sm="12" md="12" lg="12">
          <v-layout>
            <v-text-field
              prepend-inner-icon="mdi-magnify"
              v-model="search"
              class="rounded-lg"
              label="What would you like to eat?"
              required
              filled
              rounded
              clearable
              color="orange darken-2"
            ></v-text-field>
          </v-layout>
          <h2 class="font-weight-bold mb-3 mt-10">Latest Added</h2>
          <v-layout v-if="isLoading" wrap>
            <v-skeleton-loader v-for="n in 6" :key="n" class="mx-2" min-width="270" max-width="270" type="card"></v-skeleton-loader>
          </v-layout>
          <v-row no-gutters dense v-else class="">
            <v-col sm="4" md="3" lg="3" v-for="(product, i) in latest_products" :key="i">
              <v-hover :key="i" v-slot="{ hover }" class="cursor-hover">
                <v-card max-width="272" class="mx-2 mb-3" :elevation="hover ? 2 : 0" :outlined="hover ? true : false">
                  <v-img :src="`http://127.0.0.1:8000/images/products/${product.product_info.image}`" max-width="272" contain></v-img>
                  <v-card-text class="position-relative pt-2">
                    <v-btn
                      @click.prevent="addToCart(product)"
                      :loading="isCartLoading && productId == product.id"
                      absolute
                      :color="isAddedSuccess && productId == product.id ? 'green' : 'orange darken-2'"
                      class="white--text"
                      fab
                      small
                      right
                      top
                      depressed
                    >
                      <v-icon small>{{ isAddedSuccess && productId == product.id ? 'mdi-check' : 'mdi-cart-plus' }}</v-icon>
                    </v-btn>
                    <v-card-title class="pt-1 pl-1 black--text lh-small text-capitalize">{{ product.product_info.name }}</v-card-title>
                    <v-card-subtitle class="pt-0 mt-n5 pl-1"> {{ product.user.info.last_name }}'s Store </v-card-subtitle>
                    <h3 class="mt-n3 pl-1 orange--text darken-2 font-weight-regular">₱ {{ formatCurrency(product.product_info.price) }}</h3>
                  </v-card-text>
                </v-card>
              </v-hover>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" sm="12" md="3" lg="3"> <v-card> </v-card> </v-col>
      </v-row>
    </v-container>

    <div class="position-relative">
      <v-container class="pb-5 pt-15">
        <v-row justify="center" align="center" class="h-100">
          <v-col cols="12" sm="11" md="5" lg="4">
            <v-layout justify-center align-center>
              <v-img lazy-src="@/assets/images/mobileapp.png" lazy-loading src="@/assets/images/mobileapp.png" max-width="400" class=""></v-img>
            </v-layout>
          </v-col>
          <v-col cols="12" sm="11" md="7" lg="6">
            <h1 class="font-weight-bold">Get the I-Market Application</h1>
            <h3 class="font-weight-light darken-3 mt-3">
              It's all at your fingertips – the fresh fruits, vegetables and meat. Find the right food in the market and we will deliver it to your doorstep. Go ahead, download us.
            </h3>
            <div class="mt-4">
              <v-btn depressed class="mt-3 me-2" text large>
                <v-icon class="mr-2"> mdi-download </v-icon>
                Android
              </v-btn>
              <v-btn depressed class="mt-3" text large>
                <v-icon class="mr-2"> mdi-download </v-icon>
                iOS
              </v-btn>
            </div>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" sm="12" md="12" lg="12">
            <h1 class="font-weight-bold mb-5 mt-15">Market Products</h1>
            <v-layout class="mt-4 pr-2 mb-5" column>
              <h2 class="font-weight-regular mb-6">Categories</h2>
              <v-slide-group show-arrows="">
                <v-slide-item v-for="(category, i) in categories" :key="i" v-slot="{ active, toggle }">
                  <v-btn class="mx-2" :input-value="active" active-class="orange darken-2 white--text" depressed rounded @click="toggle"> {{ category.category }} </v-btn>
                </v-slide-item>
              </v-slide-group>
            </v-layout>
            <v-layout v-if="isLoading" wrap>
              <v-skeleton-loader v-for="n in 9" :key="n" class="mx-2" min-width="260" max-width="260" type="card"></v-skeleton-loader>
            </v-layout>
            <v-row no-gutters dense v-else class="">
              <v-col sm="4" md="3" lg="3" v-for="(product, i) in products.data" :key="i">
                <v-hover :key="i" v-slot="{ hover }" class="cursor-hover">
                  <v-card max-width="272" class="mx-2 mb-3" :elevation="hover ? 2 : 0" :outlined="hover ? true : false">
                    <v-img :src="`http://127.0.0.1:8000/images/products/${product.product_info.image}`" max-width="272" contain></v-img>
                    <v-card-text class="position-relative pt-2">
                      <v-btn @click.prevent="addToCart(product)" :loading="isCartLoading && productId == product.id" absolute color="orange darken-2" class="white--text" fab small right top depressed>
                        <v-icon small>mdi-cart-plus</v-icon>
                      </v-btn>
                      <v-card-title class="pt-1 pl-1 black--text lh-small text-capitalize">{{ product.product_info.name }}</v-card-title>
                      <v-card-subtitle class="pt-0 mt-n5 pl-1"> {{ product.user.info.last_name }}'s Store </v-card-subtitle>
                      <h3 class="mt-n3 pl-1 orange--text darken-2 font-weight-regular">₱ {{ formatCurrency(product.product_info.price) }}</h3>
                    </v-card-text>
                  </v-card>
                </v-hover>
              </v-col>
            </v-row>
            <v-layout justify-center class="mt-5">
              <v-btn v-if="products.last_page != page" @click.prevent="getProducts" large depressed color="blue darken-2" dark :loading="isLoading">LOAD MORE</v-btn>
            </v-layout>
          </v-col>
        </v-row>
      </v-container>
    </div>

    <!--- INSERT RATE PRODUCT -->
    <div class="orange-light position-relative mt-20">
      <v-container class="pt-10">
        <v-row justify="center" align="center" class="flex-md-row-reverse flex-lg-row h-100">
          <v-col cols="12" sm="11" md="12" lg="12" class="mt-n20">
            <v-card color="orange darken-2 pa-12" dark class="rounded-xl">
              <v-row class="position-relative">
                <v-col sm="7" md="8" lg="7">
                  <h1 class="font-weight-bolder">Want to be a partner?</h1>
                  <h3 class="mt-2 font-weight-light">
                    Would you like to have new customers enjoying your fresh products? So would we. Interested? <br /><br />
                    Let’s start our parternship today!
                  </h3>
                  <v-btn color="blue darken-1" depressed class="rounded-pill btn-glow-blue mt-3" large> Get Started </v-btn>
                </v-col>
                <div class="img-right">
                  <v-img src="@/assets/images/agreement.svg" class="img-width" contain></v-img>
                </div>
              </v-row>
            </v-card>
          </v-col>
        </v-row>
        <v-row align="center" justify="center" class="mt-15">
          <v-col cols="12" sm="11" lg="6">
            <h1 class="ml-5">Got some questions and suggestions?</h1>
            <h3 class="ml-5 mt-5 font-weight-light">Visit our social media accounts or send us an email at imarket@gmail.com</h3>
          </v-col>
          <v-col></v-col>
        </v-row>
        <v-layout justify-space-between class="ml-5 mr-5 mt-15">
          <h3 class="font-weight-bold">IMarket</h3>
          <h3 class="font-weight-light">© 2022 | All Rights Reserved</h3>
        </v-layout>
      </v-container>
    </div>
  </div>
</template>
<script>
  import Navbar from './components/Navbar.vue';
  import { mapState } from 'vuex';
  import { formatCurrency } from '@/assets/js/utilities';
  export default {
    components: { Navbar },
    mixins: [formatCurrency],
    data: () => ({
      search: '',
      data: {
        search: '',
      },
      isLoading: false,
      page: 1,
      productId: null,
      isCartLoading: false,
      isAddedSuccess: false,
    }),
    async mounted() {
      this.isLoading = true;
      await this.$store.dispatch('market/getCategories');
      await this.$store.dispatch('market/getLatestProducts');
      await this.$store.dispatch('market/getProducts', 1);
      this.isLoading = false;
    },
    methods: {
      async getProducts() {
        this.isLoading = true;
        const page = this.page + 1;
        console.log(page);
        await this.$store.dispatch('market/getProducts', page);
        this.page += 1;
        this.isLoading = false;
      },
      async addToCart(product) {
        // this.isCartLoading = true;
        if (this.user.info) {
          this.isCartLoading = true;
          this.productId = product.id;
          await this.$store.dispatch('market/addToCart', product);
          await this.$store.dispatch('market/cartCount');
          await this.$store.dispatch('market/getCartItems');
          this.isCartLoading = false;
          this.isAddedSuccess = true;

          setTimeout(() => {
            this.productId = null;
            this.isAddedSuccess = false;
          }, 350);
        } else {
          this.$toast('Please login your account first!');
          this.$router.push('/login');
        }
      },
    },
    computed: {
      ...mapState('market', ['categories', 'products', 'latest_products']),
      ...mapState('auth', ['user']),
    },
    watch: {},
  };
</script>
