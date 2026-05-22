<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const token  = localStorage.getItem('token')
const api    = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  headers: {
    Authorization: `Bearer ${token}`,
    'Content-Type': 'application/json',
    Accept: 'application/json',
  }
})

const roles        = ref([])
const selectedRole = ref(null)
const questions    = ref([])
const loading      = ref(false)
const error        = ref(null)
const language     = ref('en')
const expanded     = ref({})

const icons = { bpo: '🎧', fnb: '🍽️', admin: '🗂️', logistics: '📦' }
function roleIcon(key) { return icons[key] || '💼' }

onMounted(async () => {
  try {
    const { data } = await api.get('/roles')
    roles.value = data
  } catch {
    error.value = 'Could not load roles. Is the Laravel server running on port 8000?'
  }
})

async function selectRole(role) {
  selectedRole.value = role
  questions.value    = []
  expanded.value     = {}
  loading.value      = true
  error.value        = null
  try {
    const { data } = await api.get(`/questions/${role.role_key}`)
    questions.value = data
  } catch {
    error.value = 'Failed to load questions. Please try again.'
  } finally {
    loading.value = false
  }
}

function toggleTip(id) { expanded.value[id] = !expanded.value[id] }
function reset() { selectedRole.value = null; questions.value = []; error.value = null }
</script>

<template>
  <div class="app">
    <nav class="top-nav">
      <router-link to="/dashboard" class="back-btn">← Back to Dashboard</router-link>
    </nav>

    <header>
      <div class="header-inner">
        <div class="logo-wrap">
          <span class="logo-badge">PESO</span>
          <div>
            <h1>Q&amp;A Prep</h1>
            <p class="tagline">Quezon City Public Employment Service Office</p>
          </div>
        </div>
        <p class="subtitle">Your last-minute interview confidence engine</p>
      </div>
    </header>

    <main>
      <section v-if="!selectedRole" class="step">
        <div class="step-label"><span>01</span> Choose Your Job Role</div>
        <p class="step-hint">Select the position you are applying for today.</p>
        <div v-if="error" class="error-box">{{ error }}</div>
        <div class="role-grid">
          <button v-for="role in roles" :key="role.role_key" class="role-card" @click="selectRole(role)">
            <span class="role-icon-lg">{{ roleIcon(role.role_key) }}</span>
            <span class="role-label-text">{{ role.role_label }}</span>
            <span class="role-arrow">→</span>
          </button>
        </div>
      </section>

      <section v-else class="step">
        <div class="toolbar">
          <button class="btn-back" @click="reset">← Back</button>
          <div class="role-title">
            <span>{{ roleIcon(selectedRole.role_key) }}</span>
            {{ selectedRole.role_label }}
          </div>
          <div class="lang-toggle">
            <button :class="{ active: language==='en' }" @click="language='en'">EN</button>
            <button :class="{ active: language==='tl' }" @click="language='tl'">TL</button>
          </div>
        </div>

        <div v-if="loading" class="loading-wrap">
          <div class="spinner"></div><p>Loading questions…</p>
        </div>
        <div v-if="error" class="error-box">{{ error }}</div>

        <div v-if="!loading && questions.length" class="question-list">
          <div v-for="(q, i) in questions" :key="q.id" class="q-card">
            <div class="q-number">Q{{ i + 1 }}</div>
            <div class="q-body">
              <p class="q-text">{{ language==='en' ? q.question_en : q.question_tl }}</p>
              <button class="tip-toggle" @click="toggleTip(q.id)">
                {{ expanded[q.id] ? '▲ Hide Tip' : '▼ Show Interviewer Tip' }}
              </button>
              <div v-if="expanded[q.id]" class="tip-box">
                <strong>💡 What they're looking for:</strong>
                <p>{{ language==='en' ? q.tip_en : q.tip_tl }}</p>
              </div>
            </div>
          </div>
        </div>

        <div v-if="!loading && questions.length" class="save-hint">
          📱 Screenshot this page to review before your interview!
        </div>
      </section>
    </main>

    <footer>
      <p>PESO Quezon City · Interview Prep Tool · For jobseekers only</p>
    </footer>
  </div>
</template>

