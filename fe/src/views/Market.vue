<template>
  <div>
    <Navbar />
    <v-layout class="mt-15"></v-layout>
    <v-container class="mt-15">
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
          <v-layout class="mt-10" justify-space-between align-center>
            <div>
              <h2 class="font-weight-bold mb-0">Latest Added</h2>
              <p class="mb-8 grey--text">Here are the latest added products from our sellers</p>
            </div>
            <v-btn icon @click="sortDesc = !sortDesc">
              <v-icon>{{ sortDesc ? 'mdi-sort-numeric-descending' : 'mdi-sort-numeric-ascending' }}</v-icon>
            </v-btn>
          </v-layout>
          <v-layout v-if="isLoading" wrap>
            <v-skeleton-loader v-for="n in 6" :key="n" class="mx-2" min-width="270" max-width="270" type="card"></v-skeleton-loader>
          </v-layout>

          <v-data-iterator
            class="row no-gutters row--dense"
            :items="latest_products"
            :items-per-page.sync="itemsPerPage"
            :page.sync="page"
            :search="search"
            :sort-by="sortBy"
            :sort-desc="sortDesc"
            hide-default-footer
          >
            <template v-slot:default="props">
              <v-col cols="12" sm="6" md="4" lg="3" v-for="(product, i) in props.items" :key="i">
                <v-hover :key="i" v-slot="{ hover }" class="cursor-hover">
                  <v-card link :to="`/imarket/product/${product.id}`" class="mx-2 mb-3" :elevation="hover ? 2 : 0" :outlined="hover ? true : false">
                    <v-img :src="`http://127.0.0.1:8000/images/products/${product.product_info.image}`" contain></v-img>
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
                      <v-card-subtitle class="pt-0 pl-1"> {{ product.store.name }} </v-card-subtitle>
                      <v-layout d-flex align-center>
                        <v-rating
                          class="mt-n4"
                          empty-icon="mdi-star-outline"
                          full-icon="mdi-star"
                          half-icon="mdi-star-half-full"
                          half-increments
                          hover
                          length="5"
                          :value="product.rating_avg_rating"
                          size="18"
                          readonly
                        ></v-rating>
                        <p class="mb-0 mt-n3 ml-2 black--text">
                          {{ product.rating_avg_rating && product.rating_avg_rating.toFixed(1) }}
                        </p>
                      </v-layout>
                      <p class="mb-0 ml-1">{{ product.rating_count }} Rating{{ product.rating_count > 1 ? 's' : '' }}</p>
                      <h2 class="mt-4 pl-1 orange--text darken-2 font-weight-regular">₱ {{ formatCurrency(product.product_info.price) }}</h2>
                    </v-card-text>
                  </v-card>
                </v-hover>
              </v-col>
            </template>
          </v-data-iterator>
        </v-col>
        <v-col cols="12" sm="12" md="3" lg="3"> <v-card> </v-card> </v-col>
      </v-row>
    </v-container>

    <div class="position-relative">
      <v-container class="pb-10 pt-15">
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

        <v-row class="mt-15">
          <v-col cols="12" sm="12" md="12" lg="12">
            <v-layout class="mt-10" justify-space-between align-center>
              <div>
                <h2 class="font-weight-bold mb-0">Market Products</h2>
                <p class="mb-7 grey--text">All products from our beloved partner sellers</p>
              </div>
              <v-btn icon @click="sortAllDesc = !sortAllDesc">
                <v-icon>{{ sortAllDesc ? 'mdi-sort-numeric-ascending' : 'mdi-sort-numeric-descending' }}</v-icon>
              </v-btn>
            </v-layout>
            <v-layout class="mt-4 pr-2 mb-5" column>
              <h2 class="font-weight-regular mb-6">Categories</h2>
              <v-slide-group v-model="selectedCategory" multiple show-arrows="">
                <v-slide-item v-for="(category, i) in categories" :key="i" v-slot="{ active, toggle }">
                  <v-btn class="mx-2" :input-value="active" active-class="orange darken-2 white--text" depressed rounded @click="toggle"> {{ category.category }} </v-btn>
                </v-slide-item>
              </v-slide-group>
            </v-layout>
            <v-layout v-if="isLoading" wrap>
              <v-skeleton-loader v-for="n in 9" :key="n" class="mx-2" min-width="400"  type="card"></v-skeleton-loader>
            </v-layout>
            <v-row no-gutters dense v-else class="">
              <v-col cols="12" sm="6" md="4" lg="3" v-for="(product, i) in sortedProducts" :key="i">
                <v-hover :key="i" v-slot="{ hover }" class="cursor-hover">
                  <v-card link :to="`/imarket/product/${product.id}`" class="mx-2 mb-3" :elevation="hover ? 2 : 0" :outlined="hover ? true : false">
                    <v-img :src="`http://127.0.0.1:8000/images/products/${product.product_info.image}`" contain></v-img>
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
                      <v-card-subtitle class="pt-0 pl-1"> {{ product.store.name }} </v-card-subtitle>
                      <v-layout d-flex align-center>
                        <v-rating
                          class="mt-n4"
                          empty-icon="mdi-star-outline"
                          full-icon="mdi-star"
                          half-icon="mdi-star-half-full"
                          half-increments
                          hover
                          length="5"
                          :value="product.rating_avg_rating"
                          size="18"
                          readonly
                        ></v-rating>
                        <p class="mb-0 mt-n3 ml-2 black--text">
                          {{ product.rating_avg_rating && product.rating_avg_rating.toFixed(1) }}
                        </p>
                      </v-layout>
                      <p class="mb-0 ml-1">{{ product.rating_count }} Rating{{ product.rating_count > 1 ? 's' : '' }}</p>
                      <h2 class="mt-4 pl-1 orange--text darken-2 font-weight-regular">₱ {{ formatCurrency(product.product_info.price) }}</h2>
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
                  <v-btn color="blue darken-1" depressed class="rounded-pill btn-glow-blue mt-3" large link to="/login" > Get Started </v-btn>
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
      itemsPerPage: 10,
      isLoading: false,
      page: 1,
      sortBy: 'product_info.price',
      productId: null,
      isCartLoading: false,
      isAddedSuccess: false,
      sortDesc: false,
      sortAllDesc: false,
      selectedCategory: [],
      starValue: 3.5,
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
      //Category Sorting
      sortedProducts() {
        return this.sortAllDesc
          ? this.products.data.slice().sort((a, b) => a.product_info.price - b.product_info.price)
          : this.products.data.slice().sort((a, b) => b.product_info.price - a.product_info.price);
      },
    },
    watch: {},
  };
</script>
<style>
  .v-rating .v-icon {
    padding: 0rem !important;
  }
</style>
