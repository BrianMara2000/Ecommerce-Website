import { createRouter, createWebHistory } from "vue-router";
import AppLayout from "../components/AppLayout.vue";
import Dashboard from "../views/Dashboard.vue";
import Login from "../views/Login.vue";
import ForgotPassword from "../views/ForgotPassword.vue";
import ResetPassword from "../views/ResetPassword.vue";
import Products from "../views/Products.vue";
import store from "../store";

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
