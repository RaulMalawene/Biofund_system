import api from '../client'
import { ENDPOINTS } from '../endpoints'

export const PublicService = {

    /** Dados de referência para preencher os selects do formulário */
    async getFormData() {
        const { data } = await api.get(ENDPOINTS.FORM_DATA)
        return data
    },

    /** Distritos de uma provincia */
    async getDistrictsByProvince(provinceId) {
        const { data } = await api.get(ENDPOINTS.DISTRICTS_BY_PROVINCE(provinceId))
        return data   // { districts: [...] }
    },

    /**
     * Submete uma nova ocorrência pública.
     * @param {FormData} formData - multipart/form-data com campos + attachments[]
     * @returns {{ message, tracking_code, due_date, attachments_count, info }}
     */
    async createOccurrence(formData) {
        const { data } = await api.post(ENDPOINTS.CREATE_OCCURRENCE, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        return data
    },

    /** Consultar estado de uma ocorrência pelo tracking code */
    async trackOccurrence(code) {
        const { data } = await api.get(ENDPOINTS.TRACK_OCCURRENCE(code))
        return data
    },

    /**
     * Descarrega um anexo público como blob URL (para exibir em <img> ou fazer download).
     * @param {string} trackingCode
     * @param {number} attachmentId
     * @returns {string} blob: URL
     */
    async getAttachmentBlobUrl(trackingCode, attachmentId) {
        const response = await api.get(ENDPOINTS.ATTACHMENT_DOWNLOAD(trackingCode, attachmentId), {
            responseType: 'blob',
        })
        return URL.createObjectURL(response.data)
    },
}