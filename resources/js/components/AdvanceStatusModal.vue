<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="open" class="as-backdrop" @click.self="fechar" />
    </Transition>

    <Transition name="slide-up">
      <div v-if="open" class="as-modal" role="dialog" aria-modal="true" aria-labelledby="as-title">

        <div class="as-header">
          <div class="as-header-left">
            <div class="as-icon">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="6.5" />
                <path d="M5 8l2 2 4-4.5" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
            <div>
              <h2 id="as-title" class="as-title">{{ step.title }}</h2>
              <p class="as-subtitle">
                <template v-if="trackingCode">Ocorrência {{ trackingCode }} — </template>{{ step.transition }}
              </p>
            </div>
          </div>
          <button class="as-close" @click="fechar" :disabled="submitting" aria-label="Fechar">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
              <path d="M3 3l10 10M13 3L3 13" stroke-linecap="round" />
            </svg>
          </button>
        </div>

        <div class="as-body">
          <p class="as-hint">{{ step.hint }}</p>

          <div class="as-field">
            <label class="as-label">
              {{ step.commentLabel }}
              <span v-if="step.commentRequired" class="as-req">*</span>
              <span v-else class="as-optional">opcional</span>
            </label>
            <textarea
              class="as-textarea"
              v-model="comment"
              rows="4"
              maxlength="2000"
              :placeholder="step.placeholder"
            ></textarea>
            <span class="as-counter">{{ comment.length }}/2000</span>
          </div>

          <div v-if="errorMsg" class="as-error">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
              <circle cx="8" cy="8" r="7" />
              <path d="M8 5v3.5M8 11v.5" stroke-linecap="round" />
            </svg>
            {{ errorMsg }}
          </div>
        </div>

        <div class="as-footer">
          <button class="as-btn as-btn--ghost" @click="fechar" :disabled="submitting">Cancelar</button>
          <button class="as-btn as-btn--primary" @click="submit" :disabled="!canSubmit">
            {{ submitting ? 'A guardar…' : step.confirm }}
          </button>
        </div>

      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
/**
 * Modal usado pelo funcionário para avançar uma ocorrência que ele próprio
 * registou, depois de validada pelo gestor/admin:
 *   Por Resolver → Resolvendo → Resolvido
 * As permissões são verificadas no servidor (OccurrenceService::changeStatus).
 */
import { ref, computed, watch } from 'vue'
import { InternalService } from '@/api/services/internal.service'
import { resolveErrorMessage } from '@/utils/errorMessage'

const STEPS = {
  resolvendo: {
    title: 'Iniciar Resolução',
    transition: 'Por Resolver → Resolvendo',
    hint: 'A ocorrência passa a estar em resolução.',
    commentLabel: 'Comentário',
    commentRequired: false,
    placeholder: 'Descreva o que vai ser feito (opcional)…',
    confirm: 'Iniciar Resolução',
  },
  resolvido: {
    title: 'Marcar como Resolvida',
    transition: 'Resolvendo → Resolvido',
    hint: 'A ocorrência fica encerrada como resolvida e deixa de poder ser alterada.',
    commentLabel: 'Como foi resolvida',
    commentRequired: true,
    placeholder: 'Descreva como a ocorrência foi resolvida (mínimo 10 caracteres)…',
    confirm: 'Marcar como Resolvida',
  },
}

const props = defineProps({
  open:         { type: Boolean, default: false },
  occurrenceId: { type: [Number, String], default: null },
  trackingCode: { type: String, default: '' },
  // Estado de destino: 'resolvendo' ou 'resolvido'
  target:       { type: String, default: 'resolvido' },
})
const emit = defineEmits(['close', 'submitted'])

const comment    = ref('')
const submitting = ref(false)
const errorMsg   = ref('')

const step = computed(() => STEPS[props.target] ?? STEPS.resolvido)

// O servidor exige mínimo de 10 caracteres sempre que há comentário.
const canSubmit = computed(() => {
  if (submitting.value) return false
  const len = comment.value.trim().length
  return step.value.commentRequired ? len >= 10 : (len === 0 || len >= 10)
})

watch(() => props.open, (val) => {
  if (!val) {
    comment.value = ''
    errorMsg.value = ''
  }
})

function fechar() {
  if (submitting.value) return
  emit('close')
}

async function submit() {
  if (!canSubmit.value || !props.occurrenceId) return
  errorMsg.value = ''
  submitting.value = true

  try {
    const payload = { status: props.target }
    if (comment.value.trim()) payload.comment = comment.value.trim()

    const data = await InternalService.updateStatus(props.occurrenceId, payload)
    emit('submitted', data)
  } catch (err) {
    console.error('[AdvanceStatusModal] Erro ao mudar o estado:', err?.message ?? err)
    errorMsg.value = resolveErrorMessage(err, 'Erro ao actualizar o estado. Tente novamente.')
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.as-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(10, 28, 20, 0.45);
  z-index: 300;
  backdrop-filter: blur(2px);
}

.as-modal {
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

.as-header, .as-footer { flex-shrink: 0; }

.as-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 22px 16px;
  border-bottom: 1px solid var(--border, #DDE8E1);
}
.as-header-left { display: flex; align-items: center; gap: 12px; }
.as-icon {
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
.as-title { font-size: 15px; font-weight: 700; color: var(--text-dark, #1A1A1A); margin: 0; }
.as-subtitle { font-size: 12px; color: var(--text-light, #888E8C); margin: 2px 0 0; }
.as-close {
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
.as-close:hover:not(:disabled) { background: var(--green-bg, #F0FAF4); }
.as-close:disabled { opacity: 0.5; cursor: not-allowed; }

.as-body {
  padding: 20px 22px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  overflow-y: auto;
}
.as-hint { margin: 0; font-size: 13px; line-height: 1.5; color: var(--text-gray, #555B5A); }
.as-field { display: flex; flex-direction: column; gap: 6px; }
.as-label {
  font-size: 11.5px;
  font-weight: 600;
  color: var(--text-gray, #555B5A);
  text-transform: uppercase;
  letter-spacing: 0.04em;
  display: flex;
  align-items: center;
  gap: 6px;
}
.as-req { color: #C53030; }
.as-optional { font-weight: 400; font-size: 10.5px; color: var(--text-light, #888E8C); text-transform: none; letter-spacing: 0; }

.as-textarea {
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
.as-textarea:focus { outline: none; border-color: var(--green-light, #52B788); }
.as-counter { align-self: flex-end; font-size: 11px; color: var(--text-light, #888E8C); }

.as-error {
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

.as-footer {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 10px;
  padding: 14px 22px 18px;
  border-top: 1px solid var(--border, #DDE8E1);
  background: var(--offwhite, #F7F9F8);
}
.as-btn {
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
.as-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.as-btn--ghost {
  background: none;
  color: var(--text-gray, #555B5A);
  border: 1.5px solid var(--border, #DDE8E1);
}
.as-btn--ghost:hover:not(:disabled) { background: var(--green-bg, #F0FAF4); border-color: var(--green-light, #52B788); }
.as-btn--primary { background: var(--green-dark, #1B4332); color: #fff; }
.as-btn--primary:hover:not(:disabled) { background: linear-gradient(135deg, #52B788, #1B4332); }

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.slide-up-enter-active, .slide-up-leave-active { transition: all 0.22s ease; }
.slide-up-enter-from { opacity: 0; transform: translate(-50%, -48%) scale(0.97); }
.slide-up-leave-to { opacity: 0; transform: translate(-50%, -48%) scale(0.97); }
</style>
