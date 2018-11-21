<template>
    <div>
        <v-carousel v-if="show" cycle>
            <v-carousel-item
                    v-for="(item,i) in photos"
                    :key="i"
                    :src="`${item.img_url}`|imgUrl"
                    transition="fade"
                    reverse-transition="fade"
            ></v-carousel-item>
        </v-carousel>

        <v-container>
            <v-layout row wrap align-center>
                <v-flex xs12 sm10 offset-sm1>
                    <v-card class="my-3" color="lime lighten-4">
                        <v-container fill-height fluid>
                            <v-layout row wrap>
                                <v-flex xs12 sm12>
                                    <span class="cyan--text">Category: </span>
                                    <v-chip label small color="pink" text-color="white"
                                            v-for="(category, i) in tourPackage.categories" :key="i">
                                        {{ category.name }}
                                    </v-chip>
                                </v-flex>
                                <v-flex xs12 sm12 align-end d-flex class="pt-5">
                                    <span class="headline ndigo lighten-4--text">{{ tourPackage.name }}</span>
                                </v-flex>

                                <v-flex xs4 sm4 align-center>
                                    <div class="grey--text">
                                        Agent: {{ tourPackage.admin.name }}
                                    </div>
                                </v-flex>
                                <v-flex xs4 sm4 align-center>
                                    <div class="grey--text">
                                        Token: {{ tourPackage.token }}
                                    </div>
                                </v-flex>
                                <v-flex xs4 sm4 align-center>
                                    <div class="text-xs-center">
                                        <v-chip class="title" color="green" text-color="white">
                                            Price: {{ tourPackage.price }} $
                                        </v-chip>
                                        <div class="text-xs-center">
                                            <v-btn round color="info" dark>Buy Now</v-btn>
                                        </div>

                                    </div>
                                </v-flex>
                                <v-flex xs12 sm12 class="pt-5">
                                    <div class="text-xs-center">
                                        <v-chip small color="purple" text-color="white"
                                                v-for="(tag, i) in tourPackage.tags" :key="i">
                                            {{ tag.name }}
                                        </v-chip>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <div class="title cyan--text pt-5 px-3">Description</div>
                        <v-card-text v-html="tourPackage.description.body">
                        </v-card-text>

                        <div class="title cyan--text pt-5 px-3">Detail</div>
                        <div class="elevation-1">
                            <div class="table__overflow">
                                <table class="datatable table">
                                    <tbody>
                                    <tr>
                                        <td class="text-xs-left">Origin:</td>
                                        <td class="text-xs-justify">{{ tourPackage.origin }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-left">Destination:</td>
                                        <td class="text-xs-justify">{{ tourPackage.destination }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-left">Duration:</td>
                                        <td class="text-xs-justify">{{ tourPackage.duration }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-left">Transport:</td>
                                        <td class="text-xs-justify">{{ tourPackage.transport }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-left">Accommodation:</td>
                                        <td class="text-xs-justify">{{ tourPackage.accommodation }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-left">Breakfast:</td>
                                        <td class="text-xs-justify">{{ tourPackage.breakfast }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-left">Lunch:</td>
                                        <td class="text-xs-justify">{{ tourPackage.lunch }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-left">Dinner:</td>
                                        <td class="text-xs-justify">{{ tourPackage.dinner }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-left">Sight Seeing:</td>
                                        <td class="text-xs-justify">{{ tourPackage.sight_seeing }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-left">Guide Service:</td>
                                        <td class="text-xs-justify">{{ tourPackage.guide_service }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-left">Tax:</td>
                                        <td class="text-xs-justify">{{ tourPackage.tax }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-left">Plans:</td>
                                        <v-data-table
                                                :items="tourPackage.plans"
                                                class="table__overflow"
                                                hide-actions
                                                hide-headers
                                        >
                                            <template slot="items" slot-scope="props">
                                                <td class="text-xs-left">Day: {{ props.index+1 }} Plan</td>
                                                <td class="text-xs-center">{{ props.item }}</td>
                                            </template>
                                        </v-data-table>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    export default {
        name: "AppTourPackageDetail",
        props: ['slug'],
        data() {
            return {
                tourPackage: {
                    photos: {},
                    categories: {},
                    tags: {},
                    description: {},
                    admin: {}
                },
                photos: {},
                show: false,
            }
        },
        created() {
            this.fetchTourPackage(this.slug);
        },
        watch: {
            photos(val) {
                if (val.length)
                    this.show = true;
            }
        },
        methods: {
            fetchTourPackage(slug) {
                axios.get(`/api/tour-package/${slug}`)
                    .then(response => {
                        this.tourPackage = response.data.tourPackage;
                        this.photos = this.tourPackage.photos;
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.$router.push('/tourpackage');
                    });
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
    .fade {
        transition: 0.3s ease-out;
        position: absolute;
        top: 0;
        left: 0;
    }

    .fade {
        opacity: 0;
    }

    td {
        padding: 0 15px 0 15px;
    }
</style>