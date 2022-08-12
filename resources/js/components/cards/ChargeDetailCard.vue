<template>
    <LoadingCard :loading="initialLoading" class="mb-6 px-6">
        <DetailTextField
            :field="{ name: __('ID'), value: charge.id }"
        ></DetailTextField>
        <DetailTextField
            :field="{
                name: __('Amount'),
                value: $filters.money(this.charge.currency, this.charge.amount),
            }"
        ></DetailTextField>
        <DetailTextField
            :field="{ name: __('Fee'), value: fee }"
        ></DetailTextField>
        <DetailTextField
            :field="{ name: __('Net'), value: net }"
        ></DetailTextField>
        <DetailTextField
            :field="{ name: __('Status'), value: charge.status }"
        ></DetailTextField>
        <DetailTextField
            :field="{
                name: __('Created'),
                value: $filters.date(charge.created),
            }"
        ></DetailTextField>
        <DetailTextField
            :field="{ name: __('Metadata'), value: charge.metadata }"
        ></DetailTextField>
        <DetailBooleanField
            :field="{ name: __('Livemode'), value: charge.livemode }"
        ></DetailBooleanField>
        <DetailBooleanField
            :field="{ name: __('Captured'), value: charge.captured }"
        ></DetailBooleanField>
        <DetailBooleanField
            :field="{ name: __('Paid'), value: charge.paid }"
        ></DetailBooleanField>
        <DetailBooleanField
            :field="{ name: __('Refunded'), value: charge.refunded }"
        ></DetailBooleanField>
        <DetailTextField
            :field="{ name: __('Dispute'), value: charge.dispute }"
        ></DetailTextField>
        <DetailTextField
            :field="{ name: __('Fraud Details'), value: charge.fraud_details }"
        ></DetailTextField>
        <DetailTextField
            :field="{
                name: __('Transfer Group'),
                value: charge.transfer_group,
            }"
        ></DetailTextField>
    </LoadingCard>
</template>

<script>
export default {
    props: {
        chargeId: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            initialLoading: true,
            charge: {
                amount: 0,
                currency: '',
            },
        }
    },
    computed: {
        fee() {
            if (!this.charge.balance_transaction) {
                return 0
            }

            return this.$filters.money(
                this.charge.balance_transaction.currency,
                this.charge.balance_transaction.fee
            )
        },
        net() {
            if (!this.charge.balance_transaction) {
                return 0
            }

            return this.$filters.money(
                this.charge.currency,
                this.charge.amount - this.charge.balance_transaction.fee
            )
        },
    },
    methods: {
        getCharge() {
            Nova.request()
                .get('/nova-vendor/nova-stripe/stripe/charges/' + this.chargeId)
                .then((response) => {
                    this.charge = response.data.charge
                    this.initialLoading = false
                    this.$emit('charge-loaded', response.data.charge)
                })
        },
    },
    created() {
        this.getCharge()
    },
}
</script>
