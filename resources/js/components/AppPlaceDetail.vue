<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="deep-orange darken-1">
                    <v-btn icon dark @click.native="close">
                        <v-icon>arrow_back</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{ place.name }}</v-toolbar-title>
                </v-toolbar>
                <v-carousel v-if="show" cycle>
                    <v-carousel-item
                            v-for="(item,i) in photos"
                            :key="i"
                            :src="`${item.img_url}`|imgUrl"
                    ></v-carousel-item>
                </v-carousel>

                <v-container>
                    <v-layout row wrap align-center>
                        <v-flex xs12 sm10 offset-sm1>
                            <v-card class="my-3" color="grey lighten-4">
                                <v-container fill-height fluid>
                                    <v-layout row wrap>
                                        <v-flex xs12 sm12>
                                            <span class="cyan--text">Category: </span>
                                            <v-chip label small color="pink" text-color="white"
                                                    v-for="(category, i) in place.categories" :key="i">
                                                {{ category.name }}
                                            </v-chip>
                                        </v-flex>
                                        <v-flex xs12 sm12 align-end d-flex class="pt-5">
                                            <span class="headline ndigo lighten-4--text">{{ place.name }}</span>
                                        </v-flex>
                                        <v-flex xs12 sm12 align-end d-flex class="gray--text">
                                            Address: {{ place.address1 }}, {{ place.address2 }}
                                        </v-flex>
                                        <v-flex xs12 sm12 align-end d-flex class="gray--text">
                                            City: {{ place.city }}
                                        </v-flex>
                                        <v-flex xs12 sm12 align-end d-flex class="gray--text">
                                            State: {{ place.state }}
                                        </v-flex>
                                        <v-flex xs12 sm12 align-end d-flex class="gray--text">
                                            Country: {{ place.country }}
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <div class="title cyan--text pt-3 px-3">Description</div>
                                <v-card-text v-html="place.description.body">
                                </v-card-text>
                                <v-layout row wrap>
                                    <v-flex xs12 sm12 class="pt-2">
                                        <div class="text-xs-center">
                                            <v-chip small color="purple" text-color="white"
                                                    v-for="(tag, i) in place.tags"
                                                    :key="i">
                                                {{ tag.name }}
                                            </v-chip>
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-card>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap>
                        <v-flex xs12 sm7 v-if="weather">
                            <div class="headline">
                                <v-avatar size="50px">
                                    <img
                                            class="img-circle elevation-7 mb-1"
                                            src="/img/animated/weather.svg"
                                    >
                                </v-avatar>
                                Weather
                                <a href="https://darksky.net/poweredby/">
                                    <img class="image" src="/img/animated/darksky.png" alt="darksky" width="120px" height="30px">
                                </a>
                            </div>
                            <v-container fluid grid-list-sm>
                                <v-layout row wrap>
                                    <v-flex v-for="(w, i) in weather" :key="i" xs6 class="pb-2">
                                        <v-card>
                                            <v-card-title class="pb-0">
                                                <v-avatar size="45px">
                                                    <img
                                                            class="img-circle elevation-7"
                                                            :src="w.src"
                                                    >
                                                </v-avatar>
                                                <p class="subheader"><span style="font-weight:bold">{{ w.day }}</span>
                                                </p>
                                            </v-card-title>
                                            <v-card-text class="py-0 my-0">
                                                <div>{{ w.summary }}</div>
                                                <div>{{ w.minTemp }}&deg; C &rarr; {{ w.maxTemp }}&deg; C</div>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-flex>
                        <v-flex xs12 sm5>
                            <v-layout row wrap align-center>
                                <v-flex xs12 sm10 offset-sm1  class="pb-2">
                                    <v-container>
                                        <v-layout>
                                            <v-card id="map"
                                                    style="min-height: 450px; width: 100%; z-index: 0; margin-top: 30px">

                                            </v-card>
                                        </v-layout>
                                    </v-container>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                    </v-layout>

                </v-container>
            </v-card>
        </v-dialog>
    </v-layout>

</template>

<script>
    import 'leaflet/dist/leaflet.css'
    import L from 'leaflet/dist/leaflet'
    import 'leaflet.gridlayer.googlemutant'
    import '@ansur/leaflet-pulse-icon/src/L.Icon.Pulse'
    import '@ansur/leaflet-pulse-icon/src/L.Icon.Pulse.css'

    export default {
        name: "AppPlaceDetail",
        props: ['slug', 'dialog'],
        data() {
            return {
                place: {
                    photos: {},
                    categories: {},
                    tags: {},
                    description: {},
                },
                weather: {},
                items: [
                    {
                        src: 'https://placeimg.com/640/480/nature/1'
                    },
                    {
                        src: 'https://placeimg.com/640/480/nature/2'
                    },
                    {
                        src: 'https://placeimg.com/640/480/nature/3'
                    },
                    {
                        src: 'https://placeimg.com/640/480/nature/4'
                    }
                ],
                placeMap: null,
                photos: {},
                show: false
            }
        },
        watch: {
            dialog(val) {
                if (val)
                    this.fetchPlaceDetail(this.slug);
            },
            photos(val) {
                if (val.length)
                    this.show = true;
            }
        },
        methods: {
            fetchPlaceDetail(slug) {
                if (this.placeMap) {
                    this.placeMap.remove();
                    this.placeMap = null;
                }
                axios.get(`/api/place/${slug}`)
                    .then(response => {
                        this.place = response.data.place;
                        this.photos = this.place.photos;
                        this.fetchWeatherStatus(this.place.latitude, this.place.longitude);
                        this.initMap();
                    })
                    .catch(error => console.log(error))
            },
            fetchWeatherStatus(lat, lon) {
                axios.get(`/api/weather-status/${lat}/${lon}`)
                    .then(response => {
                        console.log(response);
                        this.weather = response.data.weather;
                    })
                    .catch(error => console.log(error))
            },
            initMap() {
                this.placeMap = L.map('map').setView([this.place.latitude, this.place.longitude], 8);

                let roads = L.gridLayer.googleMutant({
                    type: 'roadmap'	// valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid'
                }).addTo(this.placeMap);


                let pulsingIcon = L.icon.pulse({iconSize: [20, 20], color: 'red'});
                let marker = L.marker([this.place.latitude, this.place.longitude], {icon: pulsingIcon}).addTo(this.placeMap);
                marker.bindPopup(
                    '<b>' + this.place.name + '</b>',
                    {minWidth: 128}
                );
            },
            close() {
                this.place.name = '';
                this.place.address1 = '';
                this.place.address2 = '';
                this.place.city = '';
                this.place.state = '';
                this.place.country = '';
                this.place.categories = {};
                this.place.tags = {};
                this.place.description.body = '';
                this.weather = {};
                this.placeMap.remove();
                this.placeMap = null;
                this.$emit('close');
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