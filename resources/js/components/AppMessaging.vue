<template>
    <v-container fluid>
        <v-layout row wrap>
            <v-flex xs3>
                <v-card>
                    <v-toolbar color="indigo" dark>
                        <v-toolbar-title>Conversation</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <!--<v-btn icon>
                            <v-icon>search</v-icon>
                        </v-btn>-->
                        <v-btn icon @click.native="dialog1 = true">
                            <v-icon>create</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-list>
                        <v-list-tile
                                avatar
                                v-for="(conv, index) in conversations"
                                :key="index"
                                :color="isSelected(conv.id)"
                                @click="setSelectedConversation(conv.id)">
                            <v-list-tile-content>
                                <v-list-tile-title v-if="conv.name" v-text="conv.name"></v-list-tile-title>
                                <v-list-tile-title v-else>
                                    <span v-for="(user, index) in conv.users" :key="index">
                                        {{ user.first_name }}
                                        <span v-if="index + 1 < conv.users.length">,</span>
                                    </span>
                                </v-list-tile-title>
                            </v-list-tile-content>
                            <v-avatar size="25px" v-for="(user, index) in conv.users" :key="index">
                                <img :src="`https://randomuser.me/api/portraits/men/${user.id % 25}.jpg`">
                            </v-avatar>
                            <v-spacer></v-spacer>
                            <v-list-tile-action v-if="conv.unread_messages">
                                <v-badge color="cyan" left>
                                    <span slot="badge">{{conv.unread_messages}}</span>
                                    <v-icon large color="grey lighten-1">mail</v-icon>
                                </v-badge>
                            </v-list-tile-action>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>

            <v-flex xs7 v-if="selectedConversation">
                <v-card>
                    <v-toolbar color="pink" dark>
                        <v-toolbar-title>Message</v-toolbar-title>
                    </v-toolbar>

                    <div class="direct-chat direct-chat-primary list-scroll" @mousemove="readUnreadMessages">
                        <div class="direct-chat-messages" v-for="(message, index) in conversation.messages"
                             :key="index">

                            <div class="direct-chat-msg right" v-if="authUser.slug === message.user.slug">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-right">{{ message.user.first_name }} {{ message.user.last_name}}</span>
                                    <span class="direct-chat-timestamp">{{ message.created_at | ago }}</span>
                                </div>

                                <img class="direct-chat-img"
                                     :src="`https://randomuser.me/api/portraits/men/${message.user.id % 25}.jpg`"
                                     alt="Message User Image">
                                <div class="direct-chat-text">
                                    {{ message.body }}
                                </div>

                            </div>

                            <div class="direct-chat-msg" v-else>
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-left">{{ message.user.first_name }} {{ message.user.last_name}}</span>
                                    <span class="direct-chat-timestamp">{{ message.created_at | ago }}</span>
                                </div>

                                <img class="direct-chat-img"
                                     :src="`https://randomuser.me/api/portraits/men/${message.user.id % 25}.jpg`"
                                     alt="Message User Image">
                                <div class="direct-chat-text">
                                    {{ message.body }}
                                </div>
                            </div>

                            <div class="clearfix">
                                <template v-for="reader in message.readers">
                                    <v-tooltip top v-if="reader.read_at && reader.user.slug !== authUser.slug">
                                        <v-avatar slot="activator" size="20px"><img
                                                :src="`https://randomuser.me/api/portraits/men/${reader.user.id % 25}.jpg`">
                                        </v-avatar>
                                        <span>{{ reader.user.first_name}} read at {{ reader.read_at | ago }}</span>
                                    </v-tooltip>
                                </template>
                            </div>
                        </div>
                    </div>

                </v-card>
                <v-divider></v-divider>
                <v-card color="grey lighten-4" flat>
                    <v-text-field
                            name="message"
                            label="Message"
                            textarea
                            rows="3"
                            v-model="message"
                            @keyup.enter="sendMessage"
                    ></v-text-field>
                </v-card>

            </v-flex>

            <v-flex xs2 v-if="selectedConversation">
                <v-card>
                    <v-toolbar color="indigo" dark>
                        <v-toolbar-title>People</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn icon @click.native="dialog3 = true">
                            <v-icon color="light-green lighten-4">add</v-icon>
                        </v-btn>
                        <v-btn icon @click.native="dialog4 = true">
                            <v-icon color="red lighten-1">remove</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-list>
                        <v-list-tile avatar v-for="(user, index) in conversation.users" :key="index" @click="">
                            <v-list-tile-avatar>
                                <img :src="`https://randomuser.me/api/portraits/men/${user.id % 25}.jpg`">
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <!--<v-icon>check_circle</v-icon>-->
                                <v-list-tile-title v-text="user.name"></v-list-tile-title>
                            </v-list-tile-content>
                            <v-list-tile-action v-if="user.slug === authUser.slug">
                                <v-btn icon @click.native="dialog2 = true">
                                    <v-icon color="red lighten-2">cancel</v-icon>
                                </v-btn>
                            </v-list-tile-action>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>
        </v-layout>


        <v-layout row justify-center>
            <v-dialog v-model="dialog1" persistent max-width="500px">
                <v-card>
                    <v-card-title>
                        <span class="headline">Create Conversation</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-text-field
                                            label="Conversation Name"
                                            single-line
                                            v-model="conversationName"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs12>
                                    <v-autocomplete
                                            label="Select People"
                                            :items="peoplesList"
                                            v-model="people"
                                            item-text="name"
                                            item-value="slug"
                                            multiple
                                            chips
                                            max-height="250"
                                            required
                                            :rules="[() => people.length > 0 || 'You must add people']"
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
                                                    <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
                                                    <!--<v-list-tile-sub-title v-html="data.item.group"></v-list-tile-sub-title>-->
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
                        <v-btn color="blue darken-1" flat @click.native="createConversation">Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>


        <v-layout row justify-center>
            <v-dialog v-model="dialog2" persistent max-width="280">
                <v-card>
                    <v-card-title class="headline">Are you sure to leave?</v-card-title>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat="flat" @click.native="dialog2 = false">No</v-btn>
                        <v-btn color="green darken-1" flat="flat" @click.native="leaveConversation">Yes</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>


        <v-layout row justify-center>
            <v-dialog v-model="dialog3" persistent max-width="500px">
                <v-card>
                    <v-card-title>
                        <span class="headline">Add People</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-autocomplete
                                            label="Select People"
                                            :items="peoplesListForAdd"
                                            v-model="people"
                                            item-text="name"
                                            item-value="slug"
                                            multiple
                                            chips
                                            max-height="250"
                                            required
                                            :rules="[() => people.length > 0 || 'You must select people']"
                                            :search-input.sync="searchToAdd"
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
                                                    <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
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
                        <v-btn color="blue darken-1" flat @click.native="dialog3 = false">Close</v-btn>
                        <v-btn color="blue darken-1" flat @click.native="addPeople">Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>

        <v-layout row justify-center>
            <v-dialog v-model="dialog4" persistent max-width="500px">
                <v-card>
                    <v-card-title>
                        <span class="headline">Remove People</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-autocomplete
                                            label="Select People"
                                            :items="conversationUsers"
                                            v-model="people"
                                            item-text="name"
                                            item-value="slug"
                                            multiple
                                            chips
                                            max-height="250"
                                            required
                                            :rules="[() => people.length > 0 || 'You must select people']"
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
                                                    <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
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
                        <v-btn color="blue darken-1" flat @click.native="dialog4 = false">Close</v-btn>
                        <v-btn color="blue darken-1" flat @click.native="removePeople">Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>


    </v-container>
