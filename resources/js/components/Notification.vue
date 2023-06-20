<template>
    <div class="notification" @mouseleave="readNotification">
        <div class="notification-body">
            <svg v-if="notification.status === 'unread'" xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-dot" viewBox="0 0 16 16">
                <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
            </svg>
            <div>
                <h4>{{ notification.title }}</h4>
                <p>{{ notification.text }}</p>
            </div>
        </div>
        <button @click="deleteNotification" class="btn btn-danger">Delete</button>
    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    props: ['notification'],
    data() {
        return {

        }
    },
    computed: mapState(['auth']),
    methods: {
        readNotification: function () {
            if (this.notification.status === "read") return
            this.notification.status = 'read'
            axios.post('/user/notification/read', {id: this.notification.id})
        },
        deleteNotification: function () {
            this.notification.status = 'deleted'
            this.$el.parentNode.removeChild(this.$el)
            axios.post('/user/notification/delete', {id: this.notification.id})
        }
    },
}
</script>
