<template>
    <loading-view :loading="initialLoading">
        <loading-card :loading="loading" class="card relative">
            <table
                    v-if="charges.length > 0"
                    class="table w-full"
                    cellpadding="0"
                    cellspacing="0"
                    data-testid="resource-table"
            >
                <thead>
                <tr>
                    <th class="text-left">
                      <span class="inline-flex items-center">
                         Charge ID
                      </span>
                    </th>
                    <th class="text-left">
                      <span class="inline-flex items-center">
                         Amount
                      </span>
                    </th>
                    <th class="text-left">
                      <span class="inline-flex items-center">
                         Created
                      </span>
                    </th>
                    <th class="text-left">
                      <span class="inline-flex items-center">
                         Status
                      </span>
                    </th>
                    <th>&nbsp;<!-- View --></th>
                </tr>
                </thead>

                <tbody v-for="charge in charges">
                <tr>
                    <td>{{ charge.id }}</td>
                    <td>{{ charge.currency | money(charge.amount) }}</td>
                    <td>{{ charge.created | date }}</td>
                    <td>{{ charge.status }}</td>
                    <td>
                        <span>
                            <router-link
                                    class="cursor-pointer text-70 hover:text-primary mr-3"
                                    :to="{ name: 'charge-detail', params: {
                                        chargeId: charge.id
                                    }}"
                                    :title="__('View')"
                            >
                                <icon type="view" width="22" height="18" view-box="0 0 22 16" />
                            </router-link>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>

            <charges-pagination-links
                    :charges="charges"
                    :hasMore="hasMore"
                    :hasPrevious="hasPrevious"
                    @previous="previousPage"
                    @next="nextPage"
            ></charges-pagination-links>
        </loading-card>
    </loading-view>
</template>

<script>
import ChargesPaginationLinks from './ChargesPaginationLinks.vue';
import money from '../utils/moneyFormat';

export default {
    components: {
        'charges-pagination-links': ChargesPaginationLinks
    },

    data() {
        return {
            charges: {},
            initialLoading: true,
            loading: false,
            hasMore: false,
            page: 1,
        }
    },

    methods: {
        moment: moment,

        listCharges(params) {
            Nova.request().get('/nova-vendor/nova-stripe/stripe/charges', { params })
                .then((response) => {
                    this.charges = response.data.charges.data
                    this.hasMore = response.data.charges.has_more
                    this.initialLoading = false
                    this.loading = false
                })
        },

        nextPage() {
            this.loading = true

            this.listCharges({ 'starting_after': this.charges[this.charges.length - 1].id })

            this.page++
        },

        previousPage() {
            this.loading = true

            this.listCharges({ 'ending_before': this.charges[0].id })

            if (this.hasPrevious) {
                this.page--
            }
        }
    },

    computed: {
        hasPrevious() {
            return this.page > 1
        }
    },

    filters: {
        date(date) {
            return moment.unix(date).format('YYYY/MM/DD h:mm:ss a')
        },

        money
    },

    created() {
        this.listCharges()
    },
}
</script>

<style>
    /* Scoped Styles */
</style>
