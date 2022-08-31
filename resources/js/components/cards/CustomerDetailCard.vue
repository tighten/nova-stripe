<template>
    <LoadingCard :loading="initialLoading" class="ns-mb-6 ns-px-6">
        <DetailTextField
            :field="{ name: __('ID'), value: customer.id }"
        ></DetailTextField>
        <DetailTextField
            :field="{ name: __('Name'), value: customer.name }"
        ></DetailTextField>
        <DetailTextField
            :field="{
                name: __('Address'),
                value: formatAddress(customer.address),
            }"
        ></DetailTextField>
        <DetailTextField
            :field="{ name: __('Email'), value: customer.email }"
        ></DetailTextField>
        <DetailTextField
            :field="{ name: __('Phone'), value: customer.phone }"
        ></DetailTextField>
        <DetailTextField
            :field="{
                name: __('Balance'),
                value: $filters.money(
                    customer.currency ?? 'usd',
                    customer.balance
                ),
            }"
        ></DetailTextField>
        <DetailTextField
            :field="{
                name: __('Created'),
                value: $filters.date(customer.created),
            }"
        ></DetailTextField>
        <DetailTextField
            :field="{
                name: __('Shipping Address'),
                value: formatAddress(customer.shipping?.address),
            }"
        ></DetailTextField>
        <DetailTextField
            :field="{ name: __('Currency'), value: customer.currency }"
        ></DetailTextField>
        <DetailTextField
            :field="{
                name: __('Default Source'),
                value: customer.default_source,
            }"
        ></DetailTextField>
        <DetailBooleanField
            :field="{ name: __('Delinquent'), value: !customer.delinquent }"
        ></DetailBooleanField>
        <DetailTextField
            :field="{ name: __('Description'), value: customer.description }"
        ></DetailTextField>
        <DetailTextField
            :field="{ name: __('Discount'), value: customer.discount }"
        ></DetailTextField>
        <DetailTextField
            :field="{
                name: __('Invoice Prefix'),
                value: customer.invoice_prefix,
            }"
        ></DetailTextField>
        <DetailBooleanField
            :field="{ name: __('Livemode'), value: customer.livemode }"
        ></DetailBooleanField>
        <DetailTextField
            :field="{ name: __('Metadata'), value: customer.metadata }"
        ></DetailTextField>
        <DetailTextField
            :field="{
                name: __('Next Invoice Sequence'),
                value: customer.next_invoice_sequence,
            }"
        ></DetailTextField>
        <DetailTextField
            :field="{
                name: __('Preferred Locales'),
                value: customer.preferred_locales,
            }"
        ></DetailTextField>
        <DetailTextField
            :field="{ name: __('Tax Exempt'), value: customer.tax_exempt }"
        ></DetailTextField>
    </LoadingCard>
</template>

<script>
export default {
    props: {
        customerId: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            customer: {},
            initialLoading: true,
            shippingAddress: null,
        }
    },
    methods: {
        loadCustomer() {
            Nova.request()
                .get(
                    '/nova-vendor/nova-stripe/stripe/customers/' +
                        this.customerId
                )
                .then((response) => {
                    this.customer = response.data.customer
                    this.initialLoading = false
                })
        },
        formatAddress(address) {
            return address?.line1
                ? `${address.line1}
                    ${address.line2}
                    ${address.city},
                    ${address.state}
                    ${address.postal_code}`
                : '—'
        },
    },
    created() {
        this.loadCustomer()
    },
}
</script>
