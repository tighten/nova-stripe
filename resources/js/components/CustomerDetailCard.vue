<template>
    <loading-card :loading="initialLoading" class="mb-6 py-3 px-6">
        <detail-text-field
            :field="{ name: 'ID', value: customer.id }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'Name', value: customer.name }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'Address', value: customer.address }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'Email', value: customer.email }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'Phone', value: customer.phone }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'Balance', value: money(customer.currency, customer.balance) }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'Created', value: date(customer.created) }"
        ></detail-text-field>
        <detail-text-field
            v-if="customer.shipping.address.line1 !== ''"
            :field="{ name: 'Shipping Address', value: `${this.customer.shipping.address.line1} ${this.customer.shipping.address.line2}, ${this.customer.shipping.address.city}, ${this.customer.shipping.address.state} ${this.customer.shipping.address.postal_code}` }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'Currency', value: customer.currency }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'Default Source', value: customer.default_source }"
        ></detail-text-field>
        <detail-boolean-field
            :field="{ name: 'Delinquent', value: customer.delinquent }"
        ></detail-boolean-field>
        <detail-text-field
            :field="{ name: 'Description', value: customer.description }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'Discount', value: customer.discount }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'Invoice Prefix', value: customer.invoice_prefix }"
        ></detail-text-field>
        <detail-boolean-field
            :field="{ name: 'Livemode', value: customer.livemode }"
        ></detail-boolean-field>
        <detail-text-field
            :field="{ name: 'Metadata', value: customer.metadata }"
        ></detail-text-field>
        <detail-text-field
            :field="{
                name: 'Next Invoice Sequence',
                value: customer.next_invoice_sequence,
            }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'Object', value: customer.object }"
        ></detail-text-field>

        <detail-text-field
            :field="{
                name: 'Preferred Locales',
                value: customer.preferred_locales,
            }"
        ></detail-text-field>
        <detail-text-field
            :field="{ name: 'Tax Exempt', value: customer.tax_exempt }"
        ></detail-text-field>
    </loading-card>
</template>

<script>
import moneyFormat from "../utils/moneyFormat";

export default {
    props: ["customerId"],
    data() {
        return {
            customer: {},
            initialLoading: true,
            money: moneyFormat,
        };
    },
    computed: {
        formattedShipping() {
            return `${this.customer.shipping.address.line1} ${this.customer.shipping.address.line2} <br/>
                    ${this.customer.shipping.address.city}, ${this.customer.shipping.address.state} ${this.customer.shipping.address.postal_code}`;
        }
    },
    methods: {
        date(date) {
            return moment.unix(date).format("YYYY/MM/DD h:mm:ss a");
        },
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
