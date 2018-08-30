<template>
    <div>
        <heading class="mb-6">Charge Details</heading>

        <charge-detail-card :charge-id="chargeId"></charge-detail-card>
    </div>
</template>

<script>
    import ChargeDetailCard from '../components/ChargeDetailCard.vue'

    export default {
        props: ['chargeId'],

        components: {
            'charge-detail-card': ChargeDetailCard,
        }
    }
</script>
