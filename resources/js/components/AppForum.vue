<template>
    <v-container class="py-3">
        <v-layout row>
            <v-flex xs12 sm10 offset-sm1>
                <v-card>
                    <v-toolbar color="cyan" dark>
                        <v-toolbar-title>All Queries</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-tooltip left color="deep-orange darken-1" light>
                            <v-btn outline fab color="indigo" slot="activator" @click.native="dialog = true">
                                <v-icon>edit</v-icon>
                            </v-btn>
                            <span>Ask a new query</span>
                        </v-tooltip>

                    </v-toolbar>
                    <v-list two-line>
                        <template v-for="(question, index) in questions">
                            <v-list-tile :to="'/forum/' + question.id">
                                <v-list-tile-avatar>
                                    <img :src="`https://randomuser.me/api/portraits/men/${question.user_id % 25}.jpg`">
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title v-html="question.title"></v-list-tile-title>
                                    <v-list-tile-sub-title>{{ question.created_at | ago }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-divider v-if="index + 1 < questions.length" :key="`divider-${index}`" inset
                                       color="cyan"></v-divider>
                        </template>
                    </v-list>
                </v-card>
            </v-flex>
        </v-layout>

        <v-layout row justify-center>
            <v-dialog v-model="dialog" persistent max-width="600px">
                <v-card v-if="isAuthenticated">
                    <v-card-title>
                        <span class="headline">Add Query</span>
                    </v-card-title>

                    <form @submit.prevent="postQuery">

                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-text-field
                                                label="Title"
                                                v-model="title"
                                                required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs12>
                                        <input
                                                style="display: none"
                                                type="file"
                                                ref="fileInput"
                                                accept="image/*"
                                                @change="onFileSelected"
                                        >
                                        <v-btn
                                                @click="$refs.fileInput.click()"
                                                color="blue-grey"
                                                class="white--text"
                                        >
                                            {{uploadBtnText}}
                                            <v-icon right dark>cloud_upload</v-icon>
                                        </v-btn>
                                    </v-flex>
                                    <v-flex xs12>
                                        <img class="mx-auto" v-for="imageData in imagesData" :src="imageData"
                                             style="width: 100px; height: 60px">
                                    </v-flex>
                                    <v-flex xs12>
                                        <label>Description*</label>
                                        <ckeditor v-model="body"></ckeditor>
                                    </v-flex>

                                </v-layout>
                            </v-container>
                            <small>*indicates required field</small>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click.native="dialog = false">Close</v-btn>
                            <v-btn type="submit" color="blue darken-1" flat>Save</v-btn>
                        </v-card-actions>

                    </form>

                </v-card>
                <v-card v-else color="pink lighten-2">
                    <v-card-title>
                        <span class="headline white--text mx-auto">Please login to add Query</span>
                    </v-card-title>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="white" flat @click.native="dialog = false">Close</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Ckeditor from 'vue-ckeditor2'

    const moment = require('moment');
    export default {
        name: "app-forum",
        components: {Ckeditor},
        data() {
            return {
                questions: {},
                dialog: false,
                title: '',
                body: '',
                images: [],
                imagesData: [],
            }
        },
        created() {
            this.fetchQuries();
            this.listen();
        },
        computed: {
            ...mapGetters({
                isAuthenticated: 'isAuthenticated'
            }),
            uploadBtnText() {
                return this.images.length > 0 ? 'Add another image' : 'Image';
            }
        },
        methods: {
            onFileSelected(event) {
                let file = event.target.files[0];
                this.images.push(file);

                let reader = new FileReader();
                reader.onload = (e) => {
                    this.imagesData.push(e.target.result);
                };
                reader.readAsDataURL(file);
            },
            fetchQuries() {
                axios.get('/api/question')
                    .then(response => {
                        this.questions = response.data.questions;
                        console.log(response);
                    })
                    .catch(response => console.log(response));
            },
            postQuery() {
                if (this.title && this.body) {
                    const fd = new FormData();
                    fd.append('title', this.title);
                    fd.append('body', this.body);
                    for (let i = 0; i < this.images.length; i++) {
                        fd.append('images[' + i + ']', this.images[i]);
                    }

                    axios.post('/api/question', fd, {
                        onUploadProgress: uploadEvent => {
                            console.log('Uploading' + Math.round(uploadEvent.loaded / uploadEvent.total * 100) + '%');
                        }
                    })
                        .then(response => {
                            this.dialog = false;
                            this.questions.unshift(response.data.question);
                            this.resetData();
                        })
                        .catch(response => console.log(response));
                }
            },
            listen() {
                Echo.channel('forum')
                    .listen('NewQuestion', (question) => {
                        this.questions.unshift(question);
                    })
            },
            resetData() {
                this.title = '';
                this.body = '';
                this.images = [];
                this.imagesData = [];
            }
        },
        filters: {
            ago(data) {
                return moment(data).fromNow();
            }
        }
    }
</script>

<style scoped>

</style>