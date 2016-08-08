import Vue from 'vue';
import './bootstrap';
import 'babel-polyfill'

import AddManyMembers from './components/AddManyMembers.vue';
import AddMembersFile from './components/AddMembersFile.vue';

Vue.component('addManyMembers', AddManyMembers);
Vue.component('addMembersFile', AddMembersFile);

new Vue({
    el: 'body'
});
