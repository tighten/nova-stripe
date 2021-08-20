<template>
    <div>
        <heading class="mb-6">Charge Details</heading>

        <div class="flex flex-row-reverse mb-3">
            <button
                v-if="charge && !charge.refunded"
                class="btn-primary px-4 py-2 rounded"
                @click="refund(charge.id)"
                :disabled="deleting"
            >
                Refund
            </button>
        </div>

        <ChargeDetailCard
            ref="detail"
            :charge-id="chargeId"
            @charge-loaded="charge = $event"
        />
    </div>
</template>

<script>
import ChargeDetailCard from '../components/ChargeDetailCard.vue';

export default {
    props: ['chargeId'],
    components: {
        'ChargeDetailCard': ChargeDetailCard,
    },
    data() {
        return {
            charge: undefined,
            deleting: false,
        };
    },
    methods: {
        refund(chargeId) {
            this.deleting = true;

            Nova.request()
                .post(
                    '/nova-vendor/nova-stripe/stripe/charges/' +
                        this.chargeId +
                        '/refund'
                )
                .then((response) => {
                    Nova.success('Charge Successfully Refunded!');
                    this.$refs.detail.getCharge();
                });

            this.deleting = false;
        },
    },
};
</script>
