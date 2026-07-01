<template>
  <!-- Bell button in the topbar -->
  <button class="notif-btn" @click="togglePanel" ref="bellRef" aria-label="Notificações">
    <svg width="16" height="16" fill="none" stroke="#555B5A" stroke-width="1.7" viewBox="0 0 16 16">
      <path d="M8 2a5 5 0 0 0-5 5v3l-1.5 2h13L13 10V7a5 5 0 0 0-5-5z" />
      <path d="M6.5 13.5a1.5 1.5 0 0 0 3 0" stroke-linecap="round" />
    </svg>
    <span v-if="unreadCount > 0" class="notif-badge">{{ unreadCount > 99 ? '99+' : unreadCount }}</span>
    <span v-else class="notif-dot"></span>
  </button>

  <!-- Dropdown panel - teleported to body so z-index is safe -->
  <Teleport to="body">
    <!-- Backdrop -->
    <div v-if="panelOpen" class="np-backdrop" @click="closePanel"></div>

    <!-- Panel -->
    <Transition name="np-drop">
      <div v-if="panelOpen" class="np-panel" :style="panelStyle">
        <div class="np-header">
          <span class="np-title">Notificações</span>
          <button v-if="unreadCount > 0" class="np-read-all" @click="handleMarkAllRead">
            Marcar todas como lidas
          </button>
        </div>

        <div class="np-body">
          <div v-if="loading" class="np-state">A carregar…</div>

          <div v-else-if="notifications.length === 0" class="np-state">
            <svg width="32" height="32" fill="none" stroke="#A0ABA7" stroke-width="1.5" viewBox="0 0 32 32">
              <path d="M16 4a10 10 0 0 0-10 10v6l-3 4h26l-3-4v-6a10 10 0 0 0-10-10z" />
              <path d="M13 27a3 3 0 0 0 6 0" stroke-linecap="round" />
            </svg>
            <p>Sem notificações</p>
          </div>

          <ul v-else class="np-list">
            <li
              v-for="n in notifications"
              :key="n.id"
              class="np-item"
              :class="{ 'np-item--unread': !n.read_at }"
              @click="handleMarkRead(n)"
            >
              <div class="np-item-icon">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
                  <path d="M8 2a5 5 0 0 0-5 5v3l-1.5 2h13L13 10V7a5 5 0 0 0-5-5z" />
                  <path d="M6.5 13.5a1.5 1.5 0 0 0 3 0" stroke-linecap="round" />
                </svg>
              </div>
              <div class="np-item-body">
                <p class="np-item-msg">{{ n.message }}</p>
                <span v-if="n.occurrence" class="np-item-code">{{ n.occurrence.tracking_code }}</span>
                <time class="np-item-time">{{ timeAgo(n.created_at) }}</time>
              </div>
              <span v-if="!n.read_at" class="np-unread-dot"></span>
            </li>
          </ul>
        </div>
      </div>
    </Transition>

    <!-- Toast alert for new notifications -->
    <Transition name="np-toast">
      <div v-if="toast.visible" class="np-toast">
        <svg width="16" height="16" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 16 16">
          <path d="M8 2a5 5 0 0 0-5 5v3l-1.5 2h13L13 10V7a5 5 0 0 0-5-5z" />
          <path d="M6.5 13.5a1.5 1.5 0 0 0 3 0" stroke-linecap="round" />
        </svg>
        <div>
          <strong>Nova ocorrência</strong>
          <p>{{ toast.message }}</p>
        </div>
        <button class="np-toast-close" @click="toast.visible = false">×</button>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted, nextTick } from 'vue'
import { NotificationService } from '@/api/services/notification.service'

const bellRef   = ref(null)
const panelOpen = ref(false)
const loading   = ref(false)
const notifications = ref([])
const unreadCount   = ref(0)
let pollTimer = null

const toast = reactive({ visible: false, message: '' })

// ── Panel positioning ──────────────────────────────────────────
const panelPos = reactive({ top: 0, right: 0 })

