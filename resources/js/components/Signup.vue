<template>
    <div class="signup">
        <input type="text" placeholder="Full name" v-model="full_name">
        <input type="email" placeholder="Email" v-model="email">
        <input type="password" name="password" placeholder="Password" v-model="password">
        <input type="password" name="password_confirmation" placeholder="Confirm password" v-model="password_confirmation">
        <select :value="country.id" @change="getStates($event)" class="signup-select">
            <option :value="0" selected>Select country</option>
            <option
                v-for="country in helpers.countries"
                :value="country.id"
                :data-name="country.name">
                {{ country.name }}
            </option>
        </select>
        <select v-model="state.id" @change="getCities" class="signup-select">
            <option :value="0" selected>Select state</option>
            <option
                v-for="state in helpers.states"
                :value="state.id"
                :data-name="state.name">
                {{ state.name }}
            </option>
        </select>
        <select v-model="city.id" class="signup-select">
            <option :value="0" selected>Select city</option>
            <option
                v-for="city in helpers.cities"
                :value="city.id"
                :data-name="city.name">
                {{ city.name }}
            </option>
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
            country: {id: 0, name: "null"},
            state: {id: 0, name: "null"},
            city: {id: 0, name: "null"},
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
        getStates: async function ($event) {
            this.country = {
                id: $event.target.value,
                name: $event.target.options[$event.target.options.selectedIndex].dataset.name,
            }
            this.state = {id: 0, name: "null"}
            this.city = {id: 0, name: "null"}
            await this.$store.dispatch('states', this.country)
        },
        getCities: async function ($event) {
            this.state = {
                id: $event.target.value,
                name: $event.target.options[$event.target.options.selectedIndex].dataset.name,
            }
            this.city = {id: 0, name: "null"}
            await this.$store.dispatch('cities', this.state)
        },
        setSelectedCity: function ($event) {
            this.userdata.city = {
                id: $event.target.value,
                name: $event.target.options[$event.target.options.selectedIndex].dataset.name,
            }
        },
    },
    mounted() {
        this.$store.dispatch('countries')
    }
}
</script>
