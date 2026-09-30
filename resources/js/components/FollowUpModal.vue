<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="open" class="fu-backdrop" @click.self="fechar" />
    </Transition>

    <Transition name="slide-up">
      <div v-if="open" class="fu-modal" role="dialog" aria-modal="true" aria-labelledby="fu-title">

        <div class="fu-header">
          <div class="fu-header-left">
            <div class="fu-icon">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
                <path d="M2 8a6 6 0 1 1 2.2 4.65" stroke-linecap="round" />
                <path d="M2 12v-3h3" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
            <div>
              <h2 id="fu-title" class="fu-title">Dar Seguimento</h2>
              <p class="fu-subtitle" v-if="trackingCode">Ocorrência {{ trackingCode }} — envia de novo para validação</p>
              <p class="fu-subtitle" v-else>Envia a ocorrência de novo para validação</p>
            </div>
          </div>
          <button class="fu-close" @click="fechar" :disabled="submitting" aria-label="Fechar">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
              <path d="M3 3l10 10M13 3L3 13" stroke-linecap="round" />
            </svg>
          </button>
        </div>

        <div class="fu-body">
          <div class="fu-field">
            <label class="fu-label">Comentário <span class="fu-req">*</span></label>
            <textarea
              class="fu-textarea"
              v-model="comment"
              rows="4"
              maxlength="2000"
              placeholder="Descreva a informação adicional (mínimo 10 caracteres)…"
            ></textarea>
            <span class="fu-counter">{{ comment.length }}/2000</span>
          </div>

          <div class="fu-field">
            <label class="fu-label">Anexos <span class="fu-optional">opcional</span></label>
            <div class="fu-upload-zone" :class="{ 'drag-over': isDragging }" @click="triggerUpload"
              @dragover.prevent="isDragging = true" @dragleave="isDragging = false" @drop.prevent="handleDrop">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 22 22">
                <path d="M3 15v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke-linecap="round" />
                <path d="M11 3v10M7 7l4-4 4 4" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <p>Clique para carregar ou arraste e solte</p>
              <span>JPG, PNG, PDF, DOC, MP4, MP3 até 10MB (máx. 5 ficheiros)</span>
            </div>
            <input ref="fileInput" type="file" multiple
              accept=".png,.jpg,.jpeg,.pdf,.doc,.docx,.mp4,.mp3" style="display:none"
              @change="handleFileSelect" />

            <div class="fu-file-list" v-if="files.length">
              <div class="fu-file-item" v-for="(f, i) in files" :key="i">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 16 16">
                  <rect x="2" y="1" width="10" height="14" rx="1.5" />
                  <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round" />
                  <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="fu-file-name">{{ f.name }}</span>
                <span class="fu-file-size">{{ (f.size / 1024).toFixed(0) }} KB</span>
                <button class="fu-file-remove" @click.stop="removeFile(i)">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                    <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div v-if="errorMsg" class="fu-error">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
              <circle cx="8" cy="8" r="7" />
              <path d="M8 5v3.5M8 11v.5" stroke-linecap="round" />
            </svg>
            {{ errorMsg }}
          </div>
        </div>

        <div class="fu-footer">
          <button class="fu-btn fu-btn--ghost" @click="fechar" :disabled="submitting">Cancelar</button>
          <button class="fu-btn fu-btn--primary" @click="submit" :disabled="!canSubmit">
            <svg v-if="submitting" class="fu-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
            </svg>
            {{ submitting ? 'A enviar…' : 'Submeter Seguimento' }}
          </button>
        </div>

      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { InternalService } from '@/api/services/internal.service'
import { resolveErrorMessage } from '@/utils/errorMessage'

const props = defineProps({
  open:          { type: Boolean, default: false },
  occurrenceId:  { type: [Number, String], default: null },
  trackingCode:  { type: String, default: '' },
})
const emit = defineEmits(['close', 'submitted'])

const comment     = ref('')
const files       = ref([])
const isDragging  = ref(false)
const fileInput   = ref(null)
const submitting  = ref(false)
const errorMsg    = ref('')

const canSubmit = computed(() =>
  comment.value.trim().length >= 10 && !submitting.value
)

watch(() => props.open, (val) => {
  if (!val) {
    comment.value = ''
    files.value = []
    errorMsg.value = ''
    isDragging.value = false
  }
})

const triggerUpload    = () => fileInput.value?.click()
const handleFileSelect = (e) => { addFiles(Array.from(e.target.files)); e.target.value = '' }
const handleDrop       = (e) => { isDragging.value = false; addFiles(Array.from(e.dataTransfer.files)) }

function addFiles(list) {
  list.forEach(f => {
    if (files.value.length >= 5) return
    if (f.size <= 10 * 1024 * 1024) files.value.push(f)
  })
}

const removeFile = (i) => files.value.splice(i, 1)

function fechar() {
  if (submitting.value) return
  emit('close')
}

