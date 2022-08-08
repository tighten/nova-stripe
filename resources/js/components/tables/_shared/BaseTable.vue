<template>
    <LoadingView :loading="initialLoading">
        <LoadingCard :loading="loading" class="card relative">
            <div class="overflow-hidden overflow-x-auto relative">
                <table
                    v-if="data.length"
                    class="w-full table-default"
                    data-testid="resource-table"
                >
                    <slot />
                </table>

                <div v-else class="w-full italic">No {{ modelName }}</div>
            </div>

            <PaginationLinks
                :resource="data"
                :hasMore="hasMore"
                :hasPrevious="hasPrevious"
                @previous="previousPage"
                @next="nextPage"
            ></PaginationLinks>
        </LoadingCard>
    </LoadingView>
</template>

<script>
export default {
    props: {
        data: Array,
        modelName: String,
        loading: Boolean,
        hasMore: Boolean,
        getPreviousPage: Function,
        getNextPage: Function,
    },
    data() {
        return {
            page: 1,
            initialLoading: true,
        }
    },
    computed: {
        hasPrevious() {
            return this.page > 1
        },
    },
    watch: {
        loading() {
            this.initialLoading = false
        },
        page(newValue) {
            this.$emit('page-updated', newValue)
        },
    },
    methods: {
        nextPage() {
            this.getNextPage()

            this.page++
        },
        previousPage() {
            this.getPreviousPage()

            if (this.hasPrevious) {
                this.page--
            }
        },
    },
}
</script>
