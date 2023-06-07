import {createRouter, createWebHistory} from 'vue-router'
import store from "./store";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            redirect: {name: 'welcome'},
        },
        {
            path: '/welcome',
            name: 'welcome',
            component: () => import('./components/Welcome.vue'),
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: () => import('./components/Dashboard.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('./components/Login.vue'),
        },
        {
            path: '/signup',
            name: 'signup',
            component: () => import('./components/Signup.vue'),
        },
        {
            path: '/logout',
            name: 'logout',
        },
        {
            path: '/posts',
            name: 'posts',
            component: () => import('./components/Posts.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/profile',
            name: 'profile',
            component: () => import('./components/Profile.vue'),
            meta: {
                requiresAuth: true
            },
        },
        {
            path: '/admin',
            name: 'admin',
            component: () => import('./components/Admin.vue'),
            meta: {
                requiresAuth: true
            },
        },
    ],
})
router.beforeEach(async (to) => {
    await store.dispatch('user')
    if (to.meta.requiresAuth && !store.state.auth.authenticated) return {name: 'login'}
    if (to.name === 'login' && store.state.auth.authenticated) return {name: 'dashboard'}
    if (to.name === 'signup' && store.state.auth.authenticated) return {name: 'dashboard'}
})
export default router
