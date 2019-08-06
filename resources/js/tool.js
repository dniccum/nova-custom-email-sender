import vClickOutside from 'v-click-outside'

Nova.booting((Vue, router, store) => {
    Vue.use(vClickOutside);

    router.addRoutes([
        {
            name: 'custom-email-sender',
            path: '/custom-email-sender',
            component: require('./components/Tool'),
        },
    ])
})
