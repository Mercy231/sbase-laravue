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
import {useRouter} from "vue-router";

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
        this.getCountries()
    }
}
</script>
<!--<script setup>-->
<!--import {useRouter} from "vue-router"-->
<!--import {onMounted, ref} from "vue"-->

<!--const router = useRouter()-->

<!--let full_name = ref()-->
<!--let email = ref()-->
<!--let password = ref()-->
<!--let password_confirmation = ref()-->

<!--let countries = ref()-->
<!--let states = ref()-->
<!--let cities = ref()-->

<!--let country = ref('')-->
<!--let state = ref('')-->
<!--let city = ref('')-->

<!--let error = ref('')-->

<!--const signup = async () => {-->
<!--    await axios-->
<!--        .post('/signup', {-->
<!--            full_name: full_name.value,-->
<!--            email: email.value,-->
<!--            password: password.value,-->
<!--            password_confirmation: password_confirmation.value,-->
<!--            country: country.value,-->
<!--            state: state.value,-->
<!--            city: city.value,-->
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
<!--const getCountries = async () => {-->
<!--    await axios-->
<!--        .get('/countries')-->
<!--        .then(response => {-->
<!--            if ('countries' in response.data) {-->
<!--                countries.value = response.data.countries-->
<!--            }-->
<!--        })-->
<!--}-->
<!--const getStates = async () => {-->
<!--    states.value = null-->
<!--    state.value = ''-->
<!--    cities.value = null-->
<!--    city.value = ''-->
<!--    await axios-->
<!--        .post('/states', {-->
<!--            country: country.value,-->
<!--        })-->
<!--        .then(response => {-->
<!--            if ('states' in response.data) {-->
<!--                states.value = response.data.states-->
<!--            }-->
<!--        })-->
<!--}-->
<!--const getCities = async () => {-->
<!--    cities.value = ''-->
<!--    city.value = ''-->
<!--    await axios-->
<!--        .post('/cities', {-->
<!--            state: state.value,-->
<!--        })-->
<!--        .then(response => {-->
<!--            if ('cities' in response.data) {-->
<!--                cities.value = response.data.cities-->
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
<!--    await getCountries()-->
<!--})-->
<!--</script>-->
