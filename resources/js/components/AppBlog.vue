<template>
    <v-container>
        <v-layout row wrap align-center>
            <v-flex xs12 md10 offset-md1>
                <div v-for="post in posts" :key="post.title">
                    <v-card class="my-3" hover :to="`/blog/${post.slug}`">
                        <v-img
                                class="white--text"
                                height="170px"
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
                        <v-card-text v-html="post.content">
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
                            <v-spacer></v-spacer>
                            <v-btn flat class="blue--text">Read More</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "app-blog",
        data() {
            return {
                posts: {}
            }
        },
        created() {
            this.fetchArticles();
        },
        methods: {
            fetchArticles() {
                axios.get('/api/article')
                    .then(response => {
                        this.posts = response.data.articles;
                    })
                    .catch(error => console.log(error));
            }
        },
        filters: {
            excerpt(data) {
                return data.split(" ", 50).join(" ");
            },
            imgUrl(url) {
                return url.replace('public', '/storage');
            }
        }
    }
</script>

<style scoped>

</style>