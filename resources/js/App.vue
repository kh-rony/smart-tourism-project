<template>
    <div id="app">
        <v-app id="inspire">
            <app-toolbar app></app-toolbar>
            <v-content>
                <!--<v-container>-->
                    <router-view/>
                <!--</v-container>-->
            </v-content>
            <app-footer app></app-footer>
        </v-app>
    </div>
</template>

<script>
    import WebFontLoader from 'webfontloader'
    import AppToolbar from './components/AppToolbar'
    import AppFooter from './components/AppFooter'
    import {mapGetters} from 'vuex'
    import {
        AUTH_VALIDATE,
        LISTEN_MESSAGE_ARRIVE,
        LOGGED_USERS,
        SET_AUTH_TOKEN,
        UNREAD_MESSAGES_REQUEST
    } from "./store/actions";

    export default {
        name: 'App',
        components: {
            AppToolbar, AppFooter
        },
        created() {
            this.authCheck();
        },
        mounted () {
            WebFontLoader.load({
                google: {
                    families: ['Roboto:100,300,400,500,700,900']
                },
                active: this.setFontLoaded
            })
        },
        computed: {
            ...mapGetters({
                isAuthenticated: 'isAuthenticated'
            })
        },
        methods: {
            authCheck() {
                if (window.location.hash && window.location.hash === "#/_=_") {
                    this.$router.push('/');
                }

                if (window.access_token && window.verified !== '0') {
                    this.$store.dispatch(SET_AUTH_TOKEN, window.access_token)
                        .then(response => console.log(response));
                }

                if (this.isAuthenticated) {
                    this.$store.dispatch(AUTH_VALIDATE)
                        .then(response => {
                            console.log(response);
                            this.$store.dispatch(UNREAD_MESSAGES_REQUEST);
                            this.$store.dispatch(LISTEN_MESSAGE_ARRIVE);
                            this.$store.dispatch(LOGGED_USERS);
                        })
                        .catch(response => console.log(response));
                }
            },
            setFontLoaded () {
                this.$emit('font-loaded')
            }
        }
    }
</script>

<style>

</style>
