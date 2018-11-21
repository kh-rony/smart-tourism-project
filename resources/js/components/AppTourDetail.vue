<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="deep-orange darken-1">
                    <v-btn icon dark @click.native="close">
                        <v-icon>arrow_back</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{ tour.name }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn
                                dark
                                flat
                                v-if="organizer && !tour.accept_public && !isExpire"
                                @click="makePublic"
                        >Accept Public Tour Mate
                        </v-btn>
                        <v-btn
                                dark
                                flat
                                v-if="going && !isExpire"
                                @click.native="dialog1 = true"
                        >Add Tour Mate
                        </v-btn>
                        <v-chip v-if="isExpire" color="white" text-color="red">Expired</v-chip>
                        <!--<v-btn dark flat @click.native="">Save</v-btn>-->
                    </v-toolbar-items>
                </v-toolbar>
                <v-container>

                    <v-layout row v-if="!isExpire">
                        <v-flex xs8 offset-xs2 sm4 offset-sm4 class="py-3" v-if="invited">
                            <v-card v-if="!accept">
                                Are you going?
                                <v-btn round color="success" class="white--text"
                                       @click.native="accept = true">Yes
                                </v-btn>
                                <v-btn round color="warning" class="white--text" @click="denyRequest">No</v-btn>
                            </v-card>
                            <v-card v-else>
                                <v-select
                                        label="Number of person"
                                        :items="numbers"
                                        v-model="person"
                                        required
                                        :rules="[() => person !== null || 'Please select an option']"
                                        item-value="number"
                                ></v-select>
                                <v-btn flat color="success" class="white--text" @click="acceptRequest">Ok</v-btn>
                            </v-card>
                        </v-flex>
                        <v-flex xs8 offset-xs2 sm4 offset-sm4 class="py-3" v-if="acceptPublic">
                            <v-card v-if="!accept">
                                <v-btn round color="success" class="white--text"
                                       @click.native="accept = true">Interested to go
                                </v-btn>
                            </v-card>
                            <v-card v-else>
                                <v-select
                                        label="Number of person"
                                        :items="numbers"
                                        v-model="person"
                                        required
                                        :rules="[() => person !== null || 'Please select an option']"
                                        item-value="number"
                                ></v-select>
                                <v-btn flat color="success" class="white--text" @click="interestedToGo">Ok</v-btn>
                            </v-card>
                        </v-flex>
                    </v-layout>

                    <v-layout  row wrap align-center>
                        <v-flex xs12 sm3>
                            <v-text-field
                                    label="From"
                                    readonly
                                    v-model="tour.description.origin.name"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm3 px-3>
                            <v-text-field
                                    label="To"
                                    readonly
                                    v-model="tour.description.destination.name"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm3 px-3>
                            <v-text-field
                                    label="Check in"
                                    readonly
                                    v-model="tour.description.checkIn"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm3>
                            <v-text-field
                                    label="Check out"
                                    readonly
                                    v-model="tour.description.checkOut"
                            ></v-text-field>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap>

                        <v-flex xs12 sm3 v-if="tour.description.places.length">
                            <v-card>
                                <v-toolbar color="indigo" dark>
                                    <v-toolbar-title>Available Places</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                </v-toolbar>
                                <v-list two-line style="max-height: 400px; overflow-y: auto">
                                    <v-subheader v-if="tour.description.selectedPlaces.length">
                                        Selected: {{ tour.description.selectedPlaces.length }}
                                    </v-subheader>
                                    <v-divider v-if="tour.description.selectedPlaces.length"></v-divider>
                                    <template v-for="(place, index) in tour.description.places">
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
                                                <v-tooltip left v-if="isSelected(index)">
                                                    <v-btn flat icon slot="activator">
                                                        <v-icon color="green">check</v-icon>
                                                    </v-btn>
                                                    <span>Selected</span>
                                                </v-tooltip>
                                            </v-list-tile-action>
                                        </v-list-tile>
                                        <v-divider v-if="index + 1 < tour.description.places.length" :key="`divider-${index}`" inset
                                                   color="cyan"></v-divider>
                                    </template>
                                </v-list>
                            </v-card>
                        </v-flex>

                        <v-flex xs12 sm9>
                            <v-layout row wrap align-center>
                                <v-flex xs12 sm10 offset-sm1>
                                    <v-container>
                                        <v-layout>
                                            <v-card id="mapV" style="min-height: 400px; width: 100%; z-index: 0">

                                            </v-card>
                                        </v-layout>
                                    </v-container>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap class="my-2">
                        <v-flex xs12 sm8 offset-sm2 v-if="tour.description.days.length">
                            <v-card>
                                <v-toolbar color="cyan" dark>
                                    <v-toolbar-title>Trips</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                </v-toolbar>
                                <v-list>
                                    <template v-for="(item, index) in tour.description.days">
                                        <v-subheader class="indigo--text">
                                            Day {{ index+1 }}: {{ item.date }}
                                        </v-subheader>

                                        <app-stepper :item="item"></app-stepper>

                                    </template>
                                </v-list>
                            </v-card>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap class="pt-5">
                        <v-flex class="px-3" xs12 sm4 v-if="tour.mates.length">
                            <v-card>
                                <v-toolbar color="indigo" dark>
                                    <v-toolbar-title>Going</v-toolbar-title>
                                </v-toolbar>
                                <v-list two-line>
                                    <v-list-tile avatar v-for="(user, index) in tour.mates" :key="index">
                                        <v-list-tile-avatar>
                                            <img :src="`https://randomuser.me/api/portraits/men/${user.id % 25}.jpg`">
                                        </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title
                                                    v-text="`${user.first_name} ${user.last_name}`"></v-list-tile-title>
                                            <v-list-tile-sub-title>
                                                Person: {{ user.pivot.person }}
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action>
                                            <v-icon v-if="!user.pivot.relation">public</v-icon>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                </v-list>
                            </v-card>
                        </v-flex>
                        <v-flex class="px-3" xs12 sm4 v-if="tour.invited_mates.length">
                            <v-card>
                                <v-toolbar color="indigo" dark>
                                    <v-toolbar-title>Invited Friends</v-toolbar-title>
                                </v-toolbar>
                                <v-list>
                                    <v-list-tile avatar v-for="(user, index) in tour.invited_mates" :key="index">
                                        <v-list-tile-avatar>
                                            <img :src="`https://randomuser.me/api/portraits/men/${user.id % 25}.jpg`">
                                        </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title
                                                    v-text="`${user.first_name} ${user.last_name}`"></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-card>
                        </v-flex>
                        <v-flex class="px-3" xs12 sm4 v-if="tour.not_going_mates.length">
                            <v-card>
                                <v-toolbar color="indigo" dark>
                                    <v-toolbar-title>Not going Friends</v-toolbar-title>
                                </v-toolbar>
                                <v-list>
                                    <v-list-tile avatar v-for="(user, index) in tour.not_going_mates" :key="index">
                                        <v-list-tile-avatar>
                                            <img :src="`https://randomuser.me/api/portraits/men/${user.id % 25}.jpg`">
                                        </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title
                                                    v-text="`${user.first_name} ${user.last_name}`"></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-card>
                        </v-flex>
                        <v-flex class="px-3" xs12 sm4 v-if="tour.accept_public && tour.requested_public.length">
                            <v-card>
                                <v-toolbar color="indigo" dark>
                                    <v-toolbar-title>Requested</v-toolbar-title>
                                </v-toolbar>
                                <v-list two-line>
                                    <v-list-tile avatar v-for="(user, index) in tour.requested_public" :key="index">
                                        <v-list-tile-avatar>
                                            <img :src="`https://randomuser.me/api/portraits/men/${user.id % 25}.jpg`">
                                        </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-tooltip right color="red">
                                                <v-list-tile-title
                                                        v-text="`${user.first_name} ${user.last_name}`"
                                                        slot="activator"
                                                >
                                                </v-list-tile-title>
                                                <span>
                                                            <v-card color="red lighten-4">
                                                                <v-list>
                                                                    <v-list-tile avatar color="blue lighten-1">
                                                                      <v-list-tile-avatar>
                                                                        <img :src="`https://randomuser.me/api/portraits/men/${user.id % 25}.jpg`"
                                                                             alt="">
                                                                      </v-list-tile-avatar>
                                                                      <v-list-tile-content>
                                                                        <v-list-tile-title>{{ user.first_name }} {{ user.last_name }}</v-list-tile-title>
                                                                        <v-list-tile-sub-title>{{ user.occupation }}</v-list-tile-sub-title>
                                                                      </v-list-tile-content>
                                                                    </v-list-tile>
                                                                  </v-list>
                                                                  <v-divider></v-divider>
                                                                  <v-list>
                                                                    <v-list-tile color="blue lighten-1">
                                                                      <v-list-tile-title>Member since {{ user.created_at | ago }}</v-list-tile-title>
                                                                    </v-list-tile>
                                                                  </v-list>
                                                            </v-card>
                                                        </span>
                                            </v-tooltip>
                                            <v-list-tile-sub-title>
                                                Person: {{ user.pivot.person }}
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <div v-if="organizer && user.pivot.going === 0">
                                            <v-btn color="info" round small @click="acceptPublicMate(user.slug)"
                                                   :disabled="isExpire">Accept
                                            </v-btn>
                                            <v-btn color="warning" round small @click="denyPublicMate(user.slug)"
                                                   :disabled="isExpire">Deny
                                            </v-btn>
                                        </div>
                                        <v-list-tile-action v-else>
                                            <v-chip v-if="user.pivot.going === 0" outline color="green">Pending
                                            </v-chip>
                                            <v-chip v-if="user.pivot.going === 1" outline label color="orange">
                                                Denied
                                            </v-chip>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                </v-list>
                            </v-card>
                        </v-flex>
                    </v-layout>

                    <app-place-detail :slug="placeSlug" :dialog="dialogPlaceDetail"
                                      @close="closePlaceDetail"></app-place-detail>

                </v-container>

                <v-layout row justify-center>
                    <v-dialog v-model="dialog1" persistent max-width="500px">
                        <v-card>
                            <v-card-title>
                                <span class="headline">Add Tour Mate</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12>
                                            <v-autocomplete
                                                    label="Select Tour Mate"
                                                    :items="friendsList"
                                                    v-model="friends"
                                                    item-text="name"
                                                    item-value="slug"
                                                    multiple
                                                    chips
                                                    :menu-props="{maxHeight:'250'}"
                                                    required
                                                    :rules="[() => friends.length > 0 || 'You must select friends']"
                                                    :search-input.sync="search"
                                            >
                                                <template slot="selection" slot-scope="data">
                                                    <v-chip
                                                            close
                                                            @input="data.parent.selectItem(data.item)"
                                                            :selected="data.selected"
                                                            class="chip--select-multi"
                                                            :key="JSON.stringify(data.item)"
                                                    >
                                                        <v-avatar>
                                                            <img :src="`https://randomuser.me/api/portraits/men/${data.item.id % 25}.jpg`">
                                                        </v-avatar>
                                                        {{ data.item.name }}
                                                    </v-chip>
                                                </template>
                                                <template slot="item" slot-scope="data">
                                                    <template>
                                                        <v-list-tile-avatar>
                                                            <img :src="`https://randomuser.me/api/portraits/men/${data.item.id % 25}.jpg`">
                                                        </v-list-tile-avatar>
                                                        <v-list-tile-content>
                                                            <v-list-tile-title
                                                                    v-html="data.item.name"></v-list-tile-title>
                                                        </v-list-tile-content>
                                                    </template>
                                                </template>
                                            </v-autocomplete>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <small>*indicates required field</small>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click.native="dialog1 = false">Close</v-btn>
                                <v-btn color="blue darken-1" flat @click.native="addTourMate">Save</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-layout>

            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    import moment from 'moment'
    import AppStepper from './AppStepper'
    import {mapGetters} from 'vuex'
    import AppPlaceDetail from './AppPlaceDetail'

    import 'leaflet/dist/leaflet.css'
    import L from 'leaflet/dist/leaflet'
    import 'leaflet.gridlayer.googlemutant/Leaflet.GoogleMutant'
    import '@ansur/leaflet-pulse-icon/src/L.Icon.Pulse'
    import '@ansur/leaflet-pulse-icon/src/L.Icon.Pulse.css'
    import 'leaflet.polyline.snakeanim'

    export default {
        name: "AppTourDetail",
        components: {
            'app-stepper': AppStepper,
            'app-place-detail': AppPlaceDetail
        },
        props: ['id', 'dialog'],
        data() {
            return {
                dialog1: false,
                dialogPlaceDetail: false,
                placeChecked: [],
                tour: {
                    name: '',
                    accept_public: 0,
                    description: {
                        origin: {
                            name: ''
                        },
                        destination: {
                            name: ''
                        },
                        checkIn: '',
                        checkOut: '',
                        places: {},
                        selectedPlaces: {},
                        days: {}
                    },
                    organizer: {},
                    mates: {},
                    invited_mates: {},
                    not_going_mates: {},
                    requested_public: {}
                },
                search: null,
                accept: false,
                friendsList: [],
                friends: [],
                numbers: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                person: 1,
                placeSlug: '',
                markers: [],
                tourMap: null,
                placeInfo: []
            }
        },
        watch: {
            dialog(val) {
                if (val)
                    this.fetchTour(this.id);
            },
            search(val) {
                val && this.searchFriend(val);
            },
        },
        computed: {
            ...mapGetters({
                authUser: 'user'
            }),
            invited() {
                if (this.tour.invited_mates) {
                    for (let i = 0; i < this.tour.invited_mates.length; i++) {
                        if (this.tour.invited_mates[i].slug === this.authUser.slug)
                            return true;
                    }
                    return false;
                }
                else return false;
            },
            going() {
                if (this.tour.mates) {
                    for (let i = 0; i < this.tour.mates.length; i++) {
                        if (this.tour.mates[i].slug === this.authUser.slug && this.tour.mates[i].pivot.relation)
                            return true;
                    }
                    return false;
                }
                else return false;
            },
            organizer() {
                if (this.tour.organizer)
                    return this.authUser.slug === this.tour.organizer.slug;
                else return false;
            },
            acceptPublic() {
                if (this.tour.accept_public) {
                    let notFound = true;
                    for (let i = 0; i < this.tour.mates.length && notFound; i++) {
                        if (this.tour.mates[i].slug === this.authUser.slug)
                            notFound = false;
                    }
                    for (let i = 0; i < this.tour.invited_mates.length && notFound; i++) {
                        if (this.tour.invited_mates[i].slug === this.authUser.slug)
                            notFound = false;
                    }
                    for (let i = 0; i < this.tour.not_going_mates.length && notFound; i++) {
                        if (this.tour.not_going_mates[i].slug === this.authUser.slug)
                            notFound = false;
                    }
                    for (let i = 0; i < this.tour.requested_public.length && notFound; i++) {
                        if (this.tour.requested_public[i].slug === this.authUser.slug)
                            notFound = false;
                    }
                    return notFound;
                }
                return false;
            },
            isExpire() {
                return moment().add(3, 'h').diff(moment(this.tour.description.checkIn)) > 0;
            }
        },
        methods: {
            fetchTour(id) {
                console.log('open tour' + id);
                axios.get(`/api/tour/${id}`)
                    .then(response => {
                        this.tour = response.data.tour;
                        this.placeChecked = new Array(this.tour.description.places.length).fill(false);
                        for (let i = 0; i < this.tour.description.selectedPlaces.length; i++) {
                            let index = -1;
                            for (let j = 0; j < this.tour.description.places.length && index < 0; j++) {
                                if (this.tour.description.selectedPlaces[i].name === this.tour.description.places[j].name &&
                                    this.tour.description.selectedPlaces[i].latitude === this.tour.description.places[j].latitude &&
                                    this.tour.description.selectedPlaces[i].longitude === this.tour.description.places[j].longitude)
                                    index = j;
                            }
                            this.placeChecked[index] = true;
                        }
                        for (let i = 0; i < this.tour.description.days.length; i++) {
                            for (let j = 0; j < this.tour.description.days[i].trips.length; j++) {
                                this.markers.push(this.tour.description.days[i].trips[j].fromLL);
                                let place = this.findPlace(this.tour.description.days[i].trips[j].fromLL);
                                console.log('found ' + place.name);
                                this.placeInfo.push(place);
                            }
                        }
                        this.initMap();
                    })
                    .catch(error => {
                        console.log(error);
                    })

            },
            isSelected(i) {
                return this.placeChecked[i];
            },
            close() {
                this.tour.name = '';
                this.tour.description.origin.name = '';
                this.tour.description.destination.name = '';
                this.tour.description.checkIn = '';
                this.tour.description.checkOut = '';
                this.tour.description.places = {};
                this.tour.description.selectedPlaces = {};
                this.tour.description.days = {};
                this.placeChecked = [];
                this.tourMap.remove();
                this.tourMap = null;
                this.markers = [];
                this.$emit('close');
            },
            acceptRequest() {
                const fd = new FormData();
                fd.append('id', this.id);
                fd.append('person', this.person);
                axios.post('/api/tour/accept-mate-request', fd)
                    .then(response => {
                        let index = -1;
                        for (let i = 0; i < this.tour.invited_mates.length && index < 0; i++) {
                            if (this.tour.invited_mates[i].slug === this.authUser.slug)
                                index = i;
                        }
                        this.tour.invited_mates[index].pivot.person = this.person;
                        this.tour.invited_mates[index].pivot.relation = 1;
                        this.tour.mates.push(this.tour.invited_mates[index]);
                        this.tour.invited_mates.splice(index, 1);

                        this.accept = false;
                        this.person = 1;
                        console.log(response);
                    })
                    .catch(error => {
                        this.accept = false;
                        console.log(error);
                    })
            },
            denyRequest() {
                const fd = new FormData();
                fd.append('id', this.id);
                axios.post('/api/tour/deny-mate-request', fd)
                    .then(response => {
                        let index = -1;
                        for (let i = 0; i < this.tour.invited_mates.length && index < 0; i++) {
                            if (this.tour.invited_mates[i].slug === this.authUser.slug)
                                index = i;
                        }
                        this.tour.not_going_mates.push(this.tour.invited_mates[index]);
                        this.tour.invited_mates.splice(index, 1);
                        console.log(response);
                    })
                    .catch(error => console.log(error))
            },
            addTourMate() {
                if (this.friends.length && this.going) {
                    const fd = new FormData();
                    fd.append('id', this.id);
                    for (let i = 0; i < this.friends.length; i++) {
                        fd.append('users[' + i + ']', this.friends[i]);
                    }
                    axios.post('/api/tour/add-mate', fd)
                        .then(response => {
                            for (let i = 0; i < response.data.addedUsers.length; i++) {
                                this.tour.invited_mates.push(response.data.addedUsers[i]);
                            }
                            this.dialog1 = false;
                            this.friends = [];
                            this.friendsList = [];
                        })
                        .catch(response => console.log(response))
                }
            },
            searchFriend(val) {
                axios.get(`/api/tour/${this.id}/search-friend/${val}`)
                    .then(response => {
                        let friends = response.data.friends;
                        let notFound = true;
                        for (let i = 0; i < friends.length; i++) {
                            notFound = true;
                            for (let j = 0; j < this.friendsList.length && notFound; j++) {
                                if (friends[i].slug === this.friendsList[j].slug)
                                    notFound = false;
                            }
                            if (notFound) {
                                this.friendsList.push(friends[i]);
                            }
                        }
                        console.log(friends);
                    })
                    .catch(response => console.log(response))
            },
            makePublic() {
                axios.put(`/api/tour/${this.id}/make-public`)
                    .then(response => {
                        console.log(response.data.message);
                        this.tour.accept_public = 1;
                    })
                    .catch(error => console.log(error))
            },
            interestedToGo() {
                axios.put(`/api/tour/${this.id}/add-public-mate`, {person: this.person})
                    .then(response => {
                        let user = {
                            "first_name": null,
                            "last_name": null,
                            "slug": null,
                            "created_at": null,
                            "occupation": null,
                            "gender": null,
                            "pivot": {
                                "person": null
                            }
                        };
                        user.first_name = this.authUser.first_name;
                        user.last_name = this.authUser.last_name;
                        user.slug = this.authUser.slug;
                        user.created_at = this.authUser.created_at;
                        user.occupation = this.authUser.occupation;
                        user.gender = this.authUser.gender;
                        user.pivot.person = this.person;
                        this.tour.requested_public.push(user);

                        this.accept = false;
                        this.person = 1;
                        console.log(response);
                        this.$emit('interestedPublicTour', this.id);
                    })
                    .catch(error => {
                        this.accept = false;
                        console.log(error);
                    })
            },
            acceptPublicMate(user) {
                axios.put(`/api/tour/${this.id}/accept-public-mate`, {user: user})
                    .then(response => {
                        let index = -1;
                        for (let i = 0; i < this.tour.requested_public.length && index < 0; i++) {
                            if (this.tour.requested_public[i].slug === user)
                                index = i;
                        }
                        this.tour.requested_public[index].pivot.going = 2;
                        this.tour.requested_public[index].pivot.relation = 0;
                        this.tour.mates.push(this.tour.requested_public[index]);
                        this.tour.requested_public.splice(index, 1);
                        console.log(response);
                    })
                    .catch(error => console.log(error));
            },
            denyPublicMate(user) {
                axios.put(`/api/tour/${this.id}/deny-public-mate`, {user: user})
                    .then(response => {
                        let index = -1;
                        for (let i = 0; i < this.tour.requested_public.length && index < 0; i++) {
                            if (this.tour.requested_public[i].slug === user)
                                index = i;
                        }
                        this.tour.requested_public[index].pivot.going = 1;
                        console.log(response);
                    })
                    .catch(error => console.log(error));
            },

            initMap() {
                this.tourMap = L.map('mapV').setView([this.tour.description.destination.latitude, this.tour.description.destination.longitude], 7);

                let roads = L.gridLayer.googleMutant({
                    type: 'roadmap'	// valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid'
                }).addTo(this.tourMap);


                let route = L.featureGroup();

                let n = this.markers.length;


                for (let i = 0; i < n-1; i++) {
                    let pulsingIcon = L.icon.pulse({iconSize: [20, 20], color: 'red'});
                    let marker = new L.marker(this.markers[i], {icon: pulsingIcon});
                    let line = new L.polyline([this.markers[i], this.markers[i+1]]);

                    marker.bindPopup(
                        '<b>' + this.placeInfo[i].name + '</b>',
                        {minWidth: 128}
                    );

                    route.addLayer(marker);
                    route.addLayer(line);
                }
                let pulsingIcon = L.icon.pulse({iconSize: [20, 20], color: 'red'});
                let marker = new L.Marker(this.markers[n-1], {icon: pulsingIcon});
                marker.bindPopup(
                    '<b>' + this.placeInfo[n-1].name + '</b>',
                    {minWidth: 128}
                );
                route.addLayer(marker);

                let line = new L.polyline([this.markers[n-1], this.markers[0]]);
                route.addLayer(line);

                this.tourMap.fitBounds(route.getBounds());

                this.tourMap.addLayer(route);

                snake();

                function snake() {
                    route.snakeIn();
                    setTimeout(snake, 5000);
                }

            },
            openPlaceDetail(slug) {
                this.placeSlug = slug;
                this.dialogPlaceDetail = true;
            },
            closePlaceDetail() {
                this.placeSlug = '';
                this.dialogPlaceDetail = false;
            },
            findPlace(marker) {
                if (marker[0] === this.tour.description.origin.latitude && marker[1] === this.tour.description.origin.longitude) {
                    return this.tour.description.origin;
                }
                else {
                    let notFound = true;
                    let place = {};
                    for (let i = 0; i < this.tour.description.selectedPlaces.length && notFound; i++) {
                        if (marker[0] === this.tour.description.selectedPlaces[i].latitude && marker[1] === this.tour.description.selectedPlaces[i].longitude) {
                            place = this.tour.description.selectedPlaces[i];
                            notFound = false;
                        }
                    }
                    return place;
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

</style>