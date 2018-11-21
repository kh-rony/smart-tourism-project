<template>
    <v-container>
        <v-layout row wrap align-center>
            <v-flex xs4 offset-xs4>
                <div class="text-xs-center">
                    <v-avatar size="125px">
                        <img
                                class="img-circle elevation-7 mb-1"
                                :src="`https://randomuser.me/api/portraits/men/${authUser.id % 25}.jpg`"
                        >
                    </v-avatar>
                    <div class="headline">{{ authUser.first_name }} <span style="font-weight:bold">{{ authUser.last_name }}</span>
                    </div>
                    <div class="subheading text-xs-center grey--text pt-1 pb-3" v-if="authUser.occupation">{{ authUser.occupation }}</div>
                </div>
            </v-flex>
            <v-flex xs3 offset-xs9>
                <v-btn small color="info" @click="fetchRandomPeople">
                    <v-icon left dark>people</v-icon>
                    Find Friends
                </v-btn>
            </v-flex>
            <v-flex xs10 offset-xs1 v-if="sentRequests.length">
                <v-subheader>Sent Friend Requests</v-subheader>
                <v-container fluid grid-list-sm>
                    <v-layout row wrap>
                        <v-flex v-for="(friend, i) in sentRequests" :key="i" xs4 class="pb-2">
                            <v-card>
                                <v-card-actions class="pb-0 mb-0">
                                    <v-spacer></v-spacer>
                                    <v-menu
                                            open-on-hover
                                            bottom
                                            offset-y
                                    >
                                        <v-btn
                                                flat class="white--text"
                                                slot="activator"
                                        >op
                                            <v-icon color="cyan">more_vert</v-icon>
                                        </v-btn>
                                        <v-list>
                                            <v-list-tile @click="cancelFriendRequest(friend)">
                                                <v-list-tile-title>Cancel Request</v-list-tile-title>
                                            </v-list-tile>
                                        </v-list>
                                    </v-menu>
                                </v-card-actions>
                                <v-card-title class="pt-0 mt-0">
                                    <v-avatar size="80px">
                                        <img
                                                class="img-circle elevation-7 mb-1"
                                                :src="`https://randomuser.me/api/portraits/men/${i + 20}.jpg`"
                                        >
                                    </v-avatar>
                                    <p class="subheader">{{ friend.first_name }} <span style="font-weight:bold">{{ friend.last_name }}</span>
                                    </p>
                                </v-card-title>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
            <v-flex xs10 offset-xs1 v-if="receivedRequests.length">
                <v-subheader>Received Friend Requests</v-subheader>
                <v-container fluid grid-list-sm>
                    <v-layout row wrap>
                        <v-flex v-for="(friend, i) in receivedRequests" :key="i" xs4 class="pb-2">
                            <v-card>
                                <v-card-actions class="pb-0 mb-0">
                                    <v-spacer></v-spacer>
                                    <v-menu
                                            open-on-hover
                                            bottom
                                            offset-y
                                    >
                                        <v-btn
                                                flat class="white--text"
                                                slot="activator"
                                        >op
                                            <v-icon color="cyan">more_vert</v-icon>
                                        </v-btn>
                                        <v-list>
                                            <v-list-tile @click="acceptFriendRequest(friend)">
                                                <v-list-tile-title>Accept Request</v-list-tile-title>
                                            </v-list-tile>
                                            <v-list-tile @click="denyFriendRequest(friend)">
                                                <v-list-tile-title>Deny Request</v-list-tile-title>
                                            </v-list-tile>
                                        </v-list>
                                    </v-menu>
                                </v-card-actions>
                                <v-card-title class="pt-0 mt-0">
                                    <v-avatar size="80px">
                                        <img
                                                class="img-circle elevation-7 mb-1"
                                                :src="`https://randomuser.me/api/portraits/men/${i + 20}.jpg`"
                                        >
                                    </v-avatar>
                                    <p class="subheader">{{ friend.first_name }} <span style="font-weight:bold">{{ friend.last_name }}</span>
                                    </p>
                                </v-card-title>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
            <v-flex xs10 offset-xs1 v-if="friends.length">
                <v-subheader>Friends</v-subheader>
                <v-container fluid grid-list-sm>
                    <v-layout row wrap>
                        <v-flex v-for="(friend, i) in friends" :key="i" xs4 class="pb-2">
                            <v-card>
                                <v-card-actions class="pb-0 mb-0">
                                    <v-spacer></v-spacer>
                                    <v-menu
                                            open-on-hover
                                            bottom
                                            offset-y
                                    >
                                        <v-btn
                                                flat class="white--text"
                                                slot="activator"
                                        >op
                                            <v-icon color="cyan">more_vert</v-icon>
                                        </v-btn>
                                        <v-list>
                                            <v-list-tile @click="blockFriend(friend)">
                                                <v-list-tile-title>Block</v-list-tile-title>
                                            </v-list-tile>
                                            <v-list-tile @click="unfriend(friend)">
                                                <v-list-tile-title>Unfriend</v-list-tile-title>
                                            </v-list-tile>
                                        </v-list>
                                    </v-menu>
                                </v-card-actions>
                                <v-card-title class="pt-0 mt-0">
                                    <v-avatar size="80px">
                                        <img
                                                class="img-circle elevation-7 mb-1"
                                                :src="`https://randomuser.me/api/portraits/men/${i + 20}.jpg`"
                                        >
                                    </v-avatar>
                                    <p class="subheader">{{ friend.first_name }} <span style="font-weight:bold">{{ friend.last_name }}</span>
                                    </p>
                                </v-card-title>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
            <v-flex xs10 offset-xs1 v-if="blockedByMe.length">
                <v-subheader>Friends Blocked By Me</v-subheader>
                <v-container fluid grid-list-sm>
                    <v-layout row wrap>
                        <v-flex v-for="(friend, i) in blockedByMe" :key="i" xs4 class="pb-2">
                            <v-card>
                                <v-card-actions class="pb-0 mb-0">
                                    <v-spacer></v-spacer>
                                    <v-menu
                                            open-on-hover
                                            bottom
                                            offset-y
                                    >
                                        <v-btn
                                                flat class="white--text"
                                                slot="activator"
                                        >op
                                            <v-icon color="cyan">more_vert</v-icon>
                                        </v-btn>
                                        <v-list>
                                            <v-list-tile @click="unblockFriend(friend)">
                                                <v-list-tile-title>Unblock</v-list-tile-title>
                                            </v-list-tile>
                                        </v-list>
                                    </v-menu>
                                </v-card-actions>
                                <v-card-title class="pt-0 mt-0">
                                    <v-avatar size="80px">
                                        <img
                                                class="img-circle elevation-7 mb-1"
                                                :src="`https://randomuser.me/api/portraits/men/${i + 20}.jpg`"
                                        >
                                    </v-avatar>
                                    <p class="subheader">{{ friend.first_name }} <span style="font-weight:bold">{{ friend.last_name }}</span>
                                    </p>
                                </v-card-title>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
            <v-flex xs10 offset-xs1 v-if="blockedMe.length">
                <v-subheader>Friends Blocked Me</v-subheader>
                <v-container fluid grid-list-sm>
                    <v-layout row wrap>
                        <v-flex v-for="(friend, i) in blockedMe" :key="i" xs4 class="pb-2">
                            <v-card>
                                <v-card-title>
                                    <v-avatar size="80px">
                                        <img
                                                class="img-circle elevation-7 mb-1"
                                                :src="`https://randomuser.me/api/portraits/men/${i + 20}.jpg`"
                                        >
                                    </v-avatar>
                                    <p class="subheader">{{ friend.first_name }} <span style="font-weight:bold">{{ friend.last_name }}</span>
                                    </p>
                                </v-card-title>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
        </v-layout>

        <v-layout row justify-center>
            <v-dialog v-model="dialog" width="800px">
                <v-card>
                    <v-toolbar dark color="primary">
                        <v-btn icon dark @click.native="dialog = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>
                            <v-icon left dark>people</v-icon>
                            Find Friends
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-layout row align-center style="max-width: 650px">
                            <v-text-field
                                    v-model="search"
                                    placeholder="Search..."
                                    single-line
                                    append-icon="search"
                                    @click:append="searchNewPeople"
                                    color="white"
                                    hide-details
                            ></v-text-field>
                        </v-layout>
                    </v-toolbar>
                    <v-card-text>
                        <v-container fluid grid-list-sm>
                            <v-layout row wrap>
                                <v-flex xs4 offset-xs4 v-if="message"><div class="text-xs-center info--text">{{ message }}</div></v-flex>
                                <v-flex v-for="(people, i) in peoples" :key="i" xs6 class="pb-2">
                                    <v-card>
                                        <v-card-actions class="pb-0 mb-0">
                                            <v-spacer></v-spacer>
                                            <v-btn small color="info" @click.native="sendFriendRequest(people)">
                                                <v-icon left dark>person_add</v-icon>
                                                {{ people.btnText }}
                                            </v-btn>
                                        </v-card-actions>
                                        <v-card-title>
                                            <v-avatar size="80px">
                                                <img
                                                        class="img-circle elevation-7 mb-1"
                                                        :src="`https://randomuser.me/api/portraits/men/${i + 20}.jpg`"
                                                >
                                            </v-avatar>
                                            <v-tooltip right color="red">
                                            <div class="subheader" slot="activator">{{ people.first_name }} <span style="font-weight:bold">{{ people.last_name }}</span>
                                            </div>
                                                <span>
                                                    <v-card color="red lighten-4">
                                                        <v-list>
                                                            <v-list-tile avatar color="blue lighten-1">
                                                              <v-list-tile-avatar>
                                                                <img :src="`https://randomuser.me/api/portraits/men/${i + 20}.jpg`" alt="">
                                                              </v-list-tile-avatar>
                                                              <v-list-tile-content>
                                                                <v-list-tile-title>{{ people.first_name }} {{ people.last_name }}</v-list-tile-title>
                                                              </v-list-tile-content>
                                                            </v-list-tile>
                                                          </v-list>
                                                          <v-divider></v-divider>
                                                          <v-list>
                                                            <v-list-tile color="blue lighten-1">
                                                              <v-list-tile-title>Member since {{ people.created_at | ago }}</v-list-tile-title>
                                                            </v-list-tile>
                                                          </v-list>
                                                    </v-card>
                                                </span>
                                            </v-tooltip>
                                            <div class="body-2 gray--text px-3">{{ people.occupation }}</div>
                                        </v-card-title>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-layout>

    </v-container>
