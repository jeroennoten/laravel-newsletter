import Vue from 'vue';
import './bootstrap';
import 'babel-polyfill'

import AddManyMembers from './components/AddManyMembers.vue';
import AddMembersFile from './components/AddMembersFile.vue';
import AddMembers from './components/AddMembers.vue';

Vue.component('addManyMembers', AddManyMembers);
Vue.component('addMembersFile', AddMembersFile);
Vue.component('addMembers', AddMembers);

new Vue({
    el: 'body'
});
