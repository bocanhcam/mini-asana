<template>
    <div class="p-6 bg-gray-100 min-h-screen space-y-6">

        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">Task Board</h2>
            <Button label="Create Task" @click="openCreateModal" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                v-for="(task, status) in tasks"
                :key="status"
                class="bg-white rounded shadow p-4"
            >
                <h3 class="text-lg font-semibold mb-4 capitalize">
                    {{ status.replace('_', ' ') }}
                </h3>

                <draggable
                    v-model="tasks[status]"
                    group="tasks"
                    item-key="id"
                    class="space-y-3 min-h-[100px]"
                    @change="(event) => onTaskDrop(event, status)"
                >
                    <template #item="{ element }">
                        <div class="bg-gray-50 p-4 rounded border shadow flex flex-col">
                            <div class="flex gap-2 items-center mb-2">
                                <Tag :severity="tagStatus(element.status)" :value="element.status"></Tag>
                                <Tag :severity="tagPriority(element.priority)" :value="element.priority"></Tag>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col gap-2">
                                    <div class="text-lg font-semibold">{{ element.title }}</div>
                                    <div>Due date: {{ element.due_date }}</div>
                                </div>
                                <div class="space-x-2">
                                    <Button
                                        label="Edit"
                                        class="p-button-text p-button-sm"
                                        @click="openEditModal(element)"
                                    />
                                    <Button
                                        label="Delete"
                                        class="p-button-text p-button-danger p-button-sm"
                                        @click="confirmDelete(element)"
                                    />
                                </div>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>
        </div>

        <TaskForm
            :mode="modalMode"
            v-model:visible="showModal"
            v-model:modelValue="form"
            :loading="loading"
            @submit="handleSubmit"
            @cancel="handleCancel"
            :errors="errors"
            :status="props.status"
            :priorities="props.priorities"
        />
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import draggable from 'vuedraggable'
import Button from 'primevue/button';
import taskApi from "../../api/task.js";
import {useToast} from "primevue/usetoast";
import Tag from "primevue/tag";
import TaskForm from "./Form.vue";

const toast = useToast()

const props = defineProps({
    projectId: String,
    status: Array,
    priorities: Array,
})

const modalMode = ref('create')
const showModal = ref(false)
const form = ref({
    id: null,
    title: '',
    due_date: null,
    status: 'todo',
    priority: 'low',
})

const errors = ref({})

const tasks = ref(null)

const loading = ref(false)

const resetForm = () => {
    form.value = {
        id: null,
        title: '',
        due_date: null,
        status: 'todo',
        priority: 'low',
    }
}

const resetErrors = () => {
    errors.value = {}
}

const handleCancel = () => {
    showModal.value = false
}

const handleSubmit = async (formData) => {
    loading.value = true
    try {
        if (modalMode.value === 'create') {
            await taskApi.create(props.projectId, formData)
        } else {
            await taskApi.update(props.projectId, formData.id, formData)
        }
        showModal.value = false

        toast.add({ severity: 'success', summary: 'Success', detail: 'Success' })
        await fetchTasks()
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors
        }
        toast.add({ severity: 'error', summary: 'Error', detail: e.response?.data?.message || 'Failed' })
    } finally {
        loading.value = false
    }
}

const tagStatus = (status) => {
    if (status === 'todo') return 'info'
    if (status === 'in_progress') return 'success'
    if (status === 'done') return 'secondary'
}

const tagPriority = (priority) => {
    if (priority === 'low') return 'info'
    if (priority === 'medium') return 'warn'
    if (priority === 'high') return 'danger'
}

const onTaskDrop = (event, toStatus) => {
    const task = event.added?.element;

    if (task && toStatus) {
        task.status = toStatus;
        updateTaskStatus(task.id, toStatus);
    }
};

const updateTaskStatus = async (taskId, newStatus) => {
    try {
        await taskApi.changeStatus(props.projectId, taskId, { status: newStatus });
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update status' });
    }
}

const fetchTasks = async () => {
    try {
        const res = await taskApi.getAll(props.projectId)
        tasks.value = res.data.tasks
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to load tasks' })
    }
}

const openCreateModal = () => {
    modalMode.value = 'create'
    resetForm()
    resetErrors()
    showModal.value = true
}

const confirmDelete = async (task) => {
    if (!confirm(`Are you sure to delete "${task.title}"?`)) return

    try {
        await taskApi.delete(props.projectId, task.id)
        toast.add({ severity: 'success', summary: 'Deleted', detail: 'Tas deleted' })
        await fetchTasks()
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Delete failed' })
    }
}

const openEditModal = (task) => {
    modalMode.value = 'edit'
    resetErrors()
    form.value = { ...task }
    showModal.value = true
}

onMounted(fetchTasks)
</script>
