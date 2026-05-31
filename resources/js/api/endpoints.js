export const ENDPOINTS = {

    // ── Public - sem autenticação ────────────────────────────
    FORM_DATA:                '/public/form-data',
    DISTRICTS_BY_PROVINCE:    (provinceId) => `/public/provinces/${provinceId}/districts`,
    CREATE_OCCURRENCE:        '/public/occurrences',
    TRACK_OCCURRENCE:         (code)       => `/public/occurrences/track/${code}`,
    ATTACHMENT_DOWNLOAD:      (code, id)   => `/public/occurrences/${code}/attachments/${id}`,

    // ── Autenticação ─────────────────────────────────────────
    LOGIN:           '/auth/login',
    LOGOUT:          '/auth/logout',
    ME:              '/auth/me',
    FORGOT_PASSWORD: '/auth/forgot-password',
    RESET_PASSWORD:  '/auth/reset-password',
    CHANGE_PASSWORD: '/auth/change-password',

}