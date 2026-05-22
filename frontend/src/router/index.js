import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import LoginPage    from '../views/LoginPage.vue'
import RegisterPage from '../views/RegisterPage.vue'
import Dashboard    from '../views/Dashboard.vue'
import PrepPage     from '../views/PrepPage.vue'
import AdminLogin   from '../views/admin/AdminLogin.vue'
import AdminPanel   from '../views/admin/AdminPanel.vue'

const routes = [
  { path: '/',                      redirect: '/dashboard' },
  { path: '/login',                 component: LoginPage,    meta: { guest: true } },
  { path: '/register',              component: RegisterPage, meta: { guest: true } },
  { path: '/dashboard',             component: Dashboard,    meta: { auth: true } },
  { path: '/prep',                  component: PrepPage,     meta: { auth: true } },
  { path: '/admin-peso-2024',       component: AdminLogin,   meta: { adminGuest: true } },
  { path: '/admin-peso-2024/panel', component: AdminPanel,   meta: { adminAuth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to) => {
  const auth = useAuthStore()
  if (to.meta.auth       && !auth.isLoggedIn)                         return '/login'
  if (to.meta.guest      && auth.isLoggedIn)                          return '/dashboard'
  if (to.meta.adminAuth  && !localStorage.getItem('admin_token'))     return '/admin-peso-2024'
  if (to.meta.adminGuest && localStorage.getItem('admin_token'))      return '/admin-peso-2024/panel'
})

export default router
