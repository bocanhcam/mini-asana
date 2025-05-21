<script setup>
import Menubar from 'primevue/menubar';
import {Link} from '@inertiajs/vue3'
import { ref } from "vue";

const items = ref([
    {
        label: 'Projects',
        route: '/projects'
    },
]);
</script>

<template>
    <main>
        <Toast position="top-center" />
        <Menubar :model="items" >
            <template #item="{ item, props, hasSubmenu }">
                <Link v-if="item.route" :href="item.route" custom>
                    <span>{{ item.label }}</span>
                </Link>
            </template>
        </Menubar>
        <slot />
    </main>
</template>
