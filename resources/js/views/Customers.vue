<template>
    <div>
        <heading class="mb-6">Customers</heading>

        <customers-table />
    </div>
</template>

<script>
import CustomersTable from "../components/CustomersTable";

export default {
    components: { CustomersTable, },
};
</script>
