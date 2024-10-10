import { createRouter, createWebHistory } from "vue-router"; // Use createWebHistory
import Dashboard from "../views/Dashboard.vue";
import Register from "../views/Register.vue";
import Login from "../views/Login.vue";
import ForgotPassword from '../views/ForgotPassword.vue';
import ResetPassword from "../views/ResetPassword.vue";

const routes = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
    },
    {
        path: '/login',
        name: 'login',
        component: Login // Ensure Login component is correctly used
    },
    {
        path: '/forgot-password',
        name: 'ForgotPassword',
        component: ForgotPassword,
    },
    {
        path: '/reset-password/:token', // Define route with token parameter
        name: 'ResetPassword',
        component: ResetPassword,
    },
];

const router = createRouter({
    history: createWebHistory(), // Use web history for browser URL management
    // history: createWebHistory(process.env.BASE_URL),
    routes
});

export default router;
