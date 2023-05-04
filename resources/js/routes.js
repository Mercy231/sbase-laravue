import {createRouter, createWebHistory} from 'vue-router'

export default createRouter({
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
            beforeEnter(to, from, next) {
                localStorage.getItem('isAuth') ? next() : next({name: 'login'})
            },
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('./components/Login.vue'),
            beforeEnter(to, from, next) {
                localStorage.getItem('isAuth') ? next({name: 'dashboard'}) : next()
            },
        },
        {
            path: '/signup',
            name: 'signup',
            component: () => import('./components/Signup.vue'),
            beforeEnter(to, from, next) {
                localStorage.getItem('isAuth') ? next({name: 'dashboard'}) : next()
            },
        },
        {
            path: '/logout',
            name: 'logout',
            redirect: {name: 'welcome'},
        },
    ],
})
