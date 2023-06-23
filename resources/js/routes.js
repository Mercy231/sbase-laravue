import {createRouter, createWebHistory} from 'vue-router'
import store from "./store";
import Home from './components/admin/Home.vue'
import Users from './components/admin/Users.vue'
import Notifications from './components/admin/Notifications.vue'
import Roles from './components/admin/Roles.vue'

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
            children: [
                { name: 'admin/home', path: 'home', component: Home },
                { name: 'admin/users', path: 'users', component: Users },
                { name: 'admin/notifications', path: 'notifications', component: Notifications },
                { name: 'admin/roles', path: 'roles', component: Roles },
            ],
            meta: {
                requiresAuth: true,
            },
        },
    ],
})
router.beforeEach(async (to) => {
    await store.dispatch('user')
    let role = store.state.auth.user.role
    if (to.meta.requiresAuth && !store.state.auth.authenticated) return {name: 'login'}
    if (to.name === 'admin' && role === 'Guest') return {name: 'dashboard'}
    if (to.name === 'admin/home' && role === 'Guest') return {name: 'dashboard'}
    if (to.name === 'admin/users' && role === 'Guest') return {name: 'dashboard'}
    if (to.name === 'admin/notifications' && role === 'Guest') return {name: 'dashboard'}
    if (
        to.name === 'admin/roles' && role === 'Guest'
        ||
        to.name === 'admin/roles' && role === 'Manager'
    ) return {name: 'dashboard'}
    if (to.name === 'login' && store.state.auth.authenticated) return {name: 'dashboard'}
    if (to.name === 'signup' && store.state.auth.authenticated) return {name: 'dashboard'}
})
export default router
