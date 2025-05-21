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
        <div class="flex flex-col gap-4 mb-4">
            <label for="title" class="font-semibold w-24">Title</label>
            <InputText v-model="localForm.title" id="title" class="flex-auto" autocomplete="off" />
            <small v-if="errors && errors.title" class="text-red-500">{{ errors.title[0] }}</small>
        </div>

        <div class="flex flex-col gap-4 mb-4">
            <label for="description" class="font-semibold w-24">Description</label>
            <Textarea v-model="localForm.description" id="description" rows="5" cols="30" style="resize: none"/>
            <small v-if="errors && errors.description" class="text-red-500">{{ errors.description[0] }}</small>
        </div>

        <div v-if="mode === 'edit'" class="flex flex-col gap-4 mb-4">
            <label for="is_active" class="font-semibold w-24">Status</label>
            <div class="flex items-center gap-2">
                <Checkbox binary v-model="localForm.is_active" inputId="is_active" />
                <label for="is_active"> Active </label>
            </div>
            <small v-if="errors && errors.is_active" class="text-red-500">{{ errors.is_active[0] }}</small>
        </div>

        <div class="flex items-center justify-end gap-4 mb-4">
            <Button type="button" label="Cancel" severity="secondary" @click="onCancel"></Button>
            <Button type="button" label="Save" @click="onSubmit"></Button>
        </div>

    </Dialog>
</template>
<script setup lang="ts">
import {ref, watch, computed} from "vue";
import Dialog from "primevue/dialog";
import Textarea from "primevue/textarea";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Checkbox from "primevue/checkbox";

const props = defineProps({
    mode: { type: String, required: true }, // 'create' or 'edit'
    modelValue: { type: Object, required: true },
    visible: { type: Boolean, required: true },
    loading: { type: Boolean, default: false },
    errors: { type: Object, default: null },
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
    return props.mode === 'create' ? 'Create Project' : 'Edit Project'
})

const onSubmit = () => {
    emit('submit', localForm.value)
}

const onCancel = () => {
    emit('cancel')
    emit('update:visible', false)
}
</script>
