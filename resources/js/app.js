require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import { Form, HasError, AlertError } from 'vform';
import moment from 'moment';
import VueProgressBar from 'vue-progressbar'
import Swal from 'sweetalert2'

window.Form = Form;
window.Swal = Swal;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

window.Toast = Toast

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

Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '2px'
})

const app = new Vue({
    el: '#app',
    router
}).$mount('#app');