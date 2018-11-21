<template>
    <v-footer height="auto" app>
        <v-layout row wrap align-center>
            <v-flex xs12>
        <v-card
                flat
                tile
                class="deep-orange darken-4 white--text text-xs-center"
        >
            <v-card-text>
                <!--<v-btn
                        v-for="icon in icons"
                        :key="icon"
                        icon
                        class="mx-3 white&#45;&#45;text"
                >
                    <v-icon size="24px">{{ icon }}</v-icon>
                </v-btn>-->
                <a href="/admin-login" class="white--text">Admin</a>
            </v-card-text>

            <!--<v-card-text class="white&#45;&#45;text pt-2 footer&#45;&#45;inset">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illo, in reiciendis. At dicta dolorem incidunt
                iste quasi. Commodi in quod temporibus veniam voluptates. Ab assumenda quae quis similique tempore unde.
            </v-card-text>-->
            <v-card-text class="white--text">
                &copy;2018 — <strong>Smart Tourism</strong>
            </v-card-text>
        </v-card>
            </v-flex>
        </v-layout>
    </v-footer>
</template>

<script>
    export default {
        name: "app-footer",
        data: () => ({
            icons: ['fa fa-facebook', 'fa fa-twitter', 'fa fa-google-plus', 'fa fa-linkedin', 'fa fa-instagram'],
        })
    }
</script>

<style scoped>

</style>
