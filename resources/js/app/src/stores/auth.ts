import {defineStore} from 'pinia'
import {useApi} from '@/composables/useApi'

export const useAuthStore = defineStore('auth', {

    state: () => ({
        user: null,
    }),
    getters: {
        isLoggedIn: (state) => !!state.user,
        userName: (state) => state.user?.name,
        isAdmin: (state) => state.user?.role,
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
        }
    }
})
