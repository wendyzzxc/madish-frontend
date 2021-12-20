<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5"> Menu </h3>

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

            <v-data-table width="200px" :headers="headers" :items="menus" :search="search">
                <template v-slot:[`item.status`]="{ item }">
                    <v-chip
                        :color="getColor(item.status)"
                        dark
                        outlined
                    >
                    {{ item.status }}
                    </v-chip>
                </template>
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
                { text: "Name", align: "start", sortable: true, value: "nama_menu" },
                { text: "Category", value: 'kategori' },
                { text: "Price", value:'harga' },
                { text: "Status", value:'status' },
            ],
            menus:[],
        };
    },

    methods: {
        // Read Data Menus
        readData() {
            var url = this.$api + '/menu';
            this.$http.get(url, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.menus = response.data.data;
            })
        },

        getColor (status) {
            if (status == 'Tersedia') return 'green'
            else return 'red'
            },

    },
    mounted() {
        this.readData();
    },
};
</script> 