import moneyFormat from './utils/moneyFormat.js'

Nova.booting((Vue) => {
    const files = require.context('./', true, /\.vue$/i)
    files
        .keys()
        .map((key) =>
            Vue.component(
                key.split('/').pop().split('.')[0],
                files(key).default
            )
        )

    Vue.config.globalProperties.$filters = {
        money(currency, value) {
            return moneyFormat(currency, value)
        },
        date(value) {
            return new Date(value * 1000).toLocaleString()
        },
    }

    Nova.inertia('Tool', require('./pages/Tool').default)
    Nova.inertia('Detail', require('./pages/Detail').default)
    Nova.inertia('Customers', require('./pages/Customers').default)
    Nova.inertia('CustomerDetail', require('./pages/CustomerDetail').default)
})
