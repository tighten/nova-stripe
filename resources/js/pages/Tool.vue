<template>
    <NovaStripeLayout
        heading="Stripe Dashboard"
        :meta="{ title: 'Stripe Dashboard' }"
    >
        <BalanceCard />

        <Card>
            <ColumnSelect
                v-model="selectedColumns"
                :resource="singleCharge"
                @checkedColumns="selectedColumns = $event"
            />

            <ChargesTable
                @charge="singleCharge = $event"
                :columns="selectedColumns"
            />
        </Card>
    </NovaStripeLayout>
</template>

<script>
export default {
    data() {
        return {
            selectedColumns: ['id', 'amount', 'created', 'status'],
            singleCharge: {},
        }
    },
}
</script>
