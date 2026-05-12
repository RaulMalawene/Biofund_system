
export const ENDPOINTS = {

    //Public
    FORM_DATA: '/public/form-data',
    DISTRICTS_BY_PROVINCE: (provinceId) =>`/public/provinces/${provinceId}/districts`,
    CREATE_OCCURENCE: 'public/occurences',
    TRACK_OCCURRENCE: (code) => '/public/occurences/track/${code}',



}