
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import 'vue-directive-tooltip/css/index.css';

//import dashboardNavigation from './components/DashboardNavigation.vue';
import SiteNavigation from './components/SiteNavigation.vue';
import modalTrigger from './components/ModalTrigger.vue';
import providerFilters from './components/ProviderFilters.vue';
import flashMessages from './components/FlashMessages.vue';
//import task from './components/Task.vue';
//import taskList from './components/TaskList.vue';
//import allTasksList from './components/AllTasksList.vue';
import ratings from './components/Ratings.vue';
import ratingInput from './components/RatingInput.vue';
//import dashboardIndex from './components/DashboardIndex.vue';
//import progressBar from './components/ProgressBar.vue';
//import progressIcon from './components/ProgressIcon.vue';
//import Tooltip from 'vue-directive-tooltip';
import PortalVue from 'portal-vue';
//import dateFormat from 'dateformat';


//dateFormat.masks.default = 'mm/dd/yyyy';

/*Vue.mixin({
  methods: {
    dateFormat
  }
});*/

//Vue.use(Tooltip);
Vue.use(PortalVue);

const app = new Vue({
  el: '#app',

  components: {
    // progressIcon,
    // progressBar,
    // dashboardNavigation,
    ratings,
    ratingInput,
    flashMessages,
    // task,
    // taskList,
    // allTasksList,
    modalTrigger,
    'site-navigation': SiteNavigation,
    providerFilters,
    //dashboardIndex
  }
});
