<template>
    <div class="dashboard">
        <h1>{{ user.email }}</h1>
        <button @click="logout">Logout</button>
    </div>
</template>

<script setup>
import {useRouter} from "vue-router"
import {onMounted, ref} from "vue"

const router = useRouter()

let user = ref({})

function logout () {
    localStorage.removeItem('isAuth')
    router.push({name: 'logout'})
}
onMounted(async () => {
    await axios
        .get('/user')
        .then(response => {
            if ('user' in response.data) {
                user.value = response.data.user
            }
        })
})
</script>
