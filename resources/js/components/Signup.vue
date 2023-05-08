<template>
    <div class="signup">
        <input type="text" placeholder="Full name" v-model="full_name">
        <input type="email" placeholder="Email" v-model="email">
        <input type="password" name="password" placeholder="Password" v-model="password">
        <input type="password" name="password_confirmation" placeholder="Confirm password" v-model="password_confirmation">
        <select v-model="country" @change="getStates">
            <option v-for="country in countries" v-bind:value="country.id">{{ country.name }}</option>
        </select>
        <select v-model="state" @change="getCities">
            <option v-for="state in states" v-bind:value="state.id">{{ state.name }}</option>
        </select>
        <select v-model="city">
            <option v-for="city in cities" v-bind:value="city.id">{{ city.name }}</option>
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
            countries: [],
            country: '',
            states: [],
            state: '',
            cities: [],
            city: '',

        }
    },
    computed: mapState([
        'user'
    ]),
    methods: {
        signup: async function () {
            await axios
                .post('/signup', {
                    full_name: this.full_name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    country: this.country,
                    state: this.state,
                    city: this.city,
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
        getCountries: async function () {
            await axios
                .get('/countries')
                .then(response => {
                    if ('countries' in response.data) {
                        this.countries = response.data.countries
                    }
                })
        },
        getStates: async function () {
            this.states = []
            this.state = ''
            this.cities = []
            this.city = ''
            await axios
                .post('/states', {
                    country: this.country,
                })
                .then(response => {
                    if ('states' in response.data) {
                        this.states = response.data.states
                    }
                })
        },
        getCities: async function () {
            this.cities = ''
            this.city = ''
            await axios
                .post('/cities', {
                    state: this.state,
                })
                .then(response => {
                    if ('cities' in response.data) {
                        this.cities = response.data.cities
                    }
                })
        },
    },
    mounted() {
        this.$store.dispatch('user')
        if (localStorage.getItem('isAuth')) this.router.push({name: 'dashboard'})
        this.getCountries()
    }
}
</script>
