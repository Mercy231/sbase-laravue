<template>
    <div class="login">
        <input type="email" placeholder="Email" v-model="email">
        <input type="password" name="password" placeholder="Password" v-model="password">
        <button @click="login">Log in</button>
        <a href="/signup">Create account</a>
        <h3>{{ error }}</h3>
    </div>
</template>

<script setup>
import {useRouter} from "vue-router";
import {ref} from "vue";

const router = useRouter()

let email = ref()
let password = ref()

let error = ref('')

const login = async () => {
    await axios
        .post('/login', {
            email: email.value,
            password: password.value,
        })
        .then(response => {
            if (response.data.success) {
                localStorage.setItem('isAuth', 'true')
                router.push({name: 'dashboard'})
            } else {
                error.value = response.data.error
            }
        })
}
</script>
