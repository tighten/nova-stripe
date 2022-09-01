<template>
    <Dropdown placement="bottom-end">
        <DropdownTrigger
            class="text-90 px-3 shadow rounded bg-gray-100 dark:bg-gray-800"
        >
            {{ __(buttonText) }}
            <span v-if="selected.filterName === filterName" class="ns-capitalize">
                : {{ selected.selectedValue }}
            </span>
        </DropdownTrigger>

        <template #menu>
            <DropdownMenu class="ns-text-90">
                <div class="flex flex-col py-1">
                    <DropdownMenuItem
                        v-for="value in values"
                        as="button"
                        class="border-none ns-capitalize"
                        @click.prevent="$emit('ns-filter-updated', {
                            value: value.value,
                            filterName: filterName,
                        })"
                    >
                        {{ __(value.label) }}
                    </DropdownMenuItem>
                </div>
            </DropdownMenu>
        </template>
    </Dropdown>
</template>

<script>
export default {
    props: {
        buttonText: {
            required: true,
            type: String,
        },
        filterName: {
            required: true,
            type: String,
        },
        values: {
            required: true,
            type: Array,
        },
        selected: Object,
    },
    data() {
        return {
            data: '',
        }
    },
}
</script>
