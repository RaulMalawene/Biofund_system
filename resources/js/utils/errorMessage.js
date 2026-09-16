/**
 * Converte um erro do axios numa mensagem clara para o utilizador,
 * distinguindo falhas de ligação/servidor de erros de negócio (validação,
 * permissões, etc.) em vez de mostrar sempre a mesma mensagem genérica.
 *
 * @param {*} error   erro capturado no catch (normalmente um AxiosError)
 * @param {string} fallback  mensagem a usar quando não é possível ser mais específico
 */
export function resolveErrorMessage(error, fallback = 'Ocorreu um erro. Tente novamente.') {
    if (!error) return fallback

    // Sem resposta do servidor: sem internet, servidor em baixo, DNS, CORS, etc.
    if (!error.response) {
        if (error.code === 'ECONNABORTED') {
            return 'O servidor demorou demasiado tempo a responder. Verifique a sua ligação à internet e tente novamente.'
        }
        if (error.message === 'Network Error' || error.code === 'ERR_NETWORK') {
            return 'Sem ligação ao servidor. Verifique a sua ligação à internet e tente novamente.'
        }
        return 'Não foi possível comunicar com o servidor. Verifique a sua ligação à internet e tente novamente.'
    }

    const { status, data } = error.response

    switch (status) {
        case 401:
            return data?.message ?? 'A sua sessão expirou. Inicie sessão novamente.'
        case 403:
            return data?.message ?? 'Não tem permissão para realizar esta acção.'
        case 404:
            return data?.message ?? 'O recurso solicitado não foi encontrado.'
        case 409:
            return data?.message ?? 'Este registo foi alterado por outra pessoa. Recarregue a página e tente novamente.'
        case 422:
            return data?.message ?? 'Verifique os dados introduzidos e tente novamente.'
        case 429:
            return 'Demasiados pedidos em pouco tempo. Aguarde alguns instantes e tente novamente.'
        case 503:
            return data?.message ?? 'Serviço temporariamente indisponível (possível falha de ligação à base de dados). Tente novamente dentro de instantes.'
    }

    if (status >= 500) {
        return data?.message ?? 'Erro no servidor. Tente novamente dentro de alguns instantes.'
    }

    return data?.message ?? fallback
}

/**
 * true quando o erro representa uma falha de ligação (sem resposta do
 * servidor) - útil para distinguir "sem internet" de erros de autenticação.
 */
export function isNetworkError(error) {
    return !!error && !error.response
}
