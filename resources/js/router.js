import VueRouter from 'vue-router';
import UserType from './components/UserType';
import Corporate from './components/Corporate';
import Individual from './components/Individual';
import NotFound from './components/NotFound';
import About from './components/About';

const routes = [
    { path: '/advertiser/corporate', name: 'corporate', component: Corporate },
    { path: '/advertiser/individual', name: 'individual', component: Individual },
    { path: '/about', name: 'about', component: About },
    { path: '*', name: 'notfound-404', component: NotFound },
];

const router = new VueRouter({ 
    routes,
    mode: 'history'
});

export default router;