import {defineStore} from 'pinia'
import {useApi} from '@/composables/useApi'

export const useAuthStore = defineStore('auth', {

    state: () => ({
        user: null,
    }),
    getters: {
        isLoggedIn: (state) => !!state.user,
        userName: (state) => state.user?.name,
        userRole: (state) => state.user?.role,
    },
    actions: {
        async fetchAuthUser() {
            const {api} = useApi()
            try {
                const response = await api.get('/auth-user')
                this.user = response.data
            } catch (error) {
                console.error('Auth user fetch failed:', error)
                this.user = null
            }
        },
        async logOut() {
            const {api} = useApi()
            try {
                await api.post('/log-out').then(() => {
                    this.user = null
                    localStorage.removeItem('token')
                    window.location.href = '/login'
                })
            } catch (error) {
                console.error('Auth user logout:', error)
            }
        }
    }
})
