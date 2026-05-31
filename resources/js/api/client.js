import axios from 'axios'

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL ?? 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept':        'application/json',
    },
    timeout: 45000,
})

// ── Interceptor de REQUEST ────────────────────────────────────
// Anexa o token Sanctum em cada pedido autenticado
// Quando o body é FormData, remove o Content-Type para o browser
// definir automaticamente com o multipart boundary correto
api.interceptors.request.use(config => {
    const token = localStorage.getItem('mdr_token')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    if (config.data instanceof FormData) {
        delete config.headers['Content-Type']
    }
    return config
})

// ── Interceptor de RESPONSE ───────────────────────────────────
// Se o servidor devolver 401, a sessão expirou - limpa e redireciona
api.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            localStorage.removeItem('mdr_token')
            localStorage.removeItem('mdr_user')

            const isAlreadyOnLogin = window.location.pathname.includes('/acessoRestrito')
            if (!isAlreadyOnLogin) {
                window.location.href = '/acessoRestrito'
            }
        }
        return Promise.reject(error)
    }
)

export default api