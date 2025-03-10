import { createRouter, createWebHistory } from "vue-router";
import AppLayout from "../components/AppLayout.vue";
import Dashboard from "../views/Dashboard/Dashboard.vue";
import Login from "../views/Login.vue";
import ForgotPassword from "../views/ForgotPassword.vue";
import ResetPassword from "../views/ResetPassword.vue";
import Products from "../views/Products/Products.vue";
import CreateProduct from "../views/Products/CreateProduct.vue";
import EditProduct from "../views/Products/EditProduct.vue";
import Orders from "../views/Orders/Orders.vue";
import NotFound from "../views/NotFound.vue";
import store from "../store";
import OrderDetails from "../views/Orders/OrderDetails.vue";
import Users from "../views/Users/Users.vue";
import Customers from "../views/Customers/Customers.vue";
import Reports from "../views/Reports/Reports.vue";

const routes = [
  {
    path: "/app",
    name: "app",
    redirect: "/app/dashboard",
    component: AppLayout,
    meta: {
      requiresAuth: true,
    },
    children: [
      {
        path: "dashboard",
        name: "app.dashboard",
        component: Dashboard,
      },
      {
        path: "products",
        name: "app.products",
        component: Products,
      },
      {
        path: "/products/create",
        name: "CreateProduct",
        component: CreateProduct,
      },
      {
        path: "/products/edit/:id",
        name: "EditProduct",
        component: EditProduct,
        props: true,
      },
      {
        path: "orders",
        name: "app.orders",
        component: Orders,
      },
      {
        path: "orders/:id",
        name: "app.orders.view",
        component: OrderDetails,
      },

      {
        path: "users",
        name: "app.users",
        component: Users,
      },
      {
        path: "customers",
        name: "app.customers",
        component: Customers,
      },

      {
        path: "reports",
        name: "app.reports",
        component: Reports,
      },
    ],
  },

  {
    path: "/login",
    name: "login",
    component: Login,
    meta: {
      requiresGuest: true,
    },
  },
  {
    path: "/forgot-password",
    name: "forgot-password",
    component: ForgotPassword,
    meta: {
      requiresGuest: true,
    },
  },
  {
    path: "/reset-password/token",
    name: "reset-password",
    component: ResetPassword,
    meta: {
      requiresGuest: true,
    },
  },
  {
    path: "/:pathMatch(.*)",
    name: "notfound",
    component: NotFound,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !store.state.user.token) {
    next({ name: "login" });
  } else if (to.meta.requiresGuest && store.state.user.token) {
    next({ name: "app.dashboard" });
  } else {
    next();
  }
});

export default router;
