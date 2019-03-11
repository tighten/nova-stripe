<template>
    <loading-card :loading="initialLoading" class="mb-6 py-3 px-6">
        <detail-text-field :field="{name: __('ID'), value: charge.id}"></detail-text-field>
        <detail-text-field :field="{name: __('Amount'), value: amount }"></detail-text-field>
        <detail-text-field :field="{name: __('Fee'), value: fee }"></detail-text-field>
        <detail-text-field :field="{name: __('Net'), value: net }"></detail-text-field>
        <detail-text-field :field="{name: __('Status'), value: __(charge.status) }"></detail-text-field>
        <detail-text-field :field="{name: __('Created'), value: date }"></detail-text-field>
        <detail-text-field :field="{name: __('Metadata'), value: charge.metadata }"></detail-text-field>
        <detail-boolean-field :field="{name: __('Livemode'), value: charge.livemode}"></detail-boolean-field>
        <detail-boolean-field :field="{name: __('Captured'), value: charge.captured}"></detail-boolean-field>
        <detail-boolean-field :field="{name: __('Paid'), value: charge.paid}"></detail-boolean-field>
        <detail-boolean-field :field="{name: __('Refunded'), value: charge.refunded}"></detail-boolean-field>
        <detail-text-field :field="{name: __('Dispute'), value: charge.dispute }"></detail-text-field>
        <detail-text-field :field="{name: __('Fraud Details'), value: charge.fraud_details }"></detail-text-field>
        <detail-text-field :field="{name: __('Transfer Group'), value: charge.transfer_group }"></detail-text-field>
    </loading-card>
</template>

<script>
export default {
    props: ['chargeId'],

    data() {
        return {
            initialLoading: true,
            charge: {
                amount: 0,
                currency: ''
            },
        }
    },

    computed: {
        amount() {
            return this.formatMoney(this.charge.amount, this.charge.currency)
        },

        fee() {
            return this.formatMoney(this.charge.balance_transaction.fee, this.charge.balance_transaction.currency)
        },

        net() {
            if (! this.charge.balance_transaction) {
                return 0
            }

            return this.formatMoney((this.charge.amount - this.charge.balance_transaction.fee), this.charge.currency)
        },

        date() {
            return moment.unix(this.charge.created).format('YYYY/MM/DD h:mm:ss a')
        },
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

        formatMoney(amount, currency) {
            return `${ (amount / 100).toFixed(2) } ${ currency.toUpperCase() }`
        },
    },

    created() {
        this.getCharge()
    }
}
</script>
