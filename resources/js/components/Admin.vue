<template>
    <div class="admin-page">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" >
            <a href="/admin" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4">Sidebar</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <router-link to="/admin/home" class="nav-link">Home</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/admin/users" class="nav-link">Users</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/admin/notifications" class="nav-link">Notifications</router-link>
                </li>
                <li v-if="auth.user.role==='Admin'" class="nav-item">
                    <router-link to="/admin/roles" class="nav-link">Roles</router-link>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img :src="auth.user.avatar" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong>Admin</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><a class="dropdown-item" href="#">New project...</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Sign out</a></li>
                </ul>
            </div>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
import {mapState} from "vuex"

export default {
    data() {
        return {
            tab: 'Home',
        }
    },
    computed: mapState(['auth']),
    methods: {
        activeClass: function (...names) {
            for (let name of names) {
                if (name === this.$route.name)
                    return 'active';
            }
        },
    }
}
</script>
