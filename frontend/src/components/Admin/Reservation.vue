<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5"> Reservation </h3>
        
        <v-card>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
            </v-card-title>

            <v-data-table :headers="headers" :items="reservations" :search="search" >
                
                <template v-on:click="isHidden = true" v-slot:[`item.actions`]="{ item }">
                    <v-btn small  class="mr-2" @click="accHandler(item)"> Accept </v-btn>
                    <v-btn small  @click="denyHandler(item.id)"> Deny </v-btn> 
                </template>
            </v-data-table> 

        </v-card> 

        <v-dialog v-model="dialogAcc" persistent max-width="600px"> 
            <v-card> 
                <v-card-title>
                    <span class="headline">Accepting Reservation</span> 
                </v-card-title> 
                <v-card-text> 
                    <v-container> 
                        <v-text-field v-model="form.num_table" label="Table Number" required ></v-text-field>
                    </v-container> 
                </v-card-text> 
                <v-card-actions>
                    <v-spacer></v-spacer> 
                    <v-btn color="blue darken-1" text @click="cancel"> Cancel </v-btn>
                    <v-btn color="blue darken-1" text @click="accept"> Save </v-btn> 
                </v-card-actions> 
            </v-card> 
        </v-dialog>

        <v-dialog v-model="dialogConfirm" persistent max-width="400px">
          <v-card>
            <v-card-title>
                <span class="headline"> Warning! </span>
            </v-card-title>
            <v-card-text> Are you sure you want to DENY this reservation? </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="dialogConfirm = false">Cancel</v-btn>
                <v-btn color="blue darken-1" text @click="deny">Deny</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>{{ error_message }}</v-snackbar>
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
            dialogAcc: false, 
            dialogConfirm: false,
            headers: [
                { text: "Reservation ID", align: "start", sortable: true, value: "id_reservation",},
                { text: "Booked By", value: "name" },
                { text: "Number of Customers", value: "num_customer" },
                { text: "Booking Date", value: "booking_date" },
                { text: "Booking Time", value: "booking_time" },
                { text: "Room", value:"room"},
                { text: "Table Number", value:"table_num"},
                { text: "Actions", value: "actions" },
            ],
            reservation: new FormData,
            reservations: [],
            form:{
                num_table: ""
            },
            deleteId: '',
            editId: ''
        }
    },


    methods: {
        readData() {
            var url = this.$api + '/reservation';
            this.$http.get(url, {
                headers:{
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.reservations = response.data.data;
            })
        },

        accept() { 
            let newData = { 
                num_table : this.form.num_table, 
                status : 'Reservation Accepted...'
            }; 
            var url = this.$api + '/reservation/' + this.editId; 
            this.load = true; 
            this.$http.put(url, newData, { 
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.error_message = response.data.message; 
                this.color = 'green'; 
                this.snackbar = true; 
                this.load = false; 
                this.close(); 
                this.readData(); // baca data 
                this.resetForm();
            }).catch(error => {
                this.error_message = error.response.data.message; 
                this.color = 'red'; 
                this.snackbar = true; 
                this.load = false;
            });
        },

        deny() { 
            let newData = {
                num_table : '0',
                status : 'Reservation Already Deny...'
            }; 
            var url = this.$api + '/reservation/' + this.deleteId; 
            this.load = true; 
            this.$http.put(url, newData, { 
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.error_message = response.data.message; 
                this.color = 'green'; 
                this.snackbar = true; 
                this.load = false; 
                this.close(); 
                this.readData(); // baca data 
                this.resetForm();
            }).catch(error => {
                this.error_message = error.response.data.message; 
                this.color = 'red'; 
                this.snackbar = true; 
                this.load = false;
            });
        },

        accHandler(item) {
            this.editId = item.id; 
            this.form.room = item.room; 
            this.form.num_table = item.num_table;
            this.dialogAcc = true;
        },

        denyHandler(id) {
            this.deleteId = id; 
            this.dialogConfirm = true;
        },

        close() {
            this.dialogAcc = false; 
            this.dialogConfirm = false; 
            this.readData();
        },

        cancel() {
            this.resetForm(); 
            this.readData(); 
            this.dialogAcc = false; 
            this.dialogConfirm = false; 
        },

        resetForm() { 
            this.form = {
                num_table: ""
            };
        },
    },

    mounted() {
        this.readData();
    },
};
</script>