import api from '../utils/axios'

export default {
    getAll(projectId) {
        return api.get(`/projects/${projectId}/tasks`)
    },
    create(projectId, data) {
        return api.post(`/projects/${projectId}/tasks/create`, data)
    },
    update(projectId, id, data) {
        return api.put(`/projects/${projectId}/tasks/${id}/edit`, data)
    },
    delete(projectId, id) {
        return api.delete(`/projects/${projectId}/tasks/${id}/delete`)
    },
    changeStatus(projectId, id, data) {
        return api.put(`/projects/${projectId}/tasks/${id}/changeStatus`, data)
    },
}
