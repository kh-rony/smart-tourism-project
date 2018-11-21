import {
    AUTH_REQUEST,
    AUTH_SUCCESS,
    AUTH_ERROR,
    AUTH_LOGOUT,
    AUTH_VALIDATE,
    AUTH_DESTROY,
    USER_REQUEST, UNREAD_MESSAGES_REQUEST, LISTEN_MESSAGE_ARRIVE, SET_AUTH_TOKEN
} from "../actions";

export default {
    state: {
        token: localStorage.getItem('user-auth-token'),
        status: ''
    },

    getters: {
        isAuthenticated(state) {
            return !!state.token;
        },
        authStatus(state) {
            return state.status;
        },
        authToken(state) {
            return state.token;
        }
    },

    mutations: {
        [AUTH_REQUEST]: (state) => {
            state.status = 'loading';
        },
        [AUTH_SUCCESS]: (state, token) => {
            state.status = 'success';
            state.token = token;
        },
        [AUTH_ERROR]: (state, error) => {
            state.status = 'error';
        },
        [AUTH_LOGOUT]: (state) => {
            state.status = '';
            state.token = null;
        },
        [SET_AUTH_TOKEN]: (state, token) => {
            state.token = token;
        }
    },

    actions: {
        [AUTH_REQUEST]: ({commit, dispatch}, credentials) => {
            return new Promise((resolve, reject) => {
                commit(AUTH_REQUEST);
                axios.post('/api/login', credentials)
                    .then(response => {
                        localStorage.setItem('user-auth-token', response.data.access_token);
                        window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.access_token;
                        window.Echo.connector.pusher.config.auth.headers['Authorization'] = 'Bearer ' + response.data.access_token;
                        commit(AUTH_SUCCESS, response.data.access_token);
                        dispatch(USER_REQUEST)
                            .then(() => {
                                    dispatch(UNREAD_MESSAGES_REQUEST);
                                    dispatch(LISTEN_MESSAGE_ARRIVE);
                                }
                            );
                        resolve(response)
                    })
                    .catch(error => {
                        commit(AUTH_ERROR, error);
                        localStorage.removeItem('user-auth-token');
                        reject(error);
                    });
            })
        },
        [AUTH_LOGOUT]: ({commit}) => {
            return new Promise((resolve, reject) => {
                commit(AUTH_LOGOUT);
                axios.post('/api/logout')
                    .then(response => {
                        console.log(response);
                        window.axios.defaults.headers.common['Authorization'] = null;
                        localStorage.removeItem('user-auth-token');
                        resolve();
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error);
                    })


            })
        },
        [AUTH_VALIDATE]: ({state, getters, commit, dispatch}) => {
            return new Promise((resolve, reject) => {
                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + state.token;
                window.Echo.connector.pusher.config.auth.headers['Authorization'] = 'Bearer ' + state.token;
                dispatch(USER_REQUEST)
                    .then(response => {
                        commit(AUTH_SUCCESS, state.token);
                        resolve('Auth validated');
                    }, error => {
                        reject('Auth invalidated');
                    });
            })
        },
        [AUTH_DESTROY]: ({commit}) => {
            return new Promise(resolve => {
                commit(AUTH_LOGOUT);
                window.axios.defaults.headers.common['Authorization'] = null;
                window.Echo.connector.pusher.config.auth.headers['Authorization'] = null;
                localStorage.removeItem('user-auth-token');
                resolve();
            })
        },
        [SET_AUTH_TOKEN]: ({commit}, access_token) => {
            return new Promise(resolve => {
                localStorage.setItem('user-auth-token', access_token);
                commit(SET_AUTH_TOKEN, access_token);
                resolve('Token saved');
            })
        }
    }
}
