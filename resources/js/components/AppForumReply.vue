<template>
    <v-container>
        <v-layout
                class="my-5"
                justify-center
                column
                v-for="(reply, index) in replies"
                :replies="reply.replies"
                :depth="depth"
                :key="reply.id"
                :style="indent"
        >
            <v-layout align-center row spacer slot="header">
                <v-flex xs4>
                    <v-avatar
                            size="36px"
                            slot="activator"
                    >
                        <img
                                :src="`https://randomuser.me/api/portraits/men/${reply.user.id % 25}.jpg`"
                                alt=""
                        >
                    </v-avatar>
                    <strong v-html="reply.user.first_name"></strong>
                </v-flex>
                <v-flex xs8 class="grey--text">
                    &mdash;
                    {{ reply.created_at | ago }}
                </v-flex>
            </v-layout>
            <v-flex xs12>
                <v-card-text v-html="reply.body">
                </v-card-text>

                <v-layout row wrap>
                    <v-flex
                            xs12
                            sm6
                            md4
                            v-for="(photo, index) in reply.photos"
                            :key="index"
                    >
                        <v-card height="200">
                            <img :src="`${photo.img_url}`|imgUrl" alt=""
                                 style="width: 100%; height: 100%; margin: 5px; border: 1px solid grey; cursor: pointer" @click="showPhoto(photo.img_url)">
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-flex>
            <div class="pb-1">
                <v-btn small @click="showReplyForm(`/api/reply`, reply.id)" outline color="indigo">Reply</v-btn>
            </div>

            <v-divider></v-divider>

            <app-forum-reply
                    :replies="reply.replies"
                    :depth="depth + 1"
                    :style="indent"
                    @newReplyOn="showReplyForm(...arguments)"
                    @showReplyPhoto="showPhoto(...arguments)"
            ></app-forum-reply>

        </v-layout>
    </v-container>

</template>

<script>
    import AppShowPhoto from './AppShowPhoto'
    const moment = require('moment');
    export default {
        name: "app-forum-reply",
        components: {
            'app-show-photo': AppShowPhoto
        },
        props: ['replies', 'depth'],
        data() {
            return {
                dialogShowPhoto: false,
            }
        },
        computed: {
            indent() {
                return {
                    transform: `translate(${this.depth * 20}px)`
                }
            },
        },
        methods: {
            showReplyForm(url, id) {
                this.$emit('newReplyOn', url, id);
            },
            showPhoto(url) {
                this.$emit('showReplyPhoto', url);
            },
        },
        filters: {
            ago(data) {
                return moment(data).fromNow();
            },
            imgUrl(url) {
                return url.replace('public', '/storage');
            }
        }
    }
</script>

<style scoped>

</style>