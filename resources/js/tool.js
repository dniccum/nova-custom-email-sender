Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'custom-email-sender',
            path: '/custom-email-sender',
            component: require('./components/Tool'),
        },
    ])
})
