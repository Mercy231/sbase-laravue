import './bootstrap'
import {createApp} from 'vue'
import Routes from './routes'
import Store from './store'
import App from './App.vue'

createApp(App).use(Routes).use(Store).mount('#app')
