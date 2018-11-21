<template>
    <div>
        <v-navigation-drawer
                class="grey lighten-3"
                width="250"
                fixed
                clipped
                v-model="drawer"
                app
        >
            <v-list dense>
                <v-list-tile class="mt-3" to="/tourpackage">
                    <v-list-tile-action>
                        <v-icon>trending_up</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Tour Package</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile class="mt-3" v-if="authSuccess" to="/tourplanner">
                    <v-list-tile-action>
                        <v-icon>trending_up</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Tour Planner</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile class="mt-3" to="/forum">
                    <v-list-tile-action>
                        <v-icon>trending_up</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Forum</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile class="mt-3" to="/blog">
                    <v-list-tile-action>
                        <v-icon>trending_up</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Blog</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar
                color="deep-orange darken-1"
                dense
                fixed
                clipped-left
                app
        >
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <router-link to="/">
                <v-toolbar-title class="white--text">Smart Tourism</v-toolbar-title>
            </router-link>
            <!--<v-toolbar-items class="hidden-sm-and-down">
                <v-btn flat class="white&#45;&#45;text" to="/tourpackage">Tour Package</v-btn>
                <v-btn flat class="white&#45;&#45;text" v-if="authSuccess" to="/tourplanner">Tour Planner</v-btn>
                <v-btn flat class="white&#45;&#45;text" to="/forum">Forum</v-btn>
                <v-btn flat class="white&#45;&#45;text" to="/blog">Blog</v-btn>
            </v-toolbar-items>-->
            <v-spacer></v-spacer>
            <v-btn flat class="white--text" v-if="authSuccess" to="/message">
                <v-badge color="cyan" v-if="unreadMessages" right>
                    <span slot="badge">{{ unreadMessages }}</span>
                    <v-icon>message</v-icon>
                </v-badge>
                <v-icon v-else>message</v-icon>
            </v-btn>
            <v-btn flat class="white--text" v-if="!authSuccess" @click="showAuthForm">Log In</v-btn>
            <v-menu v-else
                    open-on-hover
                    bottom
                    offset-y
            >
                <v-btn
                        flat class="white--text"
                        slot="activator"
                >
                    <v-avatar size="36px"><img :src="`https://randomuser.me/api/portraits/men/${authUser.id % 25}.jpg`">
                    </v-avatar>
                    <v-icon>keyboard_arrow_down</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile :to="'/profile'">
                        <v-list-tile-action>
                            <v-icon>person</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-title>Profile</v-list-tile-title>
                    </v-list-tile>
                    <v-divider color="orange"></v-divider>
                    <v-list-tile @click="logout">
                        <v-list-tile-action>
                            <v-icon>keyboard_tab</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-title>Logout</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
        </v-toolbar>
        <app-auth @close="close" @change="change" @message="setSnackBarText"
                  :active="active"
                  :login="login"
                  :stylesection="styleSection"
                  :socialAuth="socialAuth"
                  :passwordReset="passwordReset"
        ></app-auth>

        <v-snackbar
                v-model="snackbar"
                :bottom="y === 'bottom'"
                :left="x === 'left'"
                :multi-line="mode === 'multi-line'"
                :right="x === 'right'"
                :timeout="timeout"
                :top="y === 'top'"
                :vertical="mode === 'vertical'"
        >
            {{ text }}
            <v-btn
                    color="pink"
                    flat
                    @click="snackbar = false"
            >
                Close
            </v-btn>
        </v-snackbar>

    </div>

</template>

<script>
    import AppAuth from './AppAuth'
    import {mapGetters} from 'vuex'
    import {AUTH_LOGOUT, INCREMENT_UNREAD_MESSAGES} from "../store/actions";

    export default {
        name: "app-toolbar",
        data() {
            return {
                active: false,
                login: true,
                styleSection: {
                    display: 'none',
                },
                drawer: true,
                socialAuth: false,
                passwordReset: false,

                snackbar: false,
                y: 'top',
                x: null,
                mode: '',
                timeout: 10000,
                text: ''
            }
        },
        components: {
            'app-auth': AppAuth,
        },
        created() {
            this.socialRegistrationCheck();
            this.passwordResetCheck();
            this.messageCheck();
        },
        computed: {
            ...mapGetters({
                authUser: 'user',
                authStatus: 'authStatus',
                unreadMessages: 'unreadMessages',
            }),
            authSuccess() {
                return this.authStatus === 'success';
            },
        },
        methods: {
            showAuthForm() {
                this.active = true;
                this.login = true;
                this.styleSection.display = 'block';
            },
            close() {
                this.active = false;
                this.styleSection.display = 'none';
                this.socialAuth = false;
                this.passwordReset = false;
            },
            change() {
                this.login = !this.login;
            },
            logout() {
                this.$store.dispatch(AUTH_LOGOUT);
                this.$router.push('/');
            },
            socialRegistrationCheck() {
                if (window.verified === '0') {
                    this.socialAuth = true;
                    this.active = true;
                    this.login = false;
                    this.styleSection.display = 'block';
                }
            },
            passwordResetCheck() {
                if (window.reset_token) {
                    this.passwordReset = true;
                    this.active = true;
                    this.login = true;
                    this.styleSection.display = 'block';
                }
            },
            setSnackBarText(text) {
                this.text = text;
                this.snackbar = true;
            },
            messageCheck() {
                if (window.msg) {
                    this.text = window.msg;
                    this.snackbar = true;
                }
            }
        },
    }
</script>

<style scoped>

    a {
        text-decoration: none;
    }

</style>
