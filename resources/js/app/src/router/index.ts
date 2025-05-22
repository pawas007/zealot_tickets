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
        path: '/',
        name: 'home',
        component: () => import('@/views/Home.vue'),

    },
    {
        path: '/tickets',
        name: 'tickets',
        component: () => import('@/views/ticket/TicketList.vue'),
        meta: {requiresAuth: true},
    },
    {
        path: '/tickets/:id',
        name: 'ticket-detail',
        component: () => import('@/views/ticket/TicketDetail.vue'),
        meta: {requiresAuth: true},
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
    } else {
        next()
    }
})

export default router
