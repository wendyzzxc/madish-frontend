import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

function importComponent(path) {
    return() => import(`./components/${path}.vue`);
}

const router = new VueRouter({
    mode: 'history',
    routes: [

        {
            path: '/',
            name: 'Index',
            meta: { title: 'Madish Web'},
            component: importComponent('Index'),
            children: [
                {
                    path: '/',
                    name: 'Dashboard',
                    meta: { title: 'Madish Web'},
                    component: importComponent('Dashboard'),
                },
            ]
        },

        //route halaman admin
        {
            path: "/dasboardAdmin",
            name: 'DashboardLayoutAdmin',
            component: importComponent('BarsAdmin'),
            children: [
                {
                    path: '/Amenu',
                    name: 'Menu',
                    meta: { title: 'Menu' },
                    component: importComponent('Admin/Menu'),
                },
                {
                    path: '/Aprofil',
                    name: 'Profil',
                    meta: { title: 'Profil' },
                    component: importComponent('Admin/Profil'),
                },
                {
                    path: '/Areservation',
                    name: 'Reservation',
                    meta: { title: 'Reservation' },
                    component: importComponent('Admin/Reservation'),
                },
            ],
        },

        {
            path: "/dashboardCustomer",
            name: 'DashboardLayoutCustomer',
            component: importComponent('BarsCustomer'),
            children: [
                {
                    path: '/Cmenu',
                    name: 'Menu',
                    meta: { title: 'Menu' },
                    component: importComponent('Customer/Menu'),
                },
                {
                    path: '/Cprofil',
                    name: 'Profil',
                    meta: { title: 'Profil' },
                    component: importComponent('Customer/Profil'),
                },
                {
                    path: '/Creservation',
                    name: 'Reservation',
                    meta: { title: 'Reservation' },
                    component: importComponent('Customer/Reservation'),
                },
            ],
        },

        {
            path: '/login',
            name: 'Login',
            meta: { title: 'Login'},
            component: importComponent('Login'),
        },

        {
            path: '/register',
            name: 'Register',
            meta: { title: 'Register'},
            component: importComponent('Register'),
        },
    ],
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    next();
});

export default router;