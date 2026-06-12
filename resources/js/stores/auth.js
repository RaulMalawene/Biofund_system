import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { AuthService } from "../api/services/auth.service";

const TOKEN_KEY     = "mdr_token";
const USER_KEY      = "mdr_user";
const VALIDATED_KEY = "mdr_validated_at";

export const useAuthStore = defineStore("auth", () => {
    // ── Estado ────────────────────────────────────────────────
    const token = ref(localStorage.getItem(TOKEN_KEY) ?? null);
    const sessionId = ref(Date.now());
    const user = ref(
        (() => {
            try {
                return JSON.parse(localStorage.getItem(USER_KEY) ?? "null");
            } catch {
                return null;
            }
        })(),
    );

    // ── Getters ───────────────────────────────────────────────
    const isAuthenticated = computed(() => !!token.value && !!user.value);
    const isAdmin = computed(() => user.value?.role === "admin");
    const isGestor = computed(() => user.value?.role === "gestor");
    const isFuncionario = computed(() => user.value?.role === "funcionario");
    const isObservador = computed(() => user.value?.role === "observador");
    const userInitials = computed(() => {
        if (!user.value?.name) return "?";
        return user.value.name
            .split(" ")
            .slice(0, 2)
            .map((n) => n[0].toUpperCase())
            .join("");
    });
    const dashboardRoute = computed(() => {
        if (user.value?.role === "admin") return "/admin/dashboard";
        if (user.value?.role === "gestor") return "/gestor/dashboard";
        if (user.value?.role === "funcionario") return "/funcionario/reclamacao";
        if (user.value?.role === "observador") return "/observador/dashboard";
        return "/";
    });

    // ── Acções ────────────────────────────────────────────────

    /**
     * Faz login, guarda token e utilizador no estado e localStorage
     */
    async function login(email, password) {
        const result = await AuthService.login(email, password);

        token.value = result.token;
        user.value = result.user;

        localStorage.setItem(TOKEN_KEY, result.token);
        localStorage.setItem(USER_KEY, JSON.stringify(result.user));
        localStorage.setItem(VALIDATED_KEY, String(Date.now()));

        return result;
    }

    /**
     * Faz logout - revoga token no servidor e limpa estado local
     */
    async function logout() {
        try {
            await AuthService.logout();
        } catch {
            // Mesmo que o servidor falhe, limpa a sessão local
        } finally {
            clearSession();
        }
    }

    /**
     * Recarrega os dados do utilizador a partir do servidor
     */
    async function fetchMe() {
        const result = await AuthService.me();
        user.value = result.user;
        localStorage.setItem(USER_KEY, JSON.stringify(result.user));
        return result.user;
    }

    /**
     * Limpa estado e localStorage sem chamar o servidor
     */
    function clearSession() {
        token.value = null;
        user.value = null;
        sessionId.value = Date.now();
        localStorage.removeItem(TOKEN_KEY);
        localStorage.removeItem(USER_KEY);
        localStorage.removeItem(VALIDATED_KEY);
    }

    function updateUser(patch) {
        user.value = { ...user.value, ...patch };
        localStorage.setItem(USER_KEY, JSON.stringify(user.value));
    }

    return {
        token,
        user,
        sessionId,
        isAuthenticated,
        isAdmin,
        isGestor,
        isFuncionario,
        isObservador,
        userInitials,
        dashboardRoute,
        login,
        logout,
        fetchMe,
        clearSession,
        updateUser,
    };
});
