<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const adminUser = JSON.parse(localStorage.getItem('admin_user') || '{}')

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api/admin',
  headers: { Authorization: `Bearer ${localStorage.getItem('admin_token')}` }
})

// ── State ──
const activeTab = ref('dashboard')
const stats     = ref(null)
const users     = ref([])
const questions = ref([])
const logs      = ref([])
const loading   = ref(false)
const search    = ref('')

// Question form
const showQForm  = ref(false)
const editingQ   = ref(null)
const qForm      = ref({ role_key:'', role_label:'', question_en:'', question_tl:'', tip_en:'', tip_tl:'' })
const roleOptions = [
  { key: 'bpo',       label: 'Customer Service / BPO' },
  { key: 'fnb',       label: 'Service Crew / Food & Beverage' },
  { key: 'admin',     label: 'Administrative & Office Staff' },
  { key: 'logistics', label: 'Logistics & Skilled Trades' },
]

// ── Load Data ──
async function loadStats()     { stats.value     = (await api.get('/stats')).data }
async function loadUsers()     { users.value     = (await api.get('/users')).data }
async function loadQuestions() { questions.value = (await api.get('/questions')).data }
async function loadLogs()      { logs.value      = (await api.get('/logs')).data }

async function switchTab(tab) {
  activeTab.value = tab
  if (tab === 'dashboard' && !stats.value)     await loadStats()
  if (tab === 'users'     && !users.value.length) await loadUsers()
  if (tab === 'questions' && !questions.value.length) await loadQuestions()
  if (tab === 'logs'      && !logs.value.length) await loadLogs()
}

onMounted(() => switchTab('dashboard'))

// ── Users ──
const filteredUsers = computed(() =>
  users.value.filter(u =>
    u.full_name.toLowerCase().includes(search.value.toLowerCase()) ||
    u.email.toLowerCase().includes(search.value.toLowerCase())
  )
)

async function toggleUser(user) {
  await api.patch(`/users/${user.id}/toggle`)
  user.is_active = !user.is_active
}

async function deleteUser(user) {
  if (!confirm(`Delete ${user.full_name}? This cannot be undone.`)) return
  await api.delete(`/users/${user.id}`)
  users.value = users.value.filter(u => u.id !== user.id)
}

// ── Questions ──
function openAddQuestion() {
  editingQ.value = null
  qForm.value = { role_key:'', role_label:'', question_en:'', question_tl:'', tip_en:'', tip_tl:'' }
  showQForm.value = true
}

function openEditQuestion(q) {
  editingQ.value = q
  qForm.value = { ...q }
  showQForm.value = true
}

function onRoleKeyChange() {
  const found = roleOptions.find(r => r.key === qForm.value.role_key)
  if (found) qForm.value.role_label = found.label
}

async function saveQuestion() {
  if (editingQ.value) {
    const { data } = await api.put(`/questions/${editingQ.value.id}`, qForm.value)
    const idx = questions.value.findIndex(q => q.id === editingQ.value.id)
    questions.value[idx] = data
  } else {
    const { data } = await api.post('/questions', qForm.value)
    questions.value.push(data)
  }
  showQForm.value = false
}

async function deleteQuestion(q) {
  if (!confirm(`Delete this question?`)) return
  await api.delete(`/questions/${q.id}`)
  questions.value = questions.value.filter(x => x.id !== q.id)
}

// ── Logout ──
function logout() {
  localStorage.removeItem('admin_token')
  localStorage.removeItem('admin_user')
  router.push('/admin-peso-2024')
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-PH', { year:'numeric', month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' })
}
</script>

