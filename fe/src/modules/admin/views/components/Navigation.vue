<template>
  <div>
    <v-app-bar elevation="0" :color="$vuetify.theme.isDark ? '' : 'white'" class="pt-2 pr-2" fixed height="68">
      <v-app-bar-nav-icon class="ml-2" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-spacer></v-spacer>
      <v-btn icon @click.prevent="setDarkMode">
        <v-icon color="blue darken-2"> {{ setToggleIcon() }} </v-icon>
      </v-btn>
      <v-menu v-if="user.info" transition="slide-y-transition" :close-on-content-click="false" content-class="elevation-3" v-model="showMenu" absolute bottom left style="max-width: 450px">
        <template v-slot:activator="{ on, attrs }">
          <v-btn icon text v-on="on" v-bind="attrs" color="blue darken-2">
            <v-icon>mdi-bell-outline</v-icon>
          </v-btn>
        </template>

        <v-card elevation="0" class="pl-5 pr-5 pt-2 pb-1">
          <v-list dense>
            <v-subheader>NOTIFICATIONS</v-subheader>
          </v-list>
        </v-card>
      </v-menu>
    </v-app-bar>

    <v-navigation-drawer class="pt-4 pl-0 pr-0 pb-2" width="235" elevation="1" v-model="drawer" app>
      <p class="ml-5 mb-4 mt-2">Main Menu</p>
      <v-list dense v-for="(item, i) in items" :key="i" class="pa-0 mb-1">
        <v-list-item link :to="item.link" active-class="v-list-active--item">
          <v-list-item-content class="ml-4 pa-0">
            <v-list-item-title class="font-weight-regular">
              <v-icon size="22" class="mr-2">
                {{ item.icon }}
              </v-icon>
              {{ item.title }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <p class="ml-5 mb-4 mt-6">Logs</p>
      <v-list dense v-for="(item, i) in logItems" :key="i + 7" class="pa-0 mb-1">
        <v-list-item link :to="item.link" active-class="v-list-active--item">
          <v-list-item-content class="ml-4 pa-0">
            <v-list-item-title class="font-weight-regular">
              <v-icon size="22" class="mr-2">
                {{ item.icon }}
              </v-icon>
              {{ item.title }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <p class="ml-5 mb-4 mt-7">Config</p>
      <v-list dense v-for="(item, i) in accItems" :key="i + 11" class="pa-0 mb-1">
        <v-list-item link :to="item.link" active-class="v-list-active--item">
          <v-list-item-content class="ml-4 pa-0">
            <v-list-item-title class="font-weight-regular">
              <v-icon size="22" class="mr-2">
                {{ item.icon }}
              </v-icon>
              {{ item.title }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <template v-slot:append>
        <v-list-item two-line class="mb-2">
          <div class="position-relative">
            <v-avatar color="grey" size="50">
              <img v-if="user.info.profile_img" :src="'http://127.0.0.1:8000/images/profiles/' + user.info.profile_img" alt="Profile Image" />
              <v-icon x-large color="white" v-else> mdi-account-circle </v-icon>
            </v-avatar>
            <div class="upload-image cursor-pointer v-btn">
              <label for="uploadimg" class="cursor-pointer">
                <v-icon small color="white">mdi-camera</v-icon>
              </label>
              <input type="file" id="uploadimg" @change="uploadProfileImage" />
            </div>
          </div>

          <v-list-item-content class="ml-2">
            <v-list-item-title>
              <p class="mb-0 font-weight-bold">{{ `${user.info.last_name}, ${user.info.first_name}` }}</p>
            </v-list-item-title>
            <v-list-item-subtitle>
              <v-btn @click.stop="logoutDialog = true" small color="red darken-2" text class="pa-0">
                <v-icon small> mdi-exit-to-app </v-icon>
                Log-out
              </v-btn>
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </template>
    </v-navigation-drawer>

    <v-dialog v-model="logoutDialog" max-width="500">
      <v-card>
        <v-card-title class="text-h5"> Confirm Log-out </v-card-title>
        <h4 class="font-weight-light ml-6 mr-5 mb-5">Are you sure you want to logout your account?</h4>
        <v-card-actions class="pb-2">
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" large text @click="logoutDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" large text @click="logout" :loading="btnLoading"> Logout </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import API from '@/store/base/base';
  import { mapState } from 'vuex';
  export default {
    data: () => ({
      drawer: true,
      group: null,
      showMenu: false,
      logoutDialog: false,
      btnLoading: false,
      items: [
        { title: 'Dashboard', icon: 'mdi-chart-pie', link: '/admin/dashboard' },
        { title: 'Users', icon: 'mdi-account-supervisor-circle', link: '/admin/users' },
        { title: 'Stores', icon: 'mdi-store-check-outline', link: '/admin/stores', role: '/admin/stores' },
        { title: 'Products', icon: 'mdi-package-variant-closed', link: '/admin/products' },
        { title: 'Partners', icon: 'mdi-handshake-outline', link: '/admin/partners' },
        { title: 'Categories', icon: 'mdi-tag-multiple-outline', link: '/admin/categories' },
        { title: 'Riders', icon: 'mdi-bike-fast', link: '/admin/riders' },
      ],
      logItems: [
        { title: 'Orders', icon: 'mdi-truck-delivery-outline', link: '/admin/orders' },
        { title: 'Purchase History', icon: 'mdi-clipboard-text-clock-outline', link: '/admin/purchase-history' },
        { title: 'Logs', icon: 'mdi-history', link: '/admin/logs' },
      ],
      accItems: [
        { title: 'Account Settings', icon: 'mdi-cog', link: '/admin/account-settings' },
        // { title: 'Purchase History', icon: 'mdi-clipboard-text-clock-outline', link: '/admin/purchase-history' },
        { title: 'Account Logs', icon: 'mdi-history', link: '/admin/logs' },
      ],
      overwriteBreakpoint: false,
    }),
    async mounted() {
      console.log(this.$vuetify.theme.isDark)
    },
    methods: {
      async logout() {
        this.btnLoading = true;
        const { status, data } = await this.$store.dispatch('auth/logout');
        this.toastData(status, data);
        this.btnLoading = false;
        this.$router.push('/');
        this.logoutDialog = false;
      },
      async setDarkMode(){
        const darkMode = !JSON.parse(localStorage.getItem('darkMode'))
        this.$vuetify.theme.dark = darkMode
        localStorage.setItem('darkMode', darkMode)
      },
      setToggleIcon(){
        return JSON.parse(localStorage.getItem('darkMode')) ? 'mdi-weather-sunny' : 'mdi-weather-night'
      },
      async uploadProfileImage(event) {
        let formData = new FormData();
        formData.append('img', event.target.files[0]);
        await API.post(`update-profile`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })
          .then((response) => {
            this.toastData(200, { msg: 'Profile image updated successfully!' });
          })
          .catch((error) => {
            console.log({ error });
          });
        await this.$store.dispatch('auth/checkUser');
      },
    },
    computed: {
      ...mapState('auth', ['user']),
    },
    watch: {
      group() {
        this.drawer = false;
      },
    },
  };
</script>
<style>
  .v-list .v-list-active--item {
    /* background-color: #9c5353; */
    color: #3b85df;
    position: relative;
  }

  .v-list-active--item.v-list-item--link:before {
    content: '';
    opacity: 1;
    background: #3b85df;
    position: absolute;
    top: 0;
    width: 4px;
    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
  }

  .v-navigation-drawer__content {
    width: 100%;
  }

  .v-toolbar__content {
    padding-bottom: 1rem !important;
  }
</style>
