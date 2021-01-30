import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import axios from 'axios'

window.Vue = require('vue').default;
// Make BootstrapVue available throughout  project
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
// Make Axios available throughout  project
Vue.prototype.$http = axios

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

const app = new Vue({
    el: '#app',
});
