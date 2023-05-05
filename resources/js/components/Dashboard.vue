<template>
    <div class="dashboard">
        <h1>{{ user.email }}</h1>
        <button @click="logout">Logout</button>
    </div>
</template>

<script>
import {useRouter} from "vue-router"

export default {
    data() {
        return  {
            router: useRouter(),
            user: {},
            error: '',
        }
    },
    methods: {
        logout: function () {
            this.router.push({name: 'logout'})
        },
        getUser: async function () {
            await axios
                .get('/user')
                .then(response => {
                    if ('user' in response.data) {
                        this.user = response.data.user
                        localStorage.setItem('isAuth', 'true')
                        this.router.push({name: 'dashboard'})
                    } else {
                        localStorage.removeItem('isAuth')
                    }
                })
        }
    },
    mounted() {
        this.getUser()
    }
}
</script>
<!--<script setup>-->
<!--import {useRouter} from "vue-router"-->
<!--import {onMounted, ref} from "vue"-->

<!--const router = useRouter()-->

<!--let user = ref({})-->

<!--function logout () {-->
<!--    router.push({name: 'logout'})-->
<!--}-->
<!--onMounted(async () => {-->
<!--    await axios-->
<!--        .get('/user')-->
<!--        .then(response => {-->
<!--            if ('user' in response.data) {-->
<!--                user.value = response.data.user-->
<!--                localStorage.setItem('isAuth', 'true')-->
<!--                router.push({name: 'dashboard'})-->
<!--            } else {-->
<!--                localStorage.removeItem('isAuth')-->
<!--            }-->
<!--        })-->
<!--})-->
<!--</script>-->