</template>

<script>
    import moment from 'moment'
    import {mapGetters} from 'vuex'
    import {SET_UNREAD_MESSAGES} from "../store/actions";

    export default {
        name: "app-messaging",
        data() {
            return {
                dialog1: false,
                dialog2: false,
                dialog3: false,
                dialog4: false,
                conversationName: '',
                selectedConversation: null,
                conversation: {},
                people: [],
                search: null,
                searchToAdd: null,
                conversations: {},
                message: '',
                peoplesList: [],
                peoplesListForAdd: [],
                conversationUsers: [],
                callForRead: false,
            }
        },
        created() {
            this.fetchConversations();
            this.listenNewConversation();
        },
        watch: {
            search(val) {
                val && this.searchPeople(val);
            },
            searchToAdd(val) {
                val && this.searchPeopleToAdd(val);
            },
        },
        mounted() {
            this.scrollToEnd();
        },
        computed: {
            ...mapGetters({
                authUser: 'user'
            })
        },
        methods: {
            scrollToEnd() {
                if (this.selectedConversation) {
                    let content = document.querySelector(".list-scroll");
                    content.scrollTop = content.scrollHeight;
                }
            },
            searchPeople(val) {
                axios.get(`/api/conversation/search-people/${val}`)
                    .then(response => {
                        let peoples = response.data.peoples;
                        let notFound = true;
                        for (let i = 0; i < peoples.length; i++) {
                            notFound = true;
                            for (let j = 0; j < this.peoplesList.length && notFound; j++) {
                                if (peoples[i].slug === this.peoplesList[j].slug)
                                    notFound = false;
                            }
                            if (notFound) {
                                this.peoplesList.push(peoples[i]);
                            }
                        }
                        console.log(peoples);
                    })
                    .catch(response => console.log(response))
            },
            searchPeopleToAdd(val) {
                axios.get(`/api/conversation/search-people/${val}`)
                    .then(response => {
                        let peoples = response.data.peoples;
                        let notFound = true;
                        for (let i = 0; i < peoples.length; i++) {
                            notFound = true;
                            for (let j = 0; j < this.peoplesListForAdd.length && notFound; j++) {
                                if (peoples[i].slug === this.peoplesListForAdd[j].slug)
                                    notFound = false;
                            }
                            for (let j = 0; j < this.conversation.users.length && notFound; j++) {
                                if (peoples[i].slug === this.conversation.users[j].slug)
                                    notFound = false;
                            }
                            if (notFound) {
                                this.peoplesListForAdd.push(peoples[i]);
                            }
                        }
                        console.log(peoples);
                    })
                    .catch(response => console.log(response))
            },
            fetchConversations() {
                axios.get('/api/conversation')
                    .then(response => {
                        this.conversations = response.data.conversations;
                        if (response.data.conversations.length) {
                            this.setSelectedConversation(response.data.conversations[0].id);
                            this.conversations.forEach(conv => {
                                this.listenConversation(conv.id);
                                console.log(`listening ${conv.id}`);
                            })
                        }
                        console.log(response.data.conversations)
                    })
                    .catch(response => console.log(response))
            },
            fetchConversationDetails() {
                if (this.selectedConversation) {
                    axios.get(`/api/conversation/${this.selectedConversation}`)
                        .then(response => {
                            this.conversation = response.data.conversation;

                            for (let i = 0; i < this.conversation.users.length; i++) {
                                if (this.conversation.users[i].slug !== this.$store.getters.user.slug)
                                    this.conversationUsers.push(this.conversation.users[i]);
                            }
                            console.log(response.data.conversation);
                        })
                        .catch(response => console.log(response))
                }
            },
            sendMessage() {
                if (this.message.length && this.selectedConversation) {
                    const fd = new FormData();
                    fd.append('id', this.selectedConversation);
                    fd.append('body', this.message);
                    let tempMessage = this.message;
                    this.message = '';
                    axios.post('/api/conversation/message', fd, {
                        onUploadProgress: uploadEvent => {
                            console.log('Uploading' + Math.round(uploadEvent.loaded / uploadEvent.total * 100) + '%');
                        }
                    })
                        .then(response => {
                            this.conversation.messages.push(response.data.message);
                        })
                        .catch(response => {
                            this.message = tempMessage;
                            console.log(response);
                        })
                }
            },
            isSelected(id) {
                return id === this.selectedConversation ? 'blue lighten-3' : '';
            },
            setSelectedConversation(id) {
                this.selectedConversation = id;
                this.fetchConversationDetails();
            },
            createConversation() {
                if (this.people.length) {
                    const fd = new FormData();
                    fd.append('name', this.conversationName);
                    for (let i = 0; i < this.people.length; i++) {
                        fd.append('users[' + i + ']', this.people[i]);
                    }
                    axios.post('/api/conversation', fd)
                        .then(response => {
                            this.dialog1 = false;
                            this.conversations.unshift(response.data.conversation);
                            this.setSelectedConversation(response.data.conversation.id);
                            this.conversationName = '';
                            this.people = [];
                            this.peoplesList = [];

                            this.listenConversation(response.data.conversation.id);
                            console.log(`listening ${response.data.conversation.id}`);
                        })
                        .catch(response => console.log(response))
                }
            },
            readUnreadMessages() {
                if (this.selectedConversation && !this.callForRead) {
                    this.callForRead = true;
                    let selectedConversationIndex = this.searchSelectedConversationIndex();

                    if (this.conversations[selectedConversationIndex].unread_messages) {
                        const fd = new FormData();
                        fd.append('id', this.selectedConversation);
                        axios.post('/api/conversation/mark-as-read', fd)
                            .then(response => {
                                this.$store.dispatch(SET_UNREAD_MESSAGES, response.data.totalUnreadMessages);
                                this.conversations[selectedConversationIndex].unread_messages = 0;
                                this.callForRead = false;
                            })
                            .catch(response => {
                                this.callForRead = false;
                                console.log(response);
                            });
                    }
                    else this.callForRead = false;
                }
            },
            addPeople() {
                if (this.people.length && this.selectedConversation) {
                    const fd = new FormData();
                    fd.append('id', this.selectedConversation);
                    for (let i = 0; i < this.people.length; i++) {
                        fd.append('users[' + i + ']', this.people[i]);
                    }
                    axios.post('/api/conversation/add-user', fd)
                        .then(response => {
                            let addedUsers = response.data.addedUsers;
                            this.dialog3 = false;
                            let selectedConversationIndex = this.searchSelectedConversationIndex();
                            for (let i = 0; i < addedUsers.length; i++) {
                                this.conversation.users.push(addedUsers[i]);
                                this.conversations[selectedConversationIndex].users.push(addedUsers[i]);
                                this.conversationUsers.push(addedUsers[i]);
                            }

                            this.people = [];
                            this.peoplesListForAdd = [];
                        })
                        .catch(response => console.log(response))
                }
            },
            removePeople() {
                if (this.people.length && this.selectedConversation) {
                    const fd = new FormData();
                    fd.append('id', this.selectedConversation);
                    for (let i = 0; i < this.people.length; i++) {
                        fd.append('users[' + i + ']', this.people[i]);
                    }
                    axios.post('/api/conversation/remove-user', fd)
                        .then(response => {
                            console.log(response);
                            this.dialog4 = false;
                            let selectedConversationIndex = this.searchSelectedConversationIndex();
                            for (let i = 0; i < this.people.length; i++) {
                                let removed = false;
                                for (let j = 0; j < this.conversation.users.length && !removed; j++) {
                                    if (this.people[i] === this.conversation.users[j].slug) {
                                        this.conversation.users.splice(j, 1);
                                        this.conversations[selectedConversationIndex].users.splice(j, 1);
                                        removed = true;
                                    }
                                }

                                removed = false;
                                for (let j = 0; j < this.conversationUsers.length && !removed; j++) {
                                    if (this.people[i] === this.conversationUsers[j].slug) {
                                        this.conversationUsers.splice(j, 1);
                                        removed = true;
                                    }
                                }
                            }

                            this.people = [];
                        })
                        .catch(response => console.log(response))
                }
            },
            leaveConversation() {
                const fd = new FormData();
                fd.append('id', this.selectedConversation);
                axios.post('/api/conversation/leave', fd)
                    .then(response => {
                        console.log(response);
                        this.dialog2 = false;
                        let selectedConversationIndex = this.searchSelectedConversationIndex();
                        this.conversations.splice(selectedConversationIndex, 1);
                        window.Echo.leave('conversation.' + this.selectedConversation);
                        if (this.conversations.length) {
                            this.setSelectedConversation(this.conversations[0].id);
                        }
                        else {
                            this.selectedConversation = null;
                            this.conversation = {};
                            this.conversationUsers = [];
                        }
                    })
                    .catch(response => console.log(response))
            },
            searchSelectedConversationIndex() {
                let selectedConversationIndex = -1;
                for (let i = 0; i < this.conversations.length && selectedConversationIndex < 0; i++) {
                    if (this.selectedConversation === this.conversations[i].id)
                        selectedConversationIndex = i;
                }
                return selectedConversationIndex;
            },
            listenConversation(id) {
                Echo.private('conversation.' + id)
                    .listen('NewMessage', (newMessage) => {
                        console.log('new message');
                        console.log(newMessage);
                        let notIncrement = true;
                        for (let i = 0; i < this.conversations.length && notIncrement; i++) {
                            if (newMessage.conversation_id === this.conversations[i].id) {
                                this.conversations[i].unread_messages++;
                                notIncrement = false;
                            }
                        }

                        if (newMessage.conversation_id === this.selectedConversation)
                            this.conversation.messages.push(newMessage);
                    })
                    .listen('AddUser', (addUser) => {
                        console.log('add user');
                        console.log(addUser);
                        let index = -1;
                        for (let i = 0; i < this.conversations.length && index < 0; i++) {
                            if (addUser.conversation_id === this.conversations[i].id)
                                index = i;
                        }

                        for (let i = 0; i < addUser.addedUsers.length; i++) {
                            this.conversations[index].users.push(addUser.addedUsers[i]);
                            if (addUser.conversation_id === this.selectedConversation) {
                                this.conversation.users.push(addUser.addedUsers[i]);
                                this.conversationUsers.push(addUser.addedUsers[i]);
                            }
                        }
                    })
                    .listen('RemoveUser', (removeUser) => {
                        console.log('remove user');
                        console.log(removeUser);
                        let index = -1;
                        for (let i = 0; i < this.conversations.length && index < 0; i++) {
                            if (removeUser.conversation_id === this.conversations[i].id)
                                index = i;
                        }

                        let removeConversation = false;
                        for (let i = 0; i < removeUser.removedUsers.length && !removeConversation; i++) {

                            if (removeUser.removedUsers[i] === this.$store.getters.user.slug) {
                                this.conversations.splice(index, 1);
                                window.Echo.leave('conversation.' + removeUser.conversation_id);
                                if (removeUser.conversation_id === this.selectedConversation) {
                                    if (this.conversations.length) {
                                        this.setSelectedConversation(this.conversations[index].id);
                                    }
                                    else {
                                        this.selectedConversation = null;
                                        this.conversation = {};
                                        this.conversationUsers = [];
                                    }
                                }
                                removeConversation = true;
                            }
                            else {
                                let removed = false;
                                for (let j = 0; j < this.conversations[index].users.length && !removed; j++) {
                                    if (removeUser.removedUsers[i] === this.conversations[index].users[j].slug) {
                                        this.conversations[index].users.splice(j, 1);
                                        if (removeUser.conversation_id === this.selectedConversation)
                                            this.conversation.users.splice(j, 1);
                                        removed = true;
                                    }
                                }
                                if (removeUser.conversation_id === this.selectedConversation) {
                                    removed = false;
                                    for (let j = 0; j < this.conversationUsers.length && !removed; j++) {
                                        if (removeUser.removedUsers[i] === this.conversationUsers[j].slug) {
                                            this.conversationUsers.splice(j, 1);
                                            removed = true;
                                        }
                                    }
                                }
                            }
                        }
                    })
                    .listen('MarkRead', (markRead) => {
                        console.log('mark read');
                        console.log(markRead);
                        if (markRead.conversation_id === this.selectedConversation) {
                            for (let i = 0; i < this.conversation.messages.length; i++) {
                                for (let j = 0; j < this.conversation.messages[i].readers.length; j++) {
                                    if (!this.conversation.messages[i].readers[j].read_at &&
                                        this.conversation.messages[i].readers[j].user.slug === markRead.reader) {
                                        this.conversation.messages[i].readers[j].read_at = markRead.read_at;
                                    }
                                }
                            }
                        }
                    })
            },
            listenNewConversation() {
                Echo.private('App.Model.User.' + this.$store.getters.user.slug)
                    .listen('NewConversation', (newConversation) => {
                        console.log('new conversation');
                        console.log(newConversation);
                        newConversation.conversation['unread_messages'] = 0;
                        this.conversations.unshift(newConversation.conversation);
                        if (!this.selectedConversation) {
                            this.setSelectedConversation(newConversation.conversation.id);
                        }
                        this.listenConversation(newConversation.conversation.id);
                        console.log(`listening ${newConversation.conversation.id}`);
                    })
                    .listen('RemoveConversation', (removeConversation) => {
                        console.log('remove conversation');
                        console.log(removeConversation);
                        let removed = false;
                        for (let i = 0; i < this.conversations.length && !removed; i++) {
                            if (removeConversation.conversationId === this.conversations[i].id) {
                                window.Echo.leave('conversation.' + this.conversations[i].id);
                                this.conversations.splice(i, 1);

                                if (this.conversations.length) {
                                    if (this.selectedConversation === removeConversation.conversationId)
                                        this.setSelectedConversation(this.conversations[i].id);
                                }
                                else {
                                    this.selectedConversation = null;
                                    this.conversation = {};
                                    this.conversationUsers = [];
                                }
                                removed = true;
                            }
                        }
                    })
            }
        },
        filters: {
            ago(data) {
                return moment(data).fromNow();
            },
            imgUrl(url) {
                return url.replace('public', '/storage');
            }
        },
        updated() {
            this.scrollToEnd();
        }

    }
