Nova.booting((Vue, router) => {
    Vue.config.devtools = true

    router.addRoutes([
        {
            name: 'nova-stripe',
            path: '/nova-stripe',
            component: require('./views/Index'),
        },
        {
            name: 'charge-detail',
            path: '/nova-stripe/charge/:chargeId',
            component: require('./views/Detail'),
            props: true,
        },
    ])
})
