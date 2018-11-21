<template>
    <v-stepper v-model="e1">
        <v-stepper-header>
            <template v-for="(trip, tripIndex) in item.trips">
                <v-stepper-step
                        :key="`${tripIndex+1}-step`"
                        :step="`${tripIndex+1}`"
                        :complete="e1 > `${tripIndex+1}`"
                >Trip: #{{ tripIndex+1 }}
                </v-stepper-step>
                <v-divider v-if="tripIndex+1 !== item.trips.length" :key="tripIndex"></v-divider>
            </template>
        </v-stepper-header>
        <v-stepper-items>
            <v-stepper-content
                    :step="`${tripIndex+1}`"
                    v-for="(trip, tripIndex) in item.trips"
                    :key="`${tripIndex+1}-content`"
            >
                <v-container grid-list-md text-xs-center>
                    <v-layout row wrap>
                        <v-flex xs6 sm3>
                            <v-card height="100">
                                <v-card-text class="px-0">From: {{ trip.from }}</v-card-text>
                                <v-card-text class="px-0">Start journey at: {{ trip.startJourney }}</v-card-text>
                            </v-card>
                        </v-flex>
                        <v-flex xs6 sm3>
                            <v-card height="100">
                                <v-icon color="indigo" x-large>trending_flat</v-icon>
                                <v-card-text>{{ trip.distance }} Km</v-card-text>
                            </v-card>
                        </v-flex>
                        <v-flex xs6 sm3>
                            <v-card height="100">
                                <v-card-text class="px-0">To: {{ trip.to }}</v-card-text>
                                <v-card-text class="px-0">End journey at: {{ trip.endJourney }}</v-card-text>
                            </v-card>
                        </v-flex>
                        <v-flex xs6 sm3 v-if="trip.visit">
                            <v-card height="100">
                                <v-card-text class="px-0">
                                    Depart at: {{ trip.visit}}
                                </v-card-text>
                            </v-card>
                        </v-flex>
                        <v-flex xs4 offset-xs4>
                            <v-btn fab small dark @click="backStep(tripIndex+1)" :disabled="!tripIndex">
                                <v-icon dark>arrow_back</v-icon>
                            </v-btn>
                            <v-btn fab small dark color="primary" @click="nextStep(tripIndex+1)" :disabled="tripIndex+1 === item.trips.length">
                                <v-icon dark>arrow_forward</v-icon>
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-stepper-content>
        </v-stepper-items>
    </v-stepper>
</template>

<script>
    export default {
        name: "app-stepper",
        props: [
            'item'
        ],
        data() {
            return {
                e1: 0,
            }
        },
        mounted() {
          console.log(this.item);
        },
        methods: {
            nextStep(tripIndex) {
                if (tripIndex !== this.item.trips.length)
                    this.e1 = tripIndex + 1;
            },
            backStep(tripIndex) {
                if (tripIndex > 1)
                    this.e1 = tripIndex - 1;
            }
        }
    }
</script>

<style scoped>

</style>