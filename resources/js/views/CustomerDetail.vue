<template>

    <customer-detail-card :customer-id="customerId"/>

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
