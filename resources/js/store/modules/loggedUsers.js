import {LOGGED_USERS, JOINED_USER, LEFT_USER} from "../actions";

export default {
    state: {
        users: []
    },

    getters: {

    },

    mutations: {
        [LOGGED_USERS]: (state, users) => {
            state.users = users;
        },
        [JOINED_USER]: (state, user) => {
            state.users.push(user);
        },
        [LEFT_USER]: (state, user) => {
            state.users.splice(user);
        }
    },

    actions: {
        [LOGGED_USERS]: ({getters, commit}) => {
            Echo.join('loggedUser')
                .here((users) => {
                    console.log(users);
                    commit(LOGGED_USERS, users);
                })
                .joining((user) => {
                    console.log(user);
                    commit(JOINED_USER, user)
                })
                .leaving((user) => {
                    commit(LEFT_USER, user);
                    console.log(user);
                });

        }
    }
}