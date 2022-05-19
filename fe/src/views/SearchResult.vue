<template>
  <div>
    <Navbar />
    <v-layout class="mt-15"></v-layout>
    <v-container class="mt-15">
      <v-row justify="center" align="center">
        <v-col cols="12" sm="12" md="12" lg="12">
          <search-product />
        </v-col>
      </v-row>
      <v-row class="mt-10">
        <div>
          <h2 class="font-weight-bold mb-0">Scraped Recipes</h2>
          <p class="mb-7 grey--text">Click on a recipe to view its content.</p>
        </div>
        <v-layout v-if="isLoading" wrap>
          <v-skeleton-loader v-for="n in 9" :key="n" class="mx-2" min-width="400" type="card"></v-skeleton-loader>
        </v-layout>
        <v-row no-gutters dense v-else class="">
          <v-col cols="12" sm="6" md="4" lg="3" v-for="(product, i) in scrapedRecipes" :key="i">
            <v-hover :key="i" v-slot="{ hover }" class="cursor-hover">
              <v-card class="mx-2 mb-3" :elevation="hover ? 2 : 0" :outlined="hover ? true : false">
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
      <v-row class="mt-10">
        <div>
          <h2 class="font-weight-bold mb-0">Market Products</h2>
          <p class="mb-7 grey--text">Product results from our beloved partner sellers</p>
        </div>
      </v-row>
    </v-container>
  </div>
</template>
<script>
  import SearchProduct from './components/SearchProduct.vue';
  import Navbar from './components/Navbar.vue';
  import { mapState } from 'vuex';

  export default {
    data: () => ({
      isLoading: false,
    }),
    async mounted() {},
    methods: {},
    components: { SearchProduct, Navbar },
    computed: {
      ...mapState('market', ['scrapedRecipes']),
    },
  };
</script>
