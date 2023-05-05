<template>
    <div class="login">
        <input type="email" placeholder="Email" v-model="email">
        <input type="password" name="password" placeholder="Password" v-model="password">
        <button @click="login">Log in</button>
        <a href="/signup">Create account</a>
        <h3>{{ error }}</h3>
    </div>
</template>

<script>
import {useRouter} from "vue-router";

export default {
    data() {
        return  {
            router: useRouter(),
            email: '',
            password: '',
            error: '',
        }
    },
    methods: {
        login: async function () {
            await axios
                .post('/login', {
                    email: this.email,
                    password: this.password,
                })
                .then(response => {
                    if (response.data.success) {
                        localStorage.setItem('isAuth', 'true')
                        this.router.push({name: 'dashboard'})
                    } else {
                        this.error = response.data.error
                    }
                })
        },
        getUser: async function () {
            await axios
                .get('/user')
                .then(response => {
                    if ('user' in response.data) {
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
<!--import {useRouter} from "vue-router";-->
<!--import {onMounted, ref} from "vue";-->

<!--const router = useRouter()-->

<!--let email = ref()-->
<!--let password = ref()-->

<!--let error = ref('')-->

<!--const login = async () => {-->
<!--    await axios-->
<!--        .post('/login', {-->
<!--            email: email.value,-->
<!--            password: password.value,-->
<!--        })-->
<!--        .then(response => {-->
<!--            if (response.data.success) {-->
<!--                localStorage.setItem('isAuth', 'true')-->
<!--                router.push({name: 'dashboard'})-->
<!--            } else {-->
<!--                error.value = response.data.error-->
<!--            }-->
<!--        })-->
<!--}-->
<!--onMounted(async () => {-->
<!--    await axios-->
<!--        .get('/user')-->
<!--        .then(response => {-->
<!--            if (!'user' in response.data) {-->
<!--                localStorage.removeItem('isAuth')-->
<!--            } else {-->
<!--                router.push({name: 'dashboard'})-->
<!--            }-->
<!--        })-->
<!--})-->
<!--</script>-->
