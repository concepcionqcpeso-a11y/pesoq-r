<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router  = useRouter()
const loading = ref(false)
const error   = ref('')
const success  = ref(false)

const form = ref({
  full_name: '', email: '', contact_number: '',
  password: '', password_confirmation: ''
})

async function submit() {
  error.value   = ''
  loading.value = true
  try {
    await axios.post('http://127.0.0.1:8000/api/register', {
      full_name:             form.value.full_name,
      email:                 form.value.email,
      phone:                 form.value.contact_number,
      password:              form.value.password,
      password_confirmation: form.value.password_confirmation,
    })
    localStorage.removeItem('user')
    localStorage.removeItem('token')
    success.value = true
    setTimeout(() => router.push('/login'), 2000)
  } catch (e) {
    const errors = e.response?.data?.errors
    if (errors) {
      error.value = Object.values(errors)[0][0]
    } else {
      error.value = e.response?.data?.message || 'Registration failed. Please try again.'
    }
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
        <h2>Start Your Journey<br/>to Employment.</h2>
        <p>Create a free account and get access to interview questions tailored for your target role in QC.</p>
      </div>
      <div class="steps">
        <div class="step-item"><span>01</span> Register for free</div>
        <div class="step-item"><span>02</span> Pick your job role</div>
        <div class="step-item"><span>03</span> Practice & get hired!</div>
      </div>
    </div>

    <div class="auth-right">
      <div class="auth-card">

        <!-- SUCCESS SCREEN -->
        <div v-if="success" class="success-box">
          <div class="check-wrap">
            <svg class="check-svg" viewBox="0 0 52 52">
              <circle class="check-circle" cx="26" cy="26" r="25" fill="none"/>
              <path class="check-tick" fill="none" d="M14 27 l8 8 l16-16"/>
            </svg>
          </div>
          <h3>Registration Successful!</h3>
          <p>Your account has been created.<br/>Redirecting you to the login page...</p>
          <div class="progress-bar"><div class="progress-fill"></div></div>
        </div>

        <!-- REGISTRATION FORM -->
        <template v-if="!success">
          <div class="card-header">
            <h3>Create Account</h3>
            <p>Join PESO Q&A Prep today — it's free</p>
          </div>

          <div v-if="error" class="error-box">{{ error }}</div>

          <div class="form-row">
            <div class="form-group">
              <label>Full Name</label>
              <input v-model="form.full_name" type="text" placeholder="Juan dela Cruz" />
            </div>
            <div class="form-group">
              <label>Phone Number</label>
              <input v-model="form.contact_number" type="text" placeholder="09XXXXXXXXX" maxlength="11"
                @input="form.contact_number = form.contact_number.replace(/\D/g, '').slice(0, 11)" />
            </div>
          </div>

          <div class="form-group">
            <label>Email Address</label>
            <input v-model="form.email" type="email" placeholder="Example@gmail.com" />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Password</label>
              <input v-model="form.password" type="password" placeholder="Min. 8 characters" />
            </div>
            <div class="form-group">
              <label>Confirm Password</label>
              <input v-model="form.password_confirmation" type="password" placeholder="Repeat password" />
            </div>
          </div>

          <button class="btn-primary" :disabled="loading" @click="submit">
            <span v-if="loading" class="spinner-sm"></span>
            <span v-else>Create Account →</span>
          </button>

          <p class="switch-link">
            Already have an account?
            <router-link to="/login">Sign in here</router-link>
          </p>
        </template>

      </div>
    </div>
  </div>
</template>

<style scoped>
.auth-page { min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; }
.auth-left {
  background: var(--navy); color: var(--white);
  padding: 48px; display: flex; flex-direction: column; gap: 48px;
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
  font-family: 'Playfair Display', serif; font-size: 34px; line-height: 1.2; margin-bottom: 16px;
}
.tagline-block p { font-size: 15px; opacity: .7; line-height: 1.7; }
.steps { display: flex; flex-direction: column; gap: 16px; margin-top: auto; }
.step-item { display: flex; align-items: center; gap: 14px; font-size: 14px; font-weight: 500; opacity: .85; }
.step-item span {
  background: rgba(212,160,23,.2); color: var(--gold);
  font-weight: 700; font-size: 12px; letter-spacing: 1px;
  padding: 4px 10px; border-radius: 6px; flex-shrink: 0;
}
.auth-right {
  display: flex; align-items: center; justify-content: center;
  padding: 48px 32px; background: var(--cream);
}
.auth-card {
  width: 100%; max-width: 460px; background: var(--white);
  border-radius: 20px; padding: 40px; box-shadow: var(--shadow-lg);
}
.card-header { margin-bottom: 24px; }
.card-header h3 { font-family: 'Playfair Display', serif; font-size: 26px; margin-bottom: 4px; }
.card-header p  { color: var(--slate); font-size: 14px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--slate); margin-bottom: 6px; }
.form-group input {
  width: 100%; padding: 11px 14px; border: 2px solid #e2e8f0; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 14px; outline: none;
  color: var(--navy); transition: border-color .2s;
}
.form-group input:focus { border-color: var(--navy); }
.btn-primary {
  width: 100%; padding: 14px; background: var(--navy); color: var(--white);
  border: none; border-radius: 10px; font-family: 'DM Sans', sans-serif;
  font-size: 15px; font-weight: 700; cursor: pointer; transition: all .2s;
  display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 4px;
}
.btn-primary:hover:not(:disabled) { background: var(--navy2); transform: translateY(-1px); }
.btn-primary:disabled { opacity: .6; cursor: not-allowed; }
.spinner-sm {
  width: 18px; height: 18px; border: 2px solid rgba(255,255,255,.3);
  border-top-color: white; border-radius: 50%; animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.error-box {
  background: #fff5f5; border: 1.5px solid #fc8181; border-radius: 10px;
  padding: 12px 16px; color: var(--red); font-size: 13px; margin-bottom: 16px;
}
.switch-link { text-align: center; font-size: 13px; color: var(--slate); margin-top: 18px; }
.switch-link a { color: var(--navy); font-weight: 700; text-decoration: none; }
.switch-link a:hover { color: var(--gold); }
.success-box {
  text-align: center; padding: 30px 10px;
  animation: fade-up 0.25s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
@keyframes fade-up {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}
.check-wrap { display: flex; justify-content: center; margin-bottom: 24px; }
.check-svg  { width: 90px; height: 90px; }
.check-circle {
  stroke: var(--gold); stroke-width: 2.5;
  stroke-dasharray: 166; stroke-dashoffset: 166;
  animation: draw-circle 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
.check-tick {
  stroke: var(--navy); stroke-width: 3.5;
  stroke-linecap: round; stroke-linejoin: round;
  stroke-dasharray: 48; stroke-dashoffset: 48;
  animation: draw-tick 0.2s cubic-bezier(0.4, 0, 0.2, 1) 0.25s forwards;
}
@keyframes draw-circle {
  0%   { stroke-dashoffset: 166; opacity: 0.4; }
  100% { stroke-dashoffset: 0;   opacity: 1; }
}
@keyframes draw-tick { 0% { stroke-dashoffset: 48; } 100% { stroke-dashoffset: 0; } }
.success-box h3 { font-family: 'Playfair Display', serif; font-size: 26px; margin-bottom: 10px; color: var(--navy); }
.success-box p  { font-size: 14px; color: var(--slate); line-height: 1.7; margin-bottom: 28px; }
.progress-bar   { height: 5px; background: #e2e8f0; border-radius: 4px; overflow: hidden; }
.progress-fill  {
  height: 100%; background: var(--gold); border-radius: 4px;
  animation: fill 2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
@keyframes fill { from { width: 0% } to { width: 100% } }
@media (max-width: 768px) {
  .auth-page { grid-template-columns: 1fr; }
  .auth-left  { display: none; }
  .form-row   { grid-template-columns: 1fr; }
}
</style>
