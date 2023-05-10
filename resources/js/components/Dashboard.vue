<template>
    <div class="dashboard">
        <h1>{{ auth.user.email }}</h1>
        <button @click="logout">Logout</button>
    </div>
</template>

<script>
import {useRouter} from "vue-router"
import {mapState} from "vuex"

export default {
    data() {
        return  {
            router: useRouter(),
            error: '',
        }
    },
    computed: mapState(['auth']),
    methods: {
        logout: async function () {
            await this.$store.dispatch('logout')
        },
    }
}
</script>
