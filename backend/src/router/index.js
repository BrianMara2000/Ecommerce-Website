import { createRouter, createWebHistory } from "vue-router";
import AppLayout from "../components/AppLayout.vue";
import Dashboard from "../views/Dashboard.vue";
import Login from "../views/Login.vue";
import ForgotPassword from "../views/ForgotPassword.vue";
import ResetPassword from "../views/ResetPassword.vue";

const routes = [
    {
        path: "/app",
        name: "app",
        component: AppLayout,
        children: [
            {
                path: "dashboard",
                name: "app.dashboard",
                component: Dashboard,
            },
        ],
    },

    {
        path: "/login",
        name: "login",
        component: Login,
    },
    {
        path: "/forgot-password",
        name: "forgot-password",
        component: ForgotPassword,
    },
    {
        path: "/reset-password/token",
        name: "reset-password",
        component: ResetPassword,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
