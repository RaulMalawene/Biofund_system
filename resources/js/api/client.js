import axios from 'axios'

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
        'content-Type' : 'aplication/json',
        'Accept': 'aplication/json'
    },
    timeout: 10000
})

export default api