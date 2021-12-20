<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5"> Account </h3>

        <v-card>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
            </v-card-title>

                <v-spacer></v-spacer>

            <v-data-table width="200px" :headers="headers" :items="users" :search="search">
            </v-data-table> 
            
        </v-card>
        <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>{{ error_message}}</v-snackbar>
    </v-main>
</template> 

<script>
export default {
    name: "List",
    data() {
        return {
            load: false,
            snackbar: false,
            error_message: '',
            color: '',
            search: null,
            headers: [
                { text: "Id", align: "start", sortable: true, value: "id" },
                { text: "Name", value: "name" },
                { text: "Username", value: 'username' },
                { text: "Address", value:'address' },
                { text: "Phone Number", value:'phone_num' },
                { text: "Email", value:'email' },
            ],
            users:[],
        };
    },

    methods: {
        // Read Data Menus
        readData() {
            var url = this.$api + '/user';
            this.$http.get(url, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.users = response.data.data;
            })
        },

    },
    mounted() {
        this.readData();
    },
};
</script> 