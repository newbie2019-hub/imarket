const Index = () => import("./views/Index.vue");

const moduleRoute = [
  {
    path: "/dashboard",
    name: 'Index',
    component: Index,
    meta: {
      title: 'IMarket - Ordering from the market has been made easy'
    }
  },
];

export default router => {
  router.addRoutes(moduleRoute);
};
