<template>
    <div class="users-table-wrap">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Full name</th>
                <th scope="col">Email</th>
                <th scope="col">Country</th>
                <th scope="col">State</th>
                <th scope="col">City</th>
                <th scope="col">Balance</th>
                <th scope="col">Avatar</th>
            </tr>
            </thead>
            <tbody>
            <User
                v-for="user in users.users"
                :user="user"
                @modalOpen="modalOpen"
                href="User"
            />
            </tbody>
        </table>
    </div>
    <div class="myModal" v-if="showModal">
        <div class="myModal-content">
            <span class="close" @click="modalClose">&times;</span>
            <div class="admin-edit-modal">
                <h1>Edit user</h1><br>
                <div>
                    <img :src="userdata.avatar" alt="user" width="128">
                </div>
                <br>
                <div class="input-group mb-3">
                    <span class="input-group-text">Avatar</span>
                    <input @change="onImageChange($event)" type="file" class="form-control">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Full name</span>
                    <input :value="userdata.full_name" @input="userdata.full_name=$event.target.value" type="text" class="form-control">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Email</span>
                    <input :value="userdata.email" @input="userdata.email=$event.target.value" type="text" class="form-control">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Country</span>
                    <select
                        :value="userdata.country_id"
                        @change="getStates($event)"
                        class="form-select">
                        <option :value="0">Select country</option>
                        <option
                            v-for="country in this.helpers.countries"
                            :value="country.id"
                            :data-name="country.name"
                        >{{ country.name }}</option>
                    </select>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">State</span>
                    <select
                        :value="userdata.state_id"
                        @change="getCities($event)"
                        class="form-select">
                        <option :value="0">Select state</option>
                        <option
                            v-for="state in this.helpers.states"
                            :value="state.id"
                            :data-name="state.name"
                        >{{ state.name }}</option>
                    </select>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">City</span>
                    <select
                        :value="userdata.city_id"
                        @change="setSelectedCity($event)"
                        class="form-select">
                        <option :value="0">Select city</option>
                        <option
                            v-for="city in this.helpers.cities"
                            :value="city.id"
                            :data-name="city.name"
                        >{{ city.name }}</option>
                    </select>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Balance</span>
                    <input :value="userdata.balance" @input="userdata.balance=$event.target.value" type="number" class="form-control">
                </div>
                <div v-if="userdata.role!=='Admin'" class="input-group mb-3">
                    <span class="input-group-text">Role</span>
                    <select
                    :value="userdata.role"
                    @change="setRole($event)"
                    class="form-select">
                        <option value="Manager">Manager</option>
                        <option value="Guest">Guest</option>
                    </select>
                </div>
                <div>
                    <button @click="saveChanges(userdata.id)" class="btn btn-success">Save</button>
                    <button @click="deleteUser(userdata.id)" class="btn btn-danger">Delete</button>
                </div>
            </div>
            <div class="admin-user-statistics">
                <h1>Statistics</h1>
                <div>
                    <label for="" class="input-group-text">
                        <input :value="range.from" @change="setRangeFrom($event.target.value)" type="date" class="form-control">
                        <input :value="range.to" @change="setRangeTo($event.target.value)" type="date" class="form-control">
                        <button @click="getStatistics" class="btn btn-primary">Get</button>
                    </label>
                    <Bar :data="chartDataTest" :options="chartOptionsTest" ref="myChart"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from 'vuex'
import Swal from 'sweetalert2'
import {Chart as ChartJS, ArcElement, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale} from 'chart.js'
import { Bar } from 'vue-chartjs'
import User from './users/User.vue'
import user from "@/components/admin/users/User.vue";

ChartJS.register(CategoryScale, LinearScale, ArcElement, BarElement, Title, Tooltip, Legend)

