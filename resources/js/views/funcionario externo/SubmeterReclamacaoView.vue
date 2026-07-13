<template>
  <div class="page">
    <AppNavbar />

    <div class="page-wrapper">

      <!-- HEADER -->
      <div class="page-header">
        <div class="page-badge">
          BIOFUND
        </div>
        <h1>Registar Ocorrência</h1>
        <p>Preencha o formulário abaixo com o máximo de detalhes possível.</p>
      </div>

      <!-- CARD 1: Informação da Reclamação -->
      <div class="form-card">
        <div class="card-header">
          <div class="card-icon">
            <svg fill="none" viewBox="0 0 20 20" stroke="#2D6A4F" stroke-width="1.8">
              <circle cx="10" cy="10" r="8" />
              <path d="M10 6v4l2.5 2.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
          <div class="card-header-text">
            <h3>Informação da Ocorrência</h3>
            <p>Identifique o tipo de Ocorrencia e descreva o que aconteceu.</p>
          </div>
        </div>

        <div class="field-row">
          <div class="field-group">
            <label>Projecto Relacionado</label>
            <div class="select-wrap">
              <select v-model="form.projeto" :disabled="loadingFormData">
                <option value="" disabled>{{ loadingFormData ? 'A carregar…' : 'Seleccione o projecto' }}</option>
                <option v-for="p in projectos" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
            </div>
          </div>
          <div class="field-group">
            <label>Categoria da Ocorrência</label>
            <div class="select-wrap">
              <select v-model="form.categoria" :disabled="loadingFormData">
                <option value="" disabled>{{ loadingFormData ? 'A carregar…' : 'Seleccione a categoria' }}</option>
                <option v-for="c in categorias" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="field-row single" v-if="subcategoriasDisponiveis.length">
          <div class="field-group">
            <label>Subcategoria (Opcional)</label>
            <div class="select-wrap">
              <select v-model="form.subcategoria">
                <option value="">Seleccione a subcategoria (opcional)</option>
                <option v-for="s in subcategoriasDisponiveis" :key="s.id" :value="s.id">{{ s.name }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="field-row single">
          <div class="field-group">
            <label>Tipo de Ocorrência</label>
            <div class="select-wrap">
              <select v-model="form.tipoOcorrencia" :disabled="loadingFormData">
                <option value="">{{ loadingFormData ? 'A carregar…' : 'Seleccione o tipo (opcional)' }}</option>
                <option v-for="t in tiposOcorrencia" :key="t.id" :value="t.id">{{ t.name }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="field-row single">
          <div class="field-group">
            <label>Descrição Detalhada</label>
            <textarea v-model="form.descricao"
              placeholder="Descreva o que observou, pessoas envolvidas, gravidade e outros detalhes relevantes..."
              :class="{ 'field-error': form.descricao.length > 0 && form.descricao.length < 20 }"></textarea>
            <div class="char-counter" :class="form.descricao.length >= 20 ? 'char-ok' : 'char-warn'">
              <span v-if="form.descricao.length < 20 && form.descricao.length > 0">
                Mínimo 20 caracteres - faltam {{ 20 - form.descricao.length }}
              </span>
              <span v-else-if="form.descricao.length === 0" class="char-hint">Mínimo 20 caracteres</span>
              <span v-else>✓ {{ form.descricao.length }} caracteres</span>
            </div>
          </div>
        </div>

        <div class="field-row single" style="max-width:320px">
          <div class="field-group">
            <label>Data da Ocorrência</label>
            <div class="date-wrap">
              <span class="date-icon">
                <svg width="16" height="16" fill="none" stroke="#888E8C" stroke-width="1.6" viewBox="0 0 16 16">
                  <rect x="1" y="2" width="14" height="13" rx="2" />
                  <path d="M5 1v2M11 1v2M1 6h14" stroke-linecap="round" />
                </svg>
              </span>
              <input type="date" v-model="form.data" :max="today" :class="{ 'is-empty': !form.data }" />
              <span class="date-placeholder" v-if="!form.data">dia / mês / ano</span>
            </div>
          </div>
        </div>
      </div>

      <!-- CARD 2: Localização -->
      <div class="form-card">
        <div class="card-header">
          <div class="card-icon">
            <svg fill="none" viewBox="0 0 20 20" stroke="#2D6A4F" stroke-width="1.8">
              <path d="M10 2C6.686 2 4 4.686 4 8c0 4.5 6 10 6 10s6-5.5 6-10c0-3.314-2.686-6-6-6z"
                stroke-linejoin="round" />
              <circle cx="10" cy="8" r="2.2" />
            </svg>
          </div>
          <div class="card-header-text">
            <h3>Localização da Ocorrência</h3>
            <p>Ajude a nossa equipa a localizar a Ocorrência no mapa.</p>
          </div>
        </div>

        <div class="field-row">
          <div class="field-group">
            <label>Província</label>
            <div class="select-wrap">
              <select v-model="form.provincia" @change="handleProvinceChange" :disabled="loadingFormData">
                <option value="" disabled>{{ loadingFormData ? 'A carregar…' : 'Seleccione a província' }}</option>
                <option v-for="p in provincias" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
            </div>
          </div>
          <div class="field-group">
            <label>Distrito</label>
            <div class="select-wrap">
              <select v-model="form.distrito" :disabled="!form.provincia || loadingDistricts">
                <option value="" disabled>{{ loadingDistricts ? 'A carregar…' : 'Seleccione o distrito' }}</option>
                <option v-for="d in distritos" :key="d.id" :value="d.id">{{ d.name }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="field-row">
          <div class="field-group">
            <label>Comunidade (Opcional)</label>
            <input type="text" v-model="form.comunidade" placeholder="Ex: Comunidade de Mafuiane" />
          </div>
          <div class="field-group">
            <label>Posto Administrativo (Opcional)</label>
            <input type="text" v-model="form.postoAdministrativo" placeholder="Ex: Posto Administrativo de Manhiça" />
          </div>
        </div>

        <div class="field-row single">
          <div class="field-group">
            <label>Coordenadas GPS (Opcional)</label>
            <input type="text" v-model="form.coordenadas" placeholder="Ex: -25.9682, 32.5732"
              :disabled="isVbgType" />
            <span class="field-hint vbg-hint" v-if="isVbgType">
              Por motivos de privacidade e segurança, as coordenadas GPS não são recolhidas para ocorrências de Violência Baseada no Género (VBG).
            </span>
          </div>
        </div>
      </div>

      <!-- CARD 3: Contacto -->
      <div class="form-card">
        <div class="card-header">
          <div class="card-icon">
            <svg fill="none" viewBox="0 0 20 20" stroke="#2D6A4F" stroke-width="1.8">
              <circle cx="10" cy="7" r="3.5" />
              <path d="M3 18c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke-linecap="round" />
            </svg>
          </div>
          <div class="card-header-text">
            <h3>Informação de Contacto</h3>
            <p>Como podemos contactá-lo para obter mais detalhes?</p>
          </div>
        </div>

        <div class="field-row single">
          <div class="field-group">
            <label>Seu Nome (Opcional)</label>
            <input type="text" v-model="form.nome" placeholder="Nome completo ou pseudónimo" />
          </div>
        </div>

        <div class="field-row">
          <div class="field-group">
            <label>Sexo (Opcional)</label>
            <div class="select-wrap">
              <select v-model="form.sexo">
                <option value="">Não identificado</option>
                <option value="masculino">Masculino</option>
                <option value="feminino">Feminino</option>
              </select>
            </div>
          </div>
          <div class="field-group">
            <label>Faixa Etária (Opcional)</label>
            <div class="select-wrap">
              <select v-model="form.idade">
                <option value="">Não informada</option>
                <option value="Menos de 18">Menos de 18 anos</option>
                <option value="18 - 25">18 - 25 anos</option>
                <option value="26 - 35">26 - 35 anos</option>
                <option value="36 - 45">36 - 45 anos</option>
                <option value="46 - 55">46 - 55 anos</option>
                <option value="56 - 65">56 - 65 anos</option>
                <option value="Mais de 65">Mais de 65 anos</option>
              </select>
            </div>
          </div>
        </div>

        <div class="field-row">
          <div class="field-group">
            <label>Email</label>
            <input
              type="email"
              v-model="form.email"
              placeholder="ex: nome@exemplo.com"
              :class="{ 'field-error': errors.contacto }"
            />
          </div>
          <div class="field-group">
            <label>Número de Telefone</label>
            <input
              type="tel"
              v-model="form.phone"
              placeholder="ex: +258 84 000 0000"
              :class="{ 'field-error': errors.contacto }"
            />
          </div>
        </div>

        <div class="field-row single contact-hint-row">
          <span class="contact-hint" v-if="errors.contacto" style="color:#E53E3E">{{ errors.contacto }}</span>
          <span class="contact-hint" v-else>Preencha pelo menos email ou telefone</span>
        </div>
      </div>

      <!-- CARD 4: Evidências -->
      <div class="form-card">
        <div class="card-header">
          <div class="card-icon">
            <svg fill="none" viewBox="0 0 20 20" stroke="#2D6A4F" stroke-width="1.8">
              <path d="M2 7a2 2 0 0 1 2-2h.5l1-2h5l1 2H16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7z"
                stroke-linejoin="round" />
              <circle cx="10" cy="11" r="2.5" />
            </svg>
          </div>
          <div class="card-header-text">
            <h3>Evidências e Anexos</h3>
            <p>Fotos ou documentos ajudam na validação da queixa.</p>
          </div>
        </div>

        <div class="upload-zone" :class="{ 'drag-over': isDragging }" @click="triggerUpload"
          @dragover.prevent="isDragging = true" @dragleave="isDragging = false" @drop.prevent="handleDrop">
          <div class="upload-icon">
            <svg width="22" height="22" fill="none" stroke="#2D6A4F" stroke-width="1.7" viewBox="0 0 22 22">
              <path d="M3 15v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke-linecap="round" />
              <path d="M11 3v10M7 7l4-4 4 4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
          <h4>Clique para carregar ou arraste e solte</h4>
          <p>PNG, JPG, PDF até 10MB</p>
        </div>

        <input ref="fileInput" type="file" multiple accept=".png,.jpg,.jpeg,.pdf" style="display:none"
          @change="handleFileSelect" />

        <div class="file-list" v-if="files.length">
          <div class="file-item" v-for="(f, i) in files" :key="i">
            <svg width="16" height="16" fill="none" stroke="#2D6A4F" stroke-width="1.6" viewBox="0 0 16 16">
              <rect x="2" y="1" width="10" height="14" rx="1.5" />
              <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round" />
              <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="file-item-name">{{ f.name }}</span>
            <span class="file-item-size">{{ (f.size / 1024).toFixed(0) }} KB</span>
            <button class="file-remove" @click.stop="removeFile(i)">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- ERRO GLOBAL -->
      <div class="global-error" v-if="globalError">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
          <circle cx="8" cy="8" r="7" />
          <path d="M8 5v3M8 10.5v.5" stroke-linecap="round" />
        </svg>
        {{ globalError }}
      </div>

      <!-- SUBMIT BANNER -->
      <div class="submit-banner">
        <div class="submit-banner-text">
          <p>Ao submeter esta ocorrência, você declara que as informações prestadas são verdadeiras e autoriza a BIOFUND
            a utilizá-las para fins de investigação ambiental.</p>
        </div>
        <button class="btn-submit" @click="submitForm" :disabled="submitting">
          <span v-if="submitting" class="spinner"></span>
          <span>{{ submitting ? 'A enviar…' : 'Enviar Ocorrência' }}</span>
          <svg v-if="!submitting" width="14" height="14" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 14 14">
            <path d="M2 7h10M8 3l4 4-4 4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>

    </div>

    <!-- SUCCESS MODAL -->
    <div class="success-overlay" v-if="showSuccess" @click.self="closeSuccess">
      <div class="success-card">
        <div class="success-icon">
          <svg width="36" height="36" fill="none" stroke="#2D6A4F" stroke-width="2.2" viewBox="0 0 36 36">
            <circle cx="18" cy="18" r="15" />
            <path d="M11 18l5 5 9-10" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>

        <h3>Ocorrência Enviada!</h3>
        <p>A sua ocorrência foi registada com sucesso. A equipa da  BIOFUND irá analisar e tomar as medidas necessárias.</p>

        <!-- Tracking Code -->
        <div class="tracking-box" v-if="trackingCode">
          <span class="tracking-label">Código de Seguimento</span>
          <div class="tracking-row">
            <span class="tracking-code">{{ trackingCode }}</span>
            <button class="btn-copy" @click="copyCode">
              <template v-if="!copied">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
                  <rect x="1" y="5" width="9" height="10" rx="1.5" />
                  <path d="M5 5V3a1.5 1.5 0 0 1 1.5-1.5h6A1.5 1.5 0 0 1 14 3v8a1.5 1.5 0 0 1-1.5 1.5H11"
                    stroke-linecap="round" />
                </svg>
                Copiar
              </template>
              <template v-else>
                <svg width="14" height="14" fill="none" stroke="#2D6A4F" stroke-width="2.2" viewBox="0 0 16 16">
                  <path d="M2 8l4 4 8-8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Copiado!
              </template>
            </button>
          </div>
          <p class="tracking-hint">Guarde este código para acompanhar o estado da sua ocorrência.</p>
          <p class="tracking-due" v-if="dueDate">Prazo de resposta: <strong>{{ dueDate }}</strong></p>
        </div>

        <button class="btn-ok" @click="closeSuccess">Fechar</button>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import AppNavbar from '@/components/AppNavbar.vue'
import AppFooter from '@/components/AppFooter.vue'
import { PublicService } from '../../api/services/public.service'



// ─── Data máxima para o campo de data ────────────────────────
const today = new Date().toISOString().split('T')[0]

// ─── Formulário ───────────────────────────────────────────────
const form = reactive({
  projeto: '', categoria: '', subcategoria: '', tipoOcorrencia: '', descricao: '', data: '',
  provincia: '', distrito: '', comunidade: '', postoAdministrativo: '', coordenadas: '',
  nome: '', sexo: '', idade: '', email: '', phone: '',
})

// ─── Listas de referência ─────────────────────────────────────
const projectos       = ref([])
const categorias      = ref([])
const tiposOcorrencia = ref([])
const provincias      = ref([])
const distritos       = ref([])

// Subcategorias da categoria seleccionada (opcional)
const subcategoriasDisponiveis = computed(() => {
  const c = categorias.value.find(c => c.id === form.categoria)
  return c?.subcategories ?? []
})

watch(() => form.categoria, () => {
  form.subcategoria = ''
})

// Nome do tipo seleccionado - usado como subject ao submeter
const selectedTipoName = computed(() => {
  const t = tiposOcorrencia.value.find(t => t.id === form.tipoOcorrencia)
  return t?.name ?? ''
})

// Tipo VBG seleccionado - restringe a recolha de coordenadas GPS
// por motivos de privacidade e segurança do reclamante.
const isVbgType = computed(() => {
  const t = tiposOcorrencia.value.find(t => t.id === form.tipoOcorrencia)
  return t?.alert_level === 'gbv'
})

watch(isVbgType, (vbg) => {
  if (vbg) form.coordenadas = ''
})

// ─── Ficheiros ────────────────────────────────────────────────
const files     = ref([])
const fileInput = ref(null)
const isDragging = ref(false)

// ─── Estado UI ────────────────────────────────────────────────
const loadingFormData  = ref(false)
const loadingDistricts = ref(false)
const submitting       = ref(false)
const showSuccess        = ref(false)
const trackingCode       = ref('')
const dueDate            = ref('')
const copied             = ref(false)
const errors             = reactive({})
const globalError        = ref('')

// ─── Carregar dados do formulário ─────────────────────────────
onMounted(loadFormData)

async function loadFormData() {
  try {
    loadingFormData.value = true
    const data = await PublicService.getFormData()
    projectos.value       = data.projects        ?? []
    categorias.value      = data.categories      ?? []
    tiposOcorrencia.value = data.occurrence_types ?? []
    provincias.value      = data.provinces        ?? []
  } catch (error) {
    console.error('Erro ao carregar formulário:', error)
    globalError.value = 'Não foi possível carregar os dados do formulário. Recarregue a página.'
  } finally {
    loadingFormData.value = false
  }
}

// ─── Cascata Província → Distrito ────────────────────────────
async function handleProvinceChange() {
  form.distrito  = ''
  distritos.value = []
  if (!form.provincia) return
  try {
    loadingDistricts.value = true
    const data = await PublicService.getDistrictsByProvince(form.provincia)
    distritos.value = data.districts ?? data
  } catch (error) {
    console.error('Erro ao carregar distritos:', error)
  } finally {
    loadingDistricts.value = false
  }
}

// ─── Upload de ficheiros ──────────────────────────────────────
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

// ─── Submissão ────────────────────────────────────────────────
async function submitForm() {
  // Limpa estado anterior
  Object.keys(errors).forEach(k => delete errors[k])
  globalError.value = ''

  // Validação: pelo menos email ou telefone obrigatório
  if (!form.email.trim() && !form.phone.trim()) {
    errors.contacto = 'Preencha pelo menos um contacto: email ou número de telefone.'
    return
  }

  // Monta FormData com os nomes que o backend espera
  const fd = new FormData()

  // Nome - opcional, submissão anónima permitida
  if (form.nome.trim())  fd.append('complainant_name',  form.nome.trim())
  if (form.sexo)         fd.append('complainant_gender', form.sexo)
  if (form.idade)        fd.append('complainant_age',    form.idade)
  if (form.email.trim()) fd.append('complainant_email', form.email.trim())
  if (form.phone.trim()) fd.append('complainant_phone', form.phone.trim())

  if (form.projeto)          fd.append('project_id',          form.projeto)
  if (form.categoria)        fd.append('category_id',         form.categoria)
  if (form.subcategoria)     fd.append('subcategory_id',      form.subcategoria)
  if (form.tipoOcorrencia)   fd.append('occurrence_type_id',  form.tipoOcorrencia)
  if (selectedTipoName.value) fd.append('subject',            selectedTipoName.value)
  if (form.descricao)        fd.append('description',         form.descricao.trim())
  if (form.data)      fd.append('occurrence_date', form.data)
  if (form.provincia) fd.append('province_id',     form.provincia)
  if (form.distrito)  fd.append('district_id',     form.distrito)

  // Combina comunidade + posto administrativo + coordenadas num único campo location_detail
  const locationParts = []
  if (form.comunidade.trim())          locationParts.push(form.comunidade.trim())
  if (form.postoAdministrativo.trim()) locationParts.push(form.postoAdministrativo.trim())
  if (form.coordenadas.trim())         locationParts.push(form.coordenadas.trim())
  if (locationParts.length) fd.append('location_detail', locationParts.join(' - '))

  // Anexos
  files.value.forEach(f => fd.append('attachments[]', f))

  try {
    submitting.value = true
    const result = await PublicService.createOccurrence(fd)
    trackingCode.value = result.tracking_code ?? ''
    dueDate.value      = result.due_date      ?? ''
    showSuccess.value  = true
  } catch (err) {
    if (err.response?.status === 422) {
      const serverErrors = err.response.data.errors ?? {}
      Object.entries(serverErrors).forEach(([field, msgs]) => {
        errors[field] = msgs[0]
      })
      globalError.value = 'Corrija os erros assinalados e tente novamente.'
    } else if (!err.response) {
      globalError.value = 'Sem ligação ao servidor. Verifique a sua ligação à internet.'
    } else {
      globalError.value = err.response?.data?.message ?? 'Erro ao enviar. Tente novamente.'
    }
  } finally {
    submitting.value = false
  }
}

// ─── Modal de sucesso ─────────────────────────────────────────
function closeSuccess() {
  showSuccess.value = false
  Object.keys(form).forEach(k => { form[k] = '' })
  files.value        = []
  distritos.value    = []
  trackingCode.value = ''
  dueDate.value      = ''
  // tipoOcorrencia já está coberto pelo Object.keys reset acima
}

async function copyCode() {
  if (!trackingCode.value) return
  try {
    await navigator.clipboard.writeText(trackingCode.value)
  } catch {
    // fallback para browsers sem Clipboard API
    const el = document.createElement('textarea')
    el.value = trackingCode.value
    document.body.appendChild(el)
    el.select()
    document.execCommand('copy')
    document.body.removeChild(el)
  }
  copied.value = true
  setTimeout(() => { copied.value = false }, 2500)
}
</script>

<style scoped>
.page {
  background: var(--offwhite);
  min-height: 100vh;
}

.page-wrapper {
  max-width: 760px;
  margin: 0 auto;
  padding: 52px 24px 80px;
}

/* HEADER */
.page-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: var(--green-mid);
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 1.2px;
  text-transform: uppercase;
  margin-bottom: 12px;
}

.page-header h1 {
  font-size: 32px;
  font-weight: 800;
  margin-bottom: 10px;
  line-height: 1.2;
}

.page-header p {
  font-size: 14px;
  color: var(--text-gray);
  line-height: 1.7;
  max-width: 520px;
  margin-bottom: 36px;
}

/* CARDS */
.form-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 14px;
  padding: 32px 36px;
  margin-bottom: 20px;
  animation: fadeUp 0.5s ease both;
}

.form-card:nth-child(2) { animation-delay: 0.05s; }
.form-card:nth-child(3) { animation-delay: 0.10s; }
.form-card:nth-child(4) { animation-delay: 0.15s; }

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(18px); }
  to   { opacity: 1; transform: none; }
}

