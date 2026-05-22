<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router  = useRouter()
const loading = ref(false)
const error   = ref('')
const form    = ref({ email: '', password: '' })

async function submit() {
  error.value   = ''
  loading.value = true
  try {
    const { data } = await axios.post('http://127.0.0.1:8000/api/login', form.value)
    if (!data.user.is_admin) {
      error.value = 'Access denied. Admins only.'
      return
    }
    localStorage.setItem('admin_token', data.token)
    localStorage.setItem('admin_user',  JSON.stringify(data.user))
    router.push('/admin-peso-2024/panel')
  } catch (e) {
    error.value = e.response?.data?.message
      || Object.values(e.response?.data?.errors || {})[0]?.[0]
      || 'Login failed.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="admin-login-page">
    <div class="login-box">
      <div class="logo-area">
        <span class="badge">PESO</span>
        <div>
          <h1>Admin Panel</h1>
          <p>Quezon City Public Employment Service Office</p>
        </div>
      </div>

      <div class="divider"></div>

      <h2>Staff Access Only</h2>
      <p class="sub">This page is restricted to authorized PESO personnel.</p>

      <div v-if="error" class="error-box">🔒 {{ error }}</div>

      <div class="form-group">
        <label>Email Address</label>
        <input v-model="form.email" type="email" placeholder="admin@peso.gov.ph" />
      </div>

      <div class="form-group">
        <label>Password</label>
        <input v-model="form.password" type="password" placeholder="••••••••" @keyup.enter="submit" />
      </div>

      <button class="btn-login" :disabled="loading" @click="submit">
        <span v-if="loading" class="spinner"></span>
        <span v-else>Access Admin Panel →</span>
      </button>

      <p class="back-link">
        <router-link to="/login">← Back to Jobseeker Login</router-link>
      </p>
    </div>
  </div>
</template>

<style scoped>
.admin-login-page {
  min-height: 100vh; background: var(--navy);
  display: flex; align-items: center; justify-content: center; padding: 24px;
}
.login-box {
  background: var(--white); border-radius: 20px;
  padding: 44px; width: 100%; max-width: 420px; box-shadow: var(--shadow-lg);
}
.logo-area { display: flex; align-items: center; gap: 14px; margin-bottom: 24px; }
.badge {
  background: var(--navy); color: var(--gold); font-family: Arial, sans-serif;
  font-size: 13px; font-weight: 700; letter-spacing: 2px; padding: 8px 14px; border-radius: 8px; flex-shrink: 0;
}
.logo-area h1 { font-family: Arial, sans-serif; font-size: 20px; }
.divider { height: 1px; background: #e2e8f0; margin-bottom: 24px; }
h2  { font-family: Arial, sans-serif; font-size: 22px; margin-bottom: 6px; }
.sub { font-size: 13px; color: var(--slate); margin-bottom: 24px; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--slate); margin-bottom: 6px; }
.form-group input {
  width: 100%; padding: 12px 14px; border: 2px solid #e2e8f0; border-radius: 10px;
  font-family: Arial, sans-serif; font-size: 14px; outline: none; color: var(--navy); transition: border-color .2s;
}
.form-group input:focus { border-color: var(--navy); }
.btn-login {
  width: 100%; padding: 14px; background: var(--navy); color: var(--white);
  border: none; border-radius: 10px; font-family: Arial, sans-serif;
  font-size: 15px; font-weight: 700; cursor: pointer; transition: all .2s;
  display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 8px;
}
.btn-login:hover:not(:disabled) { background: var(--navy2); transform: translateY(-1px); }
.btn-login:disabled { opacity: .6; cursor: not-allowed; }
.spinner {
  width: 18px; height: 18px; border: 2px solid rgba(255,255,255,.3);
  border-top-color: white; border-radius: 50%; animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.error-box {
  background: #fff5f5; border: 1.5px solid #fc8181; border-radius: 10px;
  padding: 12px 16px; color: var(--red); font-size: 13px; margin-bottom: 16px;
}
.back-link { text-align: center; margin-top: 20px; font-size: 13px; }
.back-link a { color: var(--slate); text-decoration: none; font-weight: 600; }
.back-link a:hover { color: var(--navy); }
</style>