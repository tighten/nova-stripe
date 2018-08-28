<template>
    <loading-view :loading="initialLoading">
        <div class="card relative">
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
                         Payment ID
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
                    <th>&nbsp;<!-- View --></th>
                </tr>
                </thead>

                <tbody v-for="charge in charges">
                <tr>
                    <td>{{ charge.id }}</td>
                    <td>{{ (charge.amount  / 100).toFixed(2) }} {{ charge.currency }}</td>
                    <td>{{ charge.created | date }}</td>
                    <td>
                        <span>
                            <router-link
                                    class="cursor-pointer text-70 hover:text-primary mr-3"
                                    :to="{ name: 'detail', params: {
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

            <charges-pagination-links charges="this.charges"></charges-pagination-links>
        </div>
    </loading-view>
</template>

<script>
import ChargesPaginationLinks from './ChargesPaginationLinks.vue';

export default {
    components: {
        'charges-pagination-links': ChargesPaginationLinks
    },

    data() {
        return {
            charges: {},
            initialLoading: true,
        }
    },

    methods: {
        moment: moment,

        listCharges() {
            Nova.request().get('/nova-vendor/nova-stripe/stripe/charges')
                .then((response) => {
                    this.charges = response.data.charges.data
                    this.initialLoading = false
                });
        }
    },

    filters: {
        date(date) {
            return moment.unix(date).format('YYYY/MM/DD h:mm:ss a')
        }
    },

    created() {
        this.listCharges()
    },
}
</script>

<style>
    /* Scoped Styles */
</style>
