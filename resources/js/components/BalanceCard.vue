<template>
    <loading-card :loading="initialLoading" class="flex flex-wrap py-8 mb-8 text-center">
        <div class="w-1/2">
            <p class="text-sm uppercase mb-2">Available</p>

            <div v-for="available in balance.available">
                <p class="text-xl">
                    {{ (available.amount / 100).toFixed(2) }} {{ available.currency }}
                </p>
            </div>
        </div>

        <div class="w-1/2">
            <p class="text-sm uppercase mb-2">Pending</p>

            <div v-for="pending in balance.pending">
                <p class="text-xl">
                    {{ (pending.amount / 100).toFixed(2) }} {{ pending.currency }}
                </p>
            </div>
        </div>
    </loading-card>
</template>

<script>
export default {
    data() {
        return {
            initialLoading: true,
            balance: {}
        }
    },

    methods: {
        getBalance() {
            Nova.request().get('/nova-vendor/nova-stripe/stripe/balance')
                .then((response) => {
                    this.balance = response.data.balance
                    this.initialLoading = false
                })
        },
    },

    created() {
        this.getBalance()
    },
}
</script>
