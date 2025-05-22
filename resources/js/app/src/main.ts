import {createApp} from 'vue'
import App from './App.vue'
import router from "./router/index";
import Vue3Toastify, {toast, type ToastContainerOptions} from 'vue3-toastify';
import {createPinia} from 'pinia'
import './assets/css/tailwind.css'
import './assets/scss/main.scss'
import './utils/echo'
import 'vue3-toastify/dist/index.css';
import './utils/validation'

const app = createApp(App)

app.use(createPinia())
app.use(Vue3Toastify, {
    autoClose: 3000,
    position: toast.POSITION.TOP_CENTER,
} as ToastContainerOptions);
app.use(router)
router.isReady().then(() => {
    createApp(App).use(router).mount('#app')
})
