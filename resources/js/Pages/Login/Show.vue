<template>
    <div class="w-full h-screen flex items-center justify-center bg-gray-100">
        <form @submit.prevent="submit" class="bg-white p-8 rounded shadow-md w-full max-w-md space-y-4">
            <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

            <div>
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                <InputText id="email" v-model="form.email" class="w-full" />
                <small v-if="errors.email" class="text-red-500">{{ errors.email }}</small>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                <Password id="password" v-model="form.password" toggleMask class="w-full" inputClass="w-full"/>
                <small v-if="errors.password" class="text-red-500">{{ errors.password }}</small>
            </div>

            <Button type="submit" label="Login" class="w-full"/>
        </form>
    </div>
</template>

<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
import api from "../../utils/axios.js";

defineProps({ errors: Object })

const form = reactive({
    email: null,
    password: null,
})

const submit = async () => {
    try {
        const response = await api.post('/login', {
            email: form.email,
            password: form.password
        })

        // Redirect or show success
        if (response.status === 200) {
            const token = response.data.token

            localStorage.setItem('token', token)

            router.visit('/projects') // hoặc bất kỳ route nào sau login
        }
    } catch (err) {
        alert('Login failed: ' + err.response?.data?.message || err.message)
    } finally {
        //isSubmitting.value = false
    }
}
</script>
