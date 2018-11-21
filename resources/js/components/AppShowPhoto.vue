<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="1080px">
            <v-card>
                <v-toolbar dark color="grey lighten-1">
                    <v-btn icon dark @click.native="close">
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-container>
                    <v-card>
                        <img :src="`${photo}`|imgUrl" alt="" style="width: 100%; height: 100%">
                    </v-card>
                </v-container>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    export default {
        name: "AppShowPhoto",
        props: ['dialog', 'photo'],
        methods: {
            close() {
                this.$emit('closePhoto');
            }
        },
        filters: {
            imgUrl(url) {
                return url.replace('public', '/storage');
            }
        }
    }
</script>

<style scoped>

</style>