<template>
    <div class="admin-notifications">
        <div class="d-flex flex-column">
            <h1>Send notification</h1>
            <hr>
            <select :value="0" @change="setRecipient($event)" class="form-select form-select-lg mb-3">
                <option :value="0" selected>All users</option>
                <option v-for="user in users.users" :value="user.id">{{user.email}}</option>
            </select>
            <br>
            <div class="input-group mb-3">
                <span class="input-group-text">Title</span>
                <input @input="setTitle($event)" type="text" class="form-control" placeholder="Title" aria-label="Username">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">Text</span>
                <textarea @input="setText($event)" class="form-control" placeholder="Text" aria-label="Username"></textarea>
            </div>
            <br>
            <button @click="sendNotification" type="button" class="btn btn-success">Send</button>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex"
import Swal from "sweetalert2"

export default {
    data() {
        return {
            recipient: 0,
            title: null,
            text: null,
        }
    },
    computed: mapState(['auth', 'users']),
    methods: {
        setRecipient: function ($event) {
            this.recipient = $event.target.value
        },
        setTitle: function ($event) {
            this.title = $event.target.value
        },
        setText: function ($event) {
            this.text = $event.target.value
        },
        sendNotification: function () {
            axios
                .post('/admin/notification/create',
                    {
                        id: this.recipient,
                        title: this.title,
                        text: this.text,
                    })
                .then(response => {
                    if (typeof response.data === 'string') {
                        return Promise.reject(response.data)
                    } else {
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: 'Notification created!',
                            confirmButtonText: 'Close',
                        })
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        icon: 'error',
                        text: error,
                        confirmButtonText: 'Close',
                    })
                })
        }
    },
    mounted() {
        this.$store.dispatch('users')
    }
}
</script>
