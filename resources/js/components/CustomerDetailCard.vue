<template>
    <loading-card :loading="initialLoading" class="mb-6 py-3 px-6">
        <detail-text-field
            :field="{ name: 'address', value: customer.address }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'balance', value: customer.balance }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'created', value: customer.created }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'currency', value: customer.currency }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'default_source', value: customer.default_source }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'delinquent', value: customer.delinquent }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'description', value: customer.description }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'discount', value: customer.discount }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'email', value: customer.email }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'id', value: customer.id }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'invoice_prefix', value: customer.invoice_prefix }"
        ></detail-text-field>
        <detail-text-field
            :field="{
                name: 'invoice_settings',
                value: customer.invoice_settings,
            }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'livemode', value: customer.livemode }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'metadata', value: customer.metadata }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'name', value: customer.name }"
        ></detail-text-field>
        <detail-text-field
            :field="{
                name: 'next_invoice_sequence',
                value: customer.next_invoice_sequence,
            }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'object', value: customer.object }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'phone', value: customer.phone }"
        ></detail-text-field>
        <detail-text-field
            :field="{
                name: 'preferred_locales',
                value: customer.preferred_locales,
            }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'shipping', value: customer.shipping }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'tax_exempt', value: customer.tax_exempt }"
        ></detail-text-field>
    </loading-card>
</template>

<script>
export default {
    props: ["customerId"],
    data() {
        return {
            customer: {},
            initialLoading: true,
        };
    },
    methods: {
        loadCustomer(id) {
            Nova.request()
                .get(
                    "/nova-vendor/nova-stripe/stripe/customers/" +
                        this.customerId
                )
                .then((response) => {
                    this.customer = response.data.customer;
                    this.initialLoading = false;
                });
        },
    },
    created() {
        this.loadCustomer();
    },
};
</script>
