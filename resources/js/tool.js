Nova.booting((Vue, router) => {
    Vue.config.devtools = true
    router.addRoutes([
        {
            name: 'nova-stripe',
            path: '/nova-stripe',
            component: require('./views/StripeIndex'),
        },
    ])
})
