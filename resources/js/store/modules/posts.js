const state = () => ({
    posts: {},
})
const getters = {
    GET_POSTS: (state) => state.states
}
const actions = {
    posts: async function ({commit}) {
        await axios.get('/post')
            .then(response => { commit('SET_POSTS', response.data) })
    }
}
const mutations = {
    SET_POSTS(state, posts) {state.posts = posts},
}

export default {
    state,
    getters,
    actions,
    mutations,
}
