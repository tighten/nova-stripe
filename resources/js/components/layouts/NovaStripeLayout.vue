<template>
    <div>
        <Head :title="__(meta.title)" />
        <h1
            class="text-90 font-normal text-xl md:text-2xl mb-3 flex items-center"
        >
            {{ __(heading) }}
        </h1>

        <slot />
    </div>
</template>

<script>
export default {
    props: {
        meta: {
            type: Object,
            required: true,
        },
        heading: String,
    },
}
</script>
