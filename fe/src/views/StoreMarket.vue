<template>
  <div>
    <page-loading v-show="initialLoading" :msg="'Retrieving data of the store. Please wait...'" />
    <navbar />
    <v-container class="mt-15">
      <v-layout class="mt-15">
        <search-product />
      </v-layout>
      <v-layout class="" column>
        <div class="store-preview">
          <v-img :src="`http://127.0.0.1:8000/images/banners/${selectedStore.store.banner_image}`" class="store-preview--image"></v-img>
          <div class="image-overlay"></div>
        </div>
        <v-layout class="store-preview--content">
          <div align-center justify-center class="position-relative">
            <v-avatar color="grey" size="140">
              <img v-if="selectedStore.store.logo != '' && selectedStore.store.logo != null" :src="'http://127.0.0.1:8000/images/logos/' + selectedStore.store.logo" alt="Store Logo" />
              <v-icon x-large color="primary" v-else> mdi-account-circle </v-icon>
            </v-avatar>
          </div>
          <v-layout column class="ml-5">
            <h1 class="font-rubik white--text">{{ selectedStore.store.name }}</h1>
            <p class="white--text">
              <v-icon color="white"> mdi-map-marker</v-icon>
              {{ selectedStore.store.address }}
            </p>
            <v-row dense>
              <v-col sm="5" md="4" lg="3">
                <p class="mb-0">Week Days</p>
                <p>
                  <small>
                    <v-icon small> mdi-clock </v-icon>
                    {{ selectedStore.store.week_days_opening | formatTime }}
                  </small>
                  -
                  <small>
                    <v-icon small> mdi-clock </v-icon>
                    {{ selectedStore.store.week_days_closing | formatTime }}
                  </small>
                </p>
              </v-col>
              <v-col sm="5" md="4" lg="3">
                <p class="mb-0">Week Ends</p>
                <p>
                  <small>
                    <v-icon small> mdi-clock </v-icon>
                    {{ selectedStore.store.week_end_opening | formatTime }}
                  </small>
                  -
                  <small>
                    <v-icon small> mdi-clock </v-icon>
                    {{ selectedStore.store.week_end_closing | formatTime }}
                  </small>
                </p>
              </v-col>
            </v-row>
          </v-layout>
        </v-layout>
      </v-layout>
      <div class="mt-10 mb-3">
        <h2 class="font-weight-bold mb-0">Store Products</h2>
        <p class="mb-8 grey--text">Here are the products from this store</p>
      </div>
      <v-row no-gutters dense class="mb-10">
        <v-layout v-if="isLoading" wrap>
          <v-skeleton-loader v-for="n in 6" :key="n" class="mx-2" min-width="270" max-width="270" type="card"></v-skeleton-loader>
        </v-layout>
        <v-col cols="12" sm="6" md="4" lg="3" v-for="(product, i) in selectedStore.products" :key="i">
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
                <v-card-subtitle class="pt-0 pl-1"> {{ selectedStore.name }} </v-card-subtitle>
                <v-layout d-flex align-center>
                  <v-rating
                    class="mt-n4"
                    empty-icon="mdi-star-outline"
                    full-icon="mdi-star"
                    half-icon="mdi-star-half-full"
                    half-increments
                    background-color="primary lighten-2"
                    color="primary"
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
    </v-container>
  </div>
</template>
<script>
  import Navbar from './components/Navbar.vue';
  import { mapState } from 'vuex';
  import { formatCurrency } from '@/assets/js/utilities';
  import SearchProduct from './components/SearchProduct.vue';
  import PageLoading from './components/PageLoading.vue';
  import moment from 'moment';
  export default {
    filters: {
      formatTime(val) {
        if (val) {
          return moment(val, ['hh:mm']).format('hh:mm A');
        }
      },
    },
    mixins: [formatCurrency],
    components: { Navbar, SearchProduct, PageLoading },
    data: () => ({
      isLoading: false,
      initialLoading: true,
      isAddedSuccess: false,
      isCartLoading: false,
      btnLoading: false,
      data: {
        id: ''
      }
    }),
    async mounted() {
      if (this.$route.params.id == null) {
        return this.$router.push('/imarket');
      }
      await this.getStoreInfo();
      if (this.selectedStore == null || this.selectedStore.length == 0) return this.$router.push('/imarket');
      setTimeout(() => {
        this.initialLoading = false;
      }, 1000);
    },
    methods: {
      async getStoreInfo() {
        await this.$store.dispatch('market/getSelectedStoreInfo', this.$route.params.id);
      },
      async addToCart(product) {
        this.data.id = product.id;
        this.data.product_info = product.product_info;
        if (this.user.info) {
          this.btnLoading = true;
          this.isCartLoading = true;
          this.productId = product.id;
          await this.$store.dispatch('market/addToCart', this.data);
          await this.$store.dispatch('market/cartCount');
          await this.$store.dispatch('market/getCartItems');
          this.isCartLoading = false;
          this.isAddedSuccess = true;

          setTimeout(() => {
            this.productId = null;
            this.isAddedSuccess = false;
          }, 350);

          this.btnLoading = false;
        } else {
          this.$toast('Please login your account first!');
          this.$router.push('/login');
        }
      },
    },
    computed: {
      ...mapState('market', ['selectedStore']),
      ...mapState('auth', ['user'])
    },
  };
</script>
