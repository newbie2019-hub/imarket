<template>
  <v-container :class="{ 'pa-0': $vuetify.breakpoint.md }" :fluid="isFluid">
    <div>
      <p class="text-h5 font-weight-bold custom-primary-color mt-8 mb-0">Products Management</p>
      <p>Manage all of the stores from this section</p>
    </div>
    <v-row class="mt-5 mb-5">
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="blue darken-1" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-car-brake-retarder </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Active <br />Products</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ products.length }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="red lighten-1" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-archive-clock-outline </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Archived <br />Products</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ archivedProducts.length }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="deep-orange lighten-1 " class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-account </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Out of <br />Stock</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ noStock }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>
    <v-row class="mb-5">
      <v-col>
        <v-layout justify-space-between>
          <p>Products</p>
          <v-col sm="5" md="4" lg="4">
            <v-text-field v-model="search" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" hide-details></v-text-field>
          </v-col>
        </v-layout>
        <v-data-table :headers="headers" :items="products" :search="search" :loading="isLoading" :loading-text="'Retrieving stores data. Please wait ...'">
          <template v-slot:item.logo="{ item }">
            <v-avatar class="ma-0" size="50" color="primary" tile>
              <img class="cursor-pointer" v-if="item.product_info.image" :src="`http://127.0.0.1:8000/images/products/${item.product_info.image}`" />
              <p v-else class="white--text font-weight-bold mb-0">{{ item.product_info.image }}</p>
            </v-avatar>
          </template>
          <template v-slot:item.product_info.name="{ item }">
            <p class="text-no-wrap">{{ item.product_info.name }}</p>
          </template>
          <template v-slot:item.product_info.description="{ item }">
            <p style="min-width: 220px" class="">{{ item.product_info.description }}</p>
          </template>
          <template v-slot:item.product_info.price="{ item }">
            <p class="text-no-wrap">{{ formatCurrency(item.product_info.price) }}</p>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-layout>
              <v-btn
                @click.prevent="
                  userData = item;
                  showModal();
                "
                small
                text
                color="green darken-1"
                >View</v-btn
              >
              <!-- <v-btn small text color="primary darken-1">Update</v-btn> -->
              <v-btn
                @click="
                  deleteData = item;
                  archiveDialog = true;
                "
                small
                text
                color="red darken-1"
                >Archive</v-btn
              >
            </v-layout>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
    <v-row class="mb-5">
      <v-col>
        <v-layout justify-space-between>
          <p>Archived Products</p>
          <v-col sm="5" md="4" lg="4">
            <v-text-field v-model="searchArchived" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" hide-details></v-text-field>
          </v-col>
        </v-layout>
        <v-data-table :headers="archivedHeaders" :items="archivedProducts" :search="searchArchived" :loading="isLoading" :loading-text="'Retrieving products data. Please wait ...'">
          <template v-slot:item.logo="{ item }">
            <v-avatar class="ma-0" size="50" color="primary" tile>
              <img class="cursor-pointer" v-if="item.product_info.image" :src="`http://127.0.0.1:8000/images/products/${item.product_info.image}`" />
              <p v-else class="white--text font-weight-bold mb-0">{{ item.name[0] }}</p>
            </v-avatar>
          </template>
          <template v-slot:item.product_info.name="{ item }">
            <p class="text-no-wrap">{{ item.product_info.name }}</p>
          </template>
          <template v-slot:item.product_info.description="{ item }">
            <p style="min-width: 220px" class="">{{ item.product_info.description }}</p>
          </template>
          <template v-slot:item.product_info.price="{ item }">
            <p class="text-no-wrap">{{ formatCurrency(item.product_info.price) }}</p>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-layout>
              <v-btn
                @click.prevent="
                  userData = item;
                  showModal();
                "
                small
                text
                color="green darken-1"
                >View</v-btn
              >
              <v-btn
                @click="
                  restoreDialog = true;
                  restoreData = item;
                "
                small
                text
                color="grey darken-1"
                >Restore</v-btn
              >
              <v-btn
                @click="
                  deleteDialog = true;
                  deleteData = item;
                "
                small
                text
                color="red darken-1"
                >Delete Forever</v-btn
              >
            </v-layout>
          </template>
        </v-data-table>
      </v-col>
    </v-row>

    <v-dialog v-model="archiveDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Confirm Action </v-card-title>
        <v-card-text class="">
          Are you sure you want to delete this product?
          <span class="red--text darken-2">Note: This account will be marked as archived to preserve any transactions that the user have.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="archiveDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="archiveProduct" :loading="isLoading"> Archive </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="deleteDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Confirm Delete </v-card-title>
        <v-card-text class="">
          Are you sure you want to permanently delete this product?
          <span class="red--text darken-2">Note: You cannot undo this action</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="deleteDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="deleteProduct" :loading="isLoading"> Delete </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="restoreDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Restore Product </v-card-title>
        <v-card-text class="">
          Are you sure you want to restore this product?
          <span class="red--text darken-2">Note: Any transactions that the product has will be restored also.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="restoreDialog = false"> Cancel </v-btn>
          <v-btn color="green " text @click="restoreProduct" :loading="isLoading"> Restore </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>
