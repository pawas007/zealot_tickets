import { useAuthStore } from '@/stores/auth'

export function useHasRole() {

        const auth = useAuthStore()

        const hasRole = (...roles: string[]): boolean => {
            return roles.includes(auth.userRole)
        }

        return { hasRole }

}
