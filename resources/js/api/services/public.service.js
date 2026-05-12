import api from "../client";
import { ENDPOINTS } from "../endpoints";

export const PublicService = {
    async getFormData() {
        const response = await api.get(ENDPOINTS.FORM_DATA);
        return response.data;
    },

    async createOccurrence(data) {
        const response = await api.post(ENDPOINTS.CREATE_OCCURENCE, data);
        return response.data;
    },

    async getDistrictsByProvince(provinceId) {
        const response = await api.get(
            ENDPOINTS.DISTRICTS_BY_PROVINCE(provinceId),
        );
        return response.data;
    },
};
