<template>
  <div>
    <Navbar />
    <v-container>
      <v-row>
        <v-col cols="12" md="7" lg="7">
          <v-card elevation="0" class="pa-6">
            <p class="mb-0 ml-4 text-h5 font-weight-bold">My Orders</p>
            <v-list>
              <div v-for="(product, i) in cart.cart_info" :key="i">
                <v-list-item link>
                  <v-list-item-avatar size="50" tile>
                    <v-img :src="'http://127.0.0.1:8000/images/products/' + product.product.product_info.image"></v-img>
                  </v-list-item-avatar>

                  <v-list-item-content>
                    <v-list-item-title>{{ product.product.product_info.name }}</v-list-item-title>
                    <v-list-item-subtitle>Quantity: {{ product.quantity }}</v-list-item-subtitle>
                    <v-list-item-subtitle>Subtotal: ₱ {{ formatCurrency(product.subtotal) }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                <v-divider />
              </div>
            </v-list>
          </v-card>
        </v-col>
        <v-col cols="12" md="5" lg="5">
          <v-card elevation="0" class="pr-8 pl-8 pb-8 pt-8">
            <p class="mb-0 text-h5 font-weight-bold">Payment Summary</p>
            <v-layout class="mt-5" column>
              <p class="mb-1">Deliver Address</p>
              <v-layout justify-space-between>
                <p class="text-h6 font-weight-bold lh-small">{{ user.info.address }}</p>
                <v-btn icon class="ml-5 blue--text">
                  <v-icon>
                    mdi-pencil
                  </v-icon>
                </v-btn>
              </v-layout>
              <v-layout d-flex>
                <small class="mb-0 mr-4">
                  <v-icon small> mdi-clock </v-icon>
                  45mins
                </small>
                <small class="mb-0">
                  <v-icon small> mdi-map-marker </v-icon>
                  8km
                </small>
              </v-layout>
            </v-layout>
            <v-layout class="mt-8" justify-space-between>
              <p class="mb-2 font-weight-bold">Subtotal</p>
              <p class="mb-2">PHP {{ formatCurrency(cart.cart_info_sum_subtotal) }}</p>
            </v-layout>
            <v-layout class="" justify-space-between>
              <p class="mb-2 font-weight-bold">Delivery Fee</p>
              <p class="mb-2">PHP 80.00</p>
            </v-layout>
            <v-layout class="" justify-space-between>
              <p class="font-weight-bold">Discount</p>
              <p class="">PHP 80.00</p>
            </v-layout>
            <v-divider class="mt-2 mb-2" />
            <v-layout class="mt-3" justify-space-between>
              <p class="font-weight-bold">Total Payment</p>
              <p class="">PHP 1580.00</p>
            </v-layout>
            <v-btn class="" color="orange darken-2" dark block depressed>CHECKOUT</v-btn>
          </v-card>
        </v-col>
      </v-row>
      <v-row>
        <h2 class="font-weight-bold mb-3 mt-10">Latest Added</h2>
        <v-layout v-if="isLoading" wrap>
          <v-skeleton-loader v-for="n in 6" :key="n" class="mx-2" min-width="270" max-width="270" type="card"></v-skeleton-loader>
        </v-layout>
        <v-row dense no-gutters v-else class="">
          <v-col sm="4" md="3" lg="3" v-for="(product, i) in latest_products" :key="i">
            <v-hover :key="i" v-slot="{ hover }" class="cursor-hover">
              <v-card class="mx-2 mb-3" :elevation="hover ? 2 : 0" :outlined="hover ? true : false">
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
                  <v-card-subtitle class="pt-0 mt-n5 pl-1"> {{ product.user.info.last_name }}'s Store </v-card-subtitle>
                  <h3 class="mt-n3 pl-1 orange--text darken-2 font-weight-regular">₱ {{ formatCurrency(product.product_info.price) }}</h3>
                </v-card-text>
              </v-card>
            </v-hover>
          </v-col>
        </v-row>
      </v-row>
    </v-container>
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
      await this.$store.dispatch('market/getLatestProducts');
      await this.$store.dispatch('market/getCartItems');
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
          }, 320);
        } else {
          this.$toast('Please login your account first!');
          this.$router.push('/login');
        }
      },
    },
    computed: {
      ...mapState('market', ['cart_count', 'cart', 'categories', 'products', 'latest_products']),
      ...mapState('auth', ['user']),
    },
    watch: {},
  };
</script>
