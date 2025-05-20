import axios from 'axios'
import { useToast } from 'primevue/usetoast'

// Tạo instance mặc định
const api = axios.create({
    baseURL: '/api',
    withCredentials: true, // nếu dùng Sanctum
})

// Gắn Authorization header
api.interceptors.request.use(config => {
    const token = localStorage.getItem('token') // hoặc từ Pinia / Vuex
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
})

// Xử lý lỗi toàn cục
api.interceptors.response.use(
    response => response,
    error => {
        const message = error.response?.data?.message || 'Something went wrong'

        useToast().add({ severity: 'error', summary: 'Error', detail: message, life: 4000 })
        return Promise.reject(error)
    }
)

export default api
