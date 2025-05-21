<template>
    <div class="w-full h-screen flex items-center justify-center bg-gray-100">
        <form @submit.prevent="submit" class="bg-white p-8 rounded shadow-md w-full max-w-md space-y-4">
            <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

            <div>
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                <InputText id="email" v-model="form.email" class="w-full" />
                <small v-if="errors.email" class="text-red-500">{{ errors.email[0] }}</small>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                <Password id="password" v-model="form.password" toggleMask class="w-full" inputClass="w-full" :feedback="false"/>
                <small v-if="errors.password" class="text-red-500">{{ errors.password[0] }}</small>
            </div>

            <Button type="submit" label="Login" class="w-full"/>

            <div class="text-center text-sm mt-4">
                Don't have an account?
                <Link href="/register" class="text-blue-600 hover:underline font-medium">Register</Link>
            </div>
        </form>
    </div>
</template>

<script setup>
import {reactive, ref} from 'vue'
import { router, Link } from '@inertiajs/vue3'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
import api from "../../utils/axios.js";
import {useToast} from "primevue/usetoast";

const toast = useToast()

const form = reactive({
    email: null,
    password: null,
})

const errors = ref({
    email: null,
    password: null,
})

const submit = async () => {
    try {
        const response = await api.post('/login', {
            email: form.email,
            password: form.password
        })

        if (response.status === 200) {
            const token = response.data.token

            localStorage.setItem('token', token)

            router.visit('/projects')
        }
    } catch (err) {
        errors.value = err.response?.data?.errors

        toast.add({severity:'error', summary:'Error', detail: err.response?.data?.message || 'Something went wrong'})
    } finally {
        //isSubmitting.value = false
    }
}
</script>
