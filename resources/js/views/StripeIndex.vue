<template>
    <div>
        <heading class="mb-6">Stripe Dashboard</heading>

        <balance-card></balance-card>

        <charges-table></charges-table>
    </div>
</template>

<script>
    import BalanceCard from '../components/BalanceCard.vue';
    import ChargesTable from '../components/ChargesTable.vue'

    export default {
        components: {
            'balance-card': BalanceCard,
            'charges-table': ChargesTable
        }
    }
</script>

<style>
    /* Scoped Styles */
</style>
