<template>
    <div class="admin-notifications">
        <div class="d-flex flex-column">
            <h1>Roles</h1>
            <hr>
            <select @change="setRole($event.target.value)" class="form-select form-select-lg mb-3">
                <option value="Manager">Manager</option>
                <option value="Guest">Guest</option>
            </select>
            <div class="form-check form-switch">
                <input :checked="rolePermissions[0]" @change="rolePermissions[0]=!rolePermissions[0]" class="form-check-input" type="checkbox">
                <label class="form-check-label">Change roles permissions</label>
            </div>
            <div class="form-check form-switch">
                <input :checked="rolePermissions[1]" @change="rolePermissions[1]=!rolePermissions[1]" class="form-check-input" type="checkbox">
                <label class="form-check-label">Edit users</label>
            </div>
            <div class="form-check form-switch">
                <input :checked="rolePermissions[2]" @change="rolePermissions[2]=!rolePermissions[2]" class="form-check-input" type="checkbox">
                <label class="form-check-label">Delete users</label>
            </div>
            <div class="form-check form-switch">
                <input :checked="rolePermissions[3]" @change="rolePermissions[3]=!rolePermissions[3]" class="form-check-input" type="checkbox">
                <label class="form-check-label">Create posts</label>
            </div>
            <div class="form-check form-switch">
                <input :checked="rolePermissions[4]" @change="rolePermissions[4]=!rolePermissions[4]" class="form-check-input" type="checkbox">
                <label class="form-check-label">Edit posts</label>
            </div>
            <div class="form-check form-switch">
                <input :checked="rolePermissions[5]" @change="rolePermissions[5]=!rolePermissions[5]" class="form-check-input" type="checkbox">
                <label class="form-check-label">Delete posts</label>
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
            role: null,
            rolePermissions: [
                false,
                false,
                false,
                false,
                false,
                false,
            ],
        }
    },
    methods: {
        setRole: function (role) {
            this.role = role
            this.getPermissions()
        },
        getPermissions: function () {
            axios
                .post('/admin/getPermissions',{role: this.role})
                .then(response => this.rolePermissions = response.data)
        },
        setPermissions: function () {
            axios
                .post('/admin/setPermissions',{role: this.role, permissions: this.rolePermissions})
                .then(response => {
                    if (typeof response.data === 'boolean') {
                        Swal.fire({title: 'Success!', icon: 'success', confirmButtonText: 'Close'})
                    } else {
                        return Promise.reject(response.data)
                    }
                })
                .catch(error => Swal.fire({title: 'Error!', icon: 'error', text: error, confirmButtonText: 'Close'}))
        }
    },
    async mounted() {
        this.role = 'Manager'
        await this.getPermissions()
    }
}
</script>