<template>
  <div class="panel">

    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="sidebar-brand">
        <span class="badge">PESO</span>
        <div>
          <p class="brand-title">Admin Panel</p>
          <p class="brand-sub">QC PESO</p>
        </div>
      </div>

      <nav class="sidebar-nav">
        <button :class="{ active: activeTab==='dashboard' }" @click="switchTab('dashboard')">
          📊 Dashboard
        </button>
        <button :class="{ active: activeTab==='users' }" @click="switchTab('users')">
          👥 Users
        </button>
        <button :class="{ active: activeTab==='questions' }" @click="switchTab('questions')">
          ❓ Questions
        </button>
        <button :class="{ active: activeTab==='logs' }" @click="switchTab('logs')">
          📋 Activity Logs
        </button>
      </nav>

      <div class="sidebar-footer">
        <div class="admin-info">
          <div class="avatar">A</div>
          <div>
            <p class="admin-name">{{ adminUser.full_name }}</p>
            <p class="admin-role">Administrator</p>
          </div>
        </div>
        <button class="btn-logout" @click="logout">Sign Out</button>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">

      <!-- DASHBOARD TAB -->
      <div v-if="activeTab==='dashboard'">
        <div class="page-header">
          <h2>Dashboard</h2>
          <p>Overview of the PESO Q&A Prep system</p>
        </div>

        <div v-if="stats" class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-value">{{ stats.total_users }}</div>
            <div class="stat-label">Total Jobseekers</div>
          </div>
          <div class="stat-card green">
            <div class="stat-icon">✅</div>
            <div class="stat-value">{{ stats.active_users }}</div>
            <div class="stat-label">Active Users</div>
          </div>
          <div class="stat-card red">
            <div class="stat-icon">🚫</div>
            <div class="stat-value">{{ stats.inactive_users }}</div>
            <div class="stat-label">Inactive Users</div>
          </div>
          <div class="stat-card gold">
            <div class="stat-icon">🆕</div>
            <div class="stat-value">{{ stats.new_today }}</div>
            <div class="stat-label">New Today</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon">❓</div>
            <div class="stat-value">{{ stats.total_questions }}</div>
            <div class="stat-label">Total Questions</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon">🔐</div>
            <div class="stat-value">{{ stats.total_logins }}</div>
            <div class="stat-label">Total Logins</div>
          </div>
        </div>

        <div v-if="stats?.popular_roles?.length" class="popular-roles">
          <h3>Most Practiced Roles</h3>
          <div class="role-bars">
            <div v-for="r in stats.popular_roles" :key="r.role" class="role-bar-item">
              <span class="role-name">{{ r.role }}</span>
              <div class="bar-wrap">
                <div class="bar-fill" :style="{ width: (r.count / stats.popular_roles[0].count * 100) + '%' }"></div>
              </div>
              <span class="role-count">{{ r.count }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- USERS TAB -->
      <div v-if="activeTab==='users'">
        <div class="page-header">
          <h2>User Management</h2>
          <p>View and manage registered jobseekers</p>
        </div>

        <div class="toolbar">
          <input v-model="search" type="text" placeholder="🔍 Search by name or email..." class="search-input" />
        </div>

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registered</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in filteredUsers" :key="user.id">
                <td><strong>{{ user.full_name }}</strong></td>
                <td>{{ user.email }}</td>
                <td>{{ user.phone || '—' }}</td>
                <td>{{ formatDate(user.created_at) }}</td>
                <td>
                  <span :class="['status-badge', user.is_active ? 'active' : 'inactive']">
                    {{ user.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="actions">
                  <button class="btn-toggle" @click="toggleUser(user)">
                    {{ user.is_active ? 'Deactivate' : 'Activate' }}
                  </button>
                  <button class="btn-delete" @click="deleteUser(user)">Delete</button>
                </td>
              </tr>
              <tr v-if="!filteredUsers.length">
                <td colspan="6" class="empty">No users found.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- QUESTIONS TAB -->
      <div v-if="activeTab==='questions'">
        <div class="page-header">
          <h2>Question Management</h2>
          <p>Add, edit, or delete interview questions</p>
        </div>

        <button class="btn-add" @click="openAddQuestion">+ Add New Question</button>

        <!-- Question Form Modal -->
        <div v-if="showQForm" class="modal-overlay" @click.self="showQForm=false">
          <div class="modal">
            <h3>{{ editingQ ? 'Edit Question' : 'Add New Question' }}</h3>

            <div class="form-group">
              <label>Job Role</label>
              <select v-model="qForm.role_key" @change="onRoleKeyChange">
                <option value="" disabled>Select a role</option>
                <option v-for="r in roleOptions" :key="r.key" :value="r.key">{{ r.label }}</option>
              </select>
            </div>

            <div class="form-group">
              <label>Question (English)</label>
              <textarea v-model="qForm.question_en" rows="2" placeholder="Enter question in English"></textarea>
            </div>
            <div class="form-group">
              <label>Question (Tagalog)</label>
              <textarea v-model="qForm.question_tl" rows="2" placeholder="Ilagay ang tanong sa Tagalog"></textarea>
            </div>
            <div class="form-group">
              <label>Tip (English)</label>
              <textarea v-model="qForm.tip_en" rows="2" placeholder="What should a good answer include?"></textarea>
            </div>
            <div class="form-group">
              <label>Tip (Tagalog)</label>
              <textarea v-model="qForm.tip_tl" rows="2" placeholder="Ano ang dapat isama sa magandang sagot?"></textarea>
            </div>

            <div class="modal-actions">
              <button class="btn-cancel" @click="showQForm=false">Cancel</button>
              <button class="btn-save" @click="saveQuestion">
                {{ editingQ ? 'Save Changes' : 'Add Question' }}
              </button>
            </div>
          </div>
        </div>

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Role</th>
                <th>Question (EN)</th>
                <th>Tip (EN)</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="q in questions" :key="q.id">
                <td><span class="role-tag">{{ q.role_key }}</span></td>
                <td class="question-cell">{{ q.question_en }}</td>
                <td class="tip-cell">{{ q.tip_en }}</td>
                <td class="actions">
                  <button class="btn-toggle" @click="openEditQuestion(q)">Edit</button>
                  <button class="btn-delete" @click="deleteQuestion(q)">Delete</button>
                </td>
              </tr>
              <tr v-if="!questions.length">
                <td colspan="4" class="empty">No questions found.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- LOGS TAB -->
      <div v-if="activeTab==='logs'">
        <div class="page-header">
          <h2>Activity Logs</h2>
          <p>Recent system activity — last 100 entries</p>
        </div>

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>User</th>
                <th>Email</th>
                <th>Action</th>
                <th>Description</th>
                <th>Date & Time</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="log in logs" :key="log.id">
                <td><strong>{{ log.user?.full_name || '—' }}</strong></td>
                <td>{{ log.user?.email || '—' }}</td>
                <td><span class="action-badge">{{ log.action }}</span></td>
                <td>{{ log.description }}</td>
                <td>{{ formatDate(log.created_at) }}</td>
              </tr>
              <tr v-if="!logs.length">
                <td colspan="5" class="empty">No activity logs yet.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</template>

<style scoped>
.panel { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }

/* SIDEBAR */
.sidebar {
  background: var(--navy); color: var(--white);
  display: flex; flex-direction: column; padding: 28px 20px;
  position: sticky; top: 0; height: 100vh;
}
.sidebar-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 36px; }
.badge {
  background: var(--gold); color: var(--navy);
  font-family: 'Playfair Display', serif;
  font-size: 11px; font-weight: 700; letter-spacing: 2px;
  padding: 6px 10px; border-radius: 6px; flex-shrink: 0;
}
.brand-title { font-weight: 700; font-size: 14px; }
.brand-sub   { font-size: 11px; opacity: .5; }

.sidebar-nav { display: flex; flex-direction: column; gap: 6px; flex: 1; }
.sidebar-nav button {
  background: none; border: none; color: rgba(255,255,255,.65);
  text-align: left; padding: 12px 16px; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 500;
  cursor: pointer; transition: all .2s;
}
.sidebar-nav button:hover  { background: rgba(255,255,255,.08); color: var(--white); }
.sidebar-nav button.active { background: var(--gold); color: var(--navy); font-weight: 700; }

.sidebar-footer { border-top: 1px solid rgba(255,255,255,.1); padding-top: 20px; }
.admin-info { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
.avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: var(--gold); color: var(--navy);
  font-weight: 700; display: flex; align-items: center; justify-content: center;
}
.admin-name { font-size: 13px; font-weight: 600; }
.admin-role { font-size: 11px; opacity: .5; }
.btn-logout {
  width: 100%; padding: 10px;
  background: rgba(255,255,255,.08); color: rgba(255,255,255,.7);
  border: 1px solid rgba(255,255,255,.15); border-radius: 8px;
  font-size: 13px; font-weight: 600; cursor: pointer; transition: all .2s;
}
.btn-logout:hover { background: rgba(255,255,255,.15); color: var(--white); }

/* CONTENT */
.content { background: var(--cream); padding: 36px; overflow-y: auto; }
.page-header { margin-bottom: 28px; }
.page-header h2 { font-family: 'Playfair Display', serif; font-size: 26px; margin-bottom: 4px; }
.page-header p  { color: var(--slate); font-size: 14px; }

/* STATS */
.stats-grid {
  display: grid; grid-template-columns: repeat(3, 1fr);
  gap: 16px; margin-bottom: 32px;
}
.stat-card {
  background: var(--white); border-radius: var(--radius);
  padding: 24px; box-shadow: var(--shadow);
  border-left: 4px solid var(--navy);
}
.stat-card.green  { border-color: #38a169; }
.stat-card.red    { border-color: #e53e3e; }
.stat-card.gold   { border-color: var(--gold); }
.stat-icon  { font-size: 24px; margin-bottom: 8px; }
.stat-value { font-size: 32px; font-weight: 700; font-family: 'Playfair Display', serif; }
.stat-label { font-size: 13px; color: var(--slate); margin-top: 4px; }

.popular-roles { background: var(--white); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); }
.popular-roles h3 { font-family: 'Playfair Display', serif; font-size: 18px; margin-bottom: 20px; }
.role-bars { display: flex; flex-direction: column; gap: 14px; }
.role-bar-item { display: flex; align-items: center; gap: 12px; }
.role-name  { font-size: 13px; font-weight: 600; width: 220px; flex-shrink: 0; }
.bar-wrap   { flex: 1; background: #e2e8f0; border-radius: 4px; height: 10px; overflow: hidden; }
.bar-fill   { height: 100%; background: var(--gold); border-radius: 4px; transition: width .5s ease; }
.role-count { font-size: 13px; font-weight: 700; width: 30px; text-align: right; }

/* TABLES */
.toolbar { margin-bottom: 16px; }
.search-input {
  width: 100%; max-width: 360px; padding: 10px 16px;
  border: 2px solid #e2e8f0; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 14px; outline: none;
}
.search-input:focus { border-color: var(--navy); }
.table-wrap { background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
table { width: 100%; border-collapse: collapse; }
thead { background: var(--navy); color: var(--white); }
th { padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; letter-spacing: .5px; }
td { padding: 14px 16px; font-size: 13px; border-bottom: 1px solid #f0f4f8; }
tr:last-child td { border-bottom: none; }
tr:hover td { background: #fafbfc; }
.empty { text-align: center; color: var(--slate); padding: 32px; }
.question-cell { max-width: 260px; }
.tip-cell { max-width: 200px; color: var(--slate); font-size: 12px; }

.status-badge {
  padding: 4px 10px; border-radius: 20px;
  font-size: 11px; font-weight: 700; letter-spacing: .5px;
}
.status-badge.active   { background: #c6f6d5; color: #276749; }
.status-badge.inactive { background: #fed7d7; color: #9b2c2c; }

.action-badge {
  background: #ebf4ff; color: #2b6cb0;
  padding: 3px 8px; border-radius: 6px;
  font-size: 11px; font-weight: 700;
}
.role-tag {
  background: rgba(11,31,58,.08); color: var(--navy);
  padding: 3px 8px; border-radius: 6px;
  font-size: 11px; font-weight: 700; text-transform: uppercase;
}

.actions { display: flex; gap: 8px; }
.btn-toggle {
  padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;
  background: #ebf4ff; color: #2b6cb0; border: none; cursor: pointer; transition: all .2s;
}
.btn-toggle:hover { background: #bee3f8; }
.btn-delete {
  padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;
  background: #fff5f5; color: #c53030; border: none; cursor: pointer; transition: all .2s;
}
.btn-delete:hover { background: #fed7d7; }
.btn-add {
  background: var(--navy); color: var(--white);
  border: none; border-radius: 10px;
  padding: 12px 20px; font-size: 14px; font-weight: 700;
  cursor: pointer; transition: all .2s; margin-bottom: 20px;
}
.btn-add:hover { background: var(--navy2); transform: translateY(-1px); }

/* MODAL */
.modal-overlay {
  position: fixed; inset: 0;
  background: rgba(11,31,58,.5);
  display: flex; align-items: center; justify-content: center;
  z-index: 100; padding: 24px;
}
.modal {
  background: var(--white); border-radius: 16px;
  padding: 32px; width: 100%; max-width: 560px;
  box-shadow: var(--shadow-lg); max-height: 90vh; overflow-y: auto;
}
.modal h3 { font-family: 'Playfair Display', serif; font-size: 22px; margin-bottom: 20px; }
.form-group { margin-bottom: 14px; }
.form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--slate); margin-bottom: 6px; letter-spacing: .3px; }
.form-group select,
.form-group textarea {
  width: 100%; padding: 10px 14px;
  border: 2px solid #e2e8f0; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 14px;
  outline: none; color: var(--navy); transition: border-color .2s; resize: vertical;
}
.form-group select:focus,
.form-group textarea:focus { border-color: var(--navy); }
.modal-actions { display: flex; gap: 12px; margin-top: 20px; justify-content: flex-end; }
.btn-cancel {
  padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600;
  background: #f7fafc; color: var(--slate); border: 2px solid #e2e8f0; cursor: pointer;
}
.btn-save {
  padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 700;
  background: var(--navy); color: var(--white); border: none; cursor: pointer; transition: all .2s;
}
.btn-save:hover { background: var(--navy2); }
</style>