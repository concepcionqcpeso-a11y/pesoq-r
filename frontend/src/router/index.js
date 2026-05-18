import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import LoginPage    from '../views/LoginPage.vue'
import RegisterPage from '../views/RegisterPage.vue'
import Dashboard    from '../views/Dashboard.vue'
import PrepPage     from '../views/PrepPage.vue'

const routes = [
  { path: '/',          redirect: '/dashboard' },
  { path: '/login',     component: LoginPage,    meta: { guest: true } },
  { path: '/register',  component: RegisterPage, meta: { guest: true } },
  { path: '/dashboard', component: Dashboard,    meta: { auth: true } },
  { path: '/prep',      component: PrepPage,     meta: { auth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to) => {
  const auth = useAuthStore()
  if (to.meta.auth  && !auth.isLoggedIn) return '/login'
  if (to.meta.guest && auth.isLoggedIn)  return '/dashboard'
})

export default router