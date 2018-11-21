<template>
    <v-container>
        <v-layout row>
            <v-flex xs12 sm10 offset-sm1>
                <v-card>
                    <v-toolbar color="cyan" dark>
                        <v-toolbar-title>Tour Packages</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <div v-for="(tourPackage, index) in tourPackages">
                        <v-card class="my-3" hover :to="'/tourpackage/' + tourPackage.slug" :key="index">
                            <v-container fill-height fluid>
                                <v-layout row wrap>
                                    <v-flex xs3>
                                        <v-img
                                                class="my-3"
                                                height="170px"
                                                :src="`${tourPackage.photo.img_url}`|imgUrl"
                                        ></v-img>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-card-title>
                                            <div>
                                                <span class="headline">{{ tourPackage.name }}</span><br>

                                                <span class="grey--text">Agent: {{ tourPackage.admin.name }}</span>
                                            </div>
                                        </v-card-title>

                                        <v-card-text v-html="$options.filters.excerpt(tourPackage.description.body)">
                                        </v-card-text>
                                    </v-flex>
                                    <v-flex xs3>
                                        <div class="text-xs-center">
                                            <v-chip class="title" color="green" text-color="white">Price: {{
                                                tourPackage.price }} $
                                            </v-chip>
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn flat class="blue--text">Read More</v-btn>
                            </v-card-actions>
                        </v-card>
                    </div>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "AppTourPackage",
        data() {
            return {
                tourPackages: {}
            }
        },
        created() {
            this.fetchTourPackages();
        },
        methods: {
            fetchTourPackages() {
                axios.get('/api/tour-package')
                    .then(response => {
                        this.tourPackages = response.data.tourPackages;
                        console.log(response);
                    })
                    .catch(response => console.log(response));
            }
        },
        filters: {
            excerpt(data) {
                return data.split(" ", 16).join(" ");
            },
            imgUrl(url) {
                return url.replace('public', '/storage');
            }
        }
    }
</script>

<style scoped>

</style>