<style scoped>
.app { min-height: 100vh; display: flex; flex-direction: column; }
.top-nav { background: var(--navy); padding: 10px 24px; }
.back-btn { color: rgba(255,255,255,.7); font-size: 13px; font-weight: 600; text-decoration: none; }
.back-btn:hover { color: var(--gold); }
header { background: var(--navy); color: var(--white); padding: 28px 24px; }
.header-inner { max-width: 720px; margin: 0 auto; }
.logo-wrap { display: flex; align-items: center; gap: 16px; margin-bottom: 8px; }
.logo-badge {
  background: var(--gold); color: var(--navy); font-family: 'Playfair Display', serif;
  font-size: 13px; font-weight: 700; letter-spacing: 2px; padding: 6px 12px; border-radius: 6px;
}
h1 { font-family: 'Playfair Display', serif; font-size: 26px; line-height: 1; }
.tagline  { font-size: 11px; opacity: .6; margin-top: 2px; }
.subtitle { font-size: 14px; opacity: .75; }
main { flex: 1; max-width: 720px; margin: 0 auto; width: 100%; padding: 32px 20px; }
.step-label { font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--gold); margin-bottom: 6px; }
.step-label span { background: var(--navy); color: var(--white); padding: 2px 8px; border-radius: 4px; margin-right: 8px; }
.step-hint { color: var(--slate); font-size: 14px; margin-bottom: 24px; }
.role-grid { display: grid; gap: 14px; }
.role-card {
  display: flex; align-items: center; gap: 16px; background: var(--white);
  border: 2px solid transparent; border-radius: var(--radius); padding: 20px 24px;
  cursor: pointer; transition: all .2s; box-shadow: var(--shadow); text-align: left; width: 100%;
}
.role-card:hover { border-color: var(--gold); transform: translateY(-2px); }
.role-icon-lg   { font-size: 28px; }
.role-label-text { flex: 1; font-size: 16px; font-weight: 600; color: var(--navy); }
.role-arrow { color: var(--gold); font-size: 20px; font-weight: 700; }
.toolbar { display: flex; align-items: center; gap: 12px; margin-bottom: 28px; flex-wrap: wrap; }
.btn-back {
  background: none; border: 2px solid var(--navy); color: var(--navy);
  border-radius: 8px; padding: 8px 16px; font-weight: 600; cursor: pointer; transition: all .2s;
}
.btn-back:hover { background: var(--navy); color: var(--white); }
.role-title { flex: 1; font-weight: 700; font-size: 15px; display: flex; align-items: center; gap: 8px; }
.lang-toggle { display: flex; border: 2px solid var(--navy); border-radius: 8px; overflow: hidden; }
.lang-toggle button {
  padding: 8px 16px; font-weight: 700; font-size: 13px;
  background: none; border: none; cursor: pointer; color: var(--navy); transition: all .2s;
}
.lang-toggle button.active { background: var(--navy); color: var(--white); }
.question-list { display: flex; flex-direction: column; gap: 16px; }
.q-card { background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow); display: flex; overflow: hidden; }
.q-number {
  background: var(--navy); color: var(--gold); font-family: 'Playfair Display', serif;
  font-size: 18px; font-weight: 700; writing-mode: vertical-rl;
  padding: 16px 12px; display: flex; align-items: center; justify-content: center;
}
.q-body { padding: 20px; flex: 1; }
.q-text { font-size: 16px; font-weight: 500; line-height: 1.6; margin-bottom: 14px; }
.tip-toggle {
  background: none; border: 1.5px solid var(--gold); color: var(--navy);
  border-radius: 6px; padding: 6px 14px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .2s;
}
.tip-toggle:hover { background: var(--gold); }
.tip-box {
  margin-top: 12px; background: #FEF9EC; border-left: 4px solid var(--gold);
  border-radius: 0 8px 8px 0; padding: 14px 16px; font-size: 14px; line-height: 1.6;
}
.tip-box strong { display: block; margin-bottom: 6px; color: var(--slate); font-size: 12px; }
.loading-wrap { display: flex; align-items: center; gap: 14px; padding: 32px 0; color: var(--slate); }
.spinner {
  width: 28px; height: 28px; border: 3px solid #e2e8f0;
  border-top-color: var(--navy); border-radius: 50%; animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.error-box {
  background: #fff5f5; border: 1.5px solid #fc8181; border-radius: 10px;
  padding: 14px 18px; color: #c53030; font-size: 14px; margin-bottom: 20px;
}
.save-hint {
  margin-top: 24px; text-align: center; font-size: 13px; color: var(--slate);
  padding: 16px; border: 1.5px dashed #cbd5e0; border-radius: 10px;
}
footer { background: var(--navy); color: rgba(255,255,255,.5); text-align: center; padding: 20px; font-size: 12px; }
</style>
