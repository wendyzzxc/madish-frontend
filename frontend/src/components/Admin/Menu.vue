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

                <v-btn color="success" dark @click="dialog = true"> Tambah </v-btn>
            </v-card-title>
            <!-- <v-spacer></v-spacer> -->
            <v-data-table :headers="headers" :items="menus" :search="search">
                <template v-slot:[`item.status`]="{ item }">
                    <v-chip
                        :color="getColor(item.status)"
                        dark
                        outlined
                    >
                    {{ item.status }}
                    </v-chip>
                </template>
                <template v-slot:[`item.actions`]="{ item }"> 
                    <v-btn color="cyan white--text" small class="mr-2" @click="editHandler(item)"> edit </v-btn>
                    <v-btn color="red white--text" small @click="deleteHandler(item.id)"> delete </v-btn>
                </template>
            </v-data-table> 
            
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">{{ formTitle }} Menu</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-text-field v-model="form.nama_menu" :rules="rules.nama" outlined dense label="Menu Name" required></v-text-field>
                        <v-select v-model="form.kategori" :items="kategori" :rules="rules.kategori" outlined dense label="Category" required></v-select>
                        <v-text-field v-model="form.harga" label="Price" outlined dense type="harga" required></v-text-field>
                        <v-select v-model="form.status" :items="status" :rules="rules.status" outlined dense label="Status" required></v-select>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="cancel"> Cancel </v-btn>
                    <v-btn color="blue darken-1" text @click="setForm"> Save </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogConfirm" persistent max-width="400px">
            <v-card>
                <v-card-title>
                    <span class="headline">Warning!</span>
                </v-card-title>
                <v-card-text>Anda Yakin ingin menghapus menu ini?</v-card-text>
                <v-card-action>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialogConfirm = false"> Cancel </v-btn>
                    <v-btn color="blue darken-1" text @click="deleteData"> Delete </v-btn>
                </v-card-action>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>{{ error_message}}</v-snackbar>
    </v-main>
</template> 

<script>
export default {
    name: "List",
    data() {
        return {
            inputType: 'Tambah',
            load: false,
            snackbar: false,
            error_message: '',
            color: '',
            search: null,
            dialog: false,
            dialogConfirm: false,
            headers: [
                { text: "Name", align: "start", sortable: true, value: "nama_menu" },
                { text: "Category", value: 'kategori' },
                { text: "Price", value:'harga' },
                { text: "Status", value:'status' },
                { text: "Actions", value:'actions' },
            ],
            rules: {
              kategori: [val => (val || '').length > 0 || 'This field is required'],
              status: [val => (val || '').length > 0 || 'This field is required'],
              nama: [
                v => !!v || 'Name is required',
              ],
            },
            kategori: ['Makanan', 'Minuman'],
            status: ['Tersedia', 'Tidak Tersedia'],
            menu: new FormData,
            menus: [],
            form: {
                nama_menu: null,
                kategori: null,
                harga: null,
                status: null,
            },
            deleteId: '',
            editId: ''
        };
    },

    methods: {
        createImage(file) {
            const reader = new FileReader();

            reader.onload = e => {
              this.imageUrl = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        onFileChange(file) {
            if (!file) {
              return;
            }
            this.createImage(file);
        },

        setForm() {
            if(this.inputType !== 'Tambah') {
                this.update();
            } else {
                this.save();
            }
        },

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

        save() {
            this.menu.append('nama_menu', this.form.nama_menu);
            this.menu.append('kategori', this.form.kategori);
            this.menu.append('harga', this.form.harga);
            this.menu.append('status', this.form.status);

            var url = this.$api + '/menu/'
            this.load = true;
            this.$http.post(url, this.menu, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token'),
                }
            }).then(response => {
                this.error_message = response.data.message;
                this.color = "green";
                this.snackbar = true;
                this.load = true;
                this.close();
                this.readData(); // baca cata
                this.resetForm();
            }).catch(error => {
                this.error_message = error.response.data.message;
                this.color = "red";
                this.snackbar = true;
                this.load = false;
            });
        },

        // ubah data Menu
        update() {
            let newData = {
                nama_menu : this.form.nama_menu,
                kategori : this.form.kategori,
                harga : this.form.harga,
                status : this.form.status
            };
            var url = this.$api + '/menu/' + this.editId;
            this.load = true;
            this.$http.put(url, newData, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.error_message = response.data.message;
                this.color = "green";
                this.snackbar = true;
                this.load = false;
                this.close();
                this.readData(); // baca data
                this.resetForm();
                this.inputType = 'Tambah';
            }).catch(error => {
                this.error_message = error.response.data.message;
                this.color = "red";
                this.snackbar = true;
                this.load = false;
            });
        },

        // Hapus data Menu
        deleteData() {
            //menghapus data
            var url = this.$api + '/menu/' + this.deleteId;
            this.load = true;
            this.$http.delete(url, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.error_message = response.data.message;
                this.color = "green";
                this.snackbar = true;
                this.load = false;
                this.close();
                this.readData(); // baca data
                this.resetForm();
                this.inputType = 'Tambah';
            }).catch(error => {
                this.error_message = error.response.data.message;
                this.color = "red";
                this.snackbar = true;
                this.load = false;
            });
        },

        editHandler(item) {
            this.inputType = 'Ubah';
            this.editId = item.id;
            this.form.nama_menu = item.nama_menu;
            this.form.kategori = item.kategori;
            this.form.harga = item.harga;
            this.form.status = item.status
            this.dialog = true;
        },

        deleteHandler(id) {
            this.deleteId = id;
            this.dialogConfirm = true;
        },

        close() {
            this.dialog = false;
            this.inputType = 'Tambah';
            this.dialogConfirm = false;
            this.readData;
        },

        cancel() {
            this.resetForm();
            this.readData();
            this.dialog = false;
            this.dialogConfirm = false;
            this.inputType = 'Tambah';
        },

        resetForm() {
            this.form = {
                nama_menu: null,
                kategori: null,
                harga: null,
                status: null
            };
        },
        
        getColor (status) {
            if (status == 'Tersedia') return 'green'
            else return 'red'
            },
    },

    computed: {
        formTitle() {
            return this.inputType;
        },
    },
    mounted() {
        this.readData();
    },
};
</script> 