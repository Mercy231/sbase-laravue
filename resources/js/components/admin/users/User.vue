<template>
    <tr>
        <td>{{ user.id }}</td>
        <td>{{ user.full_name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.country.name }}</td>
        <td>{{ user.state.name }}</td>
        <td>{{ user.city.name }}</td>
        <td>{{ user.balance }}</td>
        <td><img :src="user.avatar" alt="avatar" height="64"></td>
        <td><button type="button" class="btn btn-success" @click="this.$emit('modalOpen', user)">Edit</button></td>
    </tr>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {

        }
    },
    methods: {

    },
}
</script>