<script>
  import moment from 'moment';
  import { mapState } from 'vuex';
  import { formatCurrency } from '@/assets/js/utilities';
  export default {
    components: {},
    mixins: [formatCurrency],
    filters: {
      formatTime(val) {
        if (val) {
          return moment(val, ['hh:mm']).format('hh:mm A');
        }
      },
    },
    data: () => ({
      search: '',
      searchArchived: '',
      archiveDialog: false,
      deleteDialog: false,
      restoreDialog: false,
      deleteData: {
        id: null,
      },
      userData: {},
      restoreData: {},
      isModalVisible: false,
      isLoading: false,
      headers: [
        {
          text: 'Product Image',
          align: 'center',
          sortable: false,
          value: 'logo',
        },
        { text: 'Store', value: 'store.name' },
        { text: 'Name', value: 'product_info.name' },
        { text: 'Description', value: 'product_info.description' },
        { text: 'Price', value: 'product_info.price' },
        { text: 'Quantity', value: 'product_info.quantity' },
        { text: 'Category', value: 'product_info.category.category' },
        { text: 'Date Added', value: 'created_at' },
        { text: 'Actions', value: 'actions' },
      ],
      archivedHeaders: [
        {
          text: 'Product Image',
          align: 'center',
          sortable: false,
          value: 'logo',
        },
        { text: 'Store', value: 'store.name' },
        { text: 'Name', value: 'product_info.name' },
        { text: 'Description', value: 'product_info.description' },
        { text: 'Price', value: 'product_info.price' },
        { text: 'Quantity', value: 'product_info.quantity' },
        { text: 'Category', value: 'product_info.category.category' },
        { text: 'Date Added', value: 'created_at' },
        { text: 'Deleted On', value: 'deleted_at' },
        { text: 'Actions', value: 'actions' },
      ],
    }),
    async mounted() {
      this.isLoading = true;
      // console.log(this.$store._modules.root._children);
      await this.$store.dispatch('auth/checkUser');
      await this.getProducts();
      await this.getArchivedProducts();
      this.isLoading = false;
    },
    methods: {
      showModal() {
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
      },
      async getProducts() {
        await this.$store.dispatch('adminProduct/getProducts');
      },
      async getArchivedProducts() {
        await this.$store.dispatch('adminProduct/getArchivedProducts');
      },
      async archiveProduct() {
        const { status, data } = await this.$store.dispatch('adminProduct/archiveProduct', this.deleteData);
        this.toastData(status, data);
        this.archiveDialog = false;
        this.deleteData = null;
      },
      async deleteProduct() {
        const { status, data } = await this.$store.dispatch('adminProduct/deleteProduct', this.deleteData);
        this.toastData(status, data);
        this.deleteDialog = false;
        this.deleteData = null;
      },
      async restoreProduct() {
        const { status, data } = await this.$store.dispatch('adminProduct/restoreProduct', this.restoreData);
        this.toastData(status, data);
        this.restoreDialog = false;
        this.restoreData = null;
      },
      getData(data) {
        return data.length < 10 ? '0' + data.length : data.length;
      },
    },
    computed: {
      ...mapState('adminProduct', ['products', 'archivedProducts']),
      isFluid() {
        return this.$vuetify.breakpoint.md ? true : false | this.$vuetify.breakpoint.sm ? true : false;
      },
      noStock() {
        let products = this.products.slice().filter((prod) => prod.product_info.quantity == 0);
        return products.length;
      },
    },
  };
</script>
