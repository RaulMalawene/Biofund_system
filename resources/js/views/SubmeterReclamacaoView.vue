<template>
  <div class="page">
    <AppNavbar />

    <div class="page-wrapper">

      <!-- HEADER -->
      <div class="page-header">
        <div class="page-badge">
          <svg width="14" height="14" viewBox="0 0 32 32" fill="none">
            <path d="M6 26C6 26 8 14 20 10C28 7 28 4 28 4C28 4 30 16 20 20C12 24 10 28 10 28" fill="#2D6A4F"/>
            <path d="M10 28C10 28 14 20 20 20" stroke="#1B4332" stroke-width="2" stroke-linecap="round"/>
          </svg>
          Biofund Moçambique
        </div>
        <h1>Registar Reclamação</h1>
        <p>Ajude-nos a proteger os nossos ecossistemas. Preencha o formulário abaixo com o máximo de detalhes possível sobre o incidente ambiental observado.</p>
      </div>

      <!-- CARD 1: Informação da Reclamação -->
      <div class="form-card">
        <div class="card-header">
          <div class="card-icon">
            <svg fill="none" viewBox="0 0 20 20" stroke="#2D6A4F" stroke-width="1.8">
              <circle cx="10" cy="10" r="8"/>
              <path d="M10 6v4l2.5 2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="card-header-text">
            <h3>Informação da Reclamação</h3>
            <p>Identifique o tipo de incidente e descreva o que aconteceu.</p>
          </div>
        </div>

        <div class="field-row">
          <div class="field-group">
            <label>Projecto Relacionado</label>
            <div class="select-wrap">
              <select v-model="form.projeto">
                <option value="" disabled>Seleccione o projecto</option>
                <option v-for="p in projectos" :key="p">{{ p }}</option>
              </select>
            </div>
          </div>
          <div class="field-group">
            <label>Categoria do Incidente</label>
            <div class="select-wrap">
              <select v-model="form.categoria">
                <option value="" disabled>Seleccione a categoria</option>
                <option v-for="c in categorias" :key="c">{{ c }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="field-row single">
          <div class="field-group">
            <label>Descrição Detalhada</label>
            <textarea v-model="form.descricao"
              placeholder="Descreva o que observou, pessoas envolvidas, gravidade e outros detalhes relevantes..."></textarea>
          </div>
        </div>

        <div class="field-row single" style="max-width:320px">
          <div class="field-group">
            <label>Data da Ocorrência</label>
            <div class="date-wrap">
              <span class="date-icon">
                <svg width="16" height="16" fill="none" stroke="#888E8C" stroke-width="1.6" viewBox="0 0 16 16">
                  <rect x="1" y="2" width="14" height="13" rx="2"/>
                  <path d="M5 1v2M11 1v2M1 6h14" stroke-linecap="round"/>
                </svg>
              </span>
              <input type="date" v-model="form.data" />
            </div>
          </div>
        </div>
      </div>

      <!-- CARD 2: Localização -->
      <div class="form-card">
        <div class="card-header">
          <div class="card-icon">
            <svg fill="none" viewBox="0 0 20 20" stroke="#2D6A4F" stroke-width="1.8">
              <path d="M10 2C6.686 2 4 4.686 4 8c0 4.5 6 10 6 10s6-5.5 6-10c0-3.314-2.686-6-6-6z" stroke-linejoin="round"/>
              <circle cx="10" cy="8" r="2.2"/>
            </svg>
          </div>
          <div class="card-header-text">
            <h3>Localização do Incidente</h3>
            <p>Ajude a nossa equipa a localizar a ocorrência no mapa.</p>
          </div>
        </div>

        <div class="field-row">
          <div class="field-group">
            <label>Província</label>
            <div class="select-wrap">
              <select v-model="form.provincia" @change="form.distrito=''; form.comunidade=''">
                <option value="" disabled>Seleccione a província</option>
                <option v-for="p in provincias" :key="p">{{ p }}</option>
              </select>
            </div>
          </div>
          <div class="field-group">
            <label>Distrito</label>
            <div class="select-wrap">
              <select v-model="form.distrito" :disabled="!form.provincia" @change="form.comunidade=''">
                <option value="" disabled>Seleccione o distrito</option>
                <option v-for="d in (distritos[form.provincia] || [])" :key="d">{{ d }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="field-row single">
          <div class="field-group">
            <label>Comunidade / Posto Administrativo</label>
            <div class="select-wrap">
              <select v-model="form.comunidade" :disabled="!form.distrito">
                <option value="" disabled>Seleccione a comunidade</option>
                <option v-for="c in (comunidades[form.distrito] || [])" :key="c">{{ c }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="field-row single">
          <div class="field-group">
            <label>Coordenadas (Opcional)</label>
            <input type="text" v-model="form.coordenadas"
              placeholder="Ex: -25.9682, 32.5732 ou descrição do local" />
          </div>
        </div>
      </div>

      <!-- CARD 3: Contacto -->
      <div class="form-card">
        <div class="card-header">
          <div class="card-icon">
            <svg fill="none" viewBox="0 0 20 20" stroke="#2D6A4F" stroke-width="1.8">
              <circle cx="10" cy="7" r="3.5"/>
              <path d="M3 18c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="card-header-text">
            <h3>Informação de Contacto</h3>
            <p>Como podemos contactá-lo para obter mais detalhes?</p>
          </div>
        </div>

        <div class="field-row">
          <div class="field-group">
            <label>Seu Nome (Opcional)</label>
            <input type="text" v-model="form.nome" placeholder="Nome completo ou pseudónimo" />
          </div>
          <div class="field-group">
            <label>Telefone ou Email (Obrigatório)</label>
            <input type="text" v-model="form.contacto" placeholder="ex: +258 84… ou email@exemplo.com" />
          </div>
        </div>
      </div>

      <!-- CARD 4: Evidências -->
      <div class="form-card">
        <div class="card-header">
          <div class="card-icon">
            <svg fill="none" viewBox="0 0 20 20" stroke="#2D6A4F" stroke-width="1.8">
              <path d="M2 7a2 2 0 0 1 2-2h.5l1-2h5l1 2H16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7z" stroke-linejoin="round"/>
              <circle cx="10" cy="11" r="2.5"/>
            </svg>
          </div>
          <div class="card-header-text">
            <h3>Evidências e Anexos</h3>
            <p>Fotos ou documentos ajudam na validação da queixa.</p>
          </div>
        </div>

        <div class="upload-zone" :class="{ 'drag-over': isDragging }"
          @click="triggerUpload"
          @dragover.prevent="isDragging = true"
          @dragleave="isDragging = false"
          @drop.prevent="handleDrop">
          <div class="upload-icon">
            <svg width="22" height="22" fill="none" stroke="#2D6A4F" stroke-width="1.7" viewBox="0 0 22 22">
              <path d="M3 15v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke-linecap="round"/>
              <path d="M11 3v10M7 7l4-4 4 4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <h4>Clique para carregar ou arraste e solte</h4>
          <p>PNG, JPG, PDF até 10MB</p>
        </div>

        <input ref="fileInput" type="file" multiple accept=".png,.jpg,.jpeg,.pdf"
          style="display:none" @change="handleFileSelect" />

        <div class="file-list" v-if="files.length">
          <div class="file-item" v-for="(f, i) in files" :key="i">
            <svg width="16" height="16" fill="none" stroke="#2D6A4F" stroke-width="1.6" viewBox="0 0 16 16">
              <rect x="2" y="1" width="10" height="14" rx="1.5"/>
              <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round"/>
              <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="file-item-name">{{ f.name }}</span>
            <span class="file-item-size">{{ (f.size / 1024).toFixed(0) }} KB</span>
            <button class="file-remove" @click.stop="removeFile(i)">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round"/>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- SUBMIT BANNER -->
      <div class="submit-banner">
        <div class="submit-banner-text">
          <div class="submit-banner-icon">
            <svg width="18" height="18" viewBox="0 0 32 32" fill="none">
              <path d="M6 26C6 26 8 14 20 10C28 7 28 4 28 4C28 4 30 16 20 20C12 24 10 28 10 28" fill="#52B788"/>
              <path d="M10 28C10 28 14 20 20 20" stroke="#D8F3DC" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <p>Ao submeter esta reclamação, você declara que as informações prestadas são verdadeiras e autoriza a Biofund a utilizá-las para fins de investigação ambiental.</p>
        </div>
        <button class="btn-submit" @click="submitForm">
          Enviar Reclamação
          <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 14 14">
            <path d="M2 7h10M8 3l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>

    </div>

    <!-- SUCCESS MODAL -->
    <div class="success-overlay" v-if="showSuccess" @click.self="showSuccess = false">
      <div class="success-card">
        <div class="success-icon">
          <svg width="36" height="36" fill="none" stroke="#2D6A4F" stroke-width="2.2" viewBox="0 0 36 36">
            <circle cx="18" cy="18" r="15"/>
            <path d="M11 18l5 5 9-10" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h3>Reclamação Enviada!</h3>
        <p>A sua reclamação foi registada com sucesso. A equipa da Biofund irá analisar e tomar as medidas necessárias.</p>
        <button class="btn-ok" @click="showSuccess = false">Fechar</button>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import AppNavbar from '@/components/AppNavbar.vue'
import AppFooter from '@/components/AppFooter.vue'

const form = reactive({
  projeto: '', categoria: '', descricao: '', data: '',
  provincia: '', distrito: '', comunidade: '', coordenadas: '', nome: '', contacto: ''
})

const files       = ref([])
const isDragging  = ref(false)
const showSuccess = ref(false)
const fileInput   = ref(null)

const projectos = [
  'Reserva do Niassa', 'Parque da Gorongosa', 'Arquipélago de Bazaruto',
  'Lagoa de Bilene', 'Parque Nacional do Limpopo', 'Outro'
]

const categorias = [
  'Pesca Ilegal', 'Caça Furtiva', 'Poluição', 'Desflorestação',
  'Tráfico de Animais', 'Degradação de Habitat', 'Outro'
]

const provincias = [
  'Cabo Delgado', 'Gaza', 'Inhambane', 'Manica',
  'Maputo Cidade', 'Maputo Província', 'Nampula',
  'Niassa', 'Sofala', 'Tete', 'Zambézia'
]

const distritos = {
  'Maputo Cidade':    ['KaMpfumo','KaNhlamankulu','KaMaxaquene','KaPolana','KaMavota','KaMubukwana'],
  'Maputo Província': ['Boane','Magude','Manhiça','Marracuene','Matola','Moamba','Namaacha'],
  'Gaza':    ['Bilene','Chibuto','Chicualacuala','Chigubo','Chokwé','Guijá','Limpopo'],
  'Inhambane':['Funhalouro','Govuro','Homoíne','Inharrime','Inhassoro','Jangamo','Mabote'],
  'Niassa':  ['Cuamba','Lago','Lichinga','Majune','Mandimba','Marrupa','Mavago'],
  'Nampula': ['Angoche','Ilha de Moçambique','Meconta','Memba','Monapo','Mossuril','Nacala'],
  'Sofala':  ['Buzi','Caia','Chemba','Cheringoma','Chibabava','Dondo','Gorongosa'],
  'Manica':  ['Báruè','Chimoio','Gondola','Guro','Machaze','Macossa','Mossurize'],
  'Tete':    ['Angónia','Cahora-Bassa','Changara','Chifunde','Chiuta','Macanga','Mágoe'],
  'Zambézia':['Alto Molócuè','Chinde','Gile','Guruè','Ile','Inhassunge','Luabo'],
  'Cabo Delgado':['Ancuabe','Balama','Chiúre','Ibo','Macomia','Mecúfi','Meluco'],
}

const comunidades = {
  'KaMpfumo':       ['Sommerschield','Polana Cimento A','Polana Cimento B','Central A','Central B','Coop'],
  'KaNhlamankulu':  ['Chamanculo A','Chamanculo B','Chamanculo C','Munhuana','Xipamanine','Malanga'],
  'KaMaxaquene':    ['Maxaquene A','Maxaquene B','Maxaquene C','Maxaquene D','Polana Caniço A','Polana Caniço B'],
  'KaPolana':       ['Alto Maé A','Alto Maé B','Malhangalene A','Malhangalene B','Aeroporto'],
  'KaMavota':       ['Hulene A','Hulene B','Mavalane A','Mavalane B','Unidade A','Unidade B'],
  'KaMubukwana':    ['Bagamoio','Magoanine A','Magoanine B','Magoanine C','Luís Cabral','Inhagoia A','Inhagoia B'],
  'Boane':          ['Boane Sede','Belavista','Mafuiane','Mulotana'],
  'Magude':         ['Magude Sede','Moine','Mapulanguene','Motaze'],
  'Manhiça':        ['Manhiça Sede','Calanga','Ilha Josina','Maragra','Nwamatibjana','Xinavane'],
  'Marracuene':     ['Marracuene Sede','Machubo','Michafutene','Nhangau'],
  'Matola':         ['Matola Sede','Bunhiça','Infulene','Machava','Matola Rio','Tsalala'],
  'Moamba':         ['Moamba Sede','Pessene','Sabie'],
  'Namaacha':       ['Namaacha Sede','Changalane','Fontainhas'],
  'Bilene':         ['Bilene Sede','Chaimite','Siaia','Macuácua'],
  'Chibuto':        ['Chibuto Sede','Changanine','Malehice','São Lourenço'],
  'Chicualacuala':  ['Chicualacuala Sede','Mapai','Mabalane'],
  'Chigubo':        ['Chigubo Sede','Ndindiza'],
  'Chokwé':         ['Chokwé Sede','Lionde','Macarretane'],
  'Guijá':          ['Guijá Sede','Eduardo Mondlane','Mandlakaze'],
  'Limpopo':        ['Limpopo Sede','Chongoene','Mazivila'],
  'Funhalouro':     ['Funhalouro Sede','Zimane','Mabote'],
  'Govuro':         ['Govuro Sede','Mambone','Save'],
  'Homoíne':        ['Homoíne Sede','Morrumbene','Massinga'],
  'Inharrime':      ['Inharrime Sede','Chidenguele','Zavala'],
  'Inhassoro':      ['Inhassoro Sede','Bartolomeu Dias'],
  'Jangamo':        ['Jangamo Sede','Bela Vista','Nhamatanda'],
  'Mabote':         ['Mabote Sede','Zimane','Funhalouro'],
  'Cuamba':         ['Cuamba Sede','Chiconono','Metarica'],
  'Lago':           ['Lago Sede','Cobué','Mataka'],
  'Lichinga':       ['Lichinga Sede','Chiuanga','Namacula'],
  'Majune':         ['Majune Sede','Maúa','Nungo'],
  'Mandimba':       ['Mandimba Sede','Chiuta','Massangulo'],
  'Marrupa':        ['Marrupa Sede','Maúa','Lúrio'],
  'Mavago':         ['Mavago Sede','Mecula','Mariri'],
  'Angoche':        ['Angoche Sede','Kinga','Nanhupo','Oriente'],
  'Ilha de Moçambique': ['Lumbo','Mossuril','Nacala-Porto'],
  'Meconta':        ['Meconta Sede','Muecate','Nacarôa'],
  'Memba':          ['Memba Sede','Nacala-a-Velha','Namige'],
  'Monapo':         ['Monapo Sede','Chalaua','Iapala','Namialo'],
  'Mossuril':       ['Mossuril Sede','Liúpo','Namapa'],
  'Nacala':         ['Nacala Sede','Nacala-Porto','Nacala-a-Velha'],
  'Buzi':           ['Buzi Sede','Guara-Guara','Muxungué'],
  'Caia':           ['Caia Sede','Sena','Murraça'],
  'Chemba':         ['Chemba Sede','Mphingwe','Nhamatanda'],
  'Cheringoma':     ['Cheringoma Sede','Inhaminga','Muanza'],
  'Chibabava':      ['Chibabava Sede','Machanga','Muxungué'],
  'Dondo':          ['Dondo Sede','Lamego','Tica'],
  'Gorongosa':      ['Gorongosa Sede','Nhamatanda','Muanza','Sadjunjira'],
  'Báruè':          ['Báruè Sede','Catandica','Macossa','Nhansimba'],
  'Chimoio':        ['Chimoio Sede','Gondola','Sussundenga','Manica'],
  'Gondola':        ['Gondola Sede','Inchope','Muanza'],
  'Guro':           ['Guro Sede','Doroi','Macossa'],
  'Machaze':        ['Machaze Sede','Espungabera','Mossurize'],
  'Macossa':        ['Macossa Sede','Guro'],
  'Mossurize':      ['Mossurize Sede','Espungabera','Machaze'],
  'Angónia':        ['Angónia Sede','Domue','Ulónguè','Mkumbura'],
  'Cahora-Bassa':   ['Songo','Capoche','Chitima','Fingoe'],
  'Changara':       ['Changara Sede','Mágoè','Chiúta'],
  'Chifunde':       ['Chifunde Sede','Macanga','Zumbo'],
  'Chiuta':         ['Chiuta Sede','Furancungo','Mualadzi'],
  'Macanga':        ['Macanga Sede','Zumbo','Furancungo'],
  'Mágoe':          ['Mágoe Sede','Cahora-Bassa','Capoche'],
  'Alto Molócuè':   ['Alto Molócuè Sede','Namarrói','Mulevala'],
  'Chinde':         ['Chinde Sede','Mopeia','Marromeu'],
  'Gile':           ['Gile Sede','Pebane','Namarrói'],
  'Guruè':          ['Guruè Sede','Lioma','Ile'],
  'Ile':            ['Ile Sede','Namarrói','Alto Molócuè'],
  'Inhassunge':     ['Inhassunge Sede','Mopeia','Chinde'],
  'Luabo':          ['Luabo Sede','Mopeia','Marromeu'],
  'Ancuabe':        ['Ancuabe Sede','Balama','Chiúre'],
  'Balama':         ['Balama Sede','Chiúre','Macomia'],
  'Chiúre':         ['Chiúre Sede','Chiúre Velho','Mazeze','Namuno'],
  'Ibo':            ['Ibo Sede','Quirimba','Quissanga'],
  'Macomia':        ['Macomia Sede','Meluco','Quissanga','Mucojo'],
  'Mecúfi':         ['Mecúfi Sede','Metuge','Pemba'],
  'Meluco':         ['Meluco Sede','Macomia','Nangade'],
}

const triggerUpload    = () => fileInput.value?.click()
const handleFileSelect = (e) => { addFiles(Array.from(e.target.files)); e.target.value = '' }
const handleDrop       = (e) => { isDragging.value = false; addFiles(Array.from(e.dataTransfer.files)) }
const addFiles         = (list) => list.forEach(f => { if (f.size <= 10 * 1024 * 1024) files.value.push(f) })
const removeFile       = (i) => files.value.splice(i, 1)

const submitForm = () => {
  if (!form.contacto) {
    alert('Por favor, preencha o campo Telefone ou Email.')
    return
  }
  showSuccess.value = true
}
</script>

<style scoped>
.page { background: var(--offwhite); min-height: 100vh; }

.page-wrapper { max-width: 760px; margin: 0 auto; padding: 52px 24px 80px; }

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

.page-header h1 { font-size: 32px; font-weight: 800; margin-bottom: 10px; line-height: 1.2; }

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
  width: 40px; height: 40px;
  background: var(--green-bg);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}

.card-icon svg { width: 20px; height: 20px; }

.card-header-text h3 { font-size: 15px; font-weight: 700; margin-bottom: 3px; }
.card-header-text p  { font-size: 12.5px; color: var(--text-gray); }

/* FIELDS */
.field-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 18px;
}

.field-row.single { grid-template-columns: 1fr; }

.field-group { display: flex; flex-direction: column; gap: 6px; }

.field-group label { font-size: 12.5px; font-weight: 600; color: var(--text-dark); }

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
  box-shadow: 0 0 0 3px rgba(82,183,136,0.18);
}

