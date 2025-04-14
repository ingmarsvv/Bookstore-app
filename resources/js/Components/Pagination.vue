<script setup>
import { Link} from '@inertiajs/vue3'
const props = defineProps({
    links: Array,
})

const getLabel = (label) => {
    if (label === '&laquo; Previous') {
        return 'Iepriekšējā lapa';
    } else if (label === 'Next &raquo;') {
        return 'Nākamā lapa';
    } else {
        return label;
    }
};
</script>

<template>
    <div>
        <Component
            v-for="link in links"
            :key="link.label"
            :is="link.url ? Link : 'span'" 
            :href="link.url" 
            v-html="getLabel(link.label)" 
            class="px-1 text-base"
            v-bind:class="{
                'hover:bg-gray-200': link.url,
                'text-gray-400': !link.url,
                'font-bold': link.active // Make current page bold
            }"
        />
    </div>
   
</template>