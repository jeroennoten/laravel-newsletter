import Vue from 'vue';
import './bootstrap';
import 'babel-polyfill'

import AddManyMembers from './components/AddManyMembers.vue';
import AddMembersFile from './components/AddMembersFile.vue';
import AddMembers from './components/AddMembers.vue';
import MemberList from './components/MemberList.vue';
import DeleteButton from './components/DeleteButton.vue';

Vue.component('addManyMembers', AddManyMembers);
Vue.component('addMembersFile', AddMembersFile);
Vue.component('addMembers', AddMembers);
Vue.component('memberList', MemberList);
Vue.component('deleteButton', DeleteButton);

new Vue({
    el: 'body',
});
