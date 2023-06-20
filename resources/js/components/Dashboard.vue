<template>
    <header class="p-3 text-bg-dark">
        <div class="nav-bar">
            <div @click="showNotifications" class="nav-bar-notifications">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
                </svg>
                <svg v-if="auth.user.new_notifications.length > 0" xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-dot" viewBox="0 0 16 16">
                    <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                </svg>
            </div>
        </div>
    </header>
    <div class="dashboard">
        <h1>{{ auth.user.email }}</h1>
        <button @click="logout">Logout</button>
    </div>

<!--Notifications modal-->
    <div class="myModal" v-if="showNotificationsModal">
        <div class="myModal-content">
            <span class="close" @click="showNotifications">&times;</span>
            <h1>Notifications</h1>
            <div class="notifications-wrap">
                <div>
                    <Notification
                        v-for="notification in auth.user.notifications"
                        :notification="notification"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {useRouter} from "vue-router"
import {mapState} from "vuex"
import Notification from "./Notification.vue"

export default {
    components: {Notification},
    data() {
        return  {
            showNotificationsModal: false,
            notifications: [],
        }
    },
    computed: mapState(['auth']),
    methods: {
        logout: async function () {
            await this.$store.dispatch('logout')
        },
        showNotifications: function () {
            this.$store.dispatch('user')
            this.showNotificationsModal = !this.showNotificationsModal
        },
    },
}
</script>
