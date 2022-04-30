<template>
  <nav>
    <div class="logo">
      <p>IMarket</p>
    </div>
    <ul>
      <li>
        <a href="">Market</a>
      </li>
      <li>
        <a href="">Seller</a>
      </li>
      <li>
        <a href="">Contact</a>
      </li>
    </ul>
    <div class="login">
      <v-btn v-if="$route.path == '/'" link to="/login" class="white--text rounded-pill" color="orange darken-2" depressed large> Login </v-btn>
      <v-btn v-else link to="/" class="white--text rounded-pill" color="orange darken-2" depressed large> Home </v-btn>
    </div>
  </nav>
</template>
<script>
  export default {
    data: () => ({
      name: ''
    }),
    mounted() {},
    methods: {},
    computed: {},
    watch: {},
  };
</script>

<style lang="scss">
  .logo p {
    font-size: 1.4rem;
    font-weight: 600;
  }

  nav {
    position: fixed;
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
