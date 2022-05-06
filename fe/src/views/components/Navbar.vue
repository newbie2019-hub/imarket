<template>
  <div>
    <nav>
      <div class="logo">
        <router-link to="/" class="text-decoration-none font-weight-bold text-h5 black--text">IMarket</router-link>
      </div>
      <ul>
        <li>
          <router-link to="/imarket">Market</router-link>
        </li>
        <li>
          <a href="">Seller</a>
        </li>
        <li>
          <a href="">Contact</a>
        </li>
      </ul>
      <div class="login d-flex align-center">
        <v-layout v-if="user.info" class="pr-5">
          <cart-drop-down />
        </v-layout>
        <v-btn v-if="!user.info && $route.path != '/login'" link to="/login" class="white--text rounded-pill" color="orange darken-2" depressed large> Login </v-btn>
        <!-- <v-btn v-if="!user.info && $route.path != '/'" link to="/" class="white--text rounded-pill" color="orange darken-2" depressed large> Home </v-btn> -->
        <v-menu v-if="user.info" transition="slide-y-transition" :close-on-content-click="false" content-class="elevation-3" v-model="showMenu" absolute bottom left style="max-width: 420px">
          <template v-slot:activator="{ on, attrs }">
            <v-avatar color="" v-on="on" v-bind="attrs">
              <img v-if="user.info.profile_img != '' && user.info.profile_img != null" :src="'http://127.0.0.1:8000/images/profiles/' + user.info.profile_img" alt="Profile" />
              <v-icon x-large color="primary" v-else> mdi-account-circle </v-icon>
            </v-avatar>
          </template>

          <v-card elevation="0" class="pl-5 pr-5 pt-2 pb-1">
            <v-layout column align-center justify-center class="mt-3">
              <v-layout align-center justify-center class="position-relative">
                <v-avatar color="grey" size="80">
                  <img v-if="user.info.profile_img" :src="'http://127.0.0.1:8000/images/profiles/' + user.info.profile_img" alt="John" />
                  <v-icon x-large color="white" v-else> mdi-account-circle </v-icon>
                </v-avatar>
                <div class="upload-image cursor-pointer v-btn">
                  <label for="uploadimg" class="cursor-pointer">
                    <v-icon small color="white">mdi-camera</v-icon>
                  </label>
                  <input type="file" id="uploadimg" @change="uploadProfileImage" />
                </div>
              </v-layout>
              <v-list two-line class="lh-xsmall">
                <p class="mb-0 text-center mt-3">{{ user.info.last_name }}, {{ user.info.first_name }}</p>
                <p class="text-center grey--text">
                  <small>{{ user.email }}</small>
                </p>
              </v-list>
            </v-layout>
            <v-divider></v-divider>
            <v-list dense>
              <v-subheader>MENU</v-subheader>
              <v-list-item link to="/cart-checkout">
                <v-list-item-icon>
                  <v-icon small>mdi-cart</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>View Cart</v-list-item-title>
                </v-list-item-content>
              </v-list-item>

              <v-list-item link to="/orders">
                <v-list-item-icon>
                  <v-icon small>mdi-package</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>My Orders</v-list-item-title>
                </v-list-item-content>
              </v-list-item>

              <v-list-item link to="/account-settings">
                <v-list-item-icon>
                  <v-icon small>mdi-cog</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>Account Settings</v-list-item-title>
                </v-list-item-content>
              </v-list-item>

              <v-list-item link @click.prevent="logoutDialog = true">
                <v-list-item-icon>
                  <v-icon small>mdi-location-exit</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>Logout</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card>
        </v-menu>
      </div>
    </nav>

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
  import API from '../../store/base/base';
  import { mapState } from 'vuex';
  import CartDropDown from './CartDropDown.vue';
  export default {
    data: () => ({
      name: '',
      isCartVisible: false,
      showMenu: false,
      logoutDialog: false,
      btnLoading: false,
    }),
    components: { CartDropDown },
    async mounted() {
      if (this.$route.path == '/imarket') {
        await this.$store.dispatch('market/cartCount');
      }
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
      ...mapState('market', ['cart_count']),
      ...mapState('auth', ['user']),
    },
    watch: {},
  };
</script>

<style lang="scss">
  .logo p {
    font-size: 1.4rem;
    font-weight: 600;
  }

  nav {
    position: relative;
    width: 100%;
    padding: 2rem 2.8rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 5;

    & ul {
      display: flex;
      list-style: none;

      & li {
        padding: 0 1rem;

        & a {
          text-decoration: none;
          color: rgb(29, 29, 29) !important;
          position: relative;
          // transition: all 250ms ease-in-out;
          text-transform: uppercase;
          font-weight: 600;

          &:hover {
            color: #ff9800 !important;
          }

          &:hover:before {
            width: 100%;
            transform-origin: right;
          }

          &:before {
            content: '';
            position: absolute;
            width: 0;
            bottom: -5px;
            left: 0;
            height: 2.5px;
            background-color: #ff9800 !important;
            transition: all 250ms ease-in-out;
          }
        }
      }
    }
  }

  .upload-image {
    position: absolute;
    bottom: -5px;
    right: 0;
    background: #1976d2f5;
    border-radius: 50%;
    padding: 0.2rem 0.35rem;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .v-list-item__icon {
    margin-right: 18px !important;
  }
</style>
