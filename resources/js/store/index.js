import {createStore} from "vuex"
import createPersistedState from 'vuex-persistedstate'
import auth from './modules/auth'
import helpers from './modules/helpers'

export default createStore({
    plugins: [createPersistedState()],
    modules: {
        auth,
        helpers,
    },
})
