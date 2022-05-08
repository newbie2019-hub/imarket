const ForgotPassword = () => import("./views/ForgotPassword.vue");
const Register = () => import("./views/Register.vue");
const Login = () => import("./views/Login.vue");

const moduleRoute = [
  {
    path: "/login",
    name: 'Login',
    component: Login,
    meta: {
      title: 'Login Account',
      requiresAuth: false
    }
  },
  {
    path: "/register",
    name: 'Register',
    component: Register,
    meta: {
      title: 'Register Account',
      requiresAuth: false
    }
  },
  {
    path: "/forgot-password",
    name: 'Forgot Password',
    component: ForgotPassword,
    meta: {
      title: 'Forgot Password'
    }
  },
  {
    path: '/partnership',
    name: 'Partnership',
    component: () => import(/* webpackChunkName: "partership" */ './views/Partnership.vue'),
    meta: {
      title: 'Market - Partnership program',
      requiresAuth: false
    }
  },
];

export default router => {
  router.addRoutes(moduleRoute);
};
