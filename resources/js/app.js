/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


/**
 * for front end Vue component authorization
 */
let authorizations = require('./authorizations');

window.Vue.prototype.authorize = function(...params) {

    if(! window.App.signedIn) return false;

    if(typeof params[0] === 'string') {
        return authorizations[params[0]](params[1]);
    }

    // otherwise, first param is a callback
    return params[0](window.App.user);

    //Callback only implementation
    // let user = window.App.user;
    // return user ? handler(user) : false;
}

Vue.prototype.signedIn = window.App.signedIn;

// import VueInstantSearch from 'vue-instantsearch';
// Vue.use(VueInstantSearch);

import VueInstantSearch from 'vue-instantsearch';
Vue.use(VueInstantSearch);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('thread-view', require('./pages/Thread.vue').default);
Vue.component('paginator', require('./components/Paginator.vue').default);
Vue.component('user-notifications', require('./components/UserNotifications.vue').default);
Vue.component('avatar-form', require('./components/AvatarForm.vue').default);
Vue.component('algolia-search', require('./components/AlgoliaSearch.vue').default);
Vue.component('wysiwyg', require('./components/Wysiwyg.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
window.events = new Vue();
window.flash = function (message, level) {
    window.events.$emit('flash', {message, level}); 
};

const app = new Vue({
    el: '#app',
});
