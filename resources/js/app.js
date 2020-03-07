require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import { Form, HasError, AlertError } from 'vform';
import moment from 'moment';

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.use(VueRouter);

const routes = [
  { path: '/dashboard', component: require('./components/DashboardComponent.vue').default },
  { path: '/users', component: require('./components/UsersComponent.vue').default },
  { path: '/profile', component: require('./components/ProfileComponent.vue').default },
]

const router = new VueRouter({
	mode: 'history',
  	routes
});


Vue.filter('capitalize', function (value) {
  if (!value) return ''
  value = value.toString()
  return value.charAt(0).toUpperCase() + value.slice(1)
});

Vue.filter('humanDate', function (value) {
	return moment(value).format("MMM Do YY");
});

const app = new Vue({
    el: '#app',
    router
}).$mount('#app');