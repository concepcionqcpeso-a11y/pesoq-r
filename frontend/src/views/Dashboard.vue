<script setup>
defineOptions({ name: 'DashboardView' })
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const auth   = useAuthStore()

const roles = [
  { icon: '🎧', label: 'Customer Service / BPO',         key: 'bpo' },
  { icon: '🍽️', label: 'Service Crew / Food & Beverage', key: 'fnb' },
  { icon: '🗂️', label: 'Administrative & Office Staff',  key: 'admin' },
  { icon: '📦', label: 'Logistics & Skilled Trades',      key: 'logistics' },
]

const tips = [
  { icon: '👁️', title: 'Make Eye Contact',     body: 'Shows confidence and that you are engaged with the interviewer.' },
  { icon: '⏱️', title: 'Keep Answers Concise', body: 'Aim for 1-2 minutes per answer. Quality over quantity.' },
  { icon: '📖', title: 'Use the STAR Method',  body: 'Situation, Task, Action, Result — structure your answers clearly.' },
  { icon: '👔', title: 'Dress Professionally', body: 'First impressions matter. Dress one level above the role you want.' },
]

async function logout() {
  await auth.logout()
  router.push('/login')
}
</script>

<template>
  <div class="page">
    <header>
      <div class="header-inner">
        <div class="brand">
          <span class="logo-badge">PESO</span>
          <div>
            <h1>Q&amp;A Prep</h1>
            <p class="tagline">Quezon City Public Employment Service Office</p>
          </div>
        </div>
        <div class="user-bar">
          <div class="user-info">
            <div class="avatar">{{ auth.user?.full_name?.charAt(0) }}</div>
            <div>
              <p class="user-name">{{ auth.user?.full_name }}</p>
              <p class="user-email">{{ auth.user?.email }}</p>
            </div>
          </div>
          <button class="btn-logout" @click="logout">Sign Out</button>
        </div>
      </div>
    </header>

    <main>
      <div class="welcome-banner">
        <div>
          <h2>Good day, {{ auth.user?.full_name?.split(' ')[0] }}! 👋</h2>
          <p>You're one step closer to landing your dream job. Start practicing today!</p>
        </div>
        <router-link to="/prep" class="btn-start">Start Practicing →</router-link>
      </div>

      <div class="section">
        <div class="section-header">
          <h3>Available Job Roles</h3>
          <p>Pick any role below to get your 5 interview questions</p>
        </div>
        <div class="roles-grid">
          <router-link v-for="role in roles" :key="role.key" to="/prep" class="role-card">
            <span class="role-icon">{{ role.icon }}</span>
            <span class="role-label">{{ role.label }}</span>
            <span class="role-arrow">→</span>
          </router-link>
        </div>
      </div>

      <div class="section">
        <div class="section-header">
          <h3>How It Works</h3>
          <p>Three simple steps to interview confidence</p>
        </div>
        <div class="steps-grid">
          <div class="step-card">
            <div class="step-num">01</div>
            <h4>Choose Your Role</h4>
            <p>Select the job category that matches the position you're applying for.</p>
          </div>
          <div class="step-card">
            <div class="step-num">02</div>
            <h4>Read the Questions</h4>
            <p>Get 5 role-specific questions in English and Tagalog with interviewer tips.</p>
          </div>
          <div class="step-card">
            <div class="step-num">03</div>
            <h4>Practice & Succeed</h4>
            <p>Screenshot the questions, review them, and walk into your interview with confidence!</p>
          </div>
        </div>
      </div>

      <div class="section">
        <div class="section-header">
          <h3>Quick Interview Tips</h3>
          <p>General advice to help you shine on interview day</p>
        </div>
        <div class="tips-grid">
          <div v-for="tip in tips" :key="tip.title" class="tip-card">
            <div class="tip-icon">{{ tip.icon }}</div>
            <div>
              <h4>{{ tip.title }}</h4>
              <p>{{ tip.body }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="reminder-banner">
        <div class="reminder-icon">📅</div>
        <div>
          <h4>Interview Day Reminder</h4>
          <p>Arrive 10-15 minutes early, bring printed copies of your resume, and stay calm — you've got this!</p>
        </div>
        <router-link to="/prep" class="btn-prep">Practice Now →</router-link>
      </div>
    </main>

    <footer>
      <p>PESO Quezon City · Interview Prep Tool · For jobseekers only</p>
    </footer>
  </div>
</template>

<style scoped>
.page { min-height: 100vh; display: flex; flex-direction: column; background: var(--cream); }
header { background: var(--navy); color: var(--white); padding: 20px 24px; }
.header-inner {
  max-width: 960px; margin: 0 auto;
  display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
.brand { display: flex; align-items: center; gap: 14px; }
.logo-badge {
  background: var(--gold); color: var(--navy); font-family: 'Playfair Display', serif;
  font-size: 12px; font-weight: 700; letter-spacing: 2px; padding: 6px 12px; border-radius: 6px;
}
h1 { font-family: 'Playfair Display', serif; font-size: 22px; line-height: 1; }
.tagline { font-size: 10px; opacity: .5; margin-top: 2px; }
.user-bar { display: flex; align-items: center; gap: 16px; }
.user-info { display: flex; align-items: center; gap: 10px; }
.avatar {
  width: 38px; height: 38px; border-radius: 50%; background: var(--gold); color: var(--navy);
  font-weight: 700; font-size: 16px; display: flex; align-items: center; justify-content: center;
}
.user-name  { font-size: 14px; font-weight: 600; }
.user-email { font-size: 11px; opacity: .5; }
.btn-logout {
  background: rgba(255,255,255,.1); color: var(--white);
  border: 1.5px solid rgba(255,255,255,.2); border-radius: 8px;
  padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .2s;
}
.btn-logout:hover { background: rgba(255,255,255,.2); }
main { flex: 1; max-width: 960px; margin: 0 auto; width: 100%; padding: 32px 20px; }
.welcome-banner {
  background: var(--navy); color: var(--white); border-radius: 16px; padding: 32px;
  display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; margin-bottom: 36px;
}
.welcome-banner h2 { font-family: 'Playfair Display', serif; font-size: 26px; margin-bottom: 6px; }
.welcome-banner p  { font-size: 14px; opacity: .75; }
.btn-start {
  background: var(--gold); color: var(--navy); border-radius: 10px; padding: 14px 24px;
  font-weight: 700; font-size: 15px; text-decoration: none; white-space: nowrap; transition: all .2s;
}
.btn-start:hover { background: var(--gold2); transform: translateY(-2px); }
.section { margin-bottom: 36px; }
.section-header { margin-bottom: 16px; }
.section-header h3 { font-family: 'Playfair Display', serif; font-size: 20px; margin-bottom: 4px; }
.section-header p  { font-size: 13px; color: var(--slate); }
.roles-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
.role-card {
  display: flex; align-items: center; gap: 14px; background: var(--white);
  border: 2px solid transparent; border-radius: 12px; padding: 18px 20px;
  text-decoration: none; color: var(--navy); transition: all .2s; box-shadow: var(--shadow);
}
.role-card:hover { border-color: var(--gold); transform: translateY(-2px); }
.role-icon  { font-size: 24px; }
.role-label { flex: 1; font-size: 14px; font-weight: 600; }
.role-arrow { color: var(--gold); font-weight: 700; font-size: 18px; }
.steps-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.step-card {
  background: var(--white); border-radius: 12px; padding: 24px;
  box-shadow: var(--shadow); border-top: 4px solid var(--navy);
}
.step-num { font-family: 'Playfair Display', serif; font-size: 32px; font-weight: 700; color: var(--gold); margin-bottom: 12px; }
.step-card h4 { font-size: 15px; font-weight: 700; margin-bottom: 8px; }
.step-card p  { font-size: 13px; color: var(--slate); line-height: 1.6; }
.tips-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
.tip-card {
  background: var(--white); border-radius: 12px; padding: 20px;
  box-shadow: var(--shadow); display: flex; align-items: flex-start; gap: 14px;
}
.tip-icon { font-size: 26px; flex-shrink: 0; }
.tip-card h4 { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
.tip-card p  { font-size: 13px; color: var(--slate); line-height: 1.5; }
.reminder-banner {
  background: #FEF9EC; border: 2px solid var(--gold); border-radius: 14px; padding: 24px 28px;
  display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
}
.reminder-icon { font-size: 36px; flex-shrink: 0; }
.reminder-banner h4 { font-size: 16px; font-weight: 700; margin-bottom: 4px; }
.reminder-banner p  { font-size: 13px; color: var(--slate); line-height: 1.6; }
.btn-prep {
  background: var(--navy); color: var(--white); border-radius: 10px; padding: 12px 20px;
  font-weight: 700; font-size: 14px; text-decoration: none; white-space: nowrap;
  transition: all .2s; margin-left: auto;
}
.btn-prep:hover { background: var(--navy2); }
footer { background: var(--navy); color: rgba(255,255,255,.4); text-align: center; padding: 20px; font-size: 12px; }
@media (max-width: 768px) {
  .roles-grid  { grid-template-columns: 1fr; }
  .steps-grid  { grid-template-columns: 1fr; }
  .tips-grid   { grid-template-columns: 1fr; }
  .welcome-banner { flex-direction: column; align-items: flex-start; }
  .reminder-banner { flex-direction: column; }
  .btn-prep { margin-left: 0; }
  .header-inner { flex-direction: column; align-items: flex-start; }
}
</style>
