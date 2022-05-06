<template>
  <div>
    <Navbar />
    <v-container>
      <v-row justify="center">
        <v-col cols="12" md="9" lg="8">
          <v-card rounded="lg" elevation="0" class="pl-7 pr-7 pt-6 pb-5">
            <p class="mb-0 ml-4 text-h5 font-weight-bold">My Orders</p>
            <div class="pr-5" v-if="!orders || orders.data.length == 0">
              <p class="ml-4 mt-5 w-75">You currently have no history of orders on your account. Continue shopping and make some orders.</p>
            </div>
            <p v-else class="ml-4">Click any of the list below to view details of your order</p>
            <v-list>
              <div v-for="(order, i) in orders.data" :key="i">
                <v-list-item link>
                  <v-list-item-content>
                    <v-list-item-title class="font-weight-bold">{{ order.transaction_id }}</v-list-item-title>
                    <v-list-item-subtitle class="">{{ order.created_at }}</v-list-item-subtitle>
                    <v-list-item-subtitle class="mb-5 black--text"
                      >Status:
                      <v-chip small color="primary">
                        {{ order.status }}
                      </v-chip>
                    </v-list-item-subtitle>
                    <v-list-item-subtitle class="black--text">Subtotal: PHP {{ formatCurrency(order.subtotal) }}</v-list-item-subtitle>
                    <v-list-item-subtitle class="black--text">Delivery Fee: PHP {{ formatCurrency(order.delivery_fee) }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                <v-divider v-if="i != orders.data.length - 1"></v-divider>
              </div>
            </v-list>
          </v-card>
          <v-layout justify-center class="mt-5">
            <v-btn v-if="orders.last_page != page" @click.prevent="getOrders" large depressed color="blue darken-2" dark :loading="isLoading">LOAD MORE</v-btn>
          </v-layout>
        </v-col>
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
      removeItemDialog: false,
      isLoading: false,
      page: 1,
      productId: null,
      isCartLoading: false,
      isAddedSuccess: false,
      btnLoading: false,
    }),
    async mounted() {
      this.isLoading = true;
      await this.$store.dispatch('market/getLatestProducts');
      await this.$store.dispatch('orders/getOrders', 1);
      this.isLoading = false;
    },
    methods: {
      async getOrders() {
        this.isLoading = true;
        const page = this.page + 1;
        await this.$store.dispatch('orders/getOrders', page);
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
      ...mapState('orders', ['orders']),
      ...mapState('auth', ['user']),
    },
    watch: {},
  };
</script>
