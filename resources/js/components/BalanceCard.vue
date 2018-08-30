<template>
    <loading-card :loading="initialLoading" class="flex flex-wrap py-8 mb-8 text-center">
        <div class="w-1/2">
            <p class="text-sm uppercase mb-2 text-80">Available Balance</p>

            <div v-for="available in balance.available">
                <p class="text-2xl">
                    {{ available.currency | money(available.amount) }}
                </p>
            </div>
        </div>

        <div class="w-1/2">
            <p class="text-sm uppercase mb-2 text-80">Pending Balance</p>

            <div v-for="pending in balance.pending">
                <p class="text-2xl">
                    {{ pending.currency | money(pending.amount) }}
                </p>
            </div>
        </div>
    </loading-card>
</template>

<script>
import money from '../utils/moneyFormat';

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

    filters: {
        money
    }
}
</script>
