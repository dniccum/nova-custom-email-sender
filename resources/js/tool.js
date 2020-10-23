import vClickOutside from 'v-click-outside'
import NebulaSenderService from "./services/NebulaSenderService";

Nova.booting((Vue, router, store) => {
    Vue.use(vClickOutside);

    router.addRoutes([
        {
            name: 'custom-email-sender',
            path: '/custom-email-sender',
            component: require('./components/Tool'),
            beforeEnter: (to, from, next) => {
                if (NebulaSenderService.active) {
                    this.$router.replace('/custom-email-sender/nebula-sender');
                }

                next();
            }
        },
        {
            name: 'nebula-sender',
            path: '/custom-email-sender/nebula-sender',
            component: require('./components/NebulaSender/NebulaSenderView'),
            beforeEnter: (to, from, next) => {
                if (!NebulaSenderService.active) {
                    this.$router.replace('/custom-email-sender');
                }

                next();
            }
        },
    ])
})
