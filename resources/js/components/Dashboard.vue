<template>
    <div class="dashboard">
        <h1>{{ user.email }}</h1>
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
    computed: mapState([
       'user'
    ]),
    methods: {
        logout: function () {
            this.router.push({name: 'logout'})
        },
    },
    mounted() {
        this.$store.dispatch('user')
    }
}
</script>
