import vClickOutside from 'v-click-outside'
import VTooltip from 'v-tooltip'
import Filters from './filters';
import NebulaSenderService from "./services/NebulaSenderService";

Nova.booting((Vue, router, store) => {
    Vue.use(vClickOutside);
    Vue.use(VTooltip, {
        defaultClass: 'nebula-sender',
    });

    Vue.filter('limit', Filters.limit);
    Vue.filter('stripped', Filters.stripped);

    router.addRoutes([
        {
            name: 'custom-email-sender',
            path: '/custom-email-sender',
            component: require('./components/Tool'),
            beforeEnter: (to, from, next) => {
                if (NebulaSenderService.active) {
                    next({ path: '/custom-email-sender/nebula-sender' });
                }

                next();
            }
        },
        {
            path: '/custom-email-sender/nebula-sender',
            component: require('./components/NebulaSender/NebulaSenderLayout'),
            beforeEnter: (to, from, next) => {
                if (!NebulaSenderService.active) {
                    next({ path: '/custom-email-sender' });
                }

                next();
            },
            children: [
                {
                    name: 'nebula-sender',
                    path: '/',
                    component: require('./components/NebulaSender/views/Home')
                },
                {
                    name: 'nebula-sender-drafts',
                    path: '/custom-email-sender/nebula-sender/drafts',
                    component: require('./components/NebulaSender/views/Drafts')
                },
                {
                    name: 'nebula-sender-drafts-edit',
                    path: '/custom-email-sender/nebula-sender/drafts/:id',
                    component: require('./components/NebulaSender/views/DraftEdit')
                },
                {
                    name: 'nebula-sender-new',
                    path: '/custom-email-sender/nebula-sender/new',
                    component: require('./components/NebulaSender/views/NewMessage')
                },
                {
                    name: 'nebula-sender-sent',
                    path: '/custom-email-sender/nebula-sender/sent-messages',
                    component: require('./components/NebulaSender/views/SentMessages')
                },
            ]
        },
    ])
})
