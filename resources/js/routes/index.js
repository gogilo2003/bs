import Vue from "vue";
import VueRouter from "vue-router";
import LoginVue from "../pages/Login.vue";
import ReadingsVue from "../pages/Readings.vue";
import Reading from "../pages/Reading.vue";

Vue.use(VueRouter)

const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            component: ReadingsVue,
            meta: {
                auth: true
            }
        },
        {
            path: "/reading",
            component: Reading,
            meta: {
                auth: true
            }
        },
        {
            path: "/login",
            component: LoginVue,
            meta: {
                guest: true
            }
        }
    ]
})

let token = localStorage.getItem('token')
router.beforeEach((to, from, next) => {
    if (to.meta.auth) {
        if (token) {
            return next()
        } else {
            return next('login')
        }
    }
    return next()
})

export default router