const panelStyle = computed(() => ({
  top:   panelPos.top  + 'px',
  right: panelPos.right + 'px',
}))

function updatePosition() {
  if (!bellRef.value) return
  const rect = bellRef.value.getBoundingClientRect()
  const width = Math.min(360, window.innerWidth - 24)
  const maxRight = Math.max(12, window.innerWidth - width - 12)
  panelPos.top   = rect.bottom + 8
  panelPos.right = Math.min(window.innerWidth - rect.right, maxRight)
}

// ── Open / Close ───────────────────────────────────────────────
async function togglePanel() {
  if (panelOpen.value) {
    closePanel()
    return
  }
  await nextTick()
  updatePosition()
  panelOpen.value = true
  await fetchNotifications()
}

function closePanel() {
  panelOpen.value = false
}

// ── Data fetching ──────────────────────────────────────────────
async function fetchNotifications() {
  loading.value = true
  try {
    const res = await NotificationService.getNotifications()
    notifications.value = res.notifications ?? []
  } catch {
    // silently ignore
  } finally {
    loading.value = false
  }
}

async function fetchCount() {
  try {
    const res = await NotificationService.getUnreadCount()
    const newCount = res.unread ?? 0

    // Show toast if count increased since last poll
    if (newCount > unreadCount.value && unreadCount.value !== null) {
      const diff = newCount - unreadCount.value
      showToast(diff === 1
        ? 'Foi submetida 1 nova ocorrência.'
        : `Foram submetidas ${diff} novas ocorrências.`
      )
      // Refresh the panel list if it is open
      if (panelOpen.value) await fetchNotifications()
    }

    unreadCount.value = newCount
  } catch {
    // silently ignore
  }
}

// ── Actions ────────────────────────────────────────────────────
async function handleMarkRead(n) {
  if (n.read_at) return
  try {
    await NotificationService.markRead(n.id)
    n.read_at = new Date().toISOString()
    unreadCount.value = Math.max(0, unreadCount.value - 1)
  } catch {
    // silently ignore
  }
}

async function handleMarkAllRead() {
  try {
    await NotificationService.markAllRead()
    notifications.value.forEach(n => { if (!n.read_at) n.read_at = new Date().toISOString() })
    unreadCount.value = 0
  } catch {
    // silently ignore
  }
}

// ── Toast ──────────────────────────────────────────────────────
function showToast(message) {
  toast.message = message
  toast.visible = true
  setTimeout(() => { toast.visible = false }, 5000)
}

// ── Time formatting ────────────────────────────────────────────
function timeAgo(iso) {
  if (!iso) return ''
  const diff = Math.floor((Date.now() - new Date(iso).getTime()) / 1000)
  if (diff < 60)  return 'Agora mesmo'
  if (diff < 3600) return `${Math.floor(diff / 60)} min atrás`
  if (diff < 86400) return `${Math.floor(diff / 3600)} h atrás`
  return `${Math.floor(diff / 86400)} d atrás`
}

// ── Polling + lifecycle ────────────────────────────────────────
let lastFetchAt = 0

async function throttledFetchCount() {
  const now = Date.now()
  if (now - lastFetchAt < 30_000) return
  lastFetchAt = now
  await fetchCount()
}

function startPolling() {
  throttledFetchCount()
  pollTimer = setInterval(throttledFetchCount, 60_000)
}

function stopPolling() {
  if (pollTimer) clearInterval(pollTimer)
}

function handleVisibilityChange() {
  if (document.visibilityState === 'visible') throttledFetchCount()
}

function handleResize() {
  if (panelOpen.value) updatePosition()
}

onMounted(() => {
  startPolling()
  document.addEventListener('visibilitychange', handleVisibilityChange)
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  stopPolling()
  document.removeEventListener('visibilitychange', handleVisibilityChange)
  window.removeEventListener('resize', handleResize)
})
</script>

