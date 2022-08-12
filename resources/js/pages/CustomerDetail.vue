<template>
    <NovaStripeLayout
        :meta="{ title: 'Customer Details' }"
        heading="Customer Details"
    >
        <CustomerDetailCard :customer-id="customerId" />
    </NovaStripeLayout>
</template>

<script>
export default {
    props: {
        customerId: {
            type: String,
            required: true,
        },
    },
}
</script>
