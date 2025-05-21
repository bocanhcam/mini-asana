<template>
    <Dialog
        :visible="visible"
        :header="header"
        :closable="!loading"
        @update:visible="(val) => { if (!val) onCancel() }"
        :style="{ width: '30rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
        modal
    >
        <div class="space-y-4">
            <div class="card flex flex-wrap justify-center gap-4">
                <div class="flex flex-col gap-2 w-full">
                    <label class="block font-medium">Title</label>
                    <InputText v-model="localForm.title" class="w-full" />
                    <small v-if="errors && errors.title" class="text-red-500">{{ errors.title[0] }}</small>
                </div>
            </div>

            <div class="card flex flex-wrap justify-center gap-4">
                <div class="flex flex-col gap-2 w-full">
                    <label class="block font-medium">Due date</label>
                    <DatePicker v-model="localForm.due_date" />
                    <small v-if="errors && errors.due_date" class="text-red-500">{{ errors.due_date[0] }}</small>
                </div>
            </div>

            <div class="card flex flex-wrap justify-center gap-4">
                <div class="flex flex-col gap-2 w-full">
                    <label class="block font-medium">Status</label>
                    <Select v-model="localForm.status" :options="props.status" optionLabel="name" optionValue="value" class="w-full" />
                    <small v-if="errors && errors.status" class="text-red-500">{{ errors.status[0] }}</small>
                </div>
            </div>

            <div class="card flex flex-wrap justify-center gap-4">
                <div class="flex flex-col gap-2 w-full">
                    <label class="block font-medium">Priority</label>
                    <Select v-model="localForm.priority" :options="props.priorities" optionLabel="name" optionValue="value" class="w-full" />
                    <small v-if="errors && errors.priority" class="text-red-500">{{ errors.priority[0] }}</small>
                </div>
            </div>

            <Button
                label="Create"
                class="w-full"
                @click="onSubmit"
            />
        </div>
    </Dialog>
</template>
<script setup lang="ts">
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import DatePicker from "primevue/datepicker";
import Select from "primevue/select";
import Button from "primevue/button";
import {computed, ref, watch} from "vue";

const props = defineProps({
    mode: { type: String, required: true },
    modelValue: { type: Object, required: true },
    visible: { type: Boolean, required: true },
    loading: { type: Boolean, default: false },
    errors: { type: Object, default: null },
    status: { type: Object, required: true },
    priorities: { type: Object, required: true },
})

const emit = defineEmits(['update:modelValue', 'update:visible', 'submit', 'cancel'])

const localForm = ref({ ...props.modelValue })

watch(
    () => props.modelValue,
    (val) => {
        localForm.value = { ...val }
    },
    { deep: true }
)

const header = computed(() => {
    return props.mode === 'create' ? 'Create Task' : 'Edit Task'
})

const onSubmit = () => {
    emit('submit', localForm.value)
}

const onCancel = () => {
    emit('cancel')
    emit('update:visible', false)
}
</script>
