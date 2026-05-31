import api from "../client";
import { ENDPOINTS } from "../endpoints";

export const AuthService = {
    /**
     * Autentica o utilizador e devolve { token, user, message }
     */
    async login(email, password) {
        const { data } = await api.post(ENDPOINTS.LOGIN, { email, password });
        return data;
    },

    /**
     * Termina a sessão - revoga o token no servidor
     */
    async logout() {
        const { data } = await api.post(ENDPOINTS.LOGOUT);
        return data;
    },

    /**
     * Devolve os dados do utilizador autenticado
     */
    async me() {
        const { data } = await api.get(ENDPOINTS.ME);
        return data;
    },

    /**
     * Envia o email de recuperação de palavra-passe
     */
    async forgotPassword(email) {
        const { data } = await api.post(ENDPOINTS.FORGOT_PASSWORD, { email });
        return data;
    },

    /**
     * Redefine a palavra-passe com o token recebido por email
     */
    async resetPassword(payload) {
        const { data } = await api.post(ENDPOINTS.RESET_PASSWORD, payload);
        return data;
    },

    /**
     * Altera a palavra-passe do utilizador autenticado
     */
    async changePassword(payload) {
        const { data } = await api.post(ENDPOINTS.CHANGE_PASSWORD, payload);
        return data;
    },
};
