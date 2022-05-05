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
        <v-layout v-if="$route.path == '/imarket'" class="pr-5">
          <cart-drop-down />
          <!-- <v-badge overlap color="primary" :content="cart_count && cart_count.cart_info_count">
            <v-btn @click.prevent="showCart" text color="orange darken-2">
              <v-icon> mdi-cart </v-icon>
              Cart
            </v-btn>
          </v-badge> -->
        </v-layout>
        <v-btn v-if="!user.info" link to="/login" class="white--text rounded-pill" color="orange darken-2" depressed large> Login </v-btn>
        <v-btn v-else link to="/" class="white--text rounded-pill" color="orange darken-2" depressed large> Home </v-btn>
      </div>
    </nav>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  import CartDropDown from './CartDropDown.vue';
  export default {
    data: () => ({
      name: '',
      isCartVisible: false,
    }),
    components: { CartDropDown },
    async mounted() {
      if (this.$route.path == '/imarket') {
        await this.$store.dispatch('market/cartCount');
      }
    },
    methods: {
      // showCart() {
      //   this.isCartVisible = true;
      // },
      // closeCart() {
      //   this.isCartVisible = false;
      // },
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
</style>
