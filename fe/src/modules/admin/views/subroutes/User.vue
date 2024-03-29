<template>
  <v-container :class="{ 'pa-0': $vuetify.breakpoint.md }" :fluid="isFluid">
    <div class="mt-15">
      <p class="text-h5 font-weight-bold custom-primary-color mt-8 mb-0">Accounts Management</p>
      <p>You can manage the accounts of the users on the table</p>
    </div>
    <v-row class="mt-5 mb-5">
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="blue darken-1" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-car-brake-retarder </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Active <br />Users</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ users.length }}</p>
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
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Archived <br />Users</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ formatNumeric(archivedUsers.length) }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="green " class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-account </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">New <br />Users</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ formatNumeric(newUsersCount) }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>
    <v-row class="mb-5">
      <v-col>
        <v-layout justify-space-between>
          <p>Active Users</p>
          <v-col sm="5" md="4" lg="4">
            <v-text-field v-model="search" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" hide-details></v-text-field>
          </v-col>
        </v-layout>
        <v-data-table :headers="headers" :items="users" :search="search" :loading="isLoading" :loading-text="'Retrieving users data. Please wait ...'">
          <template v-slot:item.profile="{ item }">
            <v-avatar class="ma-0" size="45" color="primary">
              <img class="cursor-pointer" v-if="item.info.profile_img" :src="`http://127.0.0.1:8000/images/profiles/${item.info.profile_img}`" />
              <p v-else class="white--text font-weight-bold mb-0">{{ item.info.first_name[0] }}{{ item.info.last_name[0] }}</p>
            </v-avatar>
          </template>
          <template v-slot:item.birthday="{ item }">
            <p class="text-no-wrap">{{ item.info.birthday }}</p>
          </template>
          <template v-slot:item.info.full_name="{ item }">
            <p class="text-no-wrap">{{ item.info.full_name }}</p>
          </template>
          <template v-slot:item.info.contact_number="{ item }">
            <p class="text-no-wrap">{{ item.info.contact_number }}</p>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-layout>
              <v-btn @click.prevent="showModal(item)" small text color="green darken-1">View</v-btn>
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
          <p>Archived Users</p>
          <v-col sm="5" md="4" lg="4">
            <v-text-field v-model="searchArchived" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" hide-details></v-text-field>
          </v-col>
        </v-layout>
        <v-data-table :headers="archivedHeaders" :items="archivedUsers" :search="searchArchived" :loading="isLoading" :loading-text="'Retrieving users data. Please wait ...'">
          <template v-slot:item.profile="{ item }">
            <v-avatar class="ma-0" size="45" color="primary">
              <img class="cursor-pointer" @click.prevent="showProfile(item.info.profile_img)" v-if="item.info.profile_img" :src="`http://127.0.0.1:8000/images/profiles/${item.info.profile_img}`" />
              <p v-else class="white--text font-weight-bold mb-0">{{ item.info.first_name[0] }}{{ item.info.last_name[0] }}</p>
            </v-avatar>
          </template>
          <template v-slot:item.info.full_name="{ item }">
            <p class="text-no-wrap">{{ item.info.full_name }}</p>
          </template>
          <template v-slot:item.info.contact_number="{ item }">
            <p class="text-no-wrap">{{ item.info.contact_number }}</p>
          </template>
          <template v-slot:item.birthday="{ item }">
            <p class="text-no-wrap">{{ item.info.birthday }}</p>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-layout>
              <v-btn @click.prevent="showModal(item)" small text color="green darken-1">View</v-btn>
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
              <!-- <v-btn
                @click="
                  deleteDialog = true;
                  deleteData = item;"
                small
                text
                color="red darken-1"
                >Delete Forever</v-btn> -->
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
          <v-btn color="red darken-1" text @click="archiveUser" :loading="isLoading"> Archive </v-btn>
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
          <v-btn color="red darken-1" text @click="deleteUser" :loading="isLoading"> Delete </v-btn>
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
          <v-btn color="green " text @click="restoreUser" :loading="isLoading"> Restore </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="userInfoDialog" max-width="460">
      <v-card class="pt-2 pl-4 pr-4 pb-2">
        <v-layout align-center justify-center>
          <v-card-text>
            <v-layout justify-center>
              <v-avatar class="ma-0 mt-2" size="150" color="primary">
                <img
                  class="cursor-pointer"
                  @click.prevent="showProfile(userSelectedData.info.profile_img)"
                  v-if="userSelectedData.info.profile_img"
                  :src="`http://127.0.0.1:8000/images/profiles/${userSelectedData.info.profile_img}`"
                />
                <p v-else class="white--text font-weight-bold mb-0">{{ userSelectedData.info.first_name[0] }}{{ userSelectedData.info.last_name[0] }}</p>
              </v-avatar>
            </v-layout>
            <p class="text-h5 mt-5 mb-0 font-weight-bold custom-primary-color text-center">{{ userSelectedData.info.first_name }} {{ userSelectedData.info.last_name }}</p>
            <p class="grey--text text-center">{{userSelectedData.email}}</p>
          </v-card-text>
        </v-layout>
        <v-layout class="pl-3 pr-3">
          <v-col>
            <p><span class="font-weight-bold">Contact:</span> {{ userSelectedData.info.contact_number }}</p>
          </v-col>
          <v-col>
            <p><span class="font-weight-bold">Date of Birth:</span><br/> {{ userSelectedData.info.birthday }}</p>
          </v-col>
          <v-col>
            <p><span class="font-weight-bold">Gender:</span><br/> {{ userSelectedData.info.gender }}</p>
          </v-col>
        </v-layout>
        <v-layout class="pl-3 pr-4">
          <v-col>
            <p><span class="font-weight-bold">Addresss:</span><br/> {{ userSelectedData.address && userSelectedData.address.formatted_address ? userSelectedData.address.formatted_address : 'Address has not been set yet.' }}</p>
          </v-col>
          <v-col>
            <p><span class="font-weight-bold">Created On:</span><br/> {{ userSelectedData.created_at }}</p>
          </v-col>
        </v-layout>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-1" text @click="userInfoDialog = false"> Close </v-btn>
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
    data: () => ({
      search: '',
      searchArchived: '',
      archiveDialog: false,
      deleteDialog: false,
      restoreDialog: false,
      userInfoDialog: false,
      userSelectedData: {
        email: '',
        address: {
          formatted_address:''
        },
        info: {
          image: '',
          first_name: '',
          last_name: '',
          middle_name: '',
          gender: '',
          contact_number: '',
          birthday: '',
        },
      },
      deleteData: {
        id: null,
      },
      userData: {},
      restoreData: {},
      isModalVisible: false,
      isLoading: false,
      headers: [
        {
          text: 'Profile',
          align: 'start',
          sortable: false,
          value: 'profile',
        },
        { text: 'First Name', value: 'info.first_name', align: ' d-none' },
        { text: 'Last Name', value: 'info.last_name', align: ' d-none' },
        { text: 'Full Name', value: 'info.full_name' },
        { text: 'Role', value: 'roles[0].name' },
        { text: 'Email', value: 'email' },
        { text: 'Gender', value: 'info.gender' },
        { text: 'Date of Birth', value: 'birthday' },
        { text: 'Contact Number', value: 'info.contact_number' },
        { text: 'Date Registered', value: 'created_at' },
        { text: 'Actions', value: 'actions' },
      ],
      archivedHeaders: [
        {
          text: 'Profile',
          align: 'start',
          sortable: false,
          value: 'profile',
        },
        { text: 'First Name', value: 'info.first_name', align: ' d-none' },
        { text: 'Last Name', value: 'info.last_name', align: ' d-none' },
        { text: 'Full Name', value: 'info.full_name' },
        { text: 'Role', value: 'roles[0].name' },
        { text: 'Email', value: 'email' },
        { text: 'Gender', value: 'info.gender' },
        { text: 'Date of Birth', value: 'birthday' },
        { text: 'Contact Number', value: 'info.contact_number' },
        { text: 'Date Registered', value: 'created_at' },
        { text: 'Deleted On', value: 'deleted_at' },
        { text: 'Actions', value: 'actions' },
      ],
    }),
    async mounted() {
      this.isLoading = true;
      // console.log(this.$store._modules.root._children);
      await this.$store.dispatch('auth/checkUser');
      await this.getUsers();
      await this.getArchivedUsers();
      this.isLoading = false;
    },
    methods: {
      showModal(data) {
        this.userInfoDialog = true;
        this.userSelectedData = data;
      },
      closeModal() {
        this.isModalVisible = false;
      },
      async getUsers() {
        await this.$store.dispatch('adminUser/getUsers');
      },
      async getArchivedUsers() {
        await this.$store.dispatch('adminUser/getArchivedUsers');
      },
      async archiveUser() {
        const { status, data } = await this.$store.dispatch('adminUser/archiveUser', this.deleteData);
        this.toastData(status, data);
        this.archiveDialog = false;
        this.deleteData = null;
      },
      async deleteUser() {
        const { status, data } = await this.$store.dispatch('adminUser/deleteUser', this.deleteData);
        this.toastData(status, data);
        this.deleteDialog = false;
        this.deleteData = null;
      },
      async restoreUser() {
        const { status, data } = await this.$store.dispatch('adminUser/restoreUser', this.restoreData);
        this.toastData(status, data);
        this.restoreDialog = false;
        this.restoreData = null;
      },
      getData(data) {
        return data.length < 10 ? '0' + data.length : data.length;
      },
      newUsers() {
        let count = 0;
        this.users.map((user) => {
          if (moment(user.created_at).format('YYYY-MM-DD') == moment().format('YYYY-MM-DD')) {
            // console.log(moment(user.created_at).format('YYYY-MM-DD'));
            // console.log('Date Now', moment().format('YYYY-MM-DD'));
            count++;
          }
        });
        return count < 10 ? '0' + count : count;
      },
    },
    computed: {
      ...mapState('adminUser', ['users', 'archivedUsers', 'newUsersCount']),
      isFluid() {
        return this.$vuetify.breakpoint.md ? true : false | this.$vuetify.breakpoint.sm ? true : false;
      },
    },
  };
</script>
