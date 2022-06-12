<template>
  <div>
    <Navbar />
    <page-loading v-show="isSearching" :msg="'This may take a while. Please wait ...'" />
    <v-layout class="mt-15"></v-layout>
    <v-container class="mt-15">
      <v-row justify="center" align="center">
        <v-col cols="12" sm="12" md="12" lg="12">
          <search-product />
        </v-col>
      </v-row>
      <div>
        <h2 class="font-weight-bold mb-0">Scraped Recipes</h2>
        <p class="mb-7 grey--text">Click on a recipe to view its content.</p>
      </div>
      <v-row class="mt-10">
        <v-layout v-if="isLoading" wrap>
          <v-skeleton-loader v-for="n in 9" :key="n" class="mx-2" min-width="400" type="card"></v-skeleton-loader>
        </v-layout>
        <v-row no-gutters dense v-else class="">
          <v-col cols="12" sm="6" md="4" lg="3" v-for="(product, i) in scrapedRecipes" :key="i">
            <v-hover :key="i" v-slot="{ hover }" class="cursor-hover">
              <v-card @click="scrapeRecipeContent(product)" class="mx-2 mb-3" :elevation="hover ? 2 : 0" :outlined="hover ? true : false">
                <v-img :src="`${product.image}`" contain></v-img>
                <v-card-text class="position-relative pt-2">
                  <v-card-title class="pt-1 pl-1 black--text lh-small text-capitalize">{{ product.title }}</v-card-title>
                  <v-card-subtitle class="pt-0 pl-1 mt-1"> {{ product.description }} </v-card-subtitle>
                </v-card-text>
              </v-card>
            </v-hover>
          </v-col>
        </v-row>
      </v-row>
      <div>
        <h2 class="font-weight-bold mb-0">Market Products</h2>
        <p class="mb-7 grey--text">Product results from our beloved partner sellers</p>
      </div>
      <v-row class="mt-10">
        <v-col cols="12" sm="6" md="4" lg="3" v-for="(product, i) in searchedProducts.data" :key="i">
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
                    background-color="primary lighten-2"
                    color="primary"
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
        <v-layout v-if="searchedProducts.data" justify-center class="mt-5">
          <v-btn v-if="searchedProducts.last_page != page" @click.prevent="getProducts" large depressed color="blue darken-2" dark :loading="isLoading">LOAD MORE</v-btn>
        </v-layout>
      </v-row>
    </v-container>
  </div>
</template>
<script>
  import SearchProduct from './components/SearchProduct.vue';
  import Navbar from './components/Navbar.vue';
  import { formatCurrency } from '@/assets/js/utilities';
  import { mapState } from 'vuex';
  import API from '@/store/base/base';
  import PageLoading from './components/PageLoading.vue';

  export default {
    data: () => ({
      isLoading: false,
      productId: null,
      isCartLoading: false,
      isAddedSuccess: false,
      isSearching: false,
      page: 1,
    }),
    mixins: [formatCurrency],
    async mounted() {},
    methods: {
      async scrapeRecipeContent(recipe) {
        console.log(recipe);
        this.isSearching = true;
        const { status, data } = await API.post(`http://localhost:8082/recipe/${recipe.title}`, recipe)
          .then((res) => res)
          .catch((err) => err.response);
        // console.log(status);
        if (status == 0) {
          this.$toast.error('Sorry, but our scraping service is not active. \n');
        } else {
          console.log(data.result);
          // this.$store.commit('market/SET_SCRAPE', data.result);
        }
        this.isSearching = false;
      },
    },
    components: { SearchProduct, Navbar, PageLoading },
    computed: {
      ...mapState('market', ['scrapedRecipes', 'searchedProducts']),
    },
  };
</script>
