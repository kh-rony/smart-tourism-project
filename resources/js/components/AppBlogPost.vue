<template>
    <div>
    <v-container>
        <v-layout row wrap align-center>
            <v-flex xs12 md10 offset-md1>
                    <v-card class="my-3">
                        <v-img
                                v-if="post.photos"
                                class="white--text"
                                height="450px"
                                :src="`${post.photos[0].img_url}`|imgUrl"
                        >
                            <v-container fill-height fluid>
                                <v-layout>
                                    <v-flex xs12 align-end d-flex>
                                        <span class="headline">{{ post.title }}</span>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-img>
                        <v-container>
                            <div>
                                Posted by <span class="font-weight-bold">{{post.admin.name}}</span> on <span>{{post.created_at | formatDate}}</span>
                            </div>
                        </v-container>
                        <v-card-text class="pt-2" v-html="post.content">
                        </v-card-text>
                        <v-card-actions>
                            <!--<v-btn icon class="red&#45;&#45;text">
                                <v-icon medium>fa-reddit</v-icon>
                            </v-btn>
                            <v-btn icon class="light-blue&#45;&#45;text">
                                <v-icon medium>fa-twitter</v-icon>
                            </v-btn>
                            <v-btn icon class="blue&#45;&#45;text text&#45;&#45;darken-4">
                                <v-icon medium>fa-facebook</v-icon>
                            </v-btn>-->
                            <!--<v-spacer></v-spacer>-->
                            <!--<v-btn flat class="blue&#45;&#45;text">Read More</v-btn>-->
                        </v-card-actions>
                    </v-card>
            </v-flex>
        </v-layout>
    </v-container>
    </div>
</template>

<script>
    import moment from 'moment'
    export default {
        name: "app-blog-post",
        props: ['slug'],
        data() {
            return {
                post: {
                    title: '',
                    content: '',
                    admin: {
                        name: ''
                    }
                }
            }
        },
        created() {
            this.fetchArticle(this.slug);
        },
        methods: {
            fetchArticle(slug) {
                axios.get(`/api/article/${slug}`)
                    .then(response => {
                        this.post = response.data.article;
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.$router.push('/blog');
                    });
            }
        },
        filters: {
            imgUrl(url) {
                return url.replace('public', '/storage');
            },
            formatDate(val) {
                return moment(val).format("D MMMM, YYYY");
            }
        }
    }
</script>

<style scoped>

</style>