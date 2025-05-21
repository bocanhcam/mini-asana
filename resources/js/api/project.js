import api from '../utils/axios'

export default {
    getAll() {
        return api.get('/projects')
    },
    create(data) {
        return api.post('/projects/create', data)
    },
    update(id, data) {
        return api.put(`/projects/${id}/edit`, data)
    },
    delete(id) {
        return api.delete(`/projects/${id}/delete`)
    }
}
