<template>
    <v-container>
        <v-layout row wrap>
            <v-flex xs12 sm8 offset-sm2>
                <v-layout row align-center style="padding: 30px; margin: 30px">
                    <v-autocomplete
                            placeholder="Search places"
                            :loading="loading"
                            cache-items
                            :items="locations"
                            :search-input.sync="searchPlace"
                            v-model="search"
                            append-icon="search"
                            @click:append="searchPlaces"
                            color="deep-orange darken-1"
                            hide-details
                    ></v-autocomplete>
                </v-layout>
            </v-flex>


            <v-flex xs12 sm12>
                <v-layout row wrap align-center>
                    <v-flex xs12 v-if="searchResult">
                        <div class="text-xs-center gray--text">Search result: {{ searchResult }}</div>
                    </v-flex>
                    <v-flex v-for="(place, i) in places" :key="i" xs12 sm4 class="pb-2">
                        <v-card class="mx-1">
                            <v-img :src="`${place.photos[0].img_url}`|imgUrl" height="200px">
                            </v-img>
                            <v-card-title primary-title>
                                <div>
                                    <h3 class="headline mb-0">{{ place.name }}</h3>
                                </div>
                            </v-card-title>
                            <v-card-text
                                    v-html="$options.filters.excerpt(place.description.body) + ' ...'"></v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn flat color="orange" @click="openPlaceDetail(place.slug)">Explore</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
        <app-place-detail :slug="placeSlug" :dialog="dialog" @close="closePlaceDetail"></app-place-detail>
    </v-container>
</template>

<script>
    import AppPlaceDetail from './AppPlaceDetail'

    export default {
        name: "app-home",
        components: {
            'app-place-detail': AppPlaceDetail
        },
        data() {
            return {
                places: {},
                search: '',
                searchResult: '',
                placeSlug: '',
                dialog: false,
                loading: false,
                locations: [],
                searchPlace: null,
            }
        },
        watch: {
            searchPlace(val) {
                val && this.queryLocations(val, 1);
            },
        },
        methods: {
            queryLocations(queryText) {
                this.loading = true;
                axios.post('/api/autocomplete', {queryText: queryText})
                    .then(response => {
                        this.locations = response.data.locations;
                        this.loading = false;
                    })
                    .catch(response => console.log(response));
            },
            searchPlaces() {
                if (this.search) {
                    this.searchResult = '';
                    axios.get(`/api/search-place/${this.search}`)
                        .then(response => {
                            this.places = response.data.places;
                            if (this.places.length)
                                this.places.length > 1 ? this.searchResult = 'Showing ' + this.places.length + ' places.' : this.searchResult = 'Showing ' + this.places.length + ' place.';
                            else this.searchResult = 'Nothing found.';
                        })
                        .catch(error => console.log(error))
                }
            },
            openPlaceDetail(slug) {
                this.placeSlug = slug;
                this.dialog = true;
            },
            closePlaceDetail() {
                this.placeSlug = '';
                this.dialog = false;
            }
        },
        filters: {
            excerpt(data) {
                return data.split(" ", 13).join(" ");
            },
            imgUrl(url) {
                return url.replace('public', '/storage');
            }
        }
    }
</script>

<style scoped>

</style>
