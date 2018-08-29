<template>
    <loading-card :loading="initialLoading" class="flex flex-wrap py-8 mb-8 w-full">
        <charge-detail-item :label="'ID'" :value="charge.id"></charge-detail-item>
        <charge-detail-item :label="'Amount'" :value="amount"></charge-detail-item>
        <charge-detail-item :label="'Status'" :value="charge.status"></charge-detail-item>
        <charge-detail-item :label="'Created'" :value="date"></charge-detail-item>
        <charge-detail-item :label="'Metadata'" :value="charge.metadata"></charge-detail-item>
        <charge-detail-item :label="'Livemode'" :value="charge.livemode"></charge-detail-item>
        <charge-detail-item :label="'Captured'" :value="charge.captured"></charge-detail-item>
        <charge-detail-item :label="'Paid'" :value="charge.paid"></charge-detail-item>
        <charge-detail-item :label="'Refunded'" :value="charge.refunded"></charge-detail-item>
        <charge-detail-item :label="'Dispute'" :value="charge.dispute"></charge-detail-item>
        <charge-detail-item :label="'Fraud Details'" :value="charge.fraud_details"></charge-detail-item>
        <charge-detail-item :label="'Transfer Group'" :value="charge.transfer_group"></charge-detail-item>
    </loading-card>
</template>

<script>
import ChargeDetailItem from './ChargeDetailItem.vue';

export default {
    components: {
        'charge-detail-item': ChargeDetailItem
    },

    props: ['chargeId'],

    data() {
        return {
            initialLoading: true,
            charge: {},
        }
    },

    computed: {
        amount() {
            return (this.charge.amount / 100).toFixed(2) + ' ' + this.charge.currency;
        },

        date() {
            return moment.unix(this.charge.created).format('YYYY/MM/DD h:mm:ss a')
        }
    },

    methods: {
        moment: moment,

        getCharge() {
            Nova.request().get('/nova-vendor/nova-stripe/stripe/charges/' + this.chargeId)
                .then((response) => {
                    this.charge = response.data.charge
                    this.initialLoading = false
                })
        },
    },

    created() {
        this.getCharge()
    }
}
</script>
