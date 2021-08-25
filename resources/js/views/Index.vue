<template>
    <div>
        <heading class="mb-6">Stripe Dashboard</heading>

        <BalanceCard />

        <card>
            <ColumnSelect
                v-model="selectedColumns"
                :resource="singleCharge"
                @checkedColumns="selectedColumns = $event"
            />

            <ChargesTable @charge="singleCharge = $event" :columns="selectedColumns"/>
        </card>
    </div>
</template>

<script>
import BalanceCard from '../components/BalanceCard.vue';
import ChargesTable from '../components/ChargesTable.vue';
import ColumnSelect from "../components/ColumnSelect";

export default {
    components: { BalanceCard, ChargesTable, ColumnSelect, },
    data() {
        return {
            selectedColumns: ['id', 'amount', 'created', 'status'],
            singleCharge: {},
        }
    },
}
</script>
