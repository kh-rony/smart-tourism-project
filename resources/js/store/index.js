import Vue from 'vue'
import Vuex from 'vuex'
import User from './modules/user'
import Auth from './modules/auth'
import LoggedUsers from './modules/loggedUsers'

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        User,
        Auth,
        LoggedUsers
    },
    // strict: debug
});