export default {
    components: {User, Bar},
    data () {
        return {
            showModal: false,
            userdata: {
                id: null,
                full_name: null,
                email: null,
                country: {id: 0, name: "null"},
                state: {id: 0, name: "null"},
                city: {id: 0, name: "null"},
                balance: null,
                avatar: null,
                image: null,
                role: null,
            },
            range: {
                from: null,
                to: null,
            },
            chartData: {
                labels: ['January', 'February', 'March',],
                datasets: [
                    {
                        backgroundColor: ['#41B883'],
                        label: 'posts',
                        data: [40, 20, 123],
                    },
                    {
                        backgroundColor: ['#DD1B16'],
                        label: 'comments',
                        data: [20, 12, 3],
                    },
                ]
            },
            chartOptions: {
                responsive: true,
            },
        }
    },
    computed: {
        chartDataTest() {
            return this.chartData
        },
        chartOptionsTest() {
            return this.chartOptions
        },
        ...mapState(['users', 'helpers'])
    },
    methods: {
        modalOpen: function (user) {
            this.userdata = {
                id: user.id,
                full_name: user.full_name,
                email: user.email,
                country_id: user.country_id,
                state_id: user.state_id,
                city_id: user.city_id,
                balance: user.balance,
                avatar: user.avatar,
                image: null,
                role: user.role,
            }
            this.range = {from: null, to: null}
            this.$store.dispatch('countries')
            this.$store.dispatch('states', user.country_id)
            this.$store.dispatch('cities', user.state_id)
            this.showModal = true
        },
        modalClose: function () {
            this.showModal = false
        },
        getStates: function ($event) {
            this.userdata.country_id = $event.target.value
            this.userdata.state_id = 0
            this.userdata.city_id = 0
            this.$store.dispatch('states', this.userdata.country_id)
        },
        getCities: async function ($event) {
            this.userdata.state_id = $event.target.value
            this.userdata.city_id = 0
            this.$store.dispatch('cities', this.userdata.state_id)
        },
        setSelectedCity: function ($event) {
            this.userdata.city_id = $event.target.value
        },
        onImageChange: function ($event) {
            this.userdata.image = $event.target.files[0]
        },
        saveChanges: async function (id) {
            await axios
                .post(`/admin/updateUserdata/${id}`,
                    {userdata: this.userdata, image: this.userdata.image},
                    {headers: {"Content-Type": "multipart/form-data"}}
                )
                .then(response => {
                    if (typeof response.data === 'string') {
                        return Promise.reject(response.data)
                    } else {
                        this.$store.dispatch('users')
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: 'Userdata updated!',
                            confirmButtonText: 'Close',
                        })
                        this.modalClose()
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        icon: 'error',
                        text: error,
                        confirmButtonText: 'Close',
                    })
                })
        },
        deleteUser: function (id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/user/${id}`)
                    this.modalClose()
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                }
            })
        },
        setRangeFrom: function (value) {
            if (value > this.range.to) {
                this.range.from = this.range.to
                this.range.to = value
            } else {
                this.range.from = value
            }
        },
        setRangeTo: function (value) {
            if (value < this.range.from) {
                this.range.to = this.range.from
                this.range.from = value
            } else {
                this.range.to = value
            }
        },
        getStatistics: async function () {
            await axios
                .post(`/admin/getStatistics/${this.userdata.id}`, {
                    dateFrom: this.range.from, dateTo: this.range.to
                })
                .then(response => {
                    if (typeof response.data === 'string') return Promise.reject(response.data)
                    this.chartData = {
                        labels: response.data.range,
                        datasets: [
                            {
                                backgroundColor: ['#41B883'],
                                label: 'posts',
                                data: response.data.count.posts,
                            },
                            {
                                backgroundColor: ['#DD1B16'],
                                label: 'comments',
                                data: response.data.count.comments,
                            },

                        ]
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        icon: 'error',
                        text: error,
                        confirmButtonText: 'Close',
                    })
                })
        },
        setRole: function ($event) {
            this.userdata.role = $event.target.value
        },
    },
    mounted() {
        this.$store.dispatch('users')
    }
}
</script>
