<template>
  <Teleport to="body">
    <!-- Backdrop -->
    <Transition name="fade">
      <div v-if="open" class="rp-backdrop" @click.self="fechar" />
    </Transition>

    <!-- Modal -->
    <Transition name="slide-up">
      <div v-if="open" class="rp-modal" role="dialog" aria-modal="true" aria-labelledby="rp-title">

        <!-- Header -->
        <div class="rp-header">
          <div class="rp-header-left">
            <div class="rp-icon">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
                <rect x="2" y="1" width="10" height="14" rx="1.5"/>
                <path d="M5 5h5M5 8h5M5 11h3" stroke-linecap="round"/>
                <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div>
              <h2 id="rp-title" class="rp-title">Gerar Relatório Periódico</h2>
              <p class="rp-subtitle">Trimestral ou semestral — exportação em PDF e Excel</p>
            </div>
          </div>
          <button class="rp-close" @click="fechar" aria-label="Fechar">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
              <path d="M3 3l10 10M13 3L3 13" stroke-linecap="round"/>
            </svg>
          </button>
        </div>

        <!-- Form de parâmetros -->
        <div class="rp-body">

          <!-- Linha 1: Tipo e Período -->
          <div class="rp-row">
            <div class="rp-field">
              <label class="rp-label">Tipo de período</label>
              <div class="rp-toggle-group">
                <button
                  v-for="t in tipoOpcoes"
                  :key="t.value"
                  class="rp-toggle"
                  :class="{ active: form.tipo === t.value }"
                  @click="form.tipo = t.value; form.periodo = ''"
                >{{ t.label }}</button>
              </div>
            </div>

            <div class="rp-field">
              <label class="rp-label">Período</label>
              <div class="rp-toggle-group">
                <button
                  v-for="p in periodoOpcoes"
                  :key="p.value"
                  class="rp-toggle"
                  :class="{ active: form.periodo === p.value }"
                  @click="form.periodo = p.value"
                >{{ p.label }}</button>
              </div>
            </div>

            <div class="rp-field rp-field--sm">
              <label class="rp-label">Ano</label>
              <select class="rp-select" v-model="form.year">
                <option v-for="y in anosDisponiveis" :key="y" :value="y">{{ y }}</option>
              </select>
            </div>
          </div>

          <!-- Linha 2: Filtros opcionais -->
          <div class="rp-row">
            <div class="rp-field">
              <label class="rp-label">Projecto <span class="rp-optional">opcional</span></label>
              <select class="rp-select" v-model="form.project_id">
                <option value="">Todos os projectos</option>
                <option v-for="p in props.projects" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
            </div>
            <div class="rp-field">
              <label class="rp-label">Província <span class="rp-optional">opcional</span></label>
              <select class="rp-select" v-model="form.province_id">
                <option value="">Todas as províncias</option>
                <option v-for="p in props.provinces" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
            </div>
            <div class="rp-field">
              <label class="rp-label">Categoria <span class="rp-optional">opcional</span></label>
              <select class="rp-select" v-model="form.category_id">
                <option value="">Todas as categorias</option>
                <option v-for="c in props.categories" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
            </div>
          </div>

          <!-- Preview do período seleccionado -->
          <div v-if="form.periodo" class="rp-preview">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 16 16">
              <rect x="1" y="2" width="14" height="13" rx="2"/>
              <path d="M5 1v2M11 1v2M1 7h14" stroke-linecap="round"/>
            </svg>
            <span>
              <strong>{{ labelPeriodoSeleccionado }}</strong> ·
              {{ rangeTexto }}
            </span>
          </div>

          <!-- Erros -->
          <div v-if="erro" class="rp-error">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
              <circle cx="8" cy="8" r="7"/>
              <path d="M8 5v3.5M8 11v.5" stroke-linecap="round"/>
            </svg>
            {{ erro }}
          </div>
        </div>

        <!-- Footer com botões de exportação -->
        <div class="rp-footer">
          <button class="rp-btn rp-btn--ghost" @click="fechar" :disabled="loading">
            Cancelar
          </button>
          <div class="rp-export-group">
            <button
              class="rp-btn rp-btn--excel"
              @click="exportar('excel')"
              :disabled="!form.periodo || loading"
            >
              <svg v-if="loading && exportTarget === 'excel'" class="rp-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
              </svg>
              <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
                <rect x="1" y="1" width="14" height="14" rx="2"/>
                <path d="M4 5l2 6 2-4 2 4 2-6" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              {{ loading && exportTarget === 'excel' ? 'A gerar…' : 'Exportar Excel' }}
            </button>
            <button
              class="rp-btn rp-btn--pdf"
              @click="exportar('pdf')"
              :disabled="!form.periodo || loading"
            >
              <svg v-if="loading && exportTarget === 'pdf'" class="rp-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
              </svg>
              <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
                <rect x="2" y="1" width="9" height="14" rx="1.5"/>
                <path d="M9 1v5h5M5 8h4M5 11h3" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              {{ loading && exportTarget === 'pdf' ? 'A gerar…' : 'Exportar PDF' }}
            </button>
          </div>
        </div>

      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import api from '@/api/client'
import XLSXStyle from 'xlsx-js-style'

