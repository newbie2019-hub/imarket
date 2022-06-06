<template>
  <v-container :class="{ 'pa-0': $vuetify.breakpoint.md }" :fluid="isFluid">
    <div class="mt-15">
      <p class="text-h5 font-weight-bold custom-primary-color mt-8 mb-0">Stores Management</p>
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
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Total <br />Stores</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ formatNumeric(stores.length + archivedStores.length) }}</p>
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
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Archived <br />Stores</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ formatNumeric(archivedStores.length) }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>
    <v-row class="mb-5">
      <v-col>
        <v-layout justify-space-between>
          <p>Active Stores</p>
          <v-col sm="5" md="4" lg="4">
            <v-text-field v-model="search" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" hide-details></v-text-field>
          </v-col>
        </v-layout>
        <v-data-table class="" :headers="headers" :items="stores" :search="search" :loading="isLoading" :loading-text="'Retrieving stores data. Please wait ...'">
          <template v-slot:item.logo="{ item }">
            <v-avatar class="ma-0" size="45" color="primary">
              <img class="cursor-pointer" v-if="item.logo" :src="`http://127.0.0.1:8000/images/logos/${item.logo}`" />
              <p v-else class="white--text font-weight-bold mb-0">{{ item.name[0] }}</p>
            </v-avatar>
          </template>
          <template v-slot:item.week_days_opening="{ item }">
            <p class="text-no-wrap">{{ item.week_days_opening | formatTime }}</p>
          </template>
          <template v-slot:item.week_days_closing="{ item }">
            <p class="text-no-wrap">{{ item.week_days_closing | formatTime }}</p>
          </template>
          <template v-slot:item.week_end_opening="{ item }">
            <p class="text-no-wrap">{{ item.week_end_opening | formatTime }}</p>
          </template>
          <template v-slot:item.week_end_closing="{ item }">
            <p class="text-no-wrap">{{ item.week_end_closing | formatTime }}</p>
          </template>
          <template v-slot:item.description="{ item }">
            <p style="min-width: 220px" class="">{{ item.description }}</p>
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
              <v-btn small text color="primary darken-1">Update</v-btn>
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
          <p>Archived Users</p>
          <v-col sm="5" md="4" lg="4">
            <v-text-field v-model="searchArchived" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" hide-details></v-text-field>
          </v-col>
        </v-layout>
        <v-data-table :headers="archivedHeaders" :items="archivedStores" :search="searchArchived" :loading="isLoading" :loading-text="'Retrieving users data. Please wait ...'">
          <template v-slot:item.logo="{ item }">
            <v-avatar class="ma-0" size="45" color="primary">
              <img class="cursor-pointer" @click.prevent="showProfile(item.logo)" v-if="item.logo" :src="`http://127.0.0.1:8000/images/logos/${item.logo}`" />
              <p v-else class="white--text font-weight-bold mb-0">{{ item.name[0] }}</p>
            </v-avatar>
          </template>
          <template v-slot:item.description="{ item }">
            <p style="min-width: 220px" class="">{{ item.description }}</p>
          </template>
          <template v-slot:item.week_days_opening="{ item }">
            <p class="text-no-wrap">{{ item.week_days_opening | formatTime }}</p>
          </template>
          <template v-slot:item.week_days_closing="{ item }">
            <p class="text-no-wrap">{{ item.week_days_closing | formatTime }}</p>
          </template>
          <template v-slot:item.week_end_opening="{ item }">
            <p class="text-no-wrap">{{ item.week_end_opening | formatTime }}</p>
          </template>
          <template v-slot:item.week_end_closing="{ item }">
            <p class="text-no-wrap">{{ item.week_end_closing | formatTime }}</p>
          </template>
          <template v-slot:item.description="{ item }">
            <p style="min-width: 220px" class="">{{ item.description }}</p>
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
          Are you sure you want to delete this user account?
          <span class="red--text darken-2">Note: This account will be marked as archived to preserve any transactions that the user have.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="archiveDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="archiveStore" :loading="isLoading"> Archive </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="deleteDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Confirm Delete </v-card-title>
        <v-card-text class="">
          Are you sure you want to permanently delete this user account?
          <span class="red--text darken-2">Note: You cannot undo this action</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="deleteDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="deleteStore" :loading="isLoading"> Delete </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="restoreDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Restore Account </v-card-title>
        <v-card-text class="">
          Are you sure you want to restore this user account?
          <span class="red--text darken-2">Note: Any transactions that the user has will be restored also.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="restoreDialog = false"> Cancel </v-btn>
          <v-btn color="green " text @click="restoreStore" :loading="isLoading"> Restore </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>
<script>
  import moment from 'moment';
  import { mapState } from 'vuex';
  import { formatNumeric } from '@/assets/js/utilities.js';
  export default {
    components: {},
    mixins: [formatNumeric],
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
          text: 'Logo',
          align: 'start',
          sortable: false,
          value: 'logo',
        },
        { text: 'Store Name', value: 'name' },
        { text: 'Description', value: 'description' },
        { text: 'Address', value: 'address' },
        { text: 'Week Days Opening', value: 'week_days_opening' },
        { text: 'Week Days Closing', value: 'week_days_closing' },
        { text: 'Week End Opening', value: 'week_end_opening' },
        { text: 'Week End Closing', value: 'week_end_closing' },
        { text: 'Status', value: 'status' },
        { text: 'Date Created', value: 'created_at' },
        { text: 'Actions', value: 'actions' },
      ],
      archivedHeaders: [
        {
          text: 'Logo',
          align: 'start',
          sortable: false,
          value: 'logo',
        },
        { text: 'Store Name', value: 'name' },
        { text: 'Description', value: 'description' },
        { text: 'Address', value: 'address' },
        { text: 'Week Days Opening', value: 'week_days_opening' },
        { text: 'Week Days Closing', value: 'week_days_closing' },
        { text: 'Week End Opening', value: 'week_end_opening' },
        { text: 'Week End Closing', value: 'week_end_closing' },
        { text: 'Status', value: 'status' },
        { text: 'Date Created', value: 'created_at' },
        { text: 'Deleted On', value: 'deleted_at' },
        { text: 'Actions', value: 'actions' },
      ],
    }),
    async mounted() {
      this.isLoading = true;
      // console.log(this.$store._modules.root._children);
      await this.$store.dispatch('auth/checkUser');
      await this.getStores();
      await this.getArchivedStores();
      this.isLoading = false;
    },
    methods: {
      showModal() {
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
      },
      async getStores() {
        await this.$store.dispatch('adminStore/getStores');
      },
      async getArchivedStores() {
        await this.$store.dispatch('adminStore/getArchivedStores');
      },
      async archiveStore() {
        const { status, data } = await this.$store.dispatch('adminStore/archiveStore', this.deleteData);
        this.toastData(status, data);
        this.archiveDialog = false;
        this.deleteData = null;
      },
      async deleteStore() {
        const { status, data } = await this.$store.dispatch('adminStore/deleteStore', this.deleteData);
        this.toastData(status, data);
        this.deleteDialog = false;
        this.deleteData = null;
      },
      async restoreStore() {
        const { status, data } = await this.$store.dispatch('adminStore/restoreStore', this.restoreData);
        this.toastData(status, data);
        this.restoreDialog = false;
        this.restoreData = null;
      },
      getData(data) {
        return data.length < 10 ? '0' + data.length : data.length;
      },
    },
    computed: {
      ...mapState('adminStore', ['stores', 'archivedStores']),
      isFluid() {
        return this.$vuetify.breakpoint.md ? true : false | this.$vuetify.breakpoint.sm ? true : false;
      },
    },
  };
</script>
