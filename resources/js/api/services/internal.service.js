import api from '../client'

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
     * Atribui uma ocorrência a um gestor.
     */
    async assign(id, userId) {
        const { data } = await api.patch(`/occurrences/${id}/assign`, { user_id: userId })
        return data
    },

    /**
     * Dados de referência para o formulário interno.
     * Usa o mesmo endpoint público (não requer auth).
     */
    async getFormData() {
        const { data } = await api.get('/public/form-data')
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