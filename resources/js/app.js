import './bootstrap'

import {createApp} from 'vue'
import {createStore} from 'vuex'
import Routes from './routes'
import App from './App.vue'

const  store = createStore({
    state() {
        return {
            user: {},
        }
    },
    getters: {
        getUser: (state) => state.user
    },
    actions: {
        async user ({commit}) {
            await axios
                .get('/user')
                .then(response => {
                    if ('user' in response.data) {
                        localStorage.setItem('isAuth', 'true')
                        commit('setUser', response.data.user)
                    } else {
                        localStorage.removeItem('isAuth')
                    }
                })
        }
    },
    mutations: {
        setUser(state, user) {
            state.user = user
        },
    }
})
createApp(App).use(Routes).use(store).mount('#app')
