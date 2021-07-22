<template>
    <div>
        <heading class="mb-6">Stripe Dashboard</heading>

        <balance-card />

        <column-select
            v-model="selectedColumns"
            :resource="singleCharge"
            @checkedColumns="selectedColumns = $event"
        />

        <charges-table @charge="singleCharge = $event" :columns="selectedColumns"/>
    </div>
</template>

<script>
    import BalanceCard from '../components/BalanceCard.vue';
    import ChargesTable from '../components/ChargesTable.vue';
    import ColumnSelect from "../components/ColumnSelect";

    export default {
        components: {
            'balance-card': BalanceCard,
            'charges-table': ChargesTable,
            'column-select': ColumnSelect,
        },
        data() {
            return {
                selectedColumns: {},
                singleCharge: {},
            }
        }
    }
</script>

<style>
    /* Scoped Styles */
</style>
