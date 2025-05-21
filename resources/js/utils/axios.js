import axios from 'axios'
import {router} from "@inertiajs/vue3";

const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
})

api.interceptors.request.use(config => {
    const token = localStorage.getItem('token') // hoặc từ Pinia / Vuex
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
})

api.interceptors.response.use(
    response => response,
    error => {
        if (error.response.status === 401) {
            router.visit('/login')
        }
        return Promise.reject(error)
    }
)

export default api
