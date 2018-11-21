<template>
    <v-container>
        <v-layout row wrap align-center>
            <v-flex xs12 md8 offset-md2>
                <div class="text-xs-center">
                    <v-avatar size="125px">
                        <img
                                class="img-circle elevation-7 mb-1"
                                :src="`https://randomuser.me/api/portraits/men/${authUser.id % 25}.jpg`"
                        >
                    </v-avatar>
                    <div class="headline">{{ authUser.first_name }} <span style="font-weight:bold">{{ authUser.last_name }}</span></div>
                    <div class="subheading text-xs-center grey--text pt-1 pb-3" v-if="authUser.occupation">{{ authUser.occupation }}</div>
                    <v-layout align-center>
                        <router-link :to="'/' + authUser.slug + '/friends'" class="body-1" style="text-decoration: none">Friends {{ totalFriends }}</router-link>
                    </v-layout>
                </div>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex'
    export default {
        name: "AppProfile",
        data() {
            return {
                totalFriends: 0,
            }
        },
        created() {
          this.fetchFriendsNumber();
        },
        computed: {
            ...mapGetters({
                authUser: 'user'
            })
        },
        methods: {
            fetchFriendsNumber() {
                axios.get('/api/user/total-friends')
                    .then(response => {
                        this.totalFriends = response.data.totalFriends;
                    })
                    .catch(error => console.log(error))
            }
        }
    }
</script>

<style scoped>

</style>