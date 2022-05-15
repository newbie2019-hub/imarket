<template>
  <div>
    <Navbar />
    <v-layout class="mt-15"></v-layout>
    <v-container class="mt-15">
      <search-product />
      <v-row v-if="productSelected.product_info" class="mb-15 mt-8">
        <v-col cols="12" sm="12" md="7" lg="6">
          <v-img max-height="46 0" :src="`http://127.0.0.1:8000/images/products/${productSelected.product_info.image}`"></v-img>
        </v-col>
        <v-col cols="12" sm="12" md="5" lg="5">
          <h1 class="ml-4 mt-1">{{ productSelected.product_info.name }}</h1>
          <p class="ml-4 mb-0 grey--text">{{ productSelected.store.name }}</p>
          <h4 class="ml-4 mt-3 font-weight-regular text-justify">{{ productSelected.product_info.description }}</h4>
          <v-layout class="ml-3 mt-4" d-flex align-center>
            <v-rating
              class=""
              empty-icon="mdi-star-outline"
              full-icon="mdi-star"
              half-icon="mdi-star-half-full"
              half-increments
              hover
              length="5"
              background-color="primary lighten-2"
              color="primary"
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
              <v-text-field v-model="data.qty" type="number" min="1" :max="productSelected.product_info.quantity" outlined dense hide-details="auto"></v-text-field>
            </v-col>
          </v-layout>
          <v-btn @click="addToCart(productSelected)" :loading="btnLoading" depressed large class="ml-4 btn-glow-blue" color="blue darken-2" dark>
            <v-icon small class="mr-2"> mdi-cart </v-icon>
            Add to Cart
          </v-btn>
        </v-col>
      </v-row>

      <v-row class="mb-15">
        <v-col class="" cols="12" sm="12" md="7" lg="8">
          <h2 class="font-rubik text-uppercase" :class="{ 'mb-10': productSelected.rating > 0 }">Reviews</h2>
          <h4 class="font-rubik font-weight-regular mt-2" v-if="productSelected.rating == 0">No reviews for this product yet. Buy now so you can make a review for this product</h4>
          <v-layout d-flex class="mt-5" v-for="(rating, i) in productSelected.rating" :key="i">
            <v-avatar size="60" color="primary"> </v-avatar>
            <v-layout column>
              <v-layout d-flex align-center class="mb-0">
                <v-rating
                  class="ml-8"
                  empty-icon="mdi-star-outline"
                  full-icon="mdi-star"
                  half-icon="mdi-star-half-full"
                  half-increments
                  hover
                  length="5"
                  :value="parseFloat(rating.rating)"
                  size="20"
                  background-color="primary lighten-2"
                  color="primary"
                  readonly
                ></v-rating>
                <p class="mb-0 ml-2 mt-1 black--text">
                  {{ parseFloat(rating.rating).toFixed(1) }}
                </p>
              </v-layout>
              <p class="ml-8 mb-0 font-2x">{{ rating.user_info.first_name }} {{ rating.user_info.last_name }}</p>
              <p class="ml-8 mb-5">
                <small>{{ rating.created_at }}</small>
              </p>
              <p class="ml-8">{{ rating.comment }}</p>
            </v-layout>
          </v-layout>
        </v-col>
        <v-col cols="12" sm="12" md="5" lg="4">
          <h2 class="font-rubik text-uppercase mb-10">Related Product</h2>
          <v-row>
            <v-col cols="12" sm="6" md="10" lg="10" v-for="(product, i) in relatedCategoryProduct" :key="i">
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
                        background-color="primary lighten-2"
                        color="primary"
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
        </v-col>
      </v-row>
      <div>
        <h2 class="font-weight-bold mb-0">From The Same Store</h2>
        <p class="mb-8 grey--text">Here are the latest products added by this store</p>
      </div>
      <v-row no-gutters dense class="">
        <v-layout v-if="isLoading" wrap>
          <v-skeleton-loader v-for="n in 6" :key="n" class="mx-2" min-width="270" max-width="270" type="card"></v-skeleton-loader>
        </v-layout>
        <v-col cols="12" sm="6" md="4" lg="3" v-for="(product, i) in moreProducts" :key="i">
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

  export default {
    mixins: [formatCurrency],
    data: () => ({
      productId: null,
      search: '',
      data: {
        id: null,
        qty: 1,
        product_info: {
          price: '',
          quantity: '',
        },
      },
      isCartLoading: false,
      isAddedSuccess: false,
      isLoading: false,
      btnLoading: false,
    }),
    components: { Navbar, SearchProduct },
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
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('products/getProduct', this.$route.params.id);
        if (status != 200) {
          this.$toast.error('Something went wrong!');
          this.$router.push('/imarket');
        }
        this.isLoading = false;
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
      ...mapState('products', ['productSelected', 'relatedCategoryProduct', 'moreProducts']),
      ...mapState('auth', ['user']),
    },
    watch: {
      async $route() {
        await this.getProduct();
      },
    },
  };
</script>
