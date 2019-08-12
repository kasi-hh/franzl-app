import Vue from 'vue'
import VueRouter from 'vue-router'

import routes from './routes'
import {LocalStorage} from 'quasar'

Vue.use(VueRouter)

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation
 */

export default function (/* { store, ssrContext } */) {
    const router = new VueRouter({
        scrollBehavior: () => ({x: 0, y: 0}),
        routes,

        // Leave these as is and change from quasar.conf.js instead!
        // quasar.conf.js -> build -> vueRouterMode
        // quasar.conf.js -> build -> publicPath
        mode: process.env.VUE_ROUTER_MODE,
        base: process.env.VUE_ROUTER_BASE
    });
    router.beforeEach((to, from, next) => {
        /*
        console.log(to, from)
        if (to.path === '/settings' ||  to.path === '/camera' || to.path === '/post'){
            next();
            return;
        }
         */

        if (!LocalStorage.has('token') && to.path !== '/login') {
            next('/login')
        } else {
            next();
        }
    });
    return router
}
