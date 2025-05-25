import axios from 'axios'

const baseURL = import.meta.env.VITE_API_DEFAULT_URL || ''

const api = axios.create({
    baseURL,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
})

api.interceptors.request.use(config => {
    const token = localStorage.getItem('token')
    if (token && config.headers) {
        config.headers['Authorization'] = `Bearer ${token}`
    }
    config.headers['X-Socket-ID'] = window.Echo.socketId()
    return config
})

api.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            localStorage.removeItem('token')
            window.location.href = '/login'
        }
        if (error.response?.status === 403) {
            window.location.href = '/403'

        }
        return Promise.reject(error)
    }
)

export function useApi() {
    return { api }
}
