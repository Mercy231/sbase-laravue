const state = () => ({
    countries: [],
    states: [],
    cities: [],
})
const getters = {
    GET_COUNTRIES: (state) => state.countries,
    GET_STATES: (state) => state.states,
    GET_CITIES: (state) => state.cities,
}
const actions = {
    countries: async function ({commit}){
        await axios.get('/countries')
            .then(response => { if ('countries' in response.data) commit('SET_COUNTRIES', response.data.countries) })
    },
    states: async function ({commit}, country){
        await axios
            .post('/states', {country: country})
            .then(response => { if ('states' in response.data) commit('SET_STATES', response.data.states) })
    },
    cities: async function ({commit}, state){
        await axios
            .post('/cities', {state: state})
            .then(response => { if ('cities' in response.data) commit('SET_CITIES', response.data.cities) })
    },
}
const mutations = {
    SET_COUNTRIES(state, countries) {state.countries = countries},
    SET_STATES(state, states) {state.states = states},
    SET_CITIES(state, cities) {state.cities = cities},
}

export default {
    state,
    getters,
    actions,
    mutations,
}
