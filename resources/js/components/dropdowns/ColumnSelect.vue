<template>
    <Dropdown placement="bottom-end">
        <DropdownTrigger
            class="text-90 px-3 shadow rounded bg-gray-100 dark:bg-gray-800"
        >
            {{ __('Select Columns') }}
        </DropdownTrigger>

        <template #menu>
            <DropdownMenu
                style="width: 100%; columns: 25vw 3"
                class="ns-p-3 ns-text-90"
            >
                <div
                    v-if="resource"
                    v-for="(value, key) in resource"
                    class="ns-m-2"
                >
                    <input
                        class="ns-mr-2"
                        type="checkbox"
                        :id="key"
                        :value="key"
                        v-model="checkedColumns"
                        @change="$emit('checkedColumns', checkedColumns)"
                    />
                    <label :for="key" class="capitalize">
                        {{ __(key.replaceAll('_', ' ')) }}
                    </label>
                </div>
            </DropdownMenu>
        </template>
    </Dropdown>
</template>

<script>
export default {
    props: {
        resource: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            checkedColumns: this.$attrs.modelValue,
        }
    },
}
</script>
