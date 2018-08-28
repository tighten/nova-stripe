Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'nova-stripe',
            path: '/nova-stripe',
            component: require('./components/Tool'),
        },
    ])
})
