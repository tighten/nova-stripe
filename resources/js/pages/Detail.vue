<template>
    <NovaStripeLayout
        :meta="{ title: 'Charge Details' }"
        heading="Charge Details"
    >
        <div class="flex ns-flex-row-reverse mb-3">
            <button
                v-if="
                    charge && !charge.refunded && charge.status === 'succeeded'
                "
                class="shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white dark:text-gray-800 inline-flex items-center font-bold px-4 h-9 text-sm"
                @click="refund"
                :disabled="deleting"
            >
                {{ __('Refund') }}
            </button>
        </div>

        <ChargeDetailCard
            ref="detail"
            :charge-id="chargeId"
            @charge-loaded="charge = $event"
        />
    </NovaStripeLayout>
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
            charge: null,
            deleting: false,
        }
    },
    methods: {
        refund() {
            this.deleting = true

            Nova.request()
                .post(
                    '/nova-vendor/nova-stripe/stripe/charges/' +
                        this.chargeId +
                        '/refund'
                )
                .then((response) => {
                    Nova.success('Charge Successfully Refunded!')
                    this.$refs.detail.getCharge()
                })

            this.deleting = false
        },
    },
}
</script>
