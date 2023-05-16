<template>
    <div class="user">
        <div>
            <ul>
                <li>Full name: {{auth.user.full_name}}</li>
                <li>Email: {{auth.user.email}}</li>
                <li>Balance: ${{auth.user.balance}}</li>
                <li>Country: {{auth.user.country}}</li>
                <li>State: {{auth.user.state}}</li>
                <li>City: {{auth.user.city}}</li>
            </ul>
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
        }
    },
}
</script>
