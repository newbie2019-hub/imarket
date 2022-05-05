<template>
  <v-navigation-drawer v-model="isCartVisible" fixed temporary width="380" origin="center center" transition="scale-transition">
    <template v-slot:prepend>
      <v-list-item two-line>
        <v-list-item-content>
          <p class="font-weight-bold text-h5 font-weight-regular font-rubik">Cart Items</p>
          <p>Here are your cart items. You may checkout anytime you want.</p>
        </v-list-item-content>
      </v-list-item>
    </template>
    <v-list>
      <v-list-item v-for="(product, i) in cart.cart_info" :key="i" link>
        <v-list-item-avatar size="50" tile>
          <v-img :src="'http://127.0.0.1:8000/images/products/' + product.product.product_info.image"></v-img>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title>{{ product.product.product_info.name }}</v-list-item-title>
          <v-list-item-subtitle>Quantity: {{ product.quantity }}</v-list-item-subtitle>
          <v-list-item-subtitle>Subtotal: ₱ {{ formatCurrency(product.subtotal) }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-list>
    <template v-slot:append>
      <v-list-item two-line>
        <v-list-item-content>
          <p class="font-weight-bold text-h5 font-weight-regular font-rubik">Sub Total:</p>
          <p></p>
        </v-list-item-content>
      </v-list-item>
    </template>
  </v-navigation-drawer>
</template>
<script>
  import { mapState } from 'vuex';
  import { formatCurrency } from '@/assets/js/utilities';
  export default {
    mixins: [formatCurrency],
    props: {
      showCart: Boolean,
    },
    data: () => ({
      drawer: false,
    }),
    async mounted() {
      await this.$store.dispatch('market/getCartItems');
    },
    methods: {
      close() {
        this.$emit('close');
      },
    },
    computed: {
      isCartVisible: {
        get() {
          return this.showCart;
        },
        set(value) {
          if (!value) {
            this.$emit('close');
          }
        },
      },
      ...mapState('market', ['cart']),
    },
  };
</script>
