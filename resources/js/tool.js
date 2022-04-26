import vClickOutside from "click-outside-vue3"
import NebulaSenderService from "./services/NebulaSenderService";
import {
    // Directives
    VTooltip,
} from 'floating-vue'

import Tool from './pages/Tool'

Nova.booting((Vue, store) => {
    Vue.use(vClickOutside);
    Vue.directive('tooltip', VTooltip, {
        defaultClass: 'nebula-sender',
    })

    Nova.inertia('CustomEmailSender', Tool)
})
/*
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
            component: require('./components/Tool.vue'),
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
            component: require('./components/NebulaSender/NebulaSenderLayout.vue'),
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
                    component: require('./components/NebulaSender/views/Home.vue'),
                    meta: { title: 'Nebula Sender' }
                },
                {
                    name: 'nebula-sender-drafts',
                    path: '/custom-email-sender/nebula-sender/drafts',
                    component: require('./components/NebulaSender/views/Drafts.vue'),
                    meta: { title: 'Drafts' }
                },
                {
                    name: 'nebula-sender-drafts-edit',
                    path: '/custom-email-sender/nebula-sender/drafts/:id',
                    component: require('./components/NebulaSender/views/DraftEdit.vue'),
                    meta: { title: 'Edit Draft' }
                },
                {
                    name: 'nebula-sender-new',
                    path: '/custom-email-sender/nebula-sender/new',
                    component: require('./components/NebulaSender/views/NewMessage.vue'),
                    meta: { title: 'New Message' }
                },
                {
                    name: 'nebula-sender-sent',
                    path: '/custom-email-sender/nebula-sender/sent-messages',
                    component: require('./components/NebulaSender/views/SentMessages.vue'),
                    meta: { title: 'Sent Messages' }
                },
            ]
        },
    ])
})
*/