import {
    USER_REQUEST,
    USER_SUCCESS,
    USER_ERROR,
    UNREAD_MESSAGES_REQUEST,
    AUTH_LOGOUT,
    AUTH_DESTROY,
    INCREMENT_UNREAD_MESSAGES, SET_UNREAD_MESSAGES, LISTEN_MESSAGE_ARRIVE
} from "../actions";

export default {
    state: {
        user: {},
        status: '',
        totalUnreadMessages: 0,
    },

    getters: {
        user(state) {
            return state.user;
        },
        isUserLoaded(state) {
            return !!state.user.slug;
        },
        unreadMessages(state) {
            return state.totalUnreadMessages;
        }
    },

    mutations: {
        [USER_REQUEST]: (state) => {
            state.status = 'loading';
        },
        [USER_SUCCESS]: (state, response) => {
            state.status = 'success';
            state.user = response.data;
        },
        [USER_ERROR]: (state) => {
            state.status = 'error';
        },
        [AUTH_LOGOUT]: (state) => {
            state.user = {};
            state.status = '';
        },
        [UNREAD_MESSAGES_REQUEST]: (state, unreadMessages) => {
            state.totalUnreadMessages = unreadMessages;
        },
        [INCREMENT_UNREAD_MESSAGES]: (state) => {
            state.totalUnreadMessages++;
        },
        [SET_UNREAD_MESSAGES]: (state, unreadMessages) => {
            state.totalUnreadMessages = unreadMessages;
        }
    },

    actions: {
        [USER_REQUEST]: ({commit, dispatch}) => {
            return new Promise((resolve, reject) => {
                commit(USER_REQUEST);
                axios.post('/api/me')
                    .then(response => {
                        commit(USER_SUCCESS, response);
                        resolve();
                    })
                    .catch(response => {
                        commit(USER_ERROR);
                        dispatch(AUTH_DESTROY);
                        reject();
                    })
            })
        },
        [UNREAD_MESSAGES_REQUEST]: ({commit}) => {
            return new Promise((resolve, reject) => {
                axios.get('/api/total-unread')
                    .then(response => {
                        commit(UNREAD_MESSAGES_REQUEST, response.data.totalUnreadMessages);
                        resolve();
                    })
                    .catch(error => {
                        reject();
                    })
            })
        },

        [SET_UNREAD_MESSAGES]: ({commit}, unreadMessages) => {
            commit(SET_UNREAD_MESSAGES, unreadMessages);
        },
        [LISTEN_MESSAGE_ARRIVE]: ({getters, commit}) => {
            Echo.private('App.Model.User.' + getters.user.slug)
                .listen('MessageArrived', (event) => {
                    if (event.arrived)
                        commit(INCREMENT_UNREAD_MESSAGES);
                })
        }
    }
}
