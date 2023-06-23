<template>
    <div class="admin-notifications">
        <div class="d-flex flex-column">
            <h1>Roles</h1>
            <hr>
            <select @change="setSelectedRole($event.target.value)" class="form-select form-select-lg mb-3">
                <option v-for="value in roles" :value="value.name">{{value.name}}</option>
            </select>
            <div v-for="permission in permissions" class="form-check form-switch">
                <input :checked="permission.value" @change="permission.value=!permission.value" class="form-check-input" type="checkbox">
                <label class="form-check-label">{{ permission.name }}</label>
            </div>
            <button @click="setPermissions">Save</button>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2"

export default {
    data() {
        return {
            roles: null,
            selectedRole: null,
            permissions: null,
        }
    },
    methods: {
        setSelectedRole: function (role) {
            this.selectedRole = role
            this.getPermissions()
        },
        getRoles: function () {
            axios
                .get("/admin/getRoles")
                .then(response => this.roles = response.data)
        },
        getPermissions: function () {
            axios
                .post('/admin/getPermissions',{role: this.selectedRole})
                .then(response => this.permissions = response.data)
        },
        setPermissions: function () {
            axios
                .post('/admin/setPermissions',{role: this.selectedRole, permissions: this.permissions})
                .then(response => {
                    if (typeof response.data === 'boolean') {
                        Swal.fire({title: 'Success!', icon: 'success', confirmButtonText: 'Close'})
                    } else {
                        return Promise.reject(response.data)
                    }
                })
                .catch(error => Swal.fire({title: 'Error!', icon: 'error', text: error, confirmButtonText: 'Close'}))
        },
    },
    async mounted() {
        this.selectedRole = 'Manager'
        await this.getRoles()
        await this.getPermissions()
    }
}
</script>
