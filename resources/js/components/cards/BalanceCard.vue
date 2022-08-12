<template>
    <LoadingCard
        :loading="initialLoading"
        class="flex flex-wrap py-8 mb-8 text-center"
    >
        <div class="w-1/2">
            <p class="text-sm uppercase mb-2 text-80">
                {{ __('Available Balance') }}
            </p>

            <div v-for="available in balance.available">
                <p class="text-2xl">
                    {{ $filters.money(available.currency, available.amount) }}
                </p>
            </div>
        </div>

        <div class="w-1/2">
            <p class="text-sm uppercase mb-2 text-80">
                {{ __('Pending Balance') }}
            </p>

            <div v-for="pending in balance.pending">
                <p class="text-2xl">
                    {{ $filters.money(pending.currency, pending.amount) }}
                </p>
            </div>
        </div>
    </LoadingCard>
</template>

<script>
export default {
    data() {
        return {
            initialLoading: true,
            balance: {},
        }
    },

    methods: {
        getBalance() {
            Nova.request()
                .get('/nova-vendor/nova-stripe/stripe/balance')
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
