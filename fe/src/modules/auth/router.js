const ForgotPassword = () => import("./views/ForgotPassword.vue");
const Register = () => import("./views/Register.vue");
const Login = () => import("./views/Login.vue");

const moduleRoute = [
  {
    path: "/login",
    name: 'Login',
    component: Login,
    meta: {
      title: 'Login Account'
    }
  },
  {
    path: "/register",
    name: 'Register',
    component: Register,
    meta: {
      title: 'Register Account'
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
];

export default router => {
  router.addRoutes(moduleRoute);
};
