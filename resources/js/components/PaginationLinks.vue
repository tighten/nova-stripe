<template>
    <div class="bg-gray-50 dark:bg-gray-800 rounded-b-lg">
        <nav v-if="resource.length > 0" class="flex">
            <!-- Previous Link -->
            <button
                :disabled="!hasPrevious"
                class="btn btn-link py-3 px-4"
                :class="{
                    'text-gray-500 dark:text-gray-400': hasPrevious,
                    'text-80 opacity-50 cursor-not-allowed': !hasPrevious,
                }"
                rel="prev"
                @click.prevent="previousPage()"
                dusk="previous"
            >
                {{ __('Previous') }}
            </button>

            <!-- Next Link -->
            <button
                :disabled="!hasMore"
                class="ml-auto btn btn-link py-3 px-4"
                :class="{
                    'text-gray-500 dark:text-gray-400': hasMore,
                    'text-80 opacity-50 cursor-not-allowed': !hasMore,
                }"
                rel="next"
                @click.prevent="nextPage()"
                dusk="next"
            >
                {{ __('Next') }}
            </button>
        </nav>
    </div>
</template>

<script>
export default {
    props: {
        resource: {
            type: Array,
            required: true,
        },
        hasMore: {
            type: Boolean,
            required: true,
        },
        hasPrevious: {
            type: Boolean,
            required: true,
        },
    },
    methods: {
        previousPage() {
            this.$emit('previous')
        },
        nextPage() {
            this.$emit('next')
        },
    },
}
</script>