// ── Props & emits ─────────────────────────────────────────────
const props = defineProps({
  open:       { type: Boolean, default: false },
  projects:   { type: Array, default: () => [] },
  provinces:  { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
})
const emit = defineEmits(['close'])

// ── Estado do formulário ─────────────────────────────────────
const form = ref({
  tipo:        'trimestral',
  periodo:     '',
  year:        new Date().getFullYear(),
  project_id:  '',
  province_id: '',
  category_id: '',
})

const loading      = ref(false)
const exportTarget = ref('')
const erro         = ref('')

// ── Opções ───────────────────────────────────────────────────
const tipoOpcoes = [
  { value: 'trimestral', label: 'Trimestral' },
  { value: 'semestral',  label: 'Semestral'  },
]

const periodoOpcoes = computed(() => {
  if (form.value.tipo === 'trimestral') {
    return [
      { value: 'Q1', label: 'T1 · Jan–Mar' },
      { value: 'Q2', label: 'T2 · Abr–Jun' },
      { value: 'Q3', label: 'T3 · Jul–Set' },
      { value: 'Q4', label: 'T4 · Out–Dez' },
    ]
  }
  return [
    { value: 'S1', label: '1.º Sem · Jan–Jun' },
    { value: 'S2', label: '2.º Sem · Jul–Dez' },
  ]
})

const anosDisponiveis = computed(() => {
  const ano = new Date().getFullYear()
  return Array.from({ length: 6 }, (_, i) => ano - i)
})

// Texto descritivo do range seleccionado
const rangeTexto = computed(() => {
  const y = form.value.year
  const ranges = {
    Q1: `01/01/${y} – 31/03/${y}`,
    Q2: `01/04/${y} – 30/06/${y}`,
    Q3: `01/07/${y} – 30/09/${y}`,
    Q4: `01/10/${y} – 31/12/${y}`,
    S1: `01/01/${y} – 30/06/${y}`,
    S2: `01/07/${y} – 31/12/${y}`,
  }
  return ranges[form.value.periodo] ?? ''
})

const labelPeriodoSeleccionado = computed(() => {
  const y = form.value.year
  const labels = {
    Q1: `1.º Trimestre ${y}`, Q2: `2.º Trimestre ${y}`,
    Q3: `3.º Trimestre ${y}`, Q4: `4.º Trimestre ${y}`,
    S1: `1.º Semestre ${y}`,  S2: `2.º Semestre ${y}`,
  }
  return labels[form.value.periodo] ?? ''
})

// ── Reset quando fecha ────────────────────────────────────────
watch(() => props.open, (val) => {
  if (!val) {
    form.value.periodo     = ''
    form.value.project_id  = ''
    form.value.province_id = ''
    form.value.category_id = ''
    erro.value = ''
  }
})

function fechar() {
  if (loading.value) return
  emit('close')
}

// ── Exportação ────────────────────────────────────────────────
async function exportar(formato) {
  if (!form.value.periodo || loading.value) return
  erro.value     = ''
  loading.value  = true
  exportTarget.value = formato

  try {
    const params = {
      period: form.value.periodo,
      year:   form.value.year,
    }
    if (form.value.project_id)  params.project_id  = form.value.project_id
    if (form.value.province_id) params.province_id = form.value.province_id
    if (form.value.category_id) params.category_id = form.value.category_id

    const { data } = await api.get('/admin/statistics/report/periodic', { params })

    if (formato === 'excel') {
      await gerarExcel(data)
    } else {
      await gerarPdf(data)
    }
  } catch (e) {
    console.error('[RelatorioPeriodicoModal] Erro ao gerar relatório:', e)
    erro.value = 'Erro ao gerar o relatório. Por favor tente novamente.'
  } finally {
    loading.value  = false
    exportTarget.value = ''
  }
}

// ── Geração de Excel via SheetJS — totalmente estilizado ──
async function gerarExcel(data) {
  const XLSX = XLSXStyle

  // ── Paleta Biofund ────────────────────────────────────────────
  const COR = {
    verdeDark:  '1B4332',  // cabeçalhos principais
    verdeMid:   '2D6A4F',  // cabeçalhos secundários
    verdeLight: '52B788',  // destaques / separadores
    verdePale:  'D8F3DC',  // linhas alternadas
    verdeBg:    'F0FAF4',  // fundo de secção
    branco:     'FFFFFF',
    cinzaHead:  'F4F6F5',  // cabeçalho de tabela neutro
    cinzaAlt:   'FAFAFA',  // linha alternada neutra
    cinzaBorda: 'DDE8E1',
    textDark:   '1A1A1A',
    textGray:   '555B5A',
    amber:      'F4A52A',  // urgente
    vermelho:   'C53030',  // em atraso / GBV
    azul:       '2B6CB0',  // normal
  }

  // ── Helpers de estilo ────────────────────────────────────────
  // Borda fina em todos os lados
  const borda = (cor = COR.cinzaBorda) => ({
    top:    { style: 'thin', color: { rgb: cor } },
    bottom: { style: 'thin', color: { rgb: cor } },
    left:   { style: 'thin', color: { rgb: cor } },
    right:  { style: 'thin', color: { rgb: cor } },
  })

  // Célula de título principal (fundo verde escuro, texto branco, grande)
  const estTitulo = {
    font:      { bold: true, sz: 16, color: { rgb: COR.branco }, name: 'Calibri' },
    fill:      { fgColor: { rgb: COR.verdeDark } },
    alignment: { horizontal: 'left', vertical: 'center', wrapText: false },
  }

  // Célula de subtítulo (fundo verde mid, texto branco, médio)
  const estSubtitulo = {
    font:      { bold: true, sz: 11, color: { rgb: COR.branco }, name: 'Calibri' },
    fill:      { fgColor: { rgb: COR.verdeMid } },
    alignment: { horizontal: 'left', vertical: 'center' },
  }

  // Célula de metadado (fundo verde claro, texto dark)
  const estMeta = {
    font:      { sz: 10, color: { rgb: COR.textGray }, name: 'Calibri' },
    fill:      { fgColor: { rgb: COR.verdeBg } },
    alignment: { horizontal: 'left', vertical: 'center' },
  }

  // Cabeçalho de tabela (fundo verde escuro, texto branco, bold)
  const estCabecalho = (sz = 10) => ({
    font:      { bold: true, sz, color: { rgb: COR.branco }, name: 'Calibri' },
    fill:      { fgColor: { rgb: COR.verdeDark } },
    alignment: { horizontal: 'center', vertical: 'center', wrapText: true },
    border:    borda(COR.verdeLight),
  })

  // Cabeçalho de tabela secundário (verde mid)
  const estCabecalhoSec = {
    font:      { bold: true, sz: 10, color: { rgb: COR.branco }, name: 'Calibri' },
    fill:      { fgColor: { rgb: COR.verdeMid } },
    alignment: { horizontal: 'center', vertical: 'center' },
    border:    borda(COR.verdeLight),
  }

  // Linha normal (branco)
  const estLinha = (alinhamento = 'left') => ({
    font:      { sz: 10, color: { rgb: COR.textDark }, name: 'Calibri' },
    fill:      { fgColor: { rgb: COR.branco } },
    alignment: { horizontal: alinhamento, vertical: 'center', wrapText: true },
    border:    borda(),
  })

  // Linha alternada (verde palido)
  const estLinhaAlt = (alinhamento = 'left') => ({
    font:      { sz: 10, color: { rgb: COR.textDark }, name: 'Calibri' },
    fill:      { fgColor: { rgb: COR.verdePale } },
    alignment: { horizontal: alinhamento, vertical: 'center', wrapText: true },
    border:    borda(),
  })

  // Número (direita, bold)
  const estNumero = (alt = false) => ({
    font:      { bold: true, sz: 11, color: { rgb: COR.verdeDark }, name: 'Calibri' },
    fill:      { fgColor: { rgb: alt ? COR.verdePale : COR.branco } },
    alignment: { horizontal: 'center', vertical: 'center' },
    border:    borda(),
  })

  // KPI card (fundo verde pale, número grande)
  const estKpi = {
    font:      { bold: true, sz: 18, color: { rgb: COR.verdeDark }, name: 'Calibri' },
    fill:      { fgColor: { rgb: COR.verdePale } },
    alignment: { horizontal: 'center', vertical: 'center' },
    border:    borda(COR.verdeLight),
  }
  const estKpiLabel = {
    font:      { sz: 9, color: { rgb: COR.verdeMid }, name: 'Calibri' },
    fill:      { fgColor: { rgb: COR.verdeBg } },
    alignment: { horizontal: 'center', vertical: 'center' },
    border:    borda(COR.verdeLight),
  }

  // Secção separadora
  const estSeccao = {
    font:      { bold: true, sz: 11, color: { rgb: COR.branco }, name: 'Calibri' },
    fill:      { fgColor: { rgb: COR.verdeLight } },
    alignment: { horizontal: 'left', vertical: 'center' },
  }

  // Célula vazia com fundo branco (para separação visual)
  const estVazia = { fill: { fgColor: { rgb: COR.branco } } }

  // ── Helper: escrever célula num sheet ────────────────────────
  function cel(ws, col, row, value, style, numFmt) {
    const addr = XLSX.utils.encode_cell({ c: col, r: row })
    ws[addr] = { v: value, t: typeof value === 'number' ? 'n' : 's', s: style }
    if (numFmt) ws[addr].z = numFmt
  }

  // ── Helper: merge de células ─────────────────────────────────
  function merge(ws, c1, r1, c2, r2) {
    if (!ws['!merges']) ws['!merges'] = []
    ws['!merges'].push({ s: { c: c1, r: r1 }, e: { c: c2, r: r2 } })
  }

  // ── Helper: definir altura de linhas ─────────────────────────
  function alturaLinha(ws, row, hpx) {
    if (!ws['!rows']) ws['!rows'] = []
    ws['!rows'][row] = { hpx }
  }

  // ── Helper: aplicar tabela de dados estilizada ───────────────
  // headers: [{label, width}], rows: array de arrays, startRow, startCol, ws
  function tabelaEstilizada(ws, headers, rows, startRow, startCol = 0) {
    // Cabeçalho
    headers.forEach((h, ci) => {
      cel(ws, startCol + ci, startRow, h.label, estCabecalho())
    })
    alturaLinha(ws, startRow, 22)

    // Linhas de dados
    rows.forEach((row, ri) => {
      const isAlt = ri % 2 === 1
      alturaLinha(ws, startRow + 1 + ri, 18)
      row.forEach((val, ci) => {
        const isNum   = typeof val === 'number'
        const isRight = isNum || headers[ci]?.right
        const estBase = isNum
          ? estNumero(isAlt)
          : (isAlt ? estLinhaAlt(isRight ? 'right' : 'left') : estLinha(isRight ? 'right' : 'left'))
        cel(ws, startCol + ci, startRow + 1 + ri, val, estBase)
      })
    })

    // Larguras das colunas
    if (!ws['!cols']) ws['!cols'] = []
    headers.forEach((h, ci) => {
      ws['!cols'][startCol + ci] = { wch: h.width || 18 }
    })

    return startRow + 1 + rows.length
  }

  // ────────────────────────────────────────────────────────────
  // LABELS auxiliares
  // ────────────────────────────────────────────────────────────
  const statusLabels = {
    por_validar: 'Por Validar', por_resolver: 'Por Resolver',
    nao_validado: 'Não Validado', resolvendo: 'Resolvendo',
    resolvido: 'Resolvido', nao_resolvida: 'Não Resolvida',
  }
  const alertLabels = { normal: 'Normal', urgent: 'Urgente', gbv: 'GBV' }

  const wb = XLSX.utils.book_new()

  // ════════════════════════════════════════════════════════════
  // FOLHA 1 — RESUMO
  // ════════════════════════════════════════════════════════════
  const ws1 = {}
  let r = 0

  // Título principal (merge A1:F1)
  cel(ws1, 0, r, `BIOFUND MDR — ${data.meta.period_label}`, estTitulo)
  merge(ws1, 0, r, 5, r)
  alturaLinha(ws1, r, 36)
  r++

  // Metadados (merge A2:F2 e A3:F3)
  cel(ws1, 0, r, `Período: ${data.meta.date_from}  a  ${data.meta.date_to}`, estMeta)
  merge(ws1, 0, r, 5, r)
  alturaLinha(ws1, r, 18)
  r++

  cel(ws1, 0, r, `Gerado em: ${data.meta.generated_at}   |   Por: ${data.meta.generated_by}`, estMeta)
  merge(ws1, 0, r, 5, r)
  alturaLinha(ws1, r, 18)
  r++

  // Linha de espaço
  alturaLinha(ws1, r, 10)
  r++

  // ── KPIs (linha de cards) ────────────────────────────────────
  cel(ws1, 0, r, 'INDICADORES CHAVE', estSeccao)
  merge(ws1, 0, r, 5, r)
  alturaLinha(ws1, r, 20)
  r++

  const kpis = [
    { label: 'Total de Ocorrências',   valor: data.summary.total },
    { label: 'Resolvidas',             valor: data.summary.resolved },
    { label: 'Não Resolvidas',         valor: data.summary.unresolved },
    { label: 'Em Atraso',              valor: data.summary.overdue },
    { label: 'Taxa de Resolução',      valor: `${data.summary.resolution_rate}%` },
  ]

  // Linha com os valores grandes
  alturaLinha(ws1, r, 36)
  kpis.forEach((k, ci) => cel(ws1, ci, r, k.valor, estKpi))
  r++

  // Linha com as labels
  alturaLinha(ws1, r, 18)
  kpis.forEach((k, ci) => cel(ws1, ci, r, k.label, estKpiLabel))
  r++

  // Espaço
  alturaLinha(ws1, r, 14)
  r++

  // ── Tabela: Totais por Estado ────────────────────────────────
  cel(ws1, 0, r, 'TOTAIS POR ESTADO', estSeccao)
  merge(ws1, 0, r, 2, r)
  alturaLinha(ws1, r, 20)
  r++

  const estadoRows = Object.entries(data.summary.by_status).map(([k, v]) => [
    statusLabels[k] ?? k,
    v,
    data.summary.total > 0 ? `${((v / data.summary.total) * 100).toFixed(1)}%` : '0%',
  ])
  r = tabelaEstilizada(ws1,
    [{ label: 'Estado', width: 22 }, { label: 'Total', width: 12 }, { label: '%', width: 10, right: true }],
    estadoRows, r
  )

  // Espaço
  alturaLinha(ws1, r, 14)
  r++

  // ── Tabela: Por Nível de Alerta ──────────────────────────────
  cel(ws1, 0, r, 'POR NÍVEL DE ALERTA', estSeccao)
  merge(ws1, 0, r, 2, r)
  alturaLinha(ws1, r, 20)
  r++

  const alertaRows = Object.entries(data.summary.by_alert_level).map(([k, v]) => [
    alertLabels[k] ?? k,
    v,
    data.summary.total > 0 ? `${((v / data.summary.total) * 100).toFixed(1)}%` : '0%',
  ])
  r = tabelaEstilizada(ws1,
    [{ label: 'Nível de Alerta', width: 22 }, { label: 'Total', width: 12 }, { label: '%', width: 10, right: true }],
    alertaRows, r
  )

  ws1['!ref'] = XLSX.utils.encode_range({ s: { c: 0, r: 0 }, e: { c: 5, r: r + 2 } })
  ws1['!cols'] = [
    { wch: 26 }, { wch: 14 }, { wch: 12 }, { wch: 20 }, { wch: 20 }, { wch: 18 }
  ]
  XLSX.utils.book_append_sheet(wb, ws1, '📊 Resumo')

  // ════════════════════════════════════════════════════════════
  // FOLHA 2 — POR CATEGORIA
  // ════════════════════════════════════════════════════════════
  const ws2 = {}
  r = 0

  cel(ws2, 0, r, 'Ocorrências por Categoria', estTitulo)
  merge(ws2, 0, r, 2, r)
  alturaLinha(ws2, r, 32)
  r++
  cel(ws2, 0, r, `${data.meta.period_label}  ·  ${data.meta.date_from} a ${data.meta.date_to}`, estMeta)
  merge(ws2, 0, r, 2, r)
  alturaLinha(ws2, r, 18)
  r += 2

  const catRows = data.by_category.map((c, i) => [i + 1, c.name, c.total,
    data.summary.total > 0 ? `${((c.total / data.summary.total) * 100).toFixed(1)}%` : '0%'
  ])
  tabelaEstilizada(ws2,
    [{ label: '#', width: 6 }, { label: 'Categoria', width: 38 }, { label: 'Total', width: 12 }, { label: '% do Total', width: 14, right: true }],
    catRows, r
  )

  ws2['!ref'] = XLSX.utils.encode_range({ s: { c: 0, r: 0 }, e: { c: 3, r: r + catRows.length + 2 } })
  XLSX.utils.book_append_sheet(wb, ws2, '📁 Por Categoria')

  // ════════════════════════════════════════════════════════════
  // FOLHA 3 — POR PROVÍNCIA
  // ════════════════════════════════════════════════════════════
  const ws3 = {}
  r = 0

  cel(ws3, 0, r, 'Ocorrências por Província', estTitulo)
  merge(ws3, 0, r, 2, r)
  alturaLinha(ws3, r, 32)
  r++
  cel(ws3, 0, r, `${data.meta.period_label}  ·  ${data.meta.date_from} a ${data.meta.date_to}`, estMeta)
  merge(ws3, 0, r, 2, r)
  alturaLinha(ws3, r, 18)
  r += 2

  const provRows = data.by_province.map((p, i) => [i + 1, p.name, p.total,
    data.summary.total > 0 ? `${((p.total / data.summary.total) * 100).toFixed(1)}%` : '0%'
  ])
  tabelaEstilizada(ws3,
    [{ label: '#', width: 6 }, { label: 'Província', width: 30 }, { label: 'Total', width: 12 }, { label: '% do Total', width: 14, right: true }],
    provRows, r
  )

  ws3['!ref'] = XLSX.utils.encode_range({ s: { c: 0, r: 0 }, e: { c: 3, r: r + provRows.length + 2 } })
  XLSX.utils.book_append_sheet(wb, ws3, '🗺️ Por Província')

  // ════════════════════════════════════════════════════════════
  // FOLHA 4 — POR PROJECTO
  // ════════════════════════════════════════════════════════════
  const ws4 = {}
  r = 0

  cel(ws4, 0, r, 'Ocorrências por Projecto', estTitulo)
  merge(ws4, 0, r, 2, r)
  alturaLinha(ws4, r, 32)
  r++
  cel(ws4, 0, r, `${data.meta.period_label}  ·  ${data.meta.date_from} a ${data.meta.date_to}`, estMeta)
  merge(ws4, 0, r, 2, r)
  alturaLinha(ws4, r, 18)
  r += 2

  const projRows = data.by_project.map((p, i) => [i + 1, p.name, p.total,
    data.summary.total > 0 ? `${((p.total / data.summary.total) * 100).toFixed(1)}%` : '0%'
  ])
  tabelaEstilizada(ws4,
    [{ label: '#', width: 6 }, { label: 'Projecto', width: 42 }, { label: 'Total', width: 12 }, { label: '% do Total', width: 14, right: true }],
    projRows, r
  )

  ws4['!ref'] = XLSX.utils.encode_range({ s: { c: 0, r: 0 }, e: { c: 3, r: r + projRows.length + 2 } })
  XLSX.utils.book_append_sheet(wb, ws4, '🏗️ Por Projecto')

  // ════════════════════════════════════════════════════════════
  // FOLHA 5 — EVOLUÇÃO MENSAL
  // ════════════════════════════════════════════════════════════
  const ws5 = {}
  r = 0

  cel(ws5, 0, r, 'Evolução Mensal no Período', estTitulo)
  merge(ws5, 0, r, 3, r)
  alturaLinha(ws5, r, 32)
  r++
  cel(ws5, 0, r, `${data.meta.period_label}  ·  ${data.meta.date_from} a ${data.meta.date_to}`, estMeta)
  merge(ws5, 0, r, 3, r)
  alturaLinha(ws5, r, 18)
  r += 2

  const mesRows = data.by_month.map(m => [
    m.label,
    m.total,
    m.resolved,
    m.total > 0 ? `${((m.resolved / m.total) * 100).toFixed(1)}%` : '0%',
  ])
  tabelaEstilizada(ws5,
    [
      { label: 'Mês', width: 14 },
      { label: 'Submetidas', width: 16 },
      { label: 'Resolvidas', width: 16 },
      { label: 'Taxa Resolução', width: 18, right: true },
    ],
    mesRows, r
  )

  ws5['!ref'] = XLSX.utils.encode_range({ s: { c: 0, r: 0 }, e: { c: 3, r: r + mesRows.length + 2 } })
  XLSX.utils.book_append_sheet(wb, ws5, '📅 Evolução Mensal')

  // ════════════════════════════════════════════════════════════
  // FOLHA 6 — DEMOGRÁFICOS
  // ════════════════════════════════════════════════════════════
  const ws6 = {}
  r = 0

  cel(ws6, 0, r, 'Dados Demográficos', estTitulo)
  merge(ws6, 0, r, 2, r)
  alturaLinha(ws6, r, 32)
  r++
  cel(ws6, 0, r, `${data.meta.period_label}  ·  ${data.meta.date_from} a ${data.meta.date_to}`, estMeta)
  merge(ws6, 0, r, 2, r)
  alturaLinha(ws6, r, 18)
  r += 2

  // Sub-secção Género
  cel(ws6, 0, r, 'DISTRIBUIÇÃO POR GÉNERO', estSeccao)
  merge(ws6, 0, r, 2, r)
  alturaLinha(ws6, r, 20)
  r++

  const generoRows = Object.entries(data.by_gender).map(([k, v]) => [
    k, v,
    data.summary.total > 0 ? `${((v / data.summary.total) * 100).toFixed(1)}%` : '0%',
  ])
  r = tabelaEstilizada(ws6,
    [{ label: 'Género', width: 22 }, { label: 'Total', width: 12 }, { label: '%', width: 10, right: true }],
    generoRows, r
  )

  // Espaço
  alturaLinha(ws6, r, 14)
  r++

  // Sub-secção Faixa Etária
  cel(ws6, 0, r, 'DISTRIBUIÇÃO POR FAIXA ETÁRIA', estSeccao)
  merge(ws6, 0, r, 2, r)
  alturaLinha(ws6, r, 20)
  r++

  const idadeRows = data.by_age_range.map(a => [
    a.label, a.total,
    data.summary.total > 0 ? `${((a.total / data.summary.total) * 100).toFixed(1)}%` : '0%',
  ])
  r = tabelaEstilizada(ws6,
    [{ label: 'Faixa Etária', width: 22 }, { label: 'Total', width: 12 }, { label: '%', width: 10, right: true }],
    idadeRows, r
  )

  ws6['!ref'] = XLSX.utils.encode_range({ s: { c: 0, r: 0 }, e: { c: 2, r: r + 2 } })
  XLSX.utils.book_append_sheet(wb, ws6, '👥 Demográficos')

  // ════════════════════════════════════════════════════════════
  // FOLHA 7 — LISTAGEM COMPLETA
  // ════════════════════════════════════════════════════════════
  const ws7 = {}
  r = 0

  cel(ws7, 0, r, `Listagem Completa de Ocorrências — ${data.occurrences.length} registos`, estTitulo)
  merge(ws7, 0, r, 10, r)
  alturaLinha(ws7, r, 32)
  r++
  cel(ws7, 0, r, `${data.meta.period_label}  ·  ${data.meta.date_from} a ${data.meta.date_to}  ·  Gerado em: ${data.meta.generated_at}`, estMeta)
  merge(ws7, 0, r, 10, r)
  alturaLinha(ws7, r, 18)
  r += 2

  const headersLista = [
    { label: '#',             width: 6  },
    { label: 'Código',        width: 20 },
    { label: 'Assunto',       width: 45 },
    { label: 'Estado',        width: 18 },
    { label: 'Projecto',      width: 30 },
    { label: 'Província',     width: 20 },
    { label: 'Categoria',     width: 24 },
    { label: 'Tipo',          width: 22 },
    { label: 'Nível Alerta',  width: 16 },
    { label: 'Atribuído a',   width: 24 },
    { label: 'Submissão',     width: 14 },
  ]

  // Cabeçalhos da listagem
  headersLista.forEach((h, ci) => {
    cel(ws7, ci, r, h.label, estCabecalho(9))
  })
  alturaLinha(ws7, r, 24)
  r++

  // Função de cor por estado
  const corEstado = (estado) => {
    if (estado === 'Resolvido')        return { rgb: '276749' }
    if (estado === 'Não Validado')     return { rgb: COR.vermelho }
    if (estado === 'Não Resolvida')    return { rgb: '7B341E' }
    if (estado === 'Resolvendo')       return { rgb: '744210' }
    if (estado === 'Por Resolver')     return { rgb: '744210' }
    return { rgb: COR.textDark }
  }

  const corAlerta = (nivel) => {
    if (nivel === 'Urgente') return { rgb: COR.amber.replace('#','') }
    if (nivel === 'GBV')     return { rgb: COR.vermelho }
    return { rgb: COR.azul }
  }

  // Linhas de dados com cor especial no Estado e Nível Alerta
  data.occurrences.forEach((o, ri) => {
    const isAlt = ri % 2 === 1
    const bgFill = { fgColor: { rgb: isAlt ? COR.verdePale : COR.branco } }
    const estBase = (al = 'left') => ({
      font:      { sz: 9, color: { rgb: COR.textDark }, name: 'Calibri' },
      fill:      bgFill,
      alignment: { horizontal: al, vertical: 'center', wrapText: false },
      border:    borda(),
    })

    const rowData = [ri + 1, o.tracking_code, o.subject, o.status, o.project,
      o.province, o.category, o.type, o.alert_level, o.assigned_to, o.submitted_at]

    rowData.forEach((val, ci) => {
      let est = estBase(ci === 0 ? 'center' : ci >= 3 && ci <= 5 ? 'left' : 'left')

      // Colorir coluna Estado (índice 3)
      if (ci === 3) {
        est = { ...est, font: { ...est.font, bold: true, color: corEstado(val) } }
      }
      // Colorir coluna Nível Alerta (índice 8)
      if (ci === 8) {
        est = { ...est, font: { ...est.font, bold: true, color: corAlerta(val) } }
      }
      // Índice (negrito, centrado)
      if (ci === 0) {
        est = { ...est, font: { ...est.font, bold: true, color: { rgb: COR.verdeMid } } }
      }

      cel(ws7, ci, r, val, est)
    })

    alturaLinha(ws7, r, 16)
    r++
  })

  ws7['!ref']  = XLSX.utils.encode_range({ s: { c: 0, r: 0 }, e: { c: 10, r: r } })
  ws7['!cols'] = headersLista.map(h => ({ wch: h.width }))
  XLSX.utils.book_append_sheet(wb, ws7, '📋 Ocorrências')

  // ── Escrever ficheiro ────────────────────────────────────────
  const nomeFicheiro = `Relatorio_MDR_${data.meta.period}_${data.meta.year}.xlsx`
  XLSX.writeFile(wb, nomeFicheiro)
}

// ── Geração de PDF via jsPDF + autotable (CDN) ───────────────
async function gerarPdf(data) {
  const { jsPDF } = await importarJsPDF()
  const autoTable  = await importarAutoTable()

  const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' })
  const pW  = doc.internal.pageSize.getWidth()

  // Cores Biofund
  const verde      = [27, 67, 50]   // #1B4332
  const verdeLight = [82, 183, 136] // #52B788
  const verdePale  = [216, 243, 220] // #D8F3DC
  const cinza      = [88, 95, 91]
  const cinzaLight = [245, 248, 246]

  // ── Capa / Cabeçalho ────────────────────────────────────────
  doc.setFillColor(...verde)
  doc.rect(0, 0, pW, 38, 'F')

  doc.setTextColor(255, 255, 255)
  doc.setFont('helvetica', 'bold')
  doc.setFontSize(20)
  doc.text('BIOFUND', 14, 16)

  doc.setFont('helvetica', 'normal')
  doc.setFontSize(10)
  doc.text('Mecanismo de Denúncia e Reclamação', 14, 23)

  doc.setFont('helvetica', 'bold')
  doc.setFontSize(13)
  doc.text(`Relatório ${data.meta.period_label}`, 14, 33)

  doc.setTextColor(...cinza)
  doc.setFont('helvetica', 'normal')
  doc.setFontSize(8)
  doc.text(`Período: ${data.meta.date_from} a ${data.meta.date_to}   |   Gerado em: ${data.meta.generated_at} por ${data.meta.generated_by}`, 14, 43)

  // ── KPI Cards ───────────────────────────────────────────────
  const kpis = [
    { label: 'Total', valor: data.summary.total,           cor: verde },
    { label: 'Resolvidas', valor: data.summary.resolved,       cor: [45, 106, 79] },
    { label: 'Não Resolvidas', valor: data.summary.unresolved,    cor: [200, 100, 40] },
    { label: 'Em Atraso', valor: data.summary.overdue,        cor: [180, 40, 40] },
    { label: 'Taxa Resolução', valor: `${data.summary.resolution_rate}%`, cor: verdeLight },
  ]

  const cardW   = (pW - 28 - (kpis.length - 1) * 4) / kpis.length
  const cardY   = 50
  const cardH   = 20

  kpis.forEach((kpi, i) => {
    const x = 14 + i * (cardW + 4)
    doc.setFillColor(...kpi.cor)
    doc.roundedRect(x, cardY, cardW, cardH, 2, 2, 'F')
    doc.setTextColor(255, 255, 255)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(14)
    doc.text(String(kpi.valor), x + cardW / 2, cardY + 10, { align: 'center' })
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(7)
    doc.text(kpi.label, x + cardW / 2, cardY + 16, { align: 'center' })
  })

  let startY = cardY + cardH + 10

  // ── Secção helper ───────────────────────────────────────────
  function secaoTitulo(titulo, y) {
    doc.setFillColor(...verdePale)
    doc.rect(14, y, pW - 28, 7, 'F')
    doc.setTextColor(...verde)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.text(titulo, 16, y + 5)
    return y + 9
  }

  function novaSecao(doc, titulo) {
    doc.addPage()
    doc.setFillColor(...verde)
    doc.rect(0, 0, pW, 12, 'F')
    doc.setTextColor(255, 255, 255)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.text(`Relatório ${data.meta.period_label} · ${titulo}`, 14, 8)
    doc.setTextColor(...cinza)
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(7)
    doc.text(`Período: ${data.meta.date_from} a ${data.meta.date_to}`, pW - 14, 8, { align: 'right' })
    return 18
  }

  const tableDefaults = {
    styles:       { fontSize: 8, cellPadding: 2.5, textColor: [30, 30, 30], lineColor: [220, 230, 224], lineWidth: 0.1 },
    headStyles:   { fillColor: verde, textColor: [255, 255, 255], fontStyle: 'bold', fontSize: 8 },
    alternateRowStyles: { fillColor: cinzaLight },
  }

  // ── Tabela: Por Estado ──────────────────────────────────────
  const statusLabels = {
    por_validar: 'Por Validar', por_resolver: 'Por Resolver',
    nao_validado: 'Não Validado', resolvendo: 'Resolvendo',
    resolvido: 'Resolvido', nao_resolvida: 'Não Resolvida',
  }
  startY = secaoTitulo('Totais por Estado e Nível de Alerta', startY)

  const estadoBody = Object.entries(data.summary.by_status).map(([k, v]) => [
    statusLabels[k] ?? k, v,
    data.summary.total > 0 ? `${((v / data.summary.total) * 100).toFixed(1)}%` : '0%'
  ])

  autoTable(doc, {
    ...tableDefaults,
    startY,
    margin: { left: 14, right: pW / 2 + 2 },
    head: [['Estado', 'Total', '%']],
    body: estadoBody,
    columnStyles: { 1: { halign: 'right' }, 2: { halign: 'right' } },
  })

  // Nível de alerta — ao lado
  const alertaBody = Object.entries(data.summary.by_alert_level).map(([k, v]) => [
    k === 'normal' ? 'Normal' : k === 'urgent' ? 'Urgente' : 'GBV',
    v,
    data.summary.total > 0 ? `${((v / data.summary.total) * 100).toFixed(1)}%` : '0%'
  ])

  autoTable(doc, {
    ...tableDefaults,
    startY,
    margin: { left: pW / 2 + 2, right: 14 },
    head: [['Nível de Alerta', 'Total', '%']],
    body: alertaBody,
    columnStyles: { 1: { halign: 'right' }, 2: { halign: 'right' } },
  })

  startY = doc.lastAutoTable.finalY + 8

  // ── Tabela: Por Categoria e Projecto ─────────────────────────
  if (startY > 160) { startY = novaSecao(doc, 'Categoria e Projecto') }
  else { startY = secaoTitulo('Por Categoria e Por Projecto', startY) }

  autoTable(doc, {
    ...tableDefaults,
    startY,
    margin: { left: 14, right: pW / 2 + 2 },
    head: [['Categoria', 'Total']],
    body: data.by_category.map(r => [r.name, r.total]),
    columnStyles: { 1: { halign: 'right', cellWidth: 20 } },
  })

  autoTable(doc, {
    ...tableDefaults,
    startY,
    margin: { left: pW / 2 + 2, right: 14 },
    head: [['Projecto', 'Total']],
    body: data.by_project.map(r => [r.name, r.total]),
    columnStyles: { 1: { halign: 'right', cellWidth: 20 } },
  })

  startY = doc.lastAutoTable.finalY + 8

  // ── Tabela: Por Província e Tipo ─────────────────────────────
  if (startY > 160) { startY = novaSecao(doc, 'Província e Tipo') }
  else { startY = secaoTitulo('Por Província e Por Tipo de Ocorrência', startY) }

  autoTable(doc, {
    ...tableDefaults,
    startY,
    margin: { left: 14, right: pW / 2 + 2 },
    head: [['Província', 'Total']],
    body: data.by_province.map(r => [r.name, r.total]),
    columnStyles: { 1: { halign: 'right', cellWidth: 20 } },
  })

  autoTable(doc, {
    ...tableDefaults,
    startY,
    margin: { left: pW / 2 + 2, right: 14 },
    head: [['Tipo', 'Nível', 'Total']],
    body: data.by_type.map(r => [r.name, r.alert_level, r.total]),
    columnStyles: { 2: { halign: 'right', cellWidth: 18 } },
  })

  // ── Tabela: Evolução Mensal ──────────────────────────────────
  startY = novaSecao(doc, 'Evolução Mensal')
  startY = secaoTitulo('Evolução Mensal de Ocorrências no Período', startY)

  autoTable(doc, {
    ...tableDefaults,
    startY,
    margin: { left: 14, right: 14 },
    head: [['Mês', 'Submetidas', 'Resolvidas', 'Taxa Resolução']],
    body: data.by_month.map(r => [
      r.label,
      r.total,
      r.resolved,
      r.total > 0 ? `${((r.resolved / r.total) * 100).toFixed(1)}%` : '0%'
    ]),
    columnStyles: {
      1: { halign: 'right' }, 2: { halign: 'right' }, 3: { halign: 'right' }
    },
  })

  // ── Tabela: Dados Demográficos ───────────────────────────────
  startY = doc.lastAutoTable.finalY + 10
  if (startY > 160) { startY = novaSecao(doc, 'Demográficos') }
  else { startY = secaoTitulo('Dados Demográficos', startY) }

  autoTable(doc, {
    ...tableDefaults,
    startY,
    margin: { left: 14, right: pW / 2 + 2 },
    head: [['Género', 'Total']],
    body: Object.entries(data.by_gender).map(([k, v]) => [k, v]),
    columnStyles: { 1: { halign: 'right', cellWidth: 20 } },
  })

  autoTable(doc, {
    ...tableDefaults,
    startY,
    margin: { left: pW / 2 + 2, right: 14 },
    head: [['Faixa Etária', 'Total']],
    body: data.by_age_range.map(r => [r.label, r.total]),
    columnStyles: { 1: { halign: 'right', cellWidth: 20 } },
  })

  // ── Listagem completa de ocorrências ────────────────────────
  novaSecao(doc, 'Listagem de Ocorrências')
  secaoTitulo(`Listagem completa — ${data.occurrences.length} ocorrências`, 18)

  autoTable(doc, {
    ...tableDefaults,
    startY: 27,
    margin: { left: 14, right: 14 },
    head: [['Código', 'Assunto', 'Estado', 'Projecto', 'Província', 'Categoria', 'Nível', 'Atribuído a', 'Submissão']],
    body: data.occurrences.map(o => [
      o.tracking_code, o.subject?.substring(0, 45), o.status,
      o.project?.substring(0, 22), o.province, o.category?.substring(0, 18),
      o.alert_level, o.assigned_to?.substring(0, 20), o.submitted_at,
    ]),
    columnStyles: {
      0: { cellWidth: 24 },
      1: { cellWidth: 52 },
      2: { cellWidth: 22 },
      3: { cellWidth: 28 },
      4: { cellWidth: 20 },
      5: { cellWidth: 24 },
      6: { cellWidth: 16 },
      7: { cellWidth: 26 },
      8: { cellWidth: 18 },
    },
  })

  // ── Rodapé em todas as páginas ──────────────────────────────
  const totalPaginas = doc.internal.getNumberOfPages()
  for (let i = 1; i <= totalPaginas; i++) {
    doc.setPage(i)
    doc.setFillColor(...verdePale)
    doc.rect(0, doc.internal.pageSize.getHeight() - 8, pW, 8, 'F')
    doc.setTextColor(...cinza)
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(7)
    doc.text('Biofund MDR — Documento confidencial', 14, doc.internal.pageSize.getHeight() - 3)
    doc.text(`Página ${i} de ${totalPaginas}`, pW - 14, doc.internal.pageSize.getHeight() - 3, { align: 'right' })
  }

  doc.save(`Relatorio_MDR_${data.meta.period}_${data.meta.year}.pdf`)
}


function importarJsPDF() {
  return new Promise((resolve, reject) => {
    if (window.jspdf) { resolve(window.jspdf); return }
    const s = document.createElement('script')
    s.src = 'https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js'
    s.onload  = () => resolve(window.jspdf)
    s.onerror = reject
    document.head.appendChild(s)
  })
}

function importarAutoTable() {
  return new Promise((resolve, reject) => {
    if (window.jspdfAutoTable) { resolve(window.jspdfAutoTable.default ?? window.jspdfAutoTable); return }
    const s = document.createElement('script')
    s.src = 'https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js'
    s.onload  = () => {
      // autotable regista-se como plugin no prototype — retorna a função directamente
      resolve((doc, opts) => doc.autoTable(opts))
    }
    s.onerror = reject
    document.head.appendChild(s)
  })
}
</script>

<style scoped>
/* ── Backdrop ────────────────────────────────────────── */
.rp-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(10, 28, 20, 0.45);
  z-index: 200;
  backdrop-filter: blur(2px);
}

