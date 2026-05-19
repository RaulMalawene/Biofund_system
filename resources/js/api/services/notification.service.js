import api from '../client'

export const NotificationService = {
    async getNotifications() {
        const { data } = await api.get('/notifications')
        return data // { notifications: [...] }
    },

    async getUnreadCount() {
        const { data } = await api.get('/notifications/count')
        return data // { unread: N }
    },

    async markRead(id) {
        const { data } = await api.patch(`/notifications/${id}/read`)
        return data
    },

    async markAllRead() {
        const { data } = await api.post('/notifications/read-all')
        return data
    },
}