</template>

<script>
    import moment from 'moment'
    import {mapGetters} from 'vuex'

    export default {
        name: "AppFriends",
        data() {
            return {
                dialog: false,
                friends: {},
                sentRequests: {},
                receivedRequests: {},
                blockedByMe: {},
                blockedMe: {},
                peoples: {},
                search: '',
                message: '',
                addedPeople: []
            }
        },
        created() {
            this.fetchFriendsList();
        },
        computed: {
            ...mapGetters({
                authUser: 'user'
            })
        },
        methods: {
            fetchFriendsList() {
                axios.get('/api/user/friends')
                    .then(response => {
                        this.friends = response.data.friends;
                        this.sentRequests = response.data.sentRequests;
                        this.receivedRequests = response.data.receivedRequests;
                        this.blockedByMe = response.data.blockedByMe;
                        this.blockedMe = response.data.blockedMe;
                    })
                    .catch(error => console.log(error))
            },
            fetchRandomPeople() {
                this.message = '';
                this.peoples = {};
                this.addedPeople = [];
                axios.get('/api/user/random-people')
                    .then(response => {
                        this.peoples = response.data.peoples;
                        for (let i = 0; i < this.peoples.length; i++) {
                            this.peoples[i].btnText = 'Add Friend';
                        }
                        this.dialog = true;
                        if (this.peoples.length)
                            this.message = 'People you may know';
                    })
                    .catch(error => console.log(error))
            },
            searchNewPeople() {
                if (this.search !== "") {
                    this.message = '';
                    axios.get(`/api/user/search/${this.search}`)
                        .then(response => {
                            this.peoples = {};
                            this.peoples = response.data.peoples;
                            for (let i = 0; i < this.peoples.length; i++) {
                                this.peoples[i].btnText = 'Add Friend';
                            }
                            this.search = '';
                            if (!this.peoples.length)
                                this.message = 'Nobody matched';
                        })
                        .catch(error => console.log(error))
                }
            },
            blockFriend(friend) {
                axios.post('/api/user/block-friend-request', {user: friend.slug})
                    .then(response => {
                        console.log(response);
                        let index = -1;
                        for (let i = 0; i < this.friends.length && index < 0; i++) {
                            if (this.friends[i].slug === friend.slug)
                                index = i;
                        }
                        this.friends.splice(index, 1);
                        this.blockedByMe.push(friend);
                    })
                    .catch(error => console.log(error))
            },
            unblockFriend(friend) {
                axios.post('/api/user/unblock-friend-request', {user: friend.slug})
                    .then(response => {
                        console.log(response);
                        let index = -1;
                        for (let i = 0; i < this.blockedByMe.length && index < 0; i++) {
                            if (this.blockedByMe[i].slug === friend.slug)
                                index = i;
                        }
                        this.blockedByMe.splice(index, 1);
                        this.friends.push(friend);
                    })
                    .catch(error => console.log(error))
            },
            unfriend(friend) {
                axios.post('/api/user/unfriend-request', {user: friend.slug})
                    .then(response => {
                        console.log(response);
                        let index = -1;
                        for (let i = 0; i < this.friends.length && index < 0; i++) {
                            if (this.friends[i].slug === friend.slug)
                                index = i;
                        }
                        this.friends.splice(index, 1);
                    })
                    .catch(error => console.log(error))
            },
            cancelFriendRequest(friend) {
                axios.post('/api/user/cancel-friend-request', {user: friend.slug})
                    .then(response => {
                        console.log(response);
                        let index = -1;
                        for (let i = 0; i < this.sentRequests.length && index < 0; i++) {
                            if (this.sentRequests[i].slug === friend.slug)
                                index = i;
                        }
                        this.sentRequests.splice(index, 1);
                    })
                    .catch(error => console.log(error))
            },
            acceptFriendRequest(friend) {
                axios.post('/api/user/accept-friend-request', {user: friend.slug})
                    .then(response => {
                        console.log(response);
                        let index = -1;
                        for (let i = 0; i < this.receivedRequests.length && index < 0; i++) {
                            if (this.receivedRequests[i].slug === friend.slug)
                                index = i;
                        }
                        this.receivedRequests.splice(index, 1);
                        this.friends.push(friend);
                    })
                    .catch(error => console.log(error))
            },
            denyFriendRequest(friend) {
                axios.post('/api/user/deny-friend-request', {user: friend.slug})
                    .then(response => {
                        console.log(response);
                        let index = -1;
                        for (let i = 0; i < this.receivedRequests.length && index < 0; i++) {
                            if (this.receivedRequests[i].slug === friend.slug)
                                index = i;
                        }
                        this.receivedRequests.splice(index, 1);
                    })
                    .catch(error => console.log(error))
            },
            sendFriendRequest(people) {
                let notAdded = true;
                for (let i = 0; i < this.addedPeople.length && notAdded; i++) {
                    if (this.addedPeople[i] === people.slug)
                        notAdded = false;
                }
                if (notAdded) {
                    this.addedPeople.push(people.slug);
                    axios.post('/api/user/add-friend-request', {user: people.slug})
                        .then(response => {
                            console.log(response);
                            let index = -1;
                            for (let i = 0; i < this.peoples.length && index < 0; i++) {
                                if (this.peoples[i].slug === people.slug)
                                    index = i;
                            }
                            this.peoples[index].btnText = 'Request sent';
                            this.sentRequests.push(people);
                        })
                }
            }
        },
        filters: {
            ago(data) {
                return moment(data).fromNow();
            }
        }
    }
</script>

<style scoped>

</style>