<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card v-if="isAuthenticated">
                <v-card-title>
                    <span class="headline">Write {{ label }}</span>
                </v-card-title>

                <form @submit.prevent="postReply">

                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <label>Description*</label>
                                    <ckeditor v-model="body"></ckeditor>
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

                            </v-layout>
                        </v-container>
                        <small>*indicates required field</small>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="closeForm">Close</v-btn>
                        <v-btn type="submit" color="blue darken-1" flat :disabled="loading">Save</v-btn>
                    </v-card-actions>

                </form>

            </v-card>
            <v-card v-else color="pink lighten-2">
                <v-card-title>
                    <span class="headline white--text mx-auto">Please login to answer</span>
                </v-card-title>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="white" flat @click="closeForm">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    import Ckeditor from 'vue-ckeditor2'
    import {mapGetters} from 'vuex'
    export default {
        name: "app-forum-reply-form",
        props: ['dialog', 'postUrl', 'id', 'questionId', 'label'],
        components: {Ckeditor,},
        data() {
            return {
                loading: false,
                body: '',
                images: [],
                imagesData: [],
            }
        },
        computed: {
        ...mapGetters({
                isAuthenticated: 'isAuthenticated'
            }),
                uploadBtnText() {
                return this.images.length > 0 ? 'Add another image' : 'Image';
            },
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
            postReply() {
                if (this.body) {
                    this.loading = true;
                    const fd = new FormData();
                    fd.append('id', this.id);
                    fd.append('questionId', this.questionId);
                    fd.append('body', this.body);
                    for (let i = 0; i < this.images.length; i++) {
                        fd.append('images[' + i + ']', this.images[i]);
                    }
                    axios.post(this.postUrl, fd, {
                        onUploadProgress: uploadEvent => {
                            console.log('Uploading' + Math.round(uploadEvent.loaded / uploadEvent.total * 100) + '%');
                        }
                    })
                        .then(response => {
                            this.resetData();
                            console.log(response.data.reply);
                            this.$emit('newReply', response.data.reply);
                        })
                        .catch(response => {
                            console.log(response);
                            this.loading = false;
                        });
                }
            },
            closeForm() {
              this.$emit('closeForm');
            },
            resetData() {
                this.loading = false;
                this.body = '';
                this.images = [];
                this.imagesData = [];
            }
        }
    }
</script>

<style scoped>

</style>