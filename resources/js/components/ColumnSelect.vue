<template>
    <div class="flex justify-end p-2">
        <dropdown offset="-480">
            <dropdown-trigger class="text-90 bg-30 px-3 border-2 border-30 rounded">Select Columns</dropdown-trigger>
            <dropdown-menu
               slot="menu"
               direction="rtl"
               style="width: 100%; columns: 25vw 3"
               class="p-3 text-90"
            >
                <div
                    v-if="resource"
                    v-for="(key, value) in resource"
                    class="mb-2"
                >
                    <input class="" type="checkbox" :id="value" :value="value" v-model="checkedColumns" @change="$emit('checkedColumns', checkedColumns)">
                    <label :for="value" class="capitalize">{{ value.replaceAll('_', ' ') }}</label>
                </div>
            </dropdown-menu>

        </dropdown>
    </div>
</template>

<script>
export default {
    props: ['resource'],
    data() {
        return {
            checkedColumns: this.$attrs.value,
        }
    },
}
</script>
