<template>
    <div>
        <BaseTable
            :data="charges"
            model-name="charges"
            :loading="loading"
            :has-more="hasMore"
            :get-previous-page="previousPage"
            :get-next-page="nextPage"
            @page-updated="page = $event"
        >
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <!-- Id, Amount, Created date, Status-->
                    <HeaderCell v-if="columns" v-for="column in columns">
                        {{ __(column.replaceAll('_', ' ')) }}
                    </HeaderCell>
                    <HeaderCell>&nbsp;<!-- View --></HeaderCell>
                </tr>
            </thead>
            <tbody>
                <tr class="group" v-for="charge in charges">
                    <BodyCell v-for="column in columns">
                        <span
                            v-if="
                                moneyColumns.find(
                                    (moneyColumn) => moneyColumn === column
                                )
                            "
                        >
                            {{
                                $filters.money(charge.currency, charge[column])
                            }}
                        </span>
                        <span
                            v-else-if="
                                dateColumns.find(
                                    (dateColumn) => dateColumn === column
                                )
                            "
                        >
                            {{ $filters.date(charge[column]) }}
                        </span>
                        <span
                            v-else-if="column === 'status'"
                            class="rounded-lg px-3 py-1 capitalize text-xs font-bold"
                            :class="statusClass(charge)"
                        >
                            {{ statusLabel(charge) }}
                        </span>
                        <span v-else>{{ charge[column] }}</span>
                    </BodyCell>
                    <BodyCell>
                        <span>
                            <InertiaLink
                                :href="`${novaPath}/nova-stripe/charges/${charge.id}`"
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
    props: {
        columns: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            charges: {},
            loading: false,
            hasMore: false,
            moneyColumns: [
                'amount',
                'amount_captured',
                'amount_refunded',
                'application_fee_amount',
            ],
            dateColumns: ['created'],
            statusClassList: {
                succeeded: 'ns-bg-green-100 ns-text-green-600',
                pending: 'ns-bg-gray-100 ns-text-gray-600',
                disputed: 'ns-bg-orange-100 ns-text-orange-600',
                failed: 'ns-bg-red-100 ns-text-red-600',
            },
            page: 1,
            novaPath: Nova.config('path').replace(/\/$/, ''),
        }
    },
    methods: {
        listCharges(params) {
            this.loading = true

            Nova.request()
                .get('/nova-vendor/nova-stripe/stripe/charges', { params })
                .then((response) => {
                    this.charges = response.data.charges.data
                    this.charges.length > 0
                        ? this.$emit('charge', this.charges[0])
                        : ''
                    this.hasMore = response.data.charges.has_more
                    this.loading = false
                })
        },
        nextPage() {
            this.listCharges({
                starting_after: this.charges[this.charges.length - 1].id,
            })
        },
        previousPage() {
            this.listCharges(
                this.page !== 2 ? { ending_before: this.charges[0].id } : {}
            )
        },
        statusClass(charge) {
            let status = charge.status

            if (charge.refunded) {
                status = 'pending'
            } else if (charge.disputed) {
                status = 'disputed'
            }
            return this.statusClassList[status]
        },
        statusLabel(charge) {
            let status = charge.status

            if (charge.refunded) {
                status = 'Refunded'
            } else if (charge.disputed) {
                status = 'Disputed'
            }
            return status
        },
    },
    created() {
        this.listCharges()
    },
}
</script>
