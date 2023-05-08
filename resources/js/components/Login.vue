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
import {mapState} from "vuex";

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
    },
    mounted() {
        this.$store.dispatch('user')
        if (localStorage.getItem('isAuth')) this.router.push({name: 'dashboard'})
    }
}
</script>
