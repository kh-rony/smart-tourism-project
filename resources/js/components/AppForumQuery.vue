<template>
    <v-container class="py-3">
        <v-container grid-list-xl>
            <v-layout row wrap>
                <v-flex xs4>
                    <v-avatar
                            size="36px"
                            slot="activator"
                    >
                        <img
                                :src="`https://randomuser.me/api/portraits/men/${question.user.id % 25}.jpg`"
                                :alt="`${question.user.first_name}`"
                        >
                    </v-avatar>
                    <strong v-html="question.user.first_name"></strong>
                </v-flex>
                <v-flex xs6 offset-xs-1>
                    <strong v-html="question.title"></strong>
                </v-flex>
                <v-flex
                        xs2 offset-xs-5
                        class="grey--text"
                >
                    &mdash;
                    {{ question.created_at | ago }}
                </v-flex>

            </v-layout>
            <v-card>
                <v-divider></v-divider>
                <v-card-text v-html="question.body">
                </v-card-text>
            </v-card>
        </v-container>

        <v-layout row wrap>
            <v-flex
                    xs12
                    sm6
                    md4
                    v-for="(photo, index) in question.photos"
                    :key="index"
            >
                <v-card height="200">
                    <img :src="`${photo.img_url}`|imgUrl" alt=""
                         style="width: 100%; height: 100%; margin: 5px; border: 1px solid grey; cursor: pointer" @click="showPhoto(photo.img_url)">
                </v-card>
            </v-flex>
        </v-layout>

        <div class="text-xs-center py-5">
            <v-btn @click="showQuestionReplyForm" outline color="indigo">Write an answer</v-btn>
        </div>


        <!--<v-layout row>-->
        <v-toolbar color="orange" dark>
            <v-toolbar-title>{{ answerTotal }}</v-toolbar-title>
            <v-spacer></v-spacer>
        </v-toolbar>

        <app-forum-reply
                :replies="comments"
                :depth="0"
                @newReplyOn="showReplyForm(...arguments)"
                @showReplyPhoto="showPhoto(...arguments)"
        >
        </app-forum-reply>

        <app-forum-reply-form
                :dialog="dialog"
                :postUrl="url"
                :id="parentId"
                :questionId="id"
                :label="replyLabel"
                @newReply="addNewReply(...arguments)"
                @closeForm="dialog = false"
        >
        </app-forum-reply-form>

        <app-show-photo
                :dialog="dialogShowPhoto"
                :photo="photoUrl"
                @closePhoto="hidePhoto"
        ></app-show-photo>

    </v-container>
</template>

<script>
    import AppForumReply from './AppForumReply'
    import AppForumReplyForm from './AppForumReplyForm'
    import AppShowPhoto from './AppShowPhoto'

    import moment from'moment';
    export default {
        name: "app-forum-query",
        components: {
            'app-forum-reply': AppForumReply,
            'app-forum-reply-form': AppForumReplyForm,
            'app-show-photo': AppShowPhoto
        },
        props: ['id'],
        data() {
            return {
                dialog: false,
                dialogShowPhoto: false,
                url: '',
                parentId: null,
                question: {
                    title: '',
                    body: '',
                    images: [],
                    created_at: '',
                    user: {
                        first_name: '',
                        id: null
                    }
                },
                replies: {},
                comments: [],
                photoUrl: '',
                replyLabel: ''
            }
        },
        created() {
            this.fetchQuery(this.id);
        },
        computed: {
            answerTotal() {
                return (this.replies.length) ? (this.replies.length === 1 ? `Answer: 1` : `Answers: ${this.replies.length}`) : `No answer yet`;
            }
        },
        methods: {
            fetchQuery(id) {
                axios.get(`/api/question/${id}`)
                    .then(response => {
                        this.question = response.data.question;
                        console.log(response);
                        this.fetchReplies(this.id);
                        this.listen(this.id);
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.$router.push('/forum');
                    })
            },
            fetchReplies(id) {
                axios.get(`/api/reply/${id}`)
                    .then(response => {
                        this.replies = response.data.replies;
                        this.comments = this.replies.slice(0);
                        console.log(response);
                    })
                    .catch(response => console.log(response))
            },
            listen(id) {
                Echo.channel('forum.' + id)
                    .listen('NewReply', (reply) => {
                        console.log(reply);
                        if (!reply.parentReplyId) {
                            this.replies.unshift(reply);
                            this.comments = this.replies.slice(0);
                        }
                        else {
                            this.comments = [];
                            return new Promise(resolve => {
                                this.insertNewReply(reply.parentReplyId, reply);
                                resolve();
                            }, 500).then(() => {
                                this.comments = this.replies.slice(0);
                                console.log('updated');
                            })
                        }
                    })
            },
            showQuestionReplyForm() {
                console.log('q reply');
                this.url = '/api/question-reply';
                this.parentId = this.id;
                this.replyLabel = 'an answer';
                this.dialog = true;
            },
            showReplyForm(url, id) {
                console.log('r reply id: ' + id + ' ' + url);
                this.url = url;
                this.parentId = id;
                this.replyLabel = 'a reply';
                this.dialog = true;
            },
            addNewReply(reply) {
                if (!reply.parentReplyId) {
                    this.replies.unshift(reply);
                    this.comments = this.replies.slice(0);
                    console.log('updated');
                    this.dialog = false;
                    this.url = '';
                    this.parentId = '';
                }
                else {
                    this.comments = [];
                    return new Promise(resolve => {
                        this.insertNewReply(reply.parentReplyId, reply);
                        resolve();
                    }, 500).then(() => {
                        this.comments = this.replies.slice(0);
                        console.log('updated');
                        this.dialog = false;
                        this.url = '';
                        this.parentId = '';
                    })
                }
            },
            findReply(replies, id) {
                let reply = null;

                function search(replies, id, i) {
                    if (i < replies.length) {
                        console.log('id -> ' + replies[i].id + ' looking: ' + id);
                        if (replies[i].id === id) {
                            reply = replies[i];
                            return;
                        }
                        else if (replies[i].replies)
                            search(replies[i].replies, id, 0)

                        if (!reply)
                            search(replies, id, i + 1);
                    }
                }

                search(replies, id, 0);
                return reply;
            },
            insertNewReply(parentReplyId, newReply) {
                console.log('New Reply');
                console.log(newReply);
                console.log('Parent Id: ' + parentReplyId);
                let parentReply = this.findReply(this.replies, parentReplyId);
                console.log('Parent Reply');
                console.log(parentReply);
                if (!parentReply.replies)
                    parentReply.replies = [];
                parentReply.replies.push(newReply);
                console.log('inserted');
            },
            showPhoto(url) {
                console.log(url);
                this.photoUrl = url;
                this.dialogShowPhoto = true;
            },
            hidePhoto() {
                this.dialogShowPhoto = false;
                this.photoUrl = '';
            }
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