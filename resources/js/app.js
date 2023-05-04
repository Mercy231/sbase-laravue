import './bootstrap'

import {createApp} from 'vue'

import Routes from './routes'
import App from './App.vue'

createApp(App).use(Routes).mount('#app')
