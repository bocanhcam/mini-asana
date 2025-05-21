<template>
    <div class="p-6 max-w-5xl mx-auto bg-gray-100 min-h-screen space-y-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Projects</h1>
            <Button label="New Project" @click="openCreateModal" />
        </div>

        <DataView v-if="projects" :value="projects" layout="grid" :paginator="false">
            <template #grid="slotProps">
                <div class="flex flex-wrap">
                    <div v-for="(item, index) in slotProps.items" :key="index" class="p-4 w-full sm:w-1/2 md:w-1/3">
                        <Card class="shadow-md border border-gray-200 rounded-md">
                            <template #title>
                                <div class="flex items-center justify-between">
                                    <Tag :severity="item.is_active ? 'success' : 'secondary'" :value="item.is_active ? 'Active' : 'Inactive'"></Tag>
                                    <div class="space-x-2">
                                        <Button label="Edit" class="p-button-text" @click="openEditModal(item)" />
                                        <Button label="Delete" class="p-button-text p-button-danger" @click="confirmDelete(item)" />
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2 mt-2">
                                    <Link :href="`/projects/${item.id}/tasks`" class="text-xl font-bold break-words">#{{ item.id }} - {{ item.title }}</Link>
                                    <div class="break-words whitespace-normal">{{ item.description }}</div>
                                </div>
                            </template>
                        </Card>
                    </div>
                </div>
            </template>
        </DataView>

        <ProjectForm
            :mode="modalMode"
            v-model:visible="showModal"
            v-model:modelValue="form"
            :loading="loading"
            @submit="handleSubmit"
            @cancel="handleCancel"
            :errors="errors"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'
import projectApi from '../../api/project'
import Button from 'primevue/button'
import DataView from 'primevue/dataview'
import Card from 'primevue/card'
import Tag from 'primevue/tag';
import ProjectForm from './Form.vue'

const toast = useToast()

const projects = ref([])

const modalMode = ref('create')
const showModal = ref(false)
const form = ref({
    id: null,
    title: '',
    description: '',
    is_active: true,
})

const errors = ref({})

const loading = ref(false)

const resetForm = () => {
    form.value = {
        id: null,
        title: '',
        description: '',
        is_active: true,
    }
}

const resetErrors = () => {
    errors.value = {}
}

const fetchProjects = async () => {
    try {
        const res = await projectApi.getAll()
        projects.value = res.data.projects
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to load projects' })
    }
}

const openCreateModal = () => {
    modalMode.value = 'create'
    resetForm()
    resetErrors()
    showModal.value = true
}

const openEditModal = (project) => {
    modalMode.value = 'edit'
    console.log(modalMode.value)
    resetErrors()
    form.value = { ...project }
    showModal.value = true
}

const handleCancel = () => {
    showModal.value = false
}

const handleSubmit = async (formData) => {
    loading.value = true
    try {
        if (modalMode.value === 'create') {
            await projectApi.create(formData)
        } else {
            await projectApi.update(formData.id, formData)
        }
        showModal.value = false

        toast.add({ severity: 'success', summary: 'Success', detail: 'Success' })
        await fetchProjects()
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors
        }
        toast.add({ severity: 'error', summary: 'Error', detail: e.response?.data?.message || 'Failed' })
    } finally {
        loading.value = false
    }
}

const confirmDelete = async (project) => {
    if (!confirm(`Are you sure to delete "#${project.id} - ${project.title}"?`)) return

    try {
        await projectApi.delete(project.id)
        toast.add({ severity: 'success', summary: 'Deleted', detail: 'Project deleted' })
        await fetchProjects()
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Delete failed' })
    }
}

onMounted(fetchProjects)
</script>

<style scoped>
:deep(.p-dataview-content){
    background: transparent !important;
}
</style>
