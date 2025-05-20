<template>
    <div class="p-6 max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Projects</h1>
            <Button label="New Project" icon="pi pi-plus" @click="showCreateModal = true" />
        </div>

        <DataView :value="projects" layout="grid" :paginator="false">
            <template #grid="slotProps">
                <div class="p-4 w-full sm:w-1/2 md:w-1/3">
                    <Card class="h-full flex flex-col justify-between">
                        <template #title>
                            <div class="flex items-center justify-between">
                                <span>{{ slotProps.data.name }}</span>
                                <div class="space-x-2">
                                    <Button icon="pi pi-pencil" class="p-button-text" @click="editProject(slotProps.data)" />
                                    <Button icon="pi pi-trash" class="p-button-text p-button-danger" @click="confirmDelete(slotProps.data)" />
                                </div>
                            </div>
                        </template>
                    </Card>
                </div>
            </template>
        </DataView>

        <!-- Create Modal -->
        <Dialog v-model:visible="showCreateModal" header="Create Project" modal>
            <div class="space-y-4">
                <label class="block font-medium">Name</label>
                <InputText v-model="form.name" class="w-full" />
                <Button label="Create" class="w-full" @click="submitCreate" :loading="loading" />
            </div>
        </Dialog>

        <!-- Edit Modal -->
        <Dialog v-model:visible="showEditModal" header="Edit Project" modal>
            <div class="space-y-4">
                <label class="block font-medium">Name</label>
                <InputText v-model="form.name" class="w-full" />
                <Button label="Update" class="w-full" @click="submitUpdate" :loading="loading" />
            </div>
        </Dialog>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'primevue/usetoast'
import projectApi from '@/api/project'

const toast = useToast()

const projects = ref([])
const showCreateModal = ref(false)
const showEditModal = ref(false)
const loading = ref(false)

const form = ref({
    id: null,
    name: ''
})

const fetchProjects = async () => {
    try {
        const res = await projectApi.getAll()
        projects.value = res.data
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to load projects' })
    }
}

const submitCreate = async () => {
    loading.value = true
    try {
        await projectApi.create({ name: form.value.name })
        toast.add({ severity: 'success', summary: 'Success', detail: 'Project created' })
        showCreateModal.value = false
        form.value.name = ''
        await fetchProjects()
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Create failed' })
    } finally {
        loading.value = false
    }
}

const editProject = (project) => {
    form.value = { ...project }
    showEditModal.value = true
}

const submitUpdate = async () => {
    loading.value = true
    try {
        await projectApi.update(form.value.id, { name: form.value.name })
        toast.add({ severity: 'success', summary: 'Success', detail: 'Project updated' })
        showEditModal.value = false
        form.value = { id: null, name: '' }
        await fetchProjects()
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Update failed' })
    } finally {
        loading.value = false
    }
}

const confirmDelete = async (project) => {
    if (!confirm(`Are you sure to delete "${project.name}"?`)) return

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
