<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const auth   = useAuthStore()

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
          <p>Ready to practice for your interview? Choose a role and start prepping.</p>
        </div>
        <router-link to="/prep" class="btn-start">Start Practicing →</router-link>
      </div>

      <div class="cards-grid">
        <div class="info-card">
          <div class="card-icon">🎯</div>
          <h4>Role-Specific Questions</h4>
          <p>Get 5 targeted interview questions matched to your chosen job category in the QC market.</p>
        </div>
        <div class="info-card">
          <div class="card-icon">🇵🇭</div>
          <h4>Dual Language Support</h4>
          <p>Toggle between English and Tagalog so you fully understand what the interviewer is asking.</p>
        </div>
        <div class="info-card">
          <div class="card-icon">💡</div>
          <h4>Interviewer Tips</h4>
          <p>See what interviewers are actually looking for — so you know how to frame your best answer.</p>
        </div>
        <div class="info-card">
          <div class="card-icon">📱</div>
          <h4>Take It With You</h4>
          <p>Screenshot your questions and review them right before walking into your interview.</p>
        </div>
      </div>
    </main>

    <footer>
      <p>PESO Quezon City · Interview Prep Tool · For jobseekers only</p>
    </footer>
  </div>
</template>

<style scoped>
.page { min-height: 100vh; display: flex; flex-direction: column; }

header { background: var(--navy); color: var(--white); padding: 20px 24px; }
.header-inner {
  max-width: 900px; margin: 0 auto;
  display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
.brand { display: flex; align-items: center; gap: 14px; }
.logo-badge {
  background: var(--gold); color: var(--navy);
  font-family: 'Playfair Display', serif;
  font-size: 12px; font-weight: 700; letter-spacing: 2px;
  padding: 6px 12px; border-radius: 6px;
}
h1 { font-family: 'Playfair Display', serif; font-size: 22px; line-height: 1; }
.tagline { font-size: 10px; opacity: .5; margin-top: 2px; }

.user-bar { display: flex; align-items: center; gap: 16px; }
.user-info { display: flex; align-items: center; gap: 10px; }
.avatar {
  width: 38px; height: 38px; border-radius: 50%;
  background: var(--gold); color: var(--navy);
  font-weight: 700; font-size: 16px;
  display: flex; align-items: center; justify-content: center;
}
.user-name  { font-size: 14px; font-weight: 600; }
.user-email { font-size: 11px; opacity: .5; }
.btn-logout {
  background: rgba(255,255,255,.1); color: var(--white);
  border: 1.5px solid rgba(255,255,255,.2); border-radius: 8px;
  padding: 8px 16px; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: all .2s;
}
.btn-logout:hover { background: rgba(255,255,255,.2); }

main { flex: 1; max-width: 900px; margin: 0 auto; width: 100%; padding: 32px 20px; }

.welcome-banner {
  background: var(--navy); color: var(--white);
  border-radius: 16px; padding: 32px;
  display: flex; align-items: center; justify-content: space-between;
  gap: 20px; flex-wrap: wrap; margin-bottom: 32px;
}
.welcome-banner h2 { font-family: 'Playfair Display', serif; font-size: 24px; margin-bottom: 6px; }
.welcome-banner p  { font-size: 14px; opacity: .75; }
.btn-start {
  background: var(--gold); color: var(--navy);
  border-radius: 10px; padding: 14px 24px;
  font-weight: 700; font-size: 15px;
  text-decoration: none; white-space: nowrap;
  transition: all .2s;
}
.btn-start:hover { background: var(--gold2); transform: translateY(-2px); }

.cards-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
.info-card {
  background: var(--white); border-radius: var(--radius);
  padding: 24px; box-shadow: var(--shadow);
}
.card-icon { font-size: 32px; margin-bottom: 12px; }
.info-card h4 { font-size: 16px; font-weight: 600; margin-bottom: 8px; }
.info-card p { font-size: 13px; color: #666; line-height: 1.5; }

@media (max-width: 768px) {
  .cards-grid { grid-template-columns: 1fr; }
  .welcome-banner { flex-direction: column; text-align: center; }
  .header-inner { flex-direction: column; }
}
</style>