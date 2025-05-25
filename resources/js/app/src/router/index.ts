import {createRouter, createWebHistory, RouteRecordRaw} from 'vue-router'
import {useAuthStore} from '@/stores/auth'

const routes: RouteRecordRaw[] = [
    {
        path: '/login',
        name: 'login',
        component: () => import('@/views/auth/Login.vue'),
        meta: {requiresAuth: false, layout: 'blank'},
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('@/views/auth/Register.vue'),
        meta: {requiresAuth: false, layout: 'blank'},
    },
    {
        path: '/',
        name: 'home',
        component: () => import('@/views/Home.vue'),

    },
    {
        path: '/tickets',
        name: 'tickets',
        component: () => import('@/views/ticket/TicketList.vue'),
        meta: {requiresAuth: true}
    },
    {
        path: '/tickets/create',
        name: 'ticket-create',
        component: () => import('@/views/ticket/TicketCreate.vue'),
        meta: {requiresAuth: true}
    },
    {
        path: '/tickets/:id',
        name: 'ticket-detail',
        component: () => import('@/views/ticket/TicketDetail.vue'),
        meta: {requiresAuth: true}
    },
    {
        path: '/users/',
        name: 'users',
        component: () => import('@/views/users/UserList.vue'),
        meta: {requiresAuth: true, roles: ['admin']}
    },
    {
        path: '/users/create',
        name: 'users-create',
        component: () => import('@/views/users/UserCreate.vue'),
        meta: {requiresAuth: true, roles: ['admin']}
    },
    {
        path: '/403',
        name: 'forbidden',
        component: () => import('@/views/errors/403.vue'),
        meta: {layout: 'blank'},
    },
    {
        path: '/:catchAll(.*)',
        redirect: '/',
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})
router.beforeEach((to, from, next) => {
    const auth = useAuthStore()
    const isAuthenticated = !!localStorage.getItem('token')
    const requiresAuth = to.meta.requiresAuth === true
    const isPublic = to.meta.requiresAuth === false
    const allowedRoles = to.meta.roles as string[] | undefined
    const userRole = auth.userRole
    if (requiresAuth && !isAuthenticated) {
        return next({name: 'login'})
    }
    if (isPublic && isAuthenticated) {
        return next({name: 'home'})
    }

    if (allowedRoles && userRole && !allowedRoles.includes(userRole)) {
        return next({name: 'forbidden'})
    }
    next()
})

export default router
