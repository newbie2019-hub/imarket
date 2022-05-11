<template>
  <div id="cart-dropdown">
    <v-menu tile content-class="cart" transition="slide-y-transition" :close-on-content-click="false" offset-y max-height="450" max-width="420" left>
      <template v-slot:activator="{ on, attrs }">
        <v-badge overlap color="primary" :content="cart_count && cart_count.cart_info_count ? cart_count.cart_info_count : '0'">
          <v-btn :loading="initialLoading" v-on="on" v-bind="attrs" text color="orange darken-2">
            <v-icon> mdi-cart </v-icon>
            Cart
          </v-btn>
        </v-badge>
      </template>
      <v-card tile class="position-relative">
        <div class="cart-title">
          <div>
            <p class="text-h6 font-weight-bold ml-4 mt-1 mb-0">My Orders</p>
            <p class="ml-4 mb-3"><small>You may checkout your orders anytime</small></p>
          </div>
          <div class="mr-4">
            <v-btn :disabled="Object.keys(cart).length == 0 || cart.cart_info.length == 0" @click.stop="deleteDialog = true" class="white--text" text>
              <v-icon small>mdi-delete</v-icon>
              Clear
            </v-btn>
          </div>
        </div>
        <div class="pl-5 pr-5" v-if="Object.keys(cart).length == 0 || cart.cart_info.length == 0">
          <p class="text-center mt-5">There are no items in your cart. Continue shopping and start adding items to your cart now.</p>
        </div>
        <v-list>
          <v-list-item link v-for="(product, i) in cart.cart_info" :key="i">
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
                icon
              >
                <v-icon>mdi-delete-circle</v-icon>
              </v-btn>
            </v-list-item-action>
          </v-list-item>
        </v-list>

        <v-divider></v-divider>
        <div class="cart-bottom" v-if="Object.keys(cart).length != 0 && cart.cart_info.length != 0">
          <p class="mb-0"><span class="font-weight-bold">Sub Total:</span> PHP {{ formatCurrency(cart.cart_info_sum_subtotal) }}</p>
          <v-btn link to="/cart-checkout" class="" text depressed color="white darken-2" dark>Checkout</v-btn>
        </div>
      </v-card>
    </v-menu>

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

    <v-dialog v-model="deleteDialog" max-width="500">
      <v-card>
        <v-card-title class="text-h5"> Confirm Delete </v-card-title>
        <h4 class="font-weight-light ml-6 mr-5 mb-5">Are you sure you want to clear your cart items? <span class="red--text">Note: You cannot undo this action</span></h4>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="gray darken-1" text @click="deleteDialog = false"> Cancel </v-btn>
          <v-btn @click.prevent="deleteCart" color="green darken-1" text :loading="btnLoading"> Confirm </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  import { formatCurrency } from '@/assets/js/utilities';

  export default {
    mixins: [formatCurrency],
    data: () => ({
      deleteDialog: false,
      removeItemDialog: false,
      menu: false,
      itemId: null,
      btnLoading: false,
      items: [{ title: 'Click Me' }, { title: 'Click Me' }, { title: 'Click Me' }, { title: 'Click Me 2' }],
    }),
    async mounted() {
      this.initialLoading = true;
      if (this.$route.path == '/imarket') {
        await this.$store.dispatch('market/cartCount');
        await this.$store.dispatch('market/getCartItems');
      }
      this.initialLoading = false;
    },
    methods: {
      async deleteCart() {
        this.btnLoading = true;
        const { status, data } = await this.$store.dispatch('market/deleteCart', this.cart.id);
        this.toastData(status, data);
        await this.$store.dispatch('market/cartCount');
        await this.$store.dispatch('market/getCartItems');
        this.btnLoading = false;
        this.deleteDialog = false;
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
    },
    computed: {
      ...mapState('market', ['cart_count', 'cart']),
    },
    watch: {},
  };
</script>
<style>
  .cart-title {
    position: sticky;
    width: 100%;
    top: 0;
    left: 0;
    background: #1976d2 !important;
    box-shadow: 0 5px 10px 1px rgba(20, 53, 75, 0.11);
    z-index: 2;
    padding: 5px 0;
    line-height: 10px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .cart-bottom {
    position: sticky;
    bottom: 0;
    left: 0;
    display: flex;
    justify-content: space-between;
    background: #1976d2 !important;
    color: white;
    width: 100%;
    padding: 0.5rem 1rem;
    align-items: center;
  }

  .cart {
    position: relative;
  }

  .cart::before {
    content: '';
    position: absolute;
    top: -20px;
    right: 0;
    background: #000;
    width: 20px;
    height: 20px;
  }
  /* 
  .cart-checkout {
    position: fixed;
    bottom: 0;
    left: 0;
  } */
</style>
