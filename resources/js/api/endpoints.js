export const ENDPOINTS = {

    // Public — sem autenticação
    FORM_DATA:                '/public/form-data',
    DISTRICTS_BY_PROVINCE:    (provinceId)    => `/public/provinces/${provinceId}/districts`,
    COMMUNITIES_BY_DISTRICT:  (districtId)    => `/public/districts/${districtId}/communities`,
    CREATE_OCCURRENCE:        '/public/occurrences',
    TRACK_OCCURRENCE:         (code)          => `/public/occurrences/track/${code}`,
    ATTACHMENT_DOWNLOAD:      (code, id)      => `/public/occurrences/${code}/attachments/${id}`,

}