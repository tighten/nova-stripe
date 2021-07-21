<template>
    <div>
        <heading class="mb-6">Customer Details</heading>

        <customer-detail-card :customer-id="customerId"/>
    </div>
</template>

<script>
import CustomerDetailCard from "../components/CustomerDetailCard";

export default {
    components: {
        "customer-detail-card" : CustomerDetailCard
    },
    props: ["customerId"],
};
</script>
