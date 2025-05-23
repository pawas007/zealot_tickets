// src/router/index.ts
import {createRouter, createWebHistory, RouteRecordRaw} from 'vue-router'

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
        path: '/:catchAll(.*)',
        redirect: '/',
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})
router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token')
    if (to.meta.requiresAuth && !isAuthenticated) {
        next({name: 'login'})
    } else if (to.meta.requiresAuth === false && isAuthenticated) {
        next({name: 'home'})
    } else {
        next()
    }
})

export default router
