<template>
    <div>
        <heading class="mb-6">Stripe Dashboard</heading>

        <charges-table></charges-table>
    </div>
</template>

<script>
    import ChargesTable from '../components/ChargesTable.vue'

    export default {
        components: {
            'charges-table': ChargesTable
        }
    }
</script>

<style>
    /* Scoped Styles */
</style>