<style>
/* ── Bell button ── */
.notif-btn {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: none;
  background: transparent;
  cursor: pointer;
  transition: background 0.15s;
  flex-shrink: 0;
}
.notif-btn:hover { background: #F0F4F2; }

.notif-badge {
  position: absolute;
  top: 2px;
  right: 2px;
  min-width: 16px;
  height: 16px;
  padding: 0 3px;
  background: #C53030;
  color: #fff;
  font-size: 10px;
  font-weight: 700;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  line-height: 1;
}

.notif-dot {
  position: absolute;
  top: 6px;
  right: 6px;
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #52B788;
}

/* ── Backdrop ── */
.np-backdrop {
  position: fixed;
  inset: 0;
  z-index: 999;
}

/* ── Dropdown panel ── */
.np-panel {
  position: fixed;
  z-index: 1000;
  width: 360px;
  max-width: calc(100vw - 24px);
  max-height: min(500px, 80vh);
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.14), 0 2px 8px rgba(0,0,0,0.08);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.np-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 16px 12px;
  border-bottom: 1px solid #EDF0EE;
  flex-shrink: 0;
}

.np-title {
  font-size: 14px;
  font-weight: 600;
  color: #1A2421;
}

.np-read-all {
  font-size: 12px;
  color: #2D6A4F;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  font-weight: 500;
}
.np-read-all:hover { text-decoration: underline; }

.np-body {
  flex: 1;
  overflow-y: auto;
}

.np-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 40px 20px;
  color: #A0ABA7;
  font-size: 13px;
  text-align: center;
}

.np-list {
  list-style: none;
  margin: 0;
  padding: 0;
}

.np-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 12px 16px;
  cursor: pointer;
  transition: background 0.12s;
  border-bottom: 1px solid #F5F7F6;
}
.np-item:last-child { border-bottom: none; }
.np-item:hover { background: #F8FAF9; }
.np-item--unread { background: #F0F8F4; }
.np-item--unread:hover { background: #E8F5EE; }

.np-item-icon {
  flex-shrink: 0;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: #E8F5EE;
  color: #2D6A4F;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 1px;
}

.np-item-body {
  flex: 1;
  min-width: 0;
}

.np-item-msg {
  font-size: 13px;
  color: #1A2421;
  line-height: 1.4;
  margin: 0 0 4px;
  word-break: break-word;
}

.np-item-code {
  display: inline-block;
  font-size: 11px;
  font-weight: 600;
  color: #2D6A4F;
  background: #E8F5EE;
  border-radius: 4px;
  padding: 1px 6px;
  margin-bottom: 4px;
}

.np-item-time {
  display: block;
  font-size: 11px;
  color: #A0ABA7;
}

.np-unread-dot {
  flex-shrink: 0;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #2D6A4F;
  margin-top: 6px;
}

/* ── Dropdown animation ── */
.np-drop-enter-active,
.np-drop-leave-active {
  transition: opacity 0.15s ease, transform 0.15s ease;
}
.np-drop-enter-from,
.np-drop-leave-to {
  opacity: 0;
  transform: translateY(-6px) scale(0.97);
}

/* ── Toast ── */
.np-toast {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 2000;
  display: flex;
  align-items: flex-start;
  gap: 10px;
  background: #fff;
  border-left: 3px solid #52B788;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.14);
  padding: 12px 14px;
  max-width: min(320px, calc(100vw - 32px));
}

@media (max-width: 480px) {
  .np-toast { left: 16px; right: 16px; bottom: 16px; max-width: none; }
}

.np-toast svg {
  flex-shrink: 0;
  margin-top: 1px;
}

.np-toast strong {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #1A2421;
  margin-bottom: 2px;
}

.np-toast p {
  margin: 0;
  font-size: 12px;
  color: #555B5A;
}

.np-toast-close {
  flex-shrink: 0;
  background: none;
  border: none;
  cursor: pointer;
  color: #A0ABA7;
  font-size: 16px;
  padding: 0;
  line-height: 1;
  margin-left: auto;
}
.np-toast-close:hover { color: #555B5A; }

.np-toast-enter-active,
.np-toast-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}
.np-toast-enter-from,
.np-toast-leave-to {
  opacity: 0;
  transform: translateY(12px);
}
</style>
