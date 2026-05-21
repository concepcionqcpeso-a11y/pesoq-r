<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const auth   = useAuthStore()

const form  = ref({ email: '', password: '' })
const error = ref('')
const loading = ref(false)

async function submit() {
  error.value   = ''
  loading.value = true
  try {
    await auth.login(form.value)
    router.push('/dashboard')
  } catch (e) {
    error.value = e.response?.data?.message
      || Object.values(e.response?.data?.errors || {})[0]?.[0]
      || 'Login failed. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="auth-page">
    <div class="auth-left">
      <div class="brand">
        <span class="logo-badge">PESO</span>
        <div>
          <h1>Q&amp;A Prep</h1>
          <p>Quezon City Public Employment Service Office</p>
        </div>
      </div>
      <div class="tagline-block">
        <h2>Your Interview<br/>Confidence Starts Here.</h2>
        <p>Practice role-specific questions in English and Tagalog — tailored for the QC job market.</p>
      </div>
      <div class="steps">
        <div class="step-item"><span>01</span> Choose your job role</div>
        <div class="step-item"><span>02</span> Get 5 targeted questions</div>
        <div class="step-item"><span>03</span> Review tips & go ace it!</div>
      </div>
    </div>

    <div class="auth-right">
      <div class="auth-card">
        <div class="card-header">
          <h3>Welcome back</h3>
          <p>Sign in to continue your prep</p>
        </div>

        <div v-if="error" class="error-box">{{ error }}</div>

        <div class="form-group">
          <label>Email Address</label>
          <input v-model="form.email" type="email"
            placeholder="you@email.com" autocomplete="email" />
        </div>

        <div class="form-group">
          <label>Password</label>
          <input v-model="form.password" type="password"
            placeholder="••••••••" autocomplete="current-password" />
        </div>

        <button class="btn-primary" :disabled="loading" @click="submit">
          <span v-if="loading" class="spinner-sm"></span>
          <span v-else>Sign In →</span>
        </button>

        <p class="switch-link">
          Don't have an account?
          <router-link to="/register">Register here</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.auth-page {
  min-height: 100vh;
  display: grid;
  grid-template-columns: 1fr 1fr;
}

/* LEFT PANEL */
.auth-left {
  background: var(--navy);
  color: var(--white);
  padding: 48px;
  display: flex;
  flex-direction: column;
  gap: 48px;
}
.brand { display: flex; align-items: center; gap: 14px; }
.logo-badge {
  background: var(--gold); color: var(--navy);
  font-family: 'Playfair Display', serif;
  font-size: 13px; font-weight: 700; letter-spacing: 2px;
  padding: 8px 14px; border-radius: 8px; flex-shrink: 0;
}
.brand h1 { font-family: 'Playfair Display', serif; font-size: 22px; }
.brand p  { font-size: 11px; opacity: .5; margin-top: 2px; }

.tagline-block h2 {
  font-family: 'Playfair Display', serif;
  font-size: 36px; line-height: 1.2;
  margin-bottom: 16px;
}
.tagline-block p { font-size: 15px; opacity: .7; line-height: 1.7; }

.steps { display: flex; flex-direction: column; gap: 16px; margin-top: auto; }
.step-item {
  display: flex; align-items: center; gap: 14px;
  font-size: 14px; font-weight: 500; opacity: .85;
}
.step-item span {
  background: rgba(212,160,23,.2); color: var(--gold);
  font-weight: 700; font-size: 12px; letter-spacing: 1px;
  padding: 4px 10px; border-radius: 6px; flex-shrink: 0;
}

/* RIGHT PANEL */
.auth-right {
  display: flex; align-items: center; justify-content: center;
  padding: 48px 32px; background: var(--cream);
}
.auth-card {
  width: 100%; max-width: 420px;
  background: var(--white);
  border-radius: 20px;
  padding: 40px;
  box-shadow: var(--shadow-lg);
}
.card-header { margin-bottom: 28px; }
.card-header h3 {
  font-family: 'Playfair Display', serif;
  font-size: 26px; margin-bottom: 4px;
}
.card-header p { color: var(--slate); font-size: 14px; }

.form-group { margin-bottom: 18px; }
.form-group label {
  display: block; font-size: 13px; font-weight: 600;
  color: var(--slate); margin-bottom: 6px; letter-spacing: .3px;
}
.form-group input {
  width: 100%; padding: 12px 16px;
  border: 2px solid #e2e8f0; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 15px;
  transition: border-color .2s; outline: none; color: var(--navy);
}
.form-group input:focus { border-color: var(--navy); }

.btn-primary {
  width: 100%; padding: 14px;
  background: var(--navy); color: var(--white);
  border: none; border-radius: 10px;
  font-family: 'DM Sans', sans-serif;
  font-size: 15px; font-weight: 700;
  cursor: pointer; transition: all .2s;
  display: flex; align-items: center; justify-content: center;
  gap: 8px; margin-top: 8px;
}
.btn-primary:hover:not(:disabled) { background: var(--navy2); transform: translateY(-1px); }
.btn-primary:disabled { opacity: .6; cursor: not-allowed; }

.spinner-sm {
  width: 18px; height: 18px;
  border: 2px solid rgba(255,255,255,.3);
  border-top-color: white; border-radius: 50%;
  animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.error-box {
  background: #fff5f5; border: 1.5px solid #fc8181;
  border-radius: 10px; padding: 12px 16px;
  color: var(--red); font-size: 13px; margin-bottom: 18px;
}
.switch-link {
  text-align: center; font-size: 13px;
  color: var(--slate); margin-top: 20px;
}
.switch-link a { color: var(--navy); font-weight: 700; text-decoration: none; }
.switch-link a:hover { color: var(--gold); }

@media (max-width: 768px) {
  .auth-page { grid-template-columns: 1fr; }
  .auth-left  { display: none; }
}
</style>