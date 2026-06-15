import api from '../client'

// Form data (categories, provinces, types) doesn't change during a session.
// Cache it in memory so every page navigation doesn't hit the server again.
let _formDataCache = null
let _formDataCachedAt = 0
const FORM_DATA_TTL = 60 * 60 * 1000  // 1 hour

// Invalidates the in-memory form-data cache so the next getFormData() call
// fetches fresh projects/categories - used after creating/editing/deleting
// projects or categories so submission forms update without a page reload.
function invalidateFormDataCache() {
    _formDataCache = null
    _formDataCachedAt = 0
}

export const InternalService = {

    /**
     * Submete uma nova ocorrência interna (utilizador autenticado).
     * @param {FormData} formData
     * @returns {{ message, tracking_code, occurrence_id, attachments }}
     */
    async createOccurrence(formData) {
        const { data } = await api.post('/occurrences', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        return data
    },

    /**
     * Lista ocorrências (visibilidade filtrada por role no backend).
     * @param {Object} params - filtros opcionais
     * Suporta: status, province_id, project_id, category_id,
     *          date_from, date_to, search, only_mine, origin, per_page, page
     */
    async getOccurrences(params = {}) {
        const { data } = await api.get('/occurrences', { params })
        return data
    },

    /**
     * Detalhe de uma ocorrência.
     */
    async getOccurrence(id) {
        const { data } = await api.get(`/occurrences/${id}`)
        // Laravel envolve recursos individuais em { data: {...} }
        return data.data ?? data
    },

    /**
     * Actualiza a classificação de uma ocorrência (projecto, categoria e/ou subcategoria).
     * @param {number} id
     * @param {{ project_id?, category_id?, subcategory_id? }} payload
     */
    async updateClassification(id, payload) {
        const { data } = await api.patch(`/occurrences/${id}/classification`, payload)
        return data
    },

    /**
     * Altera o estado de uma ocorrência.
     */
    async updateStatus(id, payload) {
        const { data } = await api.patch(`/occurrences/${id}/status`, payload)
        return data
    },

    /**
     * Adiciona um comentário sem alterar o estado (disponível em todos os estados).
     */
    async addComment(id, payload) {
        const { data } = await api.post(`/occurrences/${id}/comment`, payload)
        return data
    },

    /**
     * Atribui uma ocorrência a um gestor.
     */
    async assign(id, userId) {
        const { data } = await api.patch(`/occurrences/${id}/assign`, { user_id: userId })
        return data
    },

    /**
     * Estatísticas do dashboard para KPIs, gráficos e tabela recente.
     * @returns {{ totals, overdue, by_province, by_category, by_month, by_month_resolved, recent }}
     */
    async getDashboardStats(params = {}) {
        const { data } = await api.get('/admin/statistics/dashboard', { params })
        return data
    },

    // ─── Perfil ──────────────────────────────────────────────────

    async updateProfile(formData) {
        const { data } = await api.post('/auth/profile', formData)
        return data  // { message, user }
    },

    // ─── Utilizadores ────────────────────────────────────────────

    async getUsers(params = {}) {
        const { data } = await api.get('/admin/users', { params })
        return data   // paginated: { data: [...], meta: {...} }
    },

    async createUser(payload) {
        const { data } = await api.post('/admin/users', payload)
        return data   // { message, user }
    },

    async updateUser(id, payload) {
        const { data } = await api.put(`/admin/users/${id}`, payload)
        return data
    },

    async toggleUserStatus(id) {
        const { data } = await api.patch(`/admin/users/${id}/toggle-status`)
        return data   // { message, is_active }
    },

    async deleteUser(id) {
        const { data } = await api.delete(`/admin/users/${id}`)
        return data
    },

    // ─── Projectos ───────────────────────────────────────────────

    async getProjects() {
        const { data } = await api.get('/admin/projects')
        return data   // { projects: [...] }
    },

    async createProject(payload) {
        const { data } = await api.post('/admin/projects', payload)
        invalidateFormDataCache()
        return data
    },

    async updateProject(id, payload) {
        const { data } = await api.put(`/admin/projects/${id}`, payload)
        invalidateFormDataCache()
        return data
    },

    async deleteProject(id) {
        const { data } = await api.delete(`/admin/projects/${id}`)
        invalidateFormDataCache()
        return data
    },

    // ─── Categorias ──────────────────────────────────────────────

    async getCategories() {
        const { data } = await api.get('/admin/categories')
        return data   // { categories: [...] }
    },

    async createCategory(payload) {
        const { data } = await api.post('/admin/categories', payload)
        invalidateFormDataCache()
        return data   // { message, category }
    },

    async updateCategory(id, payload) {
        const { data } = await api.put(`/admin/categories/${id}`, payload)
        invalidateFormDataCache()
        return data
    },

    async deleteCategory(id) {
        const { data } = await api.delete(`/admin/categories/${id}`)
        invalidateFormDataCache()
        return data
    },

    async getSubcategories(categoryId) {
        const { data } = await api.get(`/admin/categories/${categoryId}/subcategories`)
        return data   // { subcategories: [...] }
    },

    async createSubcategory(categoryId, payload) {
        const { data } = await api.post(`/admin/categories/${categoryId}/subcategories`, payload)
        return data
    },

    async updateSubcategory(subcategoryId, payload) {
        const { data } = await api.put(`/admin/subcategories/${subcategoryId}`, payload)
        return data
    },

    // ─── Anexos ──────────────────────────────────────────────────

    /**
     * Descarrega um anexo autenticado como blob URL (para exibir em <img>).
     * @param {number} occurrenceId
     * @param {number} attachmentId
     * @returns {string} blob: URL
     */
    async getAttachmentBlobUrl(occurrenceId, attachmentId) {
        const response = await api.get(`/occurrences/${occurrenceId}/attachments/${attachmentId}`, {
            responseType: 'blob',
        })
        return URL.createObjectURL(response.data)
    },

    // ─── Dados de referência ─────────────────────────────────────

    /**
     * Dados de referência para o formulário interno.
     * Cached in memory for 1 hour - categories/provinces change rarely.
     */
    async getFormData() {
        const now = Date.now()
        if (_formDataCache && (now - _formDataCachedAt) < FORM_DATA_TTL) {
            return _formDataCache
        }
        const { data } = await api.get('/public/form-data')
        _formDataCache = data
        _formDataCachedAt = now
        return data
    },

    /**
     * Distritos de uma província.
     */
    async getDistrictsByProvince(provinceId) {
        const { data } = await api.get(`/public/provinces/${provinceId}/districts`)
        return data
    },

}