.field-group textarea { resize: vertical; min-height: 110px; }

.select-wrap { position: relative; }
.select-wrap select { padding-right: 38px; cursor: pointer; }

.select-wrap::after {
  content: '';
  position: absolute;
  right: 14px; top: 50%;
  transform: translateY(-50%);
  width: 10px; height: 6px;
  background: var(--text-light);
  clip-path: polygon(0 0, 100% 0, 50% 100%);
  pointer-events: none;
}

.date-wrap { position: relative; }
.date-wrap input { padding-left: 40px; }

.date-icon {
  position: absolute;
  left: 13px; top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
}

.field-group select:disabled { background: #F4F6F5; color: var(--text-light); cursor: not-allowed; }

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
.upload-zone.drag-over { border-color: var(--green-light); background: var(--green-bg); }

.upload-icon {
  width: 48px; height: 48px;
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px;
}

.upload-zone h4 { font-size: 14px; font-weight: 600; margin-bottom: 5px; }
.upload-zone p  { font-size: 12px; color: var(--text-light); }

.file-list { margin-top: 14px; display: flex; flex-direction: column; gap: 8px; }

.file-item {
  display: flex; align-items: center; gap: 10px;
  background: var(--green-bg);
  border: 1px solid #C3E6CE;
  border-radius: 8px;
  padding: 10px 14px;
}

.file-item-name { font-size: 13px; font-weight: 500; color: var(--green-dark); flex: 1; }
.file-item-size { font-size: 11px; color: var(--text-light); }

.file-remove {
  background: none; border: none; cursor: pointer;
  color: var(--text-light); display: flex; transition: color 0.2s;
}
.file-remove:hover { color: #E53E3E; }

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
  width: 34px; height: 34px;
  background: rgba(255,255,255,0.12);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; margin-top: 1px;
}

.submit-banner p {
  font-size: 12.5px;
  color: rgba(255,255,255,0.85);
  line-height: 1.65;
  max-width: 440px;
}

.btn-submit {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--green-light); color: #fff; border: none;
  border-radius: 9px; padding: 13px 28px;
  font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 700;
  cursor: pointer; white-space: nowrap; flex-shrink: 0;
  transition: background 0.2s, transform 0.15s;
}

.btn-submit:hover { background: #40A07A; transform: translateY(-1px); }

/* SUCCESS MODAL */
.success-overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.45);
  display: flex; align-items: center; justify-content: center;
  z-index: 999;
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

.success-card {
  background: var(--white);
  border-radius: 18px;
  padding: 48px 40px;
  text-align: center;
  max-width: 400px; width: 90%;
  animation: scaleIn 0.3s ease;
}

@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.88); }
  to   { opacity: 1; transform: scale(1); }
}

.success-icon {
  width: 72px; height: 72px;
  background: var(--green-pale);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 22px;
}

.success-card h3 { font-size: 20px; font-weight: 800; margin-bottom: 10px; }

.success-card p {
  font-size: 13.5px;
  color: var(--text-gray);
  line-height: 1.65;
  margin-bottom: 28px;
}

.btn-ok {
  background: var(--green-mid); color: #fff; border: none;
  border-radius: 9px; padding: 12px 36px;
  font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 700;
  cursor: pointer; transition: background 0.2s;
}

.btn-ok:hover { background: var(--green-dark); }
</style>
