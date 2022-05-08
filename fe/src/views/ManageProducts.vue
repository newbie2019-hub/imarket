<template>
  <div>
    <Navbar />
    <v-layout class="mt-10"></v-layout>
    <v-container class="mt-15">
      <v-row justify="center">
        <!-- <v-col cols="" md="4" lg="4"> </v-col> -->
        <v-col cols="11" md="12" lg="12">
          <v-layout column>
            <h1>Store Management</h1>
            <p>Manage everything from your store here</p>
          </v-layout>
          <v-tabs hide-slider>
            <v-tab>
              <v-icon left> mdi-package-variant </v-icon>
              Products
            </v-tab>
            <v-tab>
              <v-icon left> mdi-store-marker-outline </v-icon>
              Store Info
            </v-tab>
            <v-tab>
              <v-icon left> mdi-truck-delivery-outline </v-icon>
              Store Orders
            </v-tab>
            <v-tab>
              <v-icon left> mdi-history </v-icon>
              Store History
            </v-tab>

            <v-tab-item>
              <Products />
            </v-tab-item>
            <v-tab-item>
              <StoreInfo />
            </v-tab-item>
            <v-tab-item>
              <v-card flat>
                <v-card-text>
                  <p>
                    Fusce a quam. Phasellus nec sem in justo pellentesque facilisis. Nam eget dui. Proin viverra, ligula sit amet ultrices semper, ligula arcu tristique sapien, a accumsan nisi mauris
                    ac eros. In dui magna, posuere eget, vestibulum et, tempor auctor, justo.
                  </p>

                  <p class="mb-0">
                    Cras sagittis. Phasellus nec sem in justo pellentesque facilisis. Proin sapien ipsum, porta a, auctor quis, euismod ut, mi. Donec quam felis, ultricies nec, pellentesque eu,
                    pretium quis, sem. Nam at tortor in tellus interdum sagittis.
                  </p>
                </v-card-text>
              </v-card>
            </v-tab-item>
          </v-tabs>
        </v-col>
      </v-row>
    </v-container>

    
  </div>
</template>
<script>
  import Navbar from './components/Navbar.vue';
  import Products from './userstore/Products.vue'
  import StoreInfo from './userstore/StoreInfo.vue'
  export default {
    components: { Navbar, Products, StoreInfo },
    data: () => ({
      
    }),
    async mounted() {
    
    },
    methods: {
    
    },
    computed: {

    },
  };
</script>
<style>
.v-slide-group__prev .v-slide-group__prev--disabled {
  display: none !important;
}
</style>
