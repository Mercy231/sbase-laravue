<template>
    <tr>
        <td>{{ userdata.id }}</td>
        <td>{{ userdata.full_name }}</td>
        <td>{{ userdata.email }}</td>
        <td>{{ userdata.country.name }}</td>
        <td>{{ userdata.state.name }}</td>
        <td>{{ userdata.city.name }}</td>
        <td>{{ userdata.balance }}</td>
        <td><img :src="userdata.avatar" alt="avatar" height="64"></td>
        <td><button type="button" class="btn btn-success" @click="this.$emit('modalOpen', user)">Edit</button></td>
    </tr>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            userdata: {
                id: null,
                full_name: null,
                email: null,
                country: {
                    id: null,
                    name: null,
                },
                state: {
                    id: null,
                    name: null,
                },
                city: {
                    id: null,
                    name: null,
                },
                balance: null,
                avatar: null,
            }
        }
    },
    methods: {

    },
    mounted(){
        if (this.user.country == null) this.user.country = {id: 0, name: null}
        if (this.user.state == null) this.user.state = {id: 0, name: null}
        if (this.user.city == null) this.user.city = {id: 0, name: null}
        this.userdata = this.user
    }
}
</script>