/* ── Modal ───────────────────────────────────────────── */
.rp-modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 201;
  width: 660px;
  max-width: calc(100vw - 32px);
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 20px 60px rgba(10, 28, 20, 0.18), 0 4px 16px rgba(10, 28, 20, 0.1);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* ── Header ─────────────────────────────────────────── */
.rp-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 22px 16px;
  border-bottom: 1px solid var(--border, #DDE8E1);
}
.rp-header-left {
  display: flex;
  align-items: center;
  gap: 12px;
}
.rp-icon {
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
.rp-title {
  font-size: 15px;
  font-weight: 700;
  color: var(--text-dark, #1A1A1A);
  margin: 0;
}
.rp-subtitle {
  font-size: 12px;
  color: var(--text-light, #888E8C);
  margin: 2px 0 0;
}
.rp-close {
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
.rp-close:hover { background: var(--green-bg, #F0FAF4); }

/* ── Body ────────────────────────────────────────────── */
.rp-body {
  padding: 20px 22px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.rp-row {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
}
.rp-field {
  flex: 1;
  min-width: 160px;
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.rp-field--sm {
  flex: 0 0 110px;
  min-width: 110px;
}
.rp-label {
  font-size: 11.5px;
  font-weight: 600;
  color: var(--text-gray, #555B5A);
  text-transform: uppercase;
  letter-spacing: 0.04em;
  display: flex;
  align-items: center;
  gap: 6px;
}
.rp-optional {
  font-weight: 400;
  font-size: 10.5px;
  color: var(--text-light, #888E8C);
  text-transform: none;
  letter-spacing: 0;
}

/* Toggle group */
.rp-toggle-group {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}
.rp-toggle {
  padding: 5px 11px;
  border: 1.5px solid var(--border, #DDE8E1);
  border-radius: 7px;
  background: #fff;
  color: var(--text-gray, #555B5A);
  font-size: 12px;
  font-family: inherit;
  cursor: pointer;
  transition: all 0.15s;
  white-space: nowrap;
}
.rp-toggle:hover {
  border-color: var(--green-light, #52B788);
  color: var(--green-mid, #2D6A4F);
  background: var(--green-bg, #F0FAF4);
}
.rp-toggle.active {
  border-color: var(--green-mid, #2D6A4F);
  background: var(--green-mid, #2D6A4F);
  color: #fff;
  font-weight: 600;
}

/* Select */
.rp-select {
  width: 100%;
  height: 34px;
  padding: 0 10px;
  border: 1.5px solid var(--border, #DDE8E1);
  border-radius: 7px;
  background: #fff;
  color: var(--text-dark, #1A1A1A);
  font-size: 13px;
  font-family: inherit;
  cursor: pointer;
  transition: border-color 0.15s;
}
.rp-select:focus {
  outline: none;
  border-color: var(--green-light, #52B788);
}

/* Preview do período */
.rp-preview {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 14px;
  background: var(--green-bg, #F0FAF4);
  border: 1px solid var(--green-pale, #D8F3DC);
  border-radius: 8px;
  color: var(--green-mid, #2D6A4F);
  font-size: 12.5px;
}
.rp-preview svg { flex-shrink: 0; }
.rp-preview strong { font-weight: 700; }

/* Erro */
.rp-error {
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

/* ── Footer ─────────────────────────────────────────── */
.rp-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 22px 18px;
  border-top: 1px solid var(--border, #DDE8E1);
  background: var(--offwhite, #F7F9F8);
}
.rp-export-group {
  display: flex;
  gap: 8px;
}
.rp-btn {
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
.rp-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
.rp-btn--ghost {
  background: none;
  color: var(--text-gray, #555B5A);
  border: 1.5px solid var(--border, #DDE8E1);
}
.rp-btn--ghost:hover:not(:disabled) {
  background: var(--green-bg, #F0FAF4);
  border-color: var(--green-light, #52B788);
}
.rp-btn--excel {
  background: #1D6F42;
  color: #fff;
}
.rp-btn--excel:hover:not(:disabled) { background: #155934; }

.rp-btn--pdf {
  background: var(--green-dark, #1B4332);
  color: #fff;
}
.rp-btn--pdf:hover:not(:disabled) {
  background: linear-gradient(135deg, #52B788, #1B4332);
}

/* Spinner */
.rp-spin {
  animation: spin 0.9s linear infinite;
  flex-shrink: 0;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Transições ─────────────────────────────────────── */
.fade-enter-active, .fade-leave-active   { transition: opacity 0.2s; }
.fade-enter-from,  .fade-leave-to        { opacity: 0; }
.slide-up-enter-active, .slide-up-leave-active { transition: all 0.22s ease; }
.slide-up-enter-from { opacity: 0; transform: translate(-50%, -48%) scale(0.97); }
.slide-up-leave-to   { opacity: 0; transform: translate(-50%, -48%) scale(0.97); }
</style>