<template>
  <v-container :class="{ 'pa-0': $vuetify.breakpoint.md }" :fluid="isFluid">
    <div class="mt-15">
      <p class="text-h5 font-weight-bold custom-primary-color pt-5 mb-0">Categories Management</p>
      <p>Manage the store categories from this section</p>
    </div>
    <v-row class="mt-5 mb-5">
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="blue darken-1" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-car-brake-retarder </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Total <br />Categories</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ archivedCategories.length + categories.length }}</p>
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
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Archived <br />Categories</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ archivedCategories.length }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>
    <v-row class="mb-5">
      <v-col>
        <v-layout justify-space-between>
          <p>Active Categories</p>
          <v-col sm="5" md="4" lg="4">
            <v-text-field v-model="search" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" hide-details></v-text-field>
          </v-col>
        </v-layout>
        <v-data-table class="" :headers="headers" :items="categories" :search="search" :loading="isLoading || tableUpdating" :loading-text="'Retrieving categories data. Please wait ...'">
          <template v-slot:item.category="props">
            <v-edit-dialog
              class=""
              :return-value.sync="props.item.category"
              @open="
                data.id = props.item.id;
                data.category = props.item.category;
                data.prev_category = props.item.category;
              "
              @close="closeEditField"
            >
              {{ props.item.category }}
              <template v-slot:input class="pb-2 pt-2">
                <v-text-field v-model="data.category" :rules="required" label="Update" counter></v-text-field>
              </template>
            </v-edit-dialog>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-layout>
              <!-- <v-btn small text color="green darken-1">Update</v-btn> -->
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
          <p>Archived Categories</p>
          <v-col sm="5" md="4" lg="4">
            <v-text-field v-model="searchArchived" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" hide-details></v-text-field>
          </v-col>
        </v-layout>
        <v-data-table :headers="archivedHeaders" :items="archivedCategories" :search="searchArchived" :loading="isLoading" :loading-text="'Retrieving categories data. Please wait ...'">
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
          Are you sure you want to delete this category?
          <span class="red--text darken-2">Note: This account will be marked as archived to preserve any transactions that the user have.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="archiveDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="archiveCategory" :loading="isLoading"> Archive </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="deleteDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Confirm Delete </v-card-title>
        <v-card-text class="">
          Are you sure you want to permanently delete this category?
          <span class="red--text darken-2">Note: You cannot undo this action</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="deleteDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="deleteCategory" :loading="isLoading"> Delete </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="restoreDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Restore Account </v-card-title>
        <v-card-text class="">
          Are you sure you want to restore this category?
          <span class="red--text darken-2">Note: Any transactions that the user has will be restored also.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="restoreDialog = false"> Cancel </v-btn>
          <v-btn color="green " text @click="restoreCategory" :loading="isLoading"> Restore </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>
<script>
  import moment from 'moment';
  import { mapState } from 'vuex';
  import { rules } from '@/assets/js/rules';
  export default {
    components: {},
    mixins: [rules],
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
      data: {
        prev_category: '',
        category: '',
        id: null,
      },
      userData: {},
      restoreData: {},
      isModalVisible: false,
      isLoading: false,
      tableUpdating: false,
      headers: [
        {
          text: 'Icon',
          align: 'start',
          sortable: false,
          value: 'icon',
        },
        { text: 'Category', value: 'category' },
        { text: 'Products', value: 'product_count' },
        { text: 'Date Created', value: 'created_at' },
        { text: 'Updated At', value: 'updated_at' },
        { text: 'Actions', value: 'actions' },
      ],
      archivedHeaders: [
        {
          text: 'Icon',
          align: 'start',
          sortable: false,
          value: 'icon',
        },
        {
          text: 'Logo',
          align: 'start',
          sortable: false,
          value: 'logo',
        },
        { text: 'Category', value: 'category' },
        { text: 'Products', value: 'product_count' },
        { text: 'Date Created', value: 'created_at' },
        { text: 'Deleted On', value: 'deleted_at' },
        { text: 'Updated On', value: 'updated_at' },
        { text: 'Actions', value: 'actions' },
      ],
    }),
    async mounted() {
      this.isLoading = true;
      await this.getCategories();
      await this.getArchivedCategories();
      this.isLoading = false;
    },
    methods: {
      async closeEditField() {
        console.log('Edit field closed!');
        if (this.data.category.trim() == '') return;
        if (this.data.category != this.data.prev_category) {
          this.tableUpdating = true;
          const { status, data } = await this.$store.dispatch('adminCategory/updateCategory', this.data);
          this.toastData(status, data);
          this.tableUpdating = false;
        }
      },
      showModal() {
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
      },
      async getCategories() {
        await this.$store.dispatch('adminCategory/getCategories');
      },
      async getArchivedCategories() {
        await this.$store.dispatch('adminCategory/getArchivedCategories');
      },
      async archiveCategory() {
        const { status, data } = await this.$store.dispatch('adminCategory/archiveCategory', this.deleteData);
        this.toastData(status, data);
        this.archiveDialog = false;
        this.deleteData = null;
      },
      async deleteCategory() {
        const { status, data } = await this.$store.dispatch('adminCategory/deleteCategory', this.deleteData);
        this.toastData(status, data);
        this.deleteDialog = false;
        this.deleteData = null;
      },
      async restoreCategory() {
        const { status, data } = await this.$store.dispatch('adminCategory/restoreCategory', this.restoreData);
        this.toastData(status, data);
        this.restoreDialog = false;
        this.restoreData = null;
      },
      getData(data) {
        return data.length < 10 ? '0' + data.length : data.length;
      },
    },
    computed: {
      ...mapState('adminCategory', ['categories', 'archivedCategories']),
      isFluid() {
        return this.$vuetify.breakpoint.md ? true : false | this.$vuetify.breakpoint.sm ? true : false;
      },
    },
  };
</script>
<style>
  .v-small-dialog__content {
    padding-top: 1rem;
    padding-bottom: 0.5rem;
  }
</style>
