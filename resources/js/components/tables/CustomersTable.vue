<template>
    <div>
        <BaseTable
            :data="customers"
            model-name="customers"
            :loading="loading"
            :has-more="hasMore"
            :get-previous-page="previousPage"
            :get-next-page="nextPage"
            @page-updated="page = $event"
        >
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <HeaderCell>{{ __('Customer ID') }}</HeaderCell>
                    <HeaderCell>{{ __('Name') }}</HeaderCell>
                    <HeaderCell>{{ __('Email') }}</HeaderCell>
                    <HeaderCell>{{ __('Balance') }}</HeaderCell>
                    <HeaderCell>&nbsp;</HeaderCell>
                </tr>
            </thead>

            <tbody>
                <tr class="group" v-for="customer in customers">
                    <BodyCell>
                        {{ customer.id }}
                    </BodyCell>
                    <BodyCell>
                        {{ customer.name }}
                    </BodyCell>
                    <BodyCell>
                        {{ customer.email }}
                    </BodyCell>
                    <BodyCell>
                        <span v-if="customer.currency">
                            {{
                                $filters.money(
                                    customer.currency,
                                    customer.balance
                                )
                            }}
                        </span>
                        <span v-else>-</span>
                    </BodyCell>
                    <BodyCell>
                        <span>
                            <InertiaLink
                                :href="`/nova/nova-stripe/customers/${customer.id}`"
                                :aria-label="__('View')"
                            >
                                <HeroiconsOutlineEye />
                            </InertiaLink>
                        </span>
                    </BodyCell>
                </tr>
            </tbody>
        </BaseTable>
    </div>
</template>

<script>
export default {
    data() {
        return {
            customers: {},
            loading: false,
            hasMore: false,
            page: 1,
        }
    },
    methods: {
        listCustomers(params) {
            this.loading = true

            Nova.request()
                .get('/nova-vendor/nova-stripe/stripe/customers', { params })
                .then((response) => {
                    this.customers = response.data.customers.data
                    this.hasMore = response.data.customers.has_more
                    this.loading = false
                })
        },
        nextPage() {
            this.listCustomers({
                starting_after: this.customers[this.customers.length - 1].id,
            })
        },
        previousPage() {
            this.listCustomers({ ending_before: this.customers[0].id })
        },
    },
    created() {
        this.listCustomers()
    },
}
</script>
