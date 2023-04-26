require('./bootstrap');

import store from './vuex/store'
import moment from 'moment'
import VueSweetalert2 from "vue-sweetalert2";

window.Vue = require('vue').default;

/*
 * 
 * Components
 *  
 * */
import "sweetalert2/dist/sweetalert2.min.css";
Vue.use(VueSweetalert2);

import NotificationsComponent from './components/Notifications/Notifications.vue';
Vue.component('notifications', NotificationsComponent);

import NotificationComponent from './components/Notifications/Notification.vue';
Vue.component('notification', NotificationComponent);

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD/MM hh:mm')
    }
});

const app = new Vue({
    store,
    el: '#app',
});