async function submit() {
  if (!canSubmit.value || !props.occurrenceId) return
  errorMsg.value = ''
  submitting.value = true

  try {
    const fd = new FormData()
    fd.append('comment', comment.value.trim())
    files.value.forEach(f => fd.append('attachments[]', f))

    const data = await InternalService.addFollowUp(props.occurrenceId, fd)
    emit('submitted', data)
  } catch (err) {
    console.error('[FollowUpModal] Erro ao submeter seguimento:', err?.message ?? err)
    errorMsg.value = resolveErrorMessage(err, 'Erro ao submeter o seguimento. Tente novamente.')
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.fu-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(10, 28, 20, 0.45);
  z-index: 300;
  backdrop-filter: blur(2px);
}

.fu-modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 301;
  width: 520px;
  max-width: calc(100vw - 32px);
  max-height: calc(100vh - 32px);
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 20px 60px rgba(10, 28, 20, 0.18), 0 4px 16px rgba(10, 28, 20, 0.1);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.fu-header, .fu-footer { flex-shrink: 0; }

.fu-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 22px 16px;
  border-bottom: 1px solid var(--border, #DDE8E1);
}
.fu-header-left { display: flex; align-items: center; gap: 12px; }
.fu-icon {
  width: 38px;
  height: 38px;
  border-radius: 9px;
  background: var(--green-bg, #F0FAF4);
  color: var(--green-mid, #2D6A4F);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.fu-title { font-size: 15px; font-weight: 700; color: var(--text-dark, #1A1A1A); margin: 0; }
.fu-subtitle { font-size: 12px; color: var(--text-light, #888E8C); margin: 2px 0 0; }
.fu-close {
  width: 30px;
  height: 30px;
  border: none;
  background: none;
  color: var(--text-gray, #555B5A);
  cursor: pointer;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.15s;
}
.fu-close:hover:not(:disabled) { background: var(--green-bg, #F0FAF4); }
.fu-close:disabled { opacity: 0.5; cursor: not-allowed; }

.fu-body {
  padding: 20px 22px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  overflow-y: auto;
}
.fu-field { display: flex; flex-direction: column; gap: 6px; }
.fu-label {
  font-size: 11.5px;
  font-weight: 600;
  color: var(--text-gray, #555B5A);
  text-transform: uppercase;
  letter-spacing: 0.04em;
  display: flex;
  align-items: center;
  gap: 6px;
}
.fu-req { color: #C53030; }
.fu-optional { font-weight: 400; font-size: 10.5px; color: var(--text-light, #888E8C); text-transform: none; letter-spacing: 0; }

.fu-textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1.5px solid var(--border, #DDE8E1);
  border-radius: 7px;
  font-size: 13px;
  font-family: inherit;
  color: var(--text-dark, #1A1A1A);
  resize: vertical;
  transition: border-color 0.15s;
}
.fu-textarea:focus { outline: none; border-color: var(--green-light, #52B788); }
.fu-counter { align-self: flex-end; font-size: 11px; color: var(--text-light, #888E8C); }

.fu-upload-zone {
  border: 1.5px dashed var(--border, #DDE8E1);
  border-radius: 9px;
  padding: 18px 14px;
  text-align: center;
  cursor: pointer;
  color: var(--green-mid, #2D6A4F);
  transition: all 0.15s;
}
.fu-upload-zone:hover, .fu-upload-zone.drag-over {
  border-color: var(--green-light, #52B788);
  background: var(--green-bg, #F0FAF4);
}
.fu-upload-zone p { margin: 8px 0 2px; font-size: 12.5px; font-weight: 600; color: var(--text-dark, #1A1A1A); }
.fu-upload-zone span { font-size: 11px; color: var(--text-light, #888E8C); }

.fu-file-list { display: flex; flex-direction: column; gap: 6px; margin-top: 10px; }
.fu-file-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 7px 10px;
  border: 1px solid var(--border, #DDE8E1);
  border-radius: 7px;
  font-size: 12px;
  color: var(--text-dark, #1A1A1A);
}
.fu-file-name { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.fu-file-size { color: var(--text-light, #888E8C); font-size: 11px; }
.fu-file-remove {
  border: none;
  background: none;
  color: var(--text-gray, #555B5A);
  cursor: pointer;
  display: flex;
  align-items: center;
  padding: 2px;
}
.fu-file-remove:hover { color: #C53030; }

.fu-error {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 14px;
  background: #FFF5F5;
  border: 1px solid #FED7D7;
  border-radius: 8px;
  color: #C53030;
  font-size: 12.5px;
}

.fu-footer {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 10px;
  padding: 14px 22px 18px;
  border-top: 1px solid var(--border, #DDE8E1);
  background: var(--offwhite, #F7F9F8);
}
.fu-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 0 16px;
  height: 36px;
  border: none;
  border-radius: 8px;
  font-size: 13px;
  font-family: inherit;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s;
}
.fu-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.fu-btn--ghost {
  background: none;
  color: var(--text-gray, #555B5A);
  border: 1.5px solid var(--border, #DDE8E1);
}
.fu-btn--ghost:hover:not(:disabled) { background: var(--green-bg, #F0FAF4); border-color: var(--green-light, #52B788); }
.fu-btn--primary { background: var(--green-dark, #1B4332); color: #fff; }
.fu-btn--primary:hover:not(:disabled) { background: linear-gradient(135deg, #52B788, #1B4332); }

.fu-spin { animation: fu-spin 0.9s linear infinite; }
@keyframes fu-spin { to { transform: rotate(360deg); } }

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.slide-up-enter-active, .slide-up-leave-active { transition: all 0.22s ease; }
.slide-up-enter-from { opacity: 0; transform: translate(-50%, -48%) scale(0.97); }
.slide-up-leave-to { opacity: 0; transform: translate(-50%, -48%) scale(0.97); }
</style>
