<template>
    <div class="flex justify-end">
        <span>Checked names: {{ checkedColumns }}</span>
        <dropdown offset="-467">
            <dropdown-trigger>Select Columns</dropdown-trigger>
            <dropdown-menu
               slot="menu"
               direction="rtl"
               style="width: 100%; columns: 30vw 3"
               class="p-3"
            >
                <div
                    v-if="resource"
                    v-for="(value, key) in resource"
                    class="mb-2"
                >
                    <input class="" type="checkbox" :id="key" :value="key" v-model="checkedColumns" @input="$emit('checkedColumns', checkedColumns)">
                    <label :for="key" class="capitalize">{{ key.replaceAll('_', ' ') }}</label>
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
            checkedColumns: [],
        }
    },
}
</script>

<style scoped>

</style>
