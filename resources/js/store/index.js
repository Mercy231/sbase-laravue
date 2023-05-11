import {createStore} from "vuex"
import createPersistedState from 'vuex-persistedstate'
import auth from './modules/auth'
import helpers from './modules/helpers'
import posts from './modules/posts'

export default createStore({
    plugins: [createPersistedState()],
    modules: {
        auth,
        helpers,
        posts,
    },
})
