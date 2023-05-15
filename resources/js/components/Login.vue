<template>
    <div class="login">
        <input type="email" placeholder="Email" v-model="email">
        <input type="password" name="password" placeholder="Password" v-model="password">
        <button @click="login">Log in</button>
        <a href="/signup">Create account</a>
        <h3>{{ error }}</h3>
        <div>
            <p>Login with <a href="/auth/google/redirect">Google</a></p>
        </div>
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
    computed: mapState(['auth']),
    methods: {
        login: async function () {
            await this.$store.dispatch('login', {email: this.email, password: this.password})
                .then(response => {
                    response ? this.error = response : this.router.push({name: 'dashboard'})
                })
        },
    },
}
</script>
