<template>
    <div class="user">
        <div>
            <div>
                <img :src="'/public/storage/images/avatars/' + auth.user.avatar" alt="user" width="128">
                <div>
                    <input @change="onImageChange($event)" type="file" name="test">
                    <button @click="avatarUpload">Upload</button>
                </div>
            </div>
            <button v-if="!editProfile" @click="editProfileData">Edit profile</button>
            <button v-if="editProfile" @click="saveChanges">Save</button>
            <button v-if="editProfile" @click="cancelChanges">Cancel</button>
            <ul>
                <li>
                    <div v-if="editProfile">
                        <label>
                            Full name:
                            <input :value="full_name" @input="full_name=$event.target.value" type="text">
                        </label>
                    </div>
                    <div v-else>
                        Full name: {{auth.user.full_name}}
                    </div>
                </li>
                <li>
                    <div v-if="editProfile">
                        <label>Email: <input :value="email" @input="email=$event.target.value" type="text"></label>
                    </div>
                    <div v-else>
                        Email: {{auth.user.email}}
                    </div>
                </li>
                <li>Balance: ${{auth.user.balance}}</li>
                <li>Country: {{auth.user.country}}</li>
                <li>State: {{auth.user.state}}</li>
                <li>City: {{auth.user.city}}</li>
            </ul>
            <br>
            <ul>
                <li v-for="error in errors">{{ error }}</li>
            </ul>
            <br>
        </div>
    </div>
    <div>
        <div>
            <label>
                Card number:<input v-model="number" type="text">
            </label>
            <label>
                Month:<input v-model="exp_month" type="number">
            </label>
            <label>
                Year:<input v-model="exp_year" type="number">
            </label>
            <label>
                Cvc:<input v-model="cvc" type="number">
            </label>
        </div>
        <input v-model="amount" type="number">
        <button @click="charge">Top up</button>
    </div>
</template>

<script>
import {mapState} from "vuex";
import Swal from "sweetalert2";

export default {
    data() {
        return {
            amount: null,
            number: null,
            exp_month: null,
            exp_year: null,
            cvc: null,
            image: null,
            editProfile: false,
            full_name: null,
            email: null,
            country: null,
            state: null,
            city: null,
            errors: [],
        }
    },
    computed: mapState(['auth']),
    methods: {
        charge: async function () {
            await axios.post('/charge', {
                amount: this.amount,
                number: this.number,
                exp_month: this.exp_month,
                exp_year: this.exp_year,
                cvc: this.cvc,
            }).then(response => {
                if (typeof response.data === "string") return Promise.reject(response.data)
                Swal.fire({
                    title: 'Success!',
                    icon: 'success',
                    confirmButtonText: 'Close',
                })
            }).catch(error => {
                Swal.fire({
                    title: 'Error!',
                    icon: 'error',
                    text: error,
                    confirmButtonText: 'Close',
                })
            })
        },
        onImageChange: function ($event) {
            this.image = $event.target.files[0]
        },
        avatarUpload: async function () {
            if (this.image != null) {
                await axios.post('/changeAvatar',
                    {image: this.image},
                    {headers: { 'content-type': 'multipart/form-data' }})
                    .then(response => {
                        if (typeof response.data === "string") return Promise.reject(response.data)
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            confirmButtonText: 'Close',
                        })
                        this.$store.dispatch('user')
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            icon: 'error',
                            text: error,
                            confirmButtonText: 'Close',
                        })
                    })
            } else {
                console.log('error')
            }
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
        checkFields: function () {
            if (!this.full_name || !this.checkFullName(this.full_name)) {
                this.errors.push("Full name field is invalid")
                return false
            }
            if (!this.email || !this.checkEmail(this.email)) {
                this.errors.push("Email field is invalid")
                return false
            }
            return true
        },
        checkFullName: function (fullName) {
            return /^[A-Za-z ]+$/.test(fullName)
        },
        checkEmail: function (email) {
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)
        },
        editProfileData: function () {
            this.full_name = this.auth.user.full_name
            this.email = this.auth.user.email
            this.editProfile=!this.editProfile
        },
        saveChanges: async function () {
            if (this.checkFields()) {
                await axios.post('/changeUserdata', {fullName: this.full_name, email: this.email})
                    .then(response => {
                        if (typeof response.data === "string") return Promise.reject(response.data)
                        this.$store.dispatch('user')
                        Swal.fire({title: 'Success!', icon: 'success', confirmButtonText: 'Close',})
                    })
                    .catch(error => {
                        Swal.fire({title: 'Error!', icon: 'error', text: error, confirmButtonText: 'Close',})
                    })
                this.editProfile=!this.editProfile
                this.errors = []
            }
        },
        cancelChanges: function () {
            this.errors = []
            this.editProfile=!this.editProfile
        },
    },
}
</script>
