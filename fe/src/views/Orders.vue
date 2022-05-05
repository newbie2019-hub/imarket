<template>
  <div>
    <Navbar />
    <v-container>
      <v-row>
        <v-col cols="12" md="7" lg="7">
          <v-card elevation="0" class="pa-6">
            <p class="mb-0 ml-4 text-h5 font-weight-bold">My Orders</p>
            <div class="pr-5" v-if="orders.length == 0 || orders.content.length == 0">
              <p class="ml-4 mt-5 w-75">You currently have no history of orders on your account. Continue shopping and make some orders.</p>
            </div>
            <v-list>
              <div v-for="(order, i) in orders" :key="i">
                <v-list-item link>
                  <p>{{ order.transaction_id }}</p>
                </v-list-item>
                <v-divider></v-divider>
              </div>
            </v-list>
          </v-card>
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
      await this.$store.dispatch('orders/getOrders');
      console.log(Object.keys(this.orders).length);
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
    },
    computed: {
      ...mapState('orders', ['orders']),
      ...mapState('auth', ['user']),
    },
    watch: {},
  };
</script>
