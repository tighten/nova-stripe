<template>
    <loading-card :loading="initialLoading" class="mb-6 py-3 px-6">
        <div>I am a customer.</div>
        <div>{{customer}}</div>
    </loading-card>
</template>

<script>
export default {
    props: ['customerId'],
    data() {
        return {
            customer: {},
            initialLoading: true,
        }
    },
    methods: {
        loadCustomer(id) {
            Nova.request().get('/nova-vendor/nova-stripe/stripe/customers/' + this.customerId)
                .then((response) =>{
                    this.customer = response.data.customer;
                    this.initialLoading = false;
                })
        }
    },
    created() {
        this.loadCustomer();
    }
}
</script>