.card-header {
  display: flex;
  align-items: center;
  gap: 13px;
  margin-bottom: 26px;
  padding-bottom: 18px;
  border-bottom: 1px solid var(--border);
}

.card-icon {
  width: 40px;
  height: 40px;
  background: var(--green-bg);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.card-icon svg { width: 20px; height: 20px; }

.card-header-text h3 {
  font-size: 15px;
  font-weight: 700;
  margin-bottom: 3px;
}

.card-header-text p {
  font-size: 12.5px;
  color: var(--text-gray);
}

/* FIELDS */
.field-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 18px;
}

.field-row.single { grid-template-columns: 1fr; }

.field-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.field-group label {
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-dark);
}

.field-group input,
.field-group select,
.field-group textarea {
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 11px 14px;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
  width: 100%;
  appearance: none;
  -webkit-appearance: none;
}

.field-group input::placeholder,
.field-group textarea::placeholder { color: var(--text-light); }

.field-group input:focus,
.field-group select:focus,
.field-group textarea:focus {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, 0.18);
}

.field-group textarea { resize: vertical; min-height: 110px; }

.field-error { border-color: #E53E3E !important; }
.field-error:focus { box-shadow: 0 0 0 3px rgba(229,62,62,0.15) !important; }
.error-msg { font-size: 11.5px; color: #E53E3E; }

.select-wrap { position: relative; }
.select-wrap select { padding-right: 38px; cursor: pointer; }
.select-wrap::after {
  content: '';
  position: absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  width: 10px;
  height: 6px;
  background: var(--text-light);
  clip-path: polygon(0 0, 100% 0, 50% 100%);
  pointer-events: none;
}

.date-wrap { position: relative; }
.date-wrap input { padding-left: 40px; }
.date-icon {
  position: absolute;
  left: 13px;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
}

.date-wrap input[type="date"].is-empty:not(:focus)::-webkit-datetime-edit {
  color: transparent;
}

.date-placeholder {
  position: absolute;
  left: 40px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 13px;
  color: var(--text-light);
  pointer-events: none;
}

.date-wrap:focus-within .date-placeholder {
  display: none;
}

.field-group select:disabled,
.field-group input:disabled {
  background: #F4F6F5;
  color: var(--text-light);
  cursor: not-allowed;
}

.field-hint {
  font-size: 11.5px;
  color: var(--text-gray);
  line-height: 1.5;
  margin-top: 4px;
}

.vbg-hint { color: #C66E00; }

/* UPLOAD */
.upload-zone {
  border: 2px dashed var(--border);
  border-radius: 10px;
  padding: 48px 24px;
  text-align: center;
  cursor: pointer;
  background: var(--offwhite);
  transition: border-color 0.2s, background 0.2s;
}

.upload-zone:hover,
.upload-zone.drag-over {
  border-color: var(--green-light);
  background: var(--green-bg);
}

.upload-icon {
  width: 48px;
  height: 48px;
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 16px;
}

.upload-zone h4 { font-size: 14px; font-weight: 600; margin-bottom: 5px; }
.upload-zone p  { font-size: 12px; color: var(--text-light); }

.file-list { margin-top: 14px; display: flex; flex-direction: column; gap: 8px; }

.file-item {
  display: flex;
  align-items: center;
  gap: 10px;
  background: var(--green-bg);
  border: 1px solid #C3E6CE;
  border-radius: 8px;
  padding: 10px 14px;
}

.file-item-name { font-size: 13px; font-weight: 500; color: var(--green-dark); flex: 1; }
.file-item-size { font-size: 11px; color: var(--text-light); }

.file-remove {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--text-light);
  display: flex;
  transition: color 0.2s;
}

.file-remove:hover { color: #E53E3E; }

/* ERRO GLOBAL */
.global-error {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #FFF5F5;
  border: 1px solid #FEB2B2;
  border-radius: 10px;
  padding: 14px 18px;
  color: #C53030;
  font-size: 13px;
  font-weight: 500;
  margin-bottom: 16px;
}

/* SUBMIT BANNER */
.submit-banner {
  background: linear-gradient(135deg, #1B4332 0%, #2D6A4F 100%);
  border-radius: 12px;
  padding: 22px 28px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  margin-top: 24px;
  animation: fadeUp 0.5s 0.2s ease both;
}

.submit-banner-text { display: flex; align-items: flex-start; gap: 12px; }

.submit-banner-icon {
  width: 34px;
  height: 34px;
  background: rgba(255, 255, 255, 0.12);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 1px;
  overflow: hidden;
}

.submit-banner-logo {
  width: 100%;
  height: 100%;
  object-fit: contain;
  border-radius: 6px;
}

.submit-banner p {
  font-size: 12.5px;
  color: rgba(255, 255, 255, 0.85);
  line-height: 1.65;
  max-width: 440px;
}

.btn-submit {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--green-light);
  color: #fff;
  border: none;
  border-radius: 9px;
  padding: 13px 28px;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  white-space: nowrap;
  flex-shrink: 0;
  transition: background 0.2s, transform 0.15s, opacity 0.2s;
}

.btn-submit:hover:not(:disabled) { background: #40A07A; transform: translateY(-1px); }
.btn-submit:disabled             { opacity: 0.65; cursor: not-allowed; }

.spinner {
  width: 14px;
  height: 14px;
  border: 2px solid rgba(255,255,255,0.35);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
  flex-shrink: 0;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* SUCCESS MODAL */
.success-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

.success-card {
  background: var(--white);
  border-radius: 18px;
  padding: 44px 40px;
  text-align: center;
  max-width: 420px;
  width: 90%;
  animation: scaleIn 0.3s ease;
}

@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.88); }
  to   { opacity: 1; transform: scale(1); }
}

.success-icon {
  width: 72px;
  height: 72px;
  background: var(--green-pale);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 22px;
}

.success-card h3 { font-size: 20px; font-weight: 800; margin-bottom: 10px; }

.success-card > p {
  font-size: 13.5px;
  color: var(--text-gray);
  line-height: 1.65;
  margin-bottom: 22px;
}

/* Tracking code box */
.tracking-box {
  background: var(--green-bg);
  border: 1.5px solid #C3E6CE;
  border-radius: 12px;
  padding: 18px 20px 14px;
  margin-bottom: 24px;
  text-align: left;
}

.tracking-label {
  display: block;
  font-size: 10.5px;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--text-gray);
  margin-bottom: 10px;
}

.tracking-row {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
}

.tracking-code {
  font-size: 20px;
  font-weight: 800;
  color: var(--green-dark);
  letter-spacing: 2px;
  flex: 1;
}

.btn-copy {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 7px;
  padding: 6px 12px;
  font-family: 'Poppins', sans-serif;
  font-size: 12px;
  font-weight: 600;
  color: var(--text-dark);
  cursor: pointer;
  white-space: nowrap;
  transition: background 0.2s, border-color 0.2s, color 0.2s;
}

.btn-copy:hover {
  background: var(--green-mid);
  border-color: var(--green-mid);
  color: #fff;
}

.tracking-hint {
  font-size: 11.5px;
  color: var(--text-gray);
  line-height: 1.5;
  margin: 0 0 4px;
}

.tracking-due {
  font-size: 11.5px;
  color: var(--text-gray);
  margin: 0;
}

.tracking-due strong { color: var(--green-dark); }

.btn-ok {
  background: var(--green-mid);
  color: #fff;
  border: none;
  border-radius: 9px;
  padding: 12px 36px;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-ok:hover { background: var(--green-dark); }

/* CONTADOR DE CARACTERES */
.char-counter { font-size: 11.5px; margin-top: 4px; }
.char-hint    { color: var(--text-light); }
.char-warn    { color: #C66E00; }
.char-ok      { color: var(--green-mid); }

/* HINT DE CONTACTO */
.contact-hint-group { justify-content: center; }
.contact-hint-row { margin-top: -10px; margin-bottom: 4px; }
.contact-hint { font-size: 11.5px; color: var(--text-light); }

/* ── RESPONSIVE ─────────────────────────────────────────────── */
@media (max-width: 768px) {
  .page-wrapper { padding: 32px 16px 56px; }
  .page-header h1 { font-size: 26px; }

  .form-card { padding: 22px 18px; }

  .field-row { grid-template-columns: 1fr; gap: 14px; }

  .upload-zone { padding: 32px 16px; }

  .submit-banner {
    flex-direction: column;
    align-items: stretch;
    padding: 20px;
  }

  .btn-submit { justify-content: center; }

  .success-card { padding: 32px 22px; }
}

@media (max-width: 480px) {
  .tracking-row { flex-wrap: wrap; }
  .tracking-code { font-size: 17px; }
}
</style>