</script>

<style scoped>

    .list-scroll {
        height: 400px;
        overflow-y: auto;
    }

    .direct-chat-messages {
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        -o-transform: translate(0, 0);
        transform: translate(0, 0);
        padding: 10px;
    }

    .direct-chat-msg,
    .direct-chat-text {
        display: block
    }

    .direct-chat-msg {
        margin-bottom: 10px
    }

    .direct-chat-msg:before,
    .direct-chat-msg:after {
        content: " ";
        display: table
    }

    .direct-chat-msg:after {
        clear: both
    }

    .direct-chat-text {
        border-radius: 5px;
        position: relative;
        padding: 5px 10px;
        background: #d2d6de;
        border: 1px solid #d2d6de;
        margin: 5px 0 0 50px;
        color: #444;
        width: fit-content;
    }

    .direct-chat-text:after,
    .direct-chat-text:before {
        position: absolute;
        right: 100%;
        top: 15px;
        border: solid transparent;
        border-right-color: #d2d6de;
        content: ' ';
        height: 0;
        width: 0;
        pointer-events: none
    }

    .direct-chat-text:after {
        border-width: 5px;
        margin-top: -5px
    }

    .direct-chat-text:before {
        border-width: 6px;
        margin-top: -6px
    }

    .right .direct-chat-text {
        margin-right: 50px;
        margin-left: 0
    }

    .right .direct-chat-text:after,
    .right .direct-chat-text:before {
        right: auto;
        left: 100%;
        border-right-color: transparent;
        border-left-color: #d2d6de
    }

    .direct-chat-img {
        border-radius: 50%;
        float: left;
        width: 40px;
        height: 40px
    }

    .right .direct-chat-img {
        float: right
    }

    .direct-chat-info {
        display: block;
        margin-bottom: 2px;
        font-size: 12px
    }

    .direct-chat-name {
        font-weight: 600
    }

    .direct-chat-timestamp {
        color: #6d94fb;
        margin: 0 30px;
    }

    .direct-chat-primary .right > .direct-chat-text {
        background: #3c8dbc;
        border-color: #3c8dbc;
        color: #fff
    }

    .direct-chat-primary .right > .direct-chat-text:after,
    .direct-chat-primary .right > .direct-chat-text:before {
        border-left-color: #3c8dbc
    }

    .direct-chat-info .right > .direct-chat-text {
        background: #00c0ef;
        border-color: #00c0ef;
        color: #fff
    }

    .direct-chat-info .right > .direct-chat-text:after,
    .direct-chat-info .right > .direct-chat-text:before {
        border-left-color: #00c0ef
    }

    .clearfix:after,
    .clearfix:before {
        display: table;
        content: " ";
        clear: both
    }

    .pull-right {
        float: right !important
    }

    .pull-left {
        float: left !important
    }


</style>