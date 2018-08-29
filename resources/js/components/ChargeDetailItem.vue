<template>
    <div class="flex border-b border-40 mb-6 py-3 px-6 w-full">
        <div class="w-1/4 py-4">
            <h4 class="font-normal text-80">
                {{ label }}
            </h4>
        </div>
        <div class="w-3/4 py-4">
            <p v-if="value" class="text-90">{{ value }}</p>
            <p v-else>&mdash;</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['label', 'value']
    }
</script>
