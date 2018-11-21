<template>
    <div>
        <v-tabs icons-and-text centered dark color="cyan">
            <v-tabs-slider color="yellow"></v-tabs-slider>
            <v-tab href="#tab-1">
                Create Tour
                <v-icon>create</v-icon>
            </v-tab>
            <v-tab href="#tab-2">
                Organized Tour
                <v-icon>save</v-icon>
            </v-tab>
            <v-tab href="#tab-3">
                Invited Tour
                <v-icon>arrow_downward</v-icon>
            </v-tab>
            <v-tab href="#tab-4">
                Interested Public Tour
                <v-icon>arrow_downward</v-icon>
            </v-tab>
            <v-tab href="#tab-5">
                Public Tour
                <v-icon>arrow_downward</v-icon>
            </v-tab>
            <v-tab-item :value="'tab-1'">
                <v-container>
                    <v-layout row wrap>

                        <v-flex xs12 sm4 class="px-3">
                            <v-autocomplete
                                    label="From"
                                    :loading="loading1"
                                    cache-items
                                    required
                                    :items="locations"
                                    :rules="[() => origin.name.length > 0 || 'You must choose your location']"
                                    :search-input.sync="searchOrigin"
                                    v-model="origin.name"
                            ></v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm4 class="px-3">
                            <v-autocomplete
                                    label="To"
                                    :loading="loading2"
                                    cache-items
                                    required
                                    :items="locations"
                                    :rules="[() => destination.name.length > 0 || 'You must choose a destination']"
                                    :search-input.sync="searchDestination"
                                    v-model="destination.name"
                            ></v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm2 class="px-3">
                            <v-menu
                                    lazy
                                    :close-on-content-click="false"
                                    v-model="menu"
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    :nudge-right="40"
                                    min-width="290px"
                            >
                                <v-text-field
                                        slot="activator"
                                        label="Check in"
                                        v-model="checkIn"
                                        prepend-icon="event"
                                        readonly
                                ></v-text-field>
                                <v-date-picker v-model="checkIn" no-title @input="menu = false"></v-date-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex xs12 sm2 class="px-3">
                            <div class="text-xs-center">
                                <v-btn round color="primary" dark @click="fetchPlaces"
                                       :disabled="loading3 || !(origin.name && destination.name && checkIn)">
                                    <v-progress-circular v-if="loading3" indeterminate
                                                         color="primary"></v-progress-circular>
                                    <span v-else>Search</span>
                                </v-btn>
                            </div>
                        </v-flex>

                        <v-flex xs8 offset-xs-2 sm-6 offset-sm3 v-if="selectedPlaces.length">
                            <div class="text-xs-center">
                                <v-btn
                                        round
                                        color="primary"
                                        dark
                                        @click="tripInput"
                                        :disabled="letMePlan||loading4"
                                >Let Me Plan
                                </v-btn>
                                <v-btn
                                        round
                                        color="primary"
                                        dark
                                        @click="tourPlanner"
                                        :disabled="loading4||letMePlan"
                                >
                                    <v-progress-circular
                                            v-if="loading4"
                                            indeterminate
                                            color="primary"
                                    ></v-progress-circular>
                                    <span v-else>Plan My Trip</span>
                                </v-btn>
                            </div>
                        </v-flex>

                        <v-flex xs12 sm3 v-if="places.length">
                            <v-card>
                                <v-toolbar color="indigo" dark>
                                    <v-toolbar-title>Available Places</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                </v-toolbar>
                                <v-subheader v-if="selectedPlaces.length">
                                    Selected: {{ letMePlan ? selectedPlaces.length -1 : selectedPlaces.length }}
                                </v-subheader>
                                <v-list two-line style="max-height: 400px; overflow-y: auto">
                                    <v-divider v-if="selectedPlaces.length"></v-divider>
                                    <template v-for="(place, index) in places">
                                        <v-list-tile
                                                :key="index"
                                        >
                                            <v-list-tile-avatar>
                                                <img :src="`${place.photos[0].img_url}`|imgUrl">
                                            </v-list-tile-avatar>

                                            <v-list-tile-content>
                                                <v-list-tile-title v-text="place.name"></v-list-tile-title>
                                            </v-list-tile-content>
                                            <v-spacer></v-spacer>
                                            <v-list-tile-action>
                                                <v-tooltip left>
                                                    <v-btn flat icon slot="activator" color="pink"
                                                           @click.native="openPlaceDetail(place.slug)">
                                                        <v-icon>remove_red_eye</v-icon>
                                                    </v-btn>
                                                    <span>View Place Detail</span>
                                                </v-tooltip>
                                                <v-tooltip left>
                                                    <v-btn flat icon slot="activator"
                                                           @click.native="selectPlace(index)">
                                                        <v-icon color="green" v-if="isSelected(index)">check</v-icon>
                                                        <v-icon color="grey" v-else>check</v-icon>
                                                    </v-btn>
                                                    <span v-if="isSelected(index)">Selected</span>
                                                    <span v-else>Select</span>
                                                </v-tooltip>
                                            </v-list-tile-action>
                                        </v-list-tile>
                                        <v-divider v-if="index + 1 < places.length" :key="`divider-${index}`" inset
                                                   color="cyan"></v-divider>
                                    </template>
                                </v-list>
                            </v-card>
                        </v-flex>

                        <v-flex xs12 sm8 offset-sm1 :style="mapViewStyle">
                            <v-container fluid>
                                <v-layout>
                                    <v-card id="mapView" style="min-height: 400px; width: 100%; z-index: 0">

                                    </v-card>
                                </v-layout>
                            </v-container>
                        </v-flex>


                    </v-layout>

                    <v-layout row wrap align-center style="margin-top: 20px">
                        <v-flex xs12 sm8 offset-sm2 v-if="days.length && !letMePlan">
                            <v-card>
                                <v-toolbar color="cyan" dark>
                                    <v-toolbar-title>Trips</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                </v-toolbar>
                                <v-list>
                                    <template v-for="(item, index) in days">
                                        <v-subheader class="indigo--text">
                                            Day {{ index+1 }}: {{ item.date }}
                                        </v-subheader>

                                        <app-stepper :item="item"></app-stepper>

                                    </template>
                                </v-list>
                            </v-card>

                            <v-layout row wrap class="pt-3">
                                <v-flex xs12 sm4>
                                    <v-text-field
                                            label="Give a tour name"
                                            v-model="tourName"
                                            :rules="[() => tourName.length > 0 || 'This field is required']"
                                            required
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 px-3>
                                    <v-select
                                            label="Number of person"
                                            :items="numbers"
                                            v-model="person"
                                            required
                                            :rules="[() => person !== null || 'Please select an option']"
                                            item-value="number"
                                    ></v-select>
                                </v-flex>
                                <v-flex xs12 sm4>
                                    <v-text-field
                                            label="Check out"
                                            v-model="checkOut"
                                            readonly
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs8 offset-xs2 sm6 offset-sm-3>
                                    <v-btn
                                            block
                                            color="info"
                                            dark
                                            :disabled="!(tourName && person && checkOut)"
                                            @click="savePlan"
                                    >Save Plan
                                    </v-btn>
                                </v-flex>
                            </v-layout>

                        </v-flex>

                        <v-flex xs12 sm8 offset-sm2 class="px-5" v-if="letMePlan">
                            <v-layout row wrap v-for="(trip, index) in trips" :key="index">
                                <v-flex xs12>
                                    <v-subheader>Trip #{{ index + 1 }}</v-subheader>
                                </v-flex>

                                <v-flex xs12 sm3 class="px-3">
                                    <v-menu
                                            :close-on-content-click="false"
                                            v-model="trip.menu2"
                                            :nudge-right="40"
                                            lazy
                                            transition="scale-transition"
                                            offset-y
                                            full-width
                                            max-width="290px"
                                            min-width="290px"
                                    >
                                        <v-text-field
                                                slot="activator"
                                                v-model="trip.startingTime"
                                                label="Starting Time"
                                                prepend-icon="access_time"
                                                readonly
                                        ></v-text-field>
                                        <v-time-picker v-if="trip.btn && !index"
                                                       v-model="trip.startingTime"
                                                       @change="trip.menu2 = false;"
                                                       @input="setEndingTime(index)"
                                        ></v-time-picker>
                                    </v-menu>
                                </v-flex>

                                <v-flex xs12 sm3 class="px-3">
                                    <v-text-field
                                            label="From"
                                            v-model="trip.from"
                                            readonly
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs12 sm3 class="px-3">
                                    <v-select
                                            v-if="trip.btn && notLastTrip(index)"
                                            label="To"
                                            :items="tripPlaces"
                                            v-model="trip.to"
                                            required
                                            :rules="[() => trip.to !== null || 'Please select an option']"
                                            item-value="text"
                                            @input="setEndingTime(index)"
                                    >
                                    </v-select>
                                    <v-text-field
                                            v-else
                                            label="To"
                                            v-model="trip.to"
                                            readonly
                                    >
                                    </v-text-field>
                                </v-flex>

                                <v-flex xs12 sm3 class="px-3">
                                    <v-menu
                                            :close-on-content-click="false"
                                            v-model="trip.menu4"
                                            :nudge-right="40"
                                            lazy
                                            transition="scale-transition"
                                            offset-y
                                            full-width
                                            max-width="290px"
                                            min-width="290px"
                                    >
                                        <v-text-field
                                                slot="activator"
                                                v-model="trip.endingTime"
                                                label="Ending Time"
                                                prepend-icon="access_time"
                                                readonly
                                        ></v-text-field>
                                        <!--<v-time-picker v-model="trip.endingTime"
                                                       @change="trip.menu4 = false"></v-time-picker>-->
                                    </v-menu>
                                </v-flex>

                                <v-flex xs12 sm3 class="px-3">
                                    <v-text-field
                                            label="Distance in Kilometer"
                                            v-model="trip.distance"
                                            readonly
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs12 sm9 class="px-3" v-if="notLastTrip(index)">
                                    <v-layout row wrap>
                                        <v-flex xs12 sm4>
                                            <v-select
                                                    v-if="trip.btn"
                                                    label="Stay: (how many days)"
                                                    :items="dayNumbers"
                                                    v-model="trip.stayDays"
                                                    required
                                                    :rules="[() => trip.stayDays !== null || 'Please select an option']"
                                                    item-text="index"
                                                    item-value="value"
                                            >
                                            </v-select>
                                            <v-text-field
                                                    v-else
                                                    label="Stay: (how many days)"
                                                    v-model="trip.stayDays"
                                                    readonly
                                            >
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm4 class="px-2">
                                            <v-select
                                                    v-if="trip.btn"
                                                    label="Stay: (how many hours)"
                                                    :items="hourNumbers"
                                                    v-model="trip.stayHours"
                                                    required
                                                    :rules="[() => trip.stayHours !== null || 'Please select an option']"
                                                    item-text="index"
                                                    item-value="value"
                                            >
                                            </v-select>
                                            <v-text-field
                                                    v-else
                                                    label="Stay: (how many hours)"
                                                    v-model="trip.stayHours"
                                                    readonly
                                            >
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm4>
                                            <v-select
                                                    v-if="trip.btn"
                                                    label="Stay: (how many minutes)"
                                                    :items="minuteNumbers"
                                                    v-model="trip.stayMinutes"
                                                    required
                                                    :rules="[() => trip.stayMinutes !== null || 'Please select an option']"
                                                    item-text="index"
                                                    item-value="value"
                                            >
                                            </v-select>
                                            <v-text-field
                                                    v-else
                                                    label="Stay: (how many minutes)"
                                                    v-model="trip.stayMinutes"
                                                    readonly
                                            >
                                            </v-text-field>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-3" v-if="trip.btn">
                                    <v-btn
                                            :disabled="!trip.startingTime || !trip.from || !trip.endingTime || !trip.to || ((trip.stayDays === null || trip.stayHours === null || trip.stayMinutes === null) && notLastTrip(index))"
                                            @click="nextTrip(index)"
                                            color="info"
                                    >{{ tripBtnText }}
                                    </v-btn>
                                </v-flex>

                                <v-flex xs12 v-if="notLastTrip(index)">
                                    <v-divider></v-divider>
                                </v-flex>

                            </v-layout>


                        </v-flex>
                    </v-layout>

                    <v-layout row justify-center>
                        <v-dialog v-model="dialog" persistent max-width="290">
                            <v-card>
                                <v-card-title class="headline">Tour Saved</v-card-title>
                                <v-card-text>Go organized tour to update</v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="green darken-1" flat @click.native="resetData">Close</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-layout>



                </v-container>
                <v-container>
                <app-place-detail :slug="placeSlug" :dialog="dialogPlaceDetail"
                                  @close="closePlaceDetail"></app-place-detail></v-container>
            </v-tab-item>
            <v-tab-item :value="'tab-2'">
                <v-container>
                    <v-layout row>
                        <v-flex xs12 sm8 offset-sm2>
                            <v-card>
                                <v-toolbar color="cyan" dark>
                                    <v-toolbar-title>Organized Tours</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                </v-toolbar>
                                <v-list two-line>
                                    <template v-for="(tour, index) in organizedTours">
                                        <v-list-tile
                                                :key="index"
                                                @click="openTour(tour.id)"
                                        >
                                            <v-list-tile-content>
                                                <v-list-tile-title v-html="tour.name"></v-list-tile-title>
                                                <v-list-tile-sub-title>{{ tour.created_at | ago }}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                            <v-spacer></v-spacer>
                                            <v-list-tile-action>
                                            </v-list-tile-action>
                                        </v-list-tile>
                                        <v-divider v-if="index + 1 < organizedTours.length" :key="`divider-${index}`"
                                                   color="cyan"></v-divider>
                                    </template>
                                </v-list>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-tab-item>
            <v-tab-item :value="'tab-3'">
                <v-container>
                    <v-layout row>
                        <v-flex xs12 sm8 offset-sm2>
                            <v-card>
                                <v-toolbar color="cyan" dark>
                                    <v-toolbar-title>Invited Tours</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                </v-toolbar>
                                <v-list two-line>
                                    <template v-for="(tour, index) in invitedTours">
                                        <v-list-tile
                                                :key="index"
                                                @click="openTour(tour.id)"
                                        >
                                            <v-list-tile-content>
                                                <v-list-tile-title v-html="tour.name"></v-list-tile-title>
                                                <v-list-tile-sub-title>{{ tour.created_at | ago }}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                            <v-spacer></v-spacer>
                                            <!--<v-list-tile-action>
                                                <v-icon large color="green">check</v-icon>
                                            </v-list-tile-action>-->
                                        </v-list-tile>
                                        <v-divider v-if="index + 1 < invitedTours.length" :key="`divider-${index}`"
                                                   color="cyan"></v-divider>
                                    </template>
                                </v-list>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-tab-item>
            <v-tab-item :value="'tab-4'">
                <v-container>
                    <v-layout row>
                        <v-flex xs12 sm8 offset-sm2>
                            <v-card>
                                <v-toolbar color="cyan" dark>
                                    <v-toolbar-title>Interested Public Tours</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                </v-toolbar>
                                <v-list two-line>
                                    <template v-for="(tour, index) in interestedPublicTours">
                                        <v-list-tile
                                                :key="index"
                                                @click="openTour(tour.id)"
                                        >
                                            <v-list-tile-content>
                                                <v-list-tile-title v-html="tour.name"></v-list-tile-title>
                                                <v-list-tile-sub-title>{{ tour.created_at | ago }}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                            <v-spacer></v-spacer>
                                            <!--<v-list-tile-action>
                                                <v-icon large color="green">check</v-icon>
                                            </v-list-tile-action>-->
                                        </v-list-tile>
                                        <v-divider v-if="index + 1 < interestedPublicTours.length"
                                                   :key="`divider-${index}`"
                                                   color="cyan"></v-divider>
                                    </template>
                                </v-list>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-tab-item>
            <v-tab-item :value="'tab-5'">
                <v-container>
                    <v-layout row>
                        <v-flex xs12 sm8 offset-sm2>
                            <v-card>
                                <v-toolbar color="cyan" dark>
                                    <v-toolbar-title>Public Tours</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                </v-toolbar>
                                <v-list two-line>
                                    <template v-for="(tour, index) in publicTours">
                                        <v-list-tile
                                                :key="index"
                                                @click="openTour(tour.id)"
                                        >
                                            <v-list-tile-content>
                                                <v-list-tile-title v-html="tour.name"></v-list-tile-title>
                                                <v-list-tile-sub-title>{{ tour.created_at | ago }}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                            <v-spacer></v-spacer>
                                            <!--<v-list-tile-action>
                                                <v-icon large color="green">check</v-icon>
                                            </v-list-tile-action>-->
                                        </v-list-tile>
                                        <v-divider v-if="index + 1 < publicTours.length" :key="`divider-${index}`"
                                                   color="cyan"></v-divider>
                                    </template>
                                </v-list>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-tab-item>
        </v-tabs>

        <app-tour-detail
                :id="openTourId"
                :dialog="dialogTour"
                @interestedPublicTour="insertInterestedPublicTour"
                @close="closeTour"
        ></app-tour-detail>

    </div>

