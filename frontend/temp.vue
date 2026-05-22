<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import axios from 'axios'

const router = useRouter()
const auth = useAuthStore()
const loading = ref(false)
const error = ref('')
const form = ref({ email: '', password: '' })

async function submit() {
  error.value = ''
  loading.value = true
  try {
    const { data } = await axios.post('http://127.0.0.1:8000/api/login', form.value)
    auth.setUser(data.user, data.token)
    localStorage.setItem('token', data.token)
    localStorage.setItem('user', JSON.stringify(data.user))
    router.push('/dashboard')
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
  <div class="login-page">
    <div class="login-box">
      <div class="logo-area">
        <span class="badge">PESO</span>
        <div>
          <h1>Jobseeker Login</h1>
          <p>Quezon City Public Employment Service Office</p>
        </div>
      </div>

      <div class="divider"></div>

      <h2>Interview Preparation</h2>
      <p class="sub">Practice for your interview with our Q&A prep system.</p>

      <div v-if="error" class="error-box">ðŸ”’ {{ error }}</div>

      <div class="form-group">
        <label>Email Address</label>
        <input v-model="form.email" type="email" placeholder="your@email.com" />
      </div>

      <div class="form-group">
        <label>Password</label>
        <input v-model="form.password" type="password" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢" @keyup.enter="submit" />
      </div>

      <button class="btn-login" :disabled="loading" @click="submit">
        <span v-if="loading" class="spinner"></span>
        <span v-else>Login â†’</span>
      </button>

      <p class="auth-links">
        <router-link to="/register">Create Account</router-link>
        <span> Â· </span>
        <router-link to="/admin-peso-2024">Admin Access</router-link>
      </p>
    </div>
  </div>
</template>

<style scoped>
.login-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #0b1f3a 0%, #1a3a52 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
}

.login-box {
  background: white;
  border-radius: 20px;
  padding: 44px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.logo-area {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 24px;
}

.badge {
  background: #0b1f3a;
  color: #d4af37;
  font-family: Arial, sans-serif;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 2px;
  padding: 8px 14px;
  border-radius: 8px;
  flex-shrink: 0;
}

.logo-area h1 {
  font-family: Arial, sans-serif;
  font-size: 20px;
  color: #0b1f3a;
  margin: 0;
}

.logo-area p {
  font-size: 10px;
  color: #64748b;
  margin: 2px 0 0 0;
}

.divider {
  height: 1px;
  background: #e2e8f0;
  margin-bottom: 24px;
}

h2 {
  font-family: Arial, sans-serif;
  font-size: 22px;
  margin: 0 0 6px 0;
  color: #0b1f3a;
}

.sub {
  font-size: 13px;
  color: #64748b;
  margin: 0 0 24px 0;
}

.error-box {
  background: #fff5f5;
  color: #c53030;
  border-left: 4px solid #c53030;
  padding: 12px 16px;
  border-radius: 8px;
  font-size: 13px;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 16px;
}

.form-group label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #64748b;
  margin-bottom: 6px;
}

.form-group input {
  width: 100%;
  padding: 12px 14px;
  border: 2px solid #e2e8f0;
  border-radius: 10px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  outline: none;
  color: #0b1f3a;
  transition: border-color 0.2s;
}

.form-group input:focus {
  border-color: #0b1f3a;
}

.btn-login {
  width: 100%;
  padding: 14px;
  background: #0b1f3a;
  color: white;
  border: none;
  border-radius: 10px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.btn-login:hover:not(:disabled) {
  background: #0f2847;
  transform: translateY(-2px);
  box-shadow: 0 8px 16px rgba(11, 31, 58, 0.2);
}

.btn-login:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.spinner {
  display: inline-block;
  width: 14px;
  height: 14px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.auth-links {
  margin-top: 20px;
  text-align: center;
  font-size: 13px;
  color: #64748b;
}

.auth-links a {
  color: #0b1f3a;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s;
}

.auth-links a:hover {
  color: #d4af37;
  text-decoration: underline;
}
</style>
          <p>Overview of the PESO Q&A Prep system</p>
