import vClickOutside from 'v-click-outside'
import { VTooltip } from 'v-tooltip'
import Filters from './filters';
import NebulaSenderService from "./services/NebulaSenderService";

Nova.booting((Vue, router, store) => {
    Vue.use(vClickOutside);
    Vue.directive('tooltip', VTooltip, {
        defaultClass: 'nebula-sender',
    })

    Vue.filter('limit', Filters.limit);
    Vue.filter('stripped', Filters.stripped);

    // Sets page title
    const BASE_TITLE = document.title;
    router.afterEach((to, from) => {
        Vue.nextTick(() => {
            document.title = `${to.meta.title} | ${BASE_TITLE}`;
        });
    });

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
            },
            meta: { title: 'Custom Email Sender' }
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
                    component: require('./components/NebulaSender/views/Home'),
                    meta: { title: 'Nebula Sender' }
                },
                {
                    name: 'nebula-sender-drafts',
                    path: '/custom-email-sender/nebula-sender/drafts',
                    component: require('./components/NebulaSender/views/Drafts'),
                    meta: { title: 'Drafts' }
                },
                {
                    name: 'nebula-sender-drafts-edit',
                    path: '/custom-email-sender/nebula-sender/drafts/:id',
                    component: require('./components/NebulaSender/views/DraftEdit'),
                    meta: { title: 'Edit Draft' }
                },
                {
                    name: 'nebula-sender-new',
                    path: '/custom-email-sender/nebula-sender/new',
                    component: require('./components/NebulaSender/views/NewMessage'),
                    meta: { title: 'New Message' }
                },
                {
                    name: 'nebula-sender-sent',
                    path: '/custom-email-sender/nebula-sender/sent-messages',
                    component: require('./components/NebulaSender/views/SentMessages'),
                    meta: { title: 'Sent Messages' }
                },
            ]
        },
    ])
})
