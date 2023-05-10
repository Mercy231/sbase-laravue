<template>
    <div class="signup">
        <input type="text" placeholder="Full name" v-model="full_name">
        <input type="email" placeholder="Email" v-model="email">
        <input type="password" name="password" placeholder="Password" v-model="password">
        <input type="password" name="password_confirmation" placeholder="Confirm password" v-model="password_confirmation">
        <select v-model="country" @change="getStates">
            <option v-for="country in helpers.countries" v-bind:value="country.id">{{ country.name }}</option>
        </select>
        <select v-model="state" @change="getCities">
            <option v-for="state in helpers.states" v-bind:value="state.id">{{ state.name }}</option>
        </select>
        <select v-model="city">
            <option v-for="city in helpers.cities" v-bind:value="city.id">{{ city.name }}</option>
        </select>
        <button @click="signup">Sign up</button>
        <p>Already have an account? <a href="/login">Log in</a></p>
        <h3>{{ error }}</h3>
    </div>
</template>

<script>
import {useRouter} from "vue-router"
import {mapState} from "vuex"

export default {
    data() {
        return  {
            router: useRouter(),
            full_name: '',
            email: '',
            password: '',
            password_confirmation: '',
            error: '',
            country: '',
            state: '',
            city: '',
        }
    },
    computed: mapState(['auth', 'helpers']),
    methods: {
        signup: async function () {
            await this.$store.dispatch('signup', {
                full_name: this.full_name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
                country: this.country,
                state: this.state,
                city: this.city,
            })
            .then(response => {
                response ? this.error = response : this.router.push({name: 'dashboard'})
            })
        },
        getStates: async function () {
            this.state = ''
            this.city = ''
            await this.$store.dispatch('states', this.country)
        },
        getCities: async function () {
            this.cities = ''
            this.city = ''
            await this.$store.dispatch('cities', this.state)
        },
    },
    mounted() {
        this.$store.state.helpers.states = []
        this.$store.state.helpers.cities = []
        this.$store.dispatch('countries')
    }
}
</script>
