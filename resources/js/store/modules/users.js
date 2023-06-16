import axios from "axios";

const state = () => ({
    users: {},
})
const getters = {
    GET_USERS: (state) => state.users
}
const actions = {
    users: async function ({commit}) {
        await axios
            .get('/admin/getUsers')
            .then(response => {
                if (typeof response.data != 'string') {
                    commit('SET_USERS', response.data)
                } else {
                    Promise.reject(response)
                }
            })
            .catch(response => { commit('SET_USERS', null) })
    },
}
const mutations = {
    SET_USERS(state, users) {
        state.users = users
        state.users.forEach(function (user) {
            if (user.country == null) user.country = {id: 0, name: null}
            if (user.state == null) user.state = {id: 0, name: null}
            if (user.city == null) user.city = {id: 0, name: null}
        })
    },
}

export default {
    state,
    getters,
    actions,
    mutations,
}
