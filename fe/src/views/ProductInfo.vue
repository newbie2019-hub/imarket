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
        </v-col>
      </v-row>
      <v-row v-if="productSelected.product_info" class="mb-15">
        <v-col cols="12" sm="12" md="7" lg="6">
          <v-img max-height="420" :src="`http://127.0.0.1:8000/images/products/${productSelected.product_info.image}`"></v-img>
        </v-col>
        <v-col cols="12" sm="12" md="5" lg="5">
          <h1 class="ml-4 mt-3">{{ productSelected.product_info.name }}</h1>
          <h4 class="ml-4 mt-3 font-weight-light text-justify">{{ productSelected.product_info.description }}</h4>
          <v-layout class="ml-3 mt-4" d-flex align-center>
            <v-rating
              class=""
              empty-icon="mdi-star-outline"
              full-icon="mdi-star"
              half-icon="mdi-star-half-full"
              half-increments
              hover
              length="5"
              :value="productSelected.rating_avg_rating"
              size="25"
              readonly
            ></v-rating>
            <p class="mb-0 ml-2 black--text">
              {{ productSelected.rating_avg_rating && productSelected.rating_avg_rating.toFixed(1) }}
            </p>
          </v-layout>
          <p class="mb-0 ml-4 mt-2">{{ productSelected.rating_count }} Rating{{ productSelected.rating_count > 1 ? 's' : '' }}</p>
          <h1 class="mt-4 ml-4 orange--text darken-2 font-weight-regular">₱ {{ formatCurrency(productSelected.product_info.price) }}</h1>
          <v-layout class="ml-4 mt-6" align-center>
            <p class="font-weight-bold mb-0 mr-2">QTY:</p>
            <v-col lg="3" md="3" sm="4" cols="5">
              <v-text-field v-model="data.qty" type="number" outlined dense hide-details="auto"></v-text-field>
            </v-col>
          </v-layout>
          <v-btn depressed x-large class="ml-4 btn-glow-blue" color="blue darken-2" dark>
           <v-icon small class="mr-2">
            mdi-cart
           </v-icon>
           Add to Cart
          </v-btn>
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
    mixins: [formatCurrency],
    data: () => ({
      productId: null,
      search: '',
      data: {
       qty: 1,
      }
    }),
    components: { Navbar },
    created() {},
    async mounted() {
      this.productId = this.$route.params.id;
      if (this.productId == null) {
        this.$router.push('/imarket');
      }
      // console.log(this.$route.params.id)
      await this.getProduct();
    },
    methods: {
      async getProduct() {
        const { status, data } = await this.$store.dispatch('products/getProduct', this.$route.params.id);
        if (status != 200) {
          this.$toast.error('Something went wrong!');
          this.$router.push('/imarket');
        }
      },
    },
    computed: {
      ...mapState('products', ['productSelected']),
      ...mapState('auth', ['user']),
    },
  };
</script>
