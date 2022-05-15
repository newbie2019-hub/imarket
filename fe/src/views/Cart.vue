<template>
  <div>
    <div v-show="addressLoading" class="overlay-loading"></div>
    <Navbar />
    <v-layout class="mt-15"></v-layout>
    <v-container class="mt-15">
      <v-row>
        <v-col cols="12" md="7" lg="7">
          <v-card elevation="0" class="pa-6">
            <p class="mb-0 ml-4 text-h5 font-weight-bold">My Orders</p>
            <div class="pr-5" v-if="Object.keys(cart).length == 0 || cart.cart_info.length == 0">
              <p class="ml-4 mt-5 w-75">There are no items in your cart. Continue shopping and start adding items to your cart now.</p>
            </div>
            <v-list>
              <div v-for="(product, i) in cart.cart_info" :key="i">
                <v-list-item link>
                  <v-list-item-avatar size="50" tile>
                    <v-img class="rounded-lg" :src="'http://127.0.0.1:8000/images/products/' + product.product.product_info.image"></v-img>
                  </v-list-item-avatar>

                  <v-list-item-content>
                    <v-list-item-title class="font-weight-bold">{{ product.product.product_info.name }}</v-list-item-title>
                    <v-list-item-subtitle>Quantity: {{ product.quantity }}</v-list-item-subtitle>
                    <v-list-item-subtitle>Subtotal: PHP {{ formatCurrency(product.subtotal) }}</v-list-item-subtitle>
                  </v-list-item-content>

                  <v-list-item-action>
                    <v-btn
                      @click.prevent="
                        itemId = product.id;
                        removeItemDialog = true;
                      "
                      class="red--text lighten-1"
                      text
                      small
                    >
                      <v-icon small>mdi-delete</v-icon>
                      Remove
                    </v-btn>
                  </v-list-item-action>
                </v-list-item>
                <v-divider inset></v-divider>
              </div>
            </v-list>
          </v-card>
        </v-col>
        <v-col cols="12" md="5" lg="5">
          <v-card elevation="0" class="pr-8 pl-8 pb-8 pt-8">
            <p class="mb-0 text-h5 font-weight-bold">Payment Summary</p>
            <v-layout class="mt-5" column>
              <p class="mb-1">Delivery Address</p>
              <v-layout justify-space-between>
                <p class="text-h6 font-weight-bold lh-small">{{ user.address.formatted_address ? user.address.formatted_address : 'Please set the delivery address' }}</p>
                <v-btn @click.prevent="showAddressEdit = true" icon class="ml-5 blue--text">
                  <v-icon> mdi-pencil </v-icon>
                </v-btn>
              </v-layout>
              <v-layout d-flex>
                <small class="mb-0 mr-4">
                  <v-icon small> mdi-clock </v-icon>
                  {{ distanceMatrix && distanceMatrix && distanceMatrix.rows[0].elements[1].duration.text }}
                </small>
                <small class="mb-0">
                  <v-icon small> mdi-map-marker </v-icon>
                  {{ distanceMatrix && distanceMatrix && distanceMatrix.rows[0].elements[1].distance.text }}
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
            <v-btn
              :disabled="Object.keys(cart).length == 0 || cart.cart_info.length == 0 || user.address.formatted_address == null"
              @click.prevent="checkout"
              class=""
              color="orange darken-2 white--text"
              block
              depressed
              :loading="btnLoading"
              >CHECKOUT</v-btn
            >
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    <v-container>
      <h2 class="font-weight-bold mb-3 mt-10">Latest Added</h2>
      <v-layout v-if="isLoading" wrap>
        <v-skeleton-loader v-for="n in 6" :key="n" class="mx-2" min-width="270" max-width="270" type="card"></v-skeleton-loader>
      </v-layout>
      <v-row dense no-gutters class="">
        <v-col cols="10" sm="4" md="3" lg="3" v-for="(product, i) in latest_products" :key="i">
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
                <v-card-subtitle class="pt-0 mt-n5 pl-1"> {{ product.store.name }} </v-card-subtitle>
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
                    background-color="primary lighten-2"
                    color="primary"
                    size="18"
                    readonly
                  ></v-rating>
                  <p class="mb-0 mt-n3 ml-2 black--text">
                    {{ product.rating_avg_rating && product.rating_avg_rating.toFixed(1) }}
                  </p>
                </v-layout>
                <h3 class="pl-1 orange--text darken-2 font-weight-regular">₱ {{ formatCurrency(product.product_info.price) }}</h3>
              </v-card-text>
            </v-card>
          </v-hover>
        </v-col>
        <v-layout justify-center class="mt-5 mb-5">
          <v-btn link to="/imarket" large depressed color="blue darken-2" dark :loading="isLoading">MORE PRODUCTS</v-btn>
        </v-layout>
      </v-row>
    </v-container>

    <v-dialog v-model="removeItemDialog" max-width="500">
      <v-card>
        <v-card-title class="text-h5"> Confirm Delete </v-card-title>
        <h4 class="font-weight-light ml-6 mr-5 mb-5">Are you sure you want to remove this item from your cart? <span class="red--text">Note: You cannot undo this action</span></h4>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="gray darken-1" text @click="removeItemDialog = false"> Cancel </v-btn>
          <v-btn @click.prevent="deleteItem" color="green darken-1" text :loading="btnLoading"> Confirm </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <address-update v-show="showAddressEdit" @close="showAddressEdit = false" :showModal="showAddressEdit" />
  </div>
</template>
<script>
  import Navbar from './components/Navbar.vue';
  import { mapState } from 'vuex';
  import { formatCurrency } from '@/assets/js/utilities';
  import AddressUpdate from './components/AddressUpdate.vue';
  import { gmapApi } from 'vue2-google-maps';
  export default {
    components: { Navbar, AddressUpdate },
    mixins: [formatCurrency],
    data: () => ({
      search: '',
      data: {
        search: '',
      },
      showAddressEdit: true,
      removeItemDialog: false,
      isLoading: false,
      page: 1,
      productId: null,
      isCartLoading: false,
      isAddedSuccess: false,
      btnLoading: false,
      addressLoading: true,
      hasLoaded: false
    }),
    async mounted() {
      this.isLoading = true;
      await this.$store.dispatch('market/getLatestProducts');
      await this.$store.dispatch('market/getCartItems');
      this.showAddressEdit = false
      setTimeout(() => {
        this.addressLoading = false;
      }, 250);
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
          }, 320);
        } else {
          this.$toast('Please login your account first!');
          this.$router.push('/login');
        }
      },
      async deleteItem() {
        this.btnLoading = true;
        const { status, data } = await this.$store.dispatch('market/removeItem', this.itemId);
        this.toastData(status, data);
        await this.$store.dispatch('market/cartCount');
        await this.$store.dispatch('market/getCartItems');
        this.btnLoading = false;
        this.removeItemDialog = false;
      },
      async checkout() {
        this.btnLoading = true;
        const { status, data } = await this.$store.dispatch('market/checkout', this.cart);
        this.toastData(status, data);
        await this.$store.dispatch('market/cartCount');
        await this.$store.dispatch('market/getCartItems');
        this.btnLoading = false;
      },
    },
    computed: {
      ...mapState('market', ['cart_count', 'cart', 'categories', 'products', 'latest_products']),
      ...mapState('auth', ['user', 'distanceMatrix']),
      google: gmapApi,
    },
    watch: {},
  };
</script>
