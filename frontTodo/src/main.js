import { createApp } from 'vue';
import Notifications from '@kyvg/vue3-notification'

import App from './App.vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import router from './router';

const app = createApp(App)

app.use(router)
app.use(Notifications, { position: 'top-left' }) // Add this line

app.mount('#app')