</template>

<script>
    import moment from 'moment'
    import AppStepper from './AppStepper'
    import AppTourDetail from './AppTourDetail'
    import AppPlaceDetail from './AppPlaceDetail'

    import 'leaflet/dist/leaflet.css'
    import L from 'leaflet/dist/leaflet'
    import 'leaflet.gridlayer.googlemutant'
    import '@ansur/leaflet-pulse-icon/src/L.Icon.Pulse'
    import '@ansur/leaflet-pulse-icon/src/L.Icon.Pulse.css'

    export default {
        name: "AppTourPlanner",
        components: {
            'app-stepper': AppStepper,
            'app-tour-detail': AppTourDetail,
            'app-place-detail': AppPlaceDetail
        },
        data() {
            return {
                loading1: false,
                loading2: false,
                loading3: false,
                loading4: false,
                dialog: false,
                dialogTour: false,
                dialogPlaceDetail: false,
                openTourId: null,
                locations: [],
                searchOrigin: null,
                searchDestination: null,
                menu: false,
                places: [],
                selectedPlaces: [],
                visited: [],
                visitedSequence: [],
                graph: [],
                checkIn: null,
                days: [],
                position: null,
                origin: {
                    name: '',
                    latitude: null,
                    longitude: null
                },
                destination: {
                    name: '',
                    latitude: null,
                    longitude: null,
                },
                placeChecked: [],
                tourName: '',
                person: 1,
                checkOut: null,
                numbers: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                organizedTours: {},
                invitedTours: {},
                interestedPublicTours: {},
                publicTours: {},
                letMePlan: false,
                trips: [],
                tripPlaces: [],
                dayNumbers: [{index: '0', value: 0}, {index: '1', value: 1}, {index: '2', value: 2}, {
                    index: '3',
                    value: 3
                },
                    {index: '4', value: 4}, {index: '5', value: 5}, {index: '6', value: 6}, {index: '7', value: 7},
                    {index: '8', value: 8}, {index: '9', value: 9}, {index: '10', value: 10}],
                hourNumbers: [{index: '0', value: 0}, {index: '1', value: 1}, {index: '2', value: 2}, {
                    index: '3',
                    value: 3
                },
                    {index: '4', value: 4}, {index: '5', value: 5}, {index: '6', value: 6}, {index: '7', value: 7},
                    {index: '8', value: 8}, {index: '9', value: 9}, {index: '10', value: 10}, {index: '11', value: 11},
                    {index: '12', value: 12}, {index: '13', value: 13}, {index: '14', value: 14}, {
                        index: '15',
                        value: 15
                    }, {index: '16', value: 16},
                    {index: '17', value: 17}, {index: '18', value: 18}, {index: '19', value: 19}, {
                        index: '20',
                        value: 20
                    },
                    {index: '21', value: 21}, {index: '22', value: 22}, {index: '23', value: 23}],
                minuteNumbers: [{index: '0', value: 0}, {index: '15', value: 15}, {
                    index: '30',
                    value: 30
                }, {index: '45', value: 45}],
                placeSlug: '',
                map: null,
                mapViewStyle: {
                    display: 'none'
                },
                marker: []
            }
        },
        created() {
            this.fetchTours();
        },
        /*mounted() {
            this.getCurrentLocation();
        },*/
        computed: {
            tripBtnText() {
                return this.trips.length < this.selectedPlaces.length ? 'Next' : 'Finish';
            }
        },
        watch: {
            searchOrigin(val) {
                val && this.queryLocations(val, 1);
            },

            searchDestination(val) {
                val && this.queryLocations(val, 2);
            },
        },
        methods: {
            fetchTours() {
                axios.get('/api/tour')
                    .then(response => {
                        this.organizedTours = response.data.organizedTours;
                        this.invitedTours = response.data.invitedTours;
                        this.interestedPublicTours = response.data.interestedPublicTours;
                        this.publicTours = response.data.publicTours;
                    })
                    .catch(error => console.log(error));
            },
            getCurrentLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        this.position = position.coords;
                        if (this.position) {
                            const latlng = this.position.coords.latitude + "," + this.position.coords.longitude;
                            axios.post('/api/geolocation', {latlng: latlng})
                                .then(response => {
                                    this.origin = response.data.location;
                                })
                                .catch(response => console.log(response));
                        }
                    })
                }
            },
            queryLocations(queryText, loading) {
                loading === 1 ? this.loading1 = true : this.loading2 = true;
                axios.post('/api/autocomplete', {queryText: queryText})
                    .then(response => {
                        this.locations = response.data.locations;
                        loading === 1 ? this.loading1 = false : this.loading2 = false;
                    })
                    .catch(response => console.log(response));
            },
            fetchPlaces() {
                this.loading3 = true;
                this.days = [];
                this.places = [];
                this.selectedPlaces = [];
                this.placeChecked = [];
                this.letMePlan = false;
                if (this.map) {
                    this.map.remove();
                    this.map = null;
                }

                axios.post('/api/geocode', {name: this.origin.name})
                    .then(response => {
                        this.origin.latitude = response.data.location.lat;
                        this.origin.longitude = response.data.location.lng;
                    })
                    .catch(error => {
                        console.log(error);
                    });

                axios.post('/api/geocode', {name: this.destination.name})
                    .then(response => {
                        this.destination.latitude = response.data.location.lat;
                        this.destination.longitude = response.data.location.lng;

                        this.mapViewStyle.display = "block";

                        axios.get(`/api/search-place/${this.destination.name}`)
                            .then(response => {
                                this.places = response.data.places;
                                this.placeChecked = new Array(this.places.length).fill(false);
                                this.marker = new Array(this.places.length).fill(null);
                                this.loading3 = false;
                                if (this.places.length)
                                    this.initMap();
                                else this.mapViewStyle.display = 'none';
                            })
                            .catch(error => {
                                console.log(error);
                            });


                    })
                    .catch(error => {
                        console.log(error);
                    });

            },
            inputGraph() {
                let i;
                for (let place in this.selectedPlaces) {
                    let row = [];
                    for (place in this.selectedPlaces) {
                        row.push(0);
                    }
                    this.graph.push(row);
                }

                for (i = 0; i < this.selectedPlaces.length; i++)
                    this.visited[i] = false;

                for (i = 0; i < this.selectedPlaces.length; i++) {
                    for (let j = i + 1; j < this.selectedPlaces.length; j++) {
                        this.graph[i][j] = this.graph[j][i] = this.distance(this.selectedPlaces[i].latitude, this.selectedPlaces[i].longitude, this.selectedPlaces[j].latitude, this.selectedPlaces[j].longitude)
                    }
                }
            },
            distance(lat1, lon1, lat2, lon2) {
                let p = 0.017453292519943295;    // Math.PI / 180
                let c = Math.cos;
                let a = 0.5 - c((lat2 - lat1) * p) / 2 + c(lat1 * p) * c(lat2 * p) * (1 - c((lon2 - lon1) * p)) / 2;

                return 12742 * Math.asin(Math.sqrt(a)); // 2 * R; R = 6371 km
            },
            nearestNeighbour() {
                let node = 0;
                while (this.visitedSequence.length < this.selectedPlaces.length) {
                    this.visit(node);
                    let min = Number.MAX_SAFE_INTEGER;
                    for (let i = 0; i < this.selectedPlaces.length; i++) {
                        if (i !== node && !this.visited[i] && this.graph[node][i] < min) {
                            min = this.graph[node][i];
                            node = i;
                        }
                    }
                }
                this.visitedSequence.push(0); //back to start
            },
            visit(node) {
                if (!this.visited[node]) {
                    this.visited[node] = true;
                    this.visitedSequence.push(node);
                }
            },
            tourPlanner() {
                this.loading4 = true;
                this.selectedPlaces.unshift(this.origin);
                this.days = [];
                this.inputGraph();
                this.nearestNeighbour();


                let startDateTime = moment(this.checkIn + ' ' + "08:00");
                let dateTime = moment(this.checkIn + ' ' + "08:00");

                let day = {
                    "date": null,
                    "trips": []
                };
                day.date = startDateTime.format("ddd, MMM Do YYYY");
                for (let i = 0; i < this.selectedPlaces.length; i++) {
                    let tour = {
                        "from": null,
                        "to": null,
                        "fromLL": null,
                        "startJourney": null,
                        "endJourney": null,
                        "visit": null,
                        "distance": null
                    };
                    tour.from = this.selectedPlaces[this.visitedSequence[i]].name;
                    tour.to = this.selectedPlaces[this.visitedSequence[i + 1]].name;
                    tour.fromLL = this.findLatitudeLongitude(tour.from);
                    tour.startJourney = dateTime.format("ddd, MMM Do YYYY, h:mm a");

                    tour.distance = this.graph[this.visitedSequence[i]][this.visitedSequence[i + 1]].toFixed(1);
                    let duration = this.distanceToTime(tour.distance);
                    let hour = Math.floor(duration);
                    let minute = Math.ceil((duration % 1) * 60);

                    dateTime.add(hour, 'h').add(minute, 'm');

                    tour.endJourney = dateTime.format("ddd, MMM Do YYYY, h:mm a");

                    dateTime = dateTime.add(2, 'h');
                    tour.visit = dateTime.format("ddd, MMM Do YYYY, h:mm a");

                    let t = moment(startDateTime);
                    t.add(12, 'h');

                    if (i + 1 === this.selectedPlaces.length) {
                        tour.visit = null;
                        day.trips.push(tour);
                        this.days.push(day);
                    }
                    else if (dateTime > t) {
                        if (hour + 2 < 12)
                            i--;
                        else {
                            day.trips.push(tour);
                        }
                        this.days.push(day);
                        startDateTime = startDateTime.add(1, 'd');
                        dateTime = moment(startDateTime);
                        day = {
                            "date": startDateTime.format("ddd, MMM Do YYYY"),
                            "trips": []
                        };
                    }
                    else {
                        day.trips.push(tour);
                    }
                    console.log(tour);
                }
                this.checkOut = startDateTime.format("YYYY-MM-DD");
                this.selectedPlaces.splice(0, 1);
                this.visited = [];
                this.visitedSequence = [];
                this.loading4 = false;
            },
            distanceToTime(distance) {
                return (1 / 25) * distance; // per hour 25 km, return hour
            },
            selectPlace(index) {
                if (!this.letMePlan) {
                    this.map.removeLayer(this.marker[index]);
                    if (!this.placeChecked[index]) {
                        this.selectedPlaces.push(this.places[index]);
                        this.placeChecked[index] = true;
                        let pulsingIcon = L.icon.pulse({iconSize: [20, 20], color: 'red'});
                        this.marker[index] = L.marker([this.places[index].latitude, this.places[index].longitude], {icon: pulsingIcon}).addTo(this.map);
                        this.marker[index].bindPopup(
                            '<b>' + this.places[index].name + '</b>',
                            {minWidth: 128}
                        );
                    }
                    else {
                        let removeIndex = -1;
                        for (let i = 0; i < this.selectedPlaces.length && removeIndex < 0; i++) {
                            if (this.selectedPlaces[i].name === this.places[index].name)
                                removeIndex = i;
                        }

                        this.selectedPlaces.splice(removeIndex, 1);
                        this.placeChecked[index] = false;
                        this.marker[index]  = L.circle(
                            [this.places[index].latitude, this.places[index].longitude],
                            10000,
                            {
                                color : 'pink',
                                fillColor : 'red',
                                fillOpacity : 1
                            }
                        ).addTo(this.map);
                        this.marker[index].bindPopup(
                            '<b>' + this.places[index].name + '</b>',
                            {minWidth: 128}
                        );
                    }
                }
            },
            isSelected(i) {
                return this.placeChecked[i];
            },
            savePlan() {
                if (this.days.length && this.tourName) {
                    axios.post('/api/tour', {
                        origin: this.origin,
                        destination: this.destination,
                        checkIn: this.checkIn,
                        checkOut: this.checkOut,
                        name: this.tourName,
                        days: this.days,
                        places: this.places,
                        selectedPlaces: this.selectedPlaces,
                        person: this.person
                    }, {
                        onUploadProgress: uploadEvent => {
                            console.log('Uploading' + Math.round(uploadEvent.loaded / uploadEvent.total * 100) + '%');
                        }
                    })
                        .then(response => {
                            this.dialog = true;
                            this.organizedTours.unshift(response.data.tour);
                        })
                        .catch(response => console.log(response))
                }
            },
            openTour(id) {
                this.dialogTour = true;
                this.openTourId = id;
            },
            closeTour() {
                this.openTourId = null;
                this.dialogTour = false;
            },
            resetData() {
                this.checkIn = '';
                this.checkOut = '';
                this.tourName = '';
                this.person = 1;
                this.days = [];
                this.graph = [];
                this.places = [];
                this.selectedPlaces = [];
                this.placeChecked = [];
                this.origin.name = '';
                this.destination.name = '';
                this.mapViewStyle.display = "none";
                this.dialog = false;
            },
            tripInput() {
                this.days = [];
                this.graph = [];
                this.trips = [];
                this.tripPlaces = [];
                this.letMePlan = true;
                this.selectedPlaces.forEach(place => {
                    this.tripPlaces.push(place.name);
                });
                this.selectedPlaces.unshift(this.origin);
                this.trips.push({
                    menu1: false,
                    menu2: false,
                    menu3: false,
                    menu4: false,
                    startingDate: this.checkIn,
                    startingTime: null,
                    endingDate: null,
                    endingTime: null,
                    from: this.origin.name,
                    to: null,
                    distance: null,
                    stayDays: 0,
                    stayHours: 0,
                    stayMinutes: 0,
                    btn: true,
                })
            },
            nextTrip(index) {
                if (this.validateTrip(index)) {
                    if (index === 0 && this.trips[index].startingDate !== this.checkIn)
                        this.checkIn = this.trips[index].startingDate;

                    this.trips[index].btn = false;
                    this.tripPlaces.splice(this.tripPlaces.indexOf(this.trips[index].to), 1);

                    let startingDateTime = this.setStartingTime(index);
                    if (index + 2 < this.selectedPlaces.length) {
                        this.trips.push({
                            menu1: false,
                            menu2: false,
                            menu3: false,
                            menu4: false,
                            startingDate: startingDateTime.format("YYYY-MM-DD"),
                            startingTime: startingDateTime.format("H:mm"),
                            endingDate: null,
                            endingTime: null,
                            from: this.trips[index].to,
                            to: null,
                            distance: null,
                            stayDays: 0,
                            stayHours: 0,
                            stayMinutes: 0,
                            btn: true,
                        });
                    }
                    else if (index + 2 === this.selectedPlaces.length) {
                        this.trips.push({
                            menu1: false,
                            menu2: false,
                            menu3: false,
                            menu4: false,
                            startingDate: startingDateTime.format("YYYY-MM-DD"),
                            startingTime: startingDateTime.format("H:mm"),
                            endingDate: null,
                            endingTime: null,
                            from: this.trips[index].to,
                            to: this.origin.name,
                            distance: null,
                            stayDays: null,
                            stayHours: null,
                            stayMinutes: null,
                            btn: true,
                        });
                        this.setEndingTime(index + 1);
                    }
                    else {
                        this.manualTourPlan();
                    }
                }
                else {
                    alert('Check inputs again');
                }
            },
            validateTrip(index) {
                let start = moment(this.trips[index].startingDate + ' ' + this.trips[index].startingTime);
                let end = moment(this.trips[index].endingDate + ' ' + this.trips[index].endingTime);
                let visit = moment(this.trips[index].endingDate + ' ' + this.trips[index].endingTime)
                    .add(this.trips[index].stayDays, 'd').add(this.trips[index].stayHours, 'h').add(this.trips[index].stayMinutes, 'm');
                return start < end && (end < visit || !this.notLastTrip(index));
            },
            notLastTrip(index) {
                return index + 1 !== this.selectedPlaces.length;
            },
            manualTourPlan() {
                let startDate = moment(this.trips[0].startingDate);
                let day = {
                    "date": null,
                    "trips": []
                };
                day.date = startDate.format("ddd, MMM Do YYYY");
                for (let i = 0; i < this.trips.length; i++) {
                    let tour = {
                        "from": null,
                        "to": null,
                        "fromLL": null,
                        "startJourney": null,
                        "endJourney": null,
                        "visit": null,
                        "distance": null
                    };

                    tour.from = this.trips[i].from;
                    tour.to = this.trips[i].to;
                    tour.fromLL = this.findLatitudeLongitude(tour.from);
                    tour.startJourney = moment(this.trips[i].startingDate + ' ' + this.trips[i].startingTime).format("ddd, MMM Do YYYY, h:mm a");
                    tour.endJourney = moment(this.trips[i].endingDate + ' ' + this.trips[i].endingTime).format("ddd, MMM Do YYYY, h:mm a");
                    if (i + 1 < this.trips.length)
                        tour.visit = moment(this.trips[i].endingDate + ' ' + this.trips[i].endingTime).add(this.trips[i].stayDays, 'd').add(this.trips[i].stayHours, 'h').add(this.trips[i].stayMinutes, 'm').format("ddd, MMM Do YYYY, h:mm a");
                    tour.distance = this.findDistance(tour.from, tour.to).toFixed(1);
                    console.log(tour.distance);
                    let tourDate = moment(this.trips[i].startingDate);
                    console.log('s: ' + startDate);
                    console.log('t: ' + tourDate);

                    if (startDate.isSame(tourDate)) {
                        day.trips.push(tour);
                        console.log('pushed same day');
                    }
                    else {
                        console.log('pushed next day');
                        this.days.push(day);
                        startDate = moment(tourDate);
                        day = {
                            "date": startDate.format("ddd, MMM Do YYYY"),
                            "trips": []
                        };
                        day.trips.push(tour);
                    }

                    if (i + 1 === this.trips.length) {
                        this.days.push(day);
                    }
                }
                this.checkOut = startDate.format("YYYY-MM-DD");
                this.selectedPlaces.splice(0, 1);
                this.letMePlan = false;
            },
            findDistance(start, end) {
                let startIndex = -1;
                let endIndex = -1;

                let found = 0;
                for (let i = 0; i < this.selectedPlaces.length && found < 2; i++) {
                    if (start === this.selectedPlaces[i].name) {
                        startIndex = i;
                        found++;
                    }
                    else if (end === this.selectedPlaces[i].name) {
                        endIndex = i;
                        found++;
                    }
                }
                return this.distance(this.selectedPlaces[startIndex].latitude, this.selectedPlaces[startIndex].longitude, this.selectedPlaces[endIndex].latitude, this.selectedPlaces[endIndex].longitude);
            },
            calculate(index) {
                let st = moment(this.trips[index].startingDate + ' ' + this.trips[index].startingTime);
                let et = moment(this.trips[index].endingDate + ' ' + this.trips[index].endingTime);
                let ev = moment(et).add(this.trips[index].visit, 'h');

                let vd = ev.diff(st, 'days');
                let vh = ev.diff(st, 'hours');

                console.log('Next Date: ' + moment(this.trips[index].startingDate).add(vd, 'd').format("YYYY-MM-DD"));
                console.log('Next Time: ' + moment(st).add(vd, 'd').add(vh, 'h').format("h:mm a"));
            },
            setEndingTime(index) {
                /*if (index === 0)
                    this.trips[index].startingTime = moment(this.trips[index].startingTime, "H:mm").format("h:mm a");*/

                if (this.trips[index].from && this.trips[index].to && this.trips[index].startingDate && this.trips[index].startingTime) {
                    let distance = this.findDistance(this.trips[index].from, this.trips[index].to);
                    this.trips[index].distance = distance.toFixed(1);
                    let time = this.distanceToTime(distance);
                    let day = time / 24;
                    day = Math.floor(day);
                    let hour = Math.floor((time % 24));
                    let minute = Math.ceil(((time % 24) % 1) * 60);
                    let st = moment(this.trips[index].startingDate + ' ' + this.trips[index].startingTime);
                    let et = moment(st).add(day, 'd').add(hour, 'h').add(minute, 'm');
                    this.trips[index].endingDate = et.format("YYYY-MM-DD");
                    this.trips[index].endingTime = et.format("H:mm");
                }
            },
            setStartingTime(index) {
                let st = moment(this.trips[index].endingDate + ' ' + this.trips[index].endingTime);
                st.add(this.trips[index].stayDays, 'd');
                st.add(this.trips[index].stayHours, 'h');
                st.add(this.trips[index].stayMinutes, 'm');
                return st;
            },
            insertInterestedPublicTour(tourId) {
                let index = -1;
                for (let i = 0; i < this.publicTours.length && index < 0; i++) {
                    if (this.publicTours[i].id === tourId)
                        index = i;
                }
                this.interestedPublicTours.push(this.publicTours[index]);
                this.publicTours.splice(index, 1);
            },
            openPlaceDetail(slug) {
                this.placeSlug = slug;
                this.dialogPlaceDetail = true;
            },
            closePlaceDetail() {
                this.placeSlug = '';
                this.dialogPlaceDetail = false;
            },
            initMap() {
                this.map = L.map('mapView').setView([this.destination.latitude, this.destination.longitude], 7);

                let roads = L.gridLayer.googleMutant({
                    type: 'roadmap'	// valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid'
                }).addTo(this.map);

                for (let i = 0; i < this.places.length; i++) {
                    this.marker[i]  = L.circle(
                        [this.places[i].latitude, this.places[i].longitude],
                        10000,
                        {
                            color : 'pink',
                            fillColor : 'red',
                            fillOpacity : 1
                        }
                    ).addTo(this.map);
                    this.marker[i].bindPopup(
                        '<b>' + this.places[i].name + '</b>',
                        {minWidth: 128}
                    );
                }
            },
            findLatitudeLongitude(name) {
                if (name === this.origin.name) {
                    return [this.origin.latitude, this.origin.longitude];
                }
                else {
                    let notFound = true;
                    let ll = null;
                    for (let i = 0; i < this.selectedPlaces.length && notFound; i++) {
                        if (name === this.selectedPlaces[i].name) {
                            ll = [this.selectedPlaces[i].latitude, this.selectedPlaces[i].longitude];
                            notFound = false;
                        }
                    }
                    return ll;
                }
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

    @keyframes fade {
        from { opacity: 0.5; }
    }

    .blinking {
        animation: fade 1s infinite alternate;
    }

</style>
