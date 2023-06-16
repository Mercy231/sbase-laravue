import router from "../../routes"
import axios from "axios"

const state = () => ({
    user: {
        country: null,
    },
    authenticated: false,
})
const getters = {
    GET_USER: (state) => state.user
}
const actions = {
    user: async function ({commit}) {
        await axios
            .get('/user')
            .then(response => {
                if ('user' in response.data) {
                    commit('SET_USER', response.data.user)
                    commit('SET_AUTHENTICATED', true)
                } else {
                    commit('SET_AUTHENTICATED', false)
                }
            })
    },
    logout: async function ({commit}) {
        await axios.get('/logout')
        commit('SET_USER', {})
        commit('SET_AUTHENTICATED', false)
        await router.push({name: 'login'})
    },
    login: async function ({commit}, credentials) {
        return await axios
            .post('/login', credentials)
            .then(response => {
                if (!response.data.success) {
                    return response.data.error
                } else {
                    commit('SET_AUTHENTICATED', true)
                    return false
                }
            })
    },
    signup: async function ({commit}, credentials) {
        return await axios
            .post('/signup', credentials)
            .then(response => {
                if (!response.data.success) {
                    return response.data.error
                } else {
                    commit('SET_AUTHENTICATED', true)
                    return false
                }
            })
    }
}
const mutations = {
    SET_USER(state, user) {
        state.user = user
    },
    SET_AUTHENTICATED(state, bool) { state.authenticated = bool },
}

export default {
    state,
    getters,
    actions,
    mutations,
}
