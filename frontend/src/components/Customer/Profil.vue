<template>
    <v-main>
        <span class="bg"></span>
        <v-container fluid fill-height >
            <v-layout flex-column align-center justify-center>
                <v-flex sm8 elevation-6>
                    <v-toolbar class="red darken-4">
                        <v-toolbar-title class="red--text">
                            <h4 class="white--text">Profil Akun Anda</h4>
                        </v-toolbar-title>
                    </v-toolbar>
                    <v-card>
                        <v-card-text class="pt-4">
                            <div class="container-grid">
                                <div class="text-container">
                                    <v-row>
                                        <p class="desc-profil"><v-icon color="blue">{{ icons.mdiAccount }}</v-icon> : {{name}}</p>
                                    </v-row>
                                    <v-row>
                                        <p class="desc-profil"><v-icon color="blue-grey">mdi-email</v-icon> : {{email}} </p>
                                    </v-row>
                                    <v-row>
                                        <p class="desc-profil"><v-icon color="green">mdi-phone</v-icon> : {{phone_num}}</p>
                                    </v-row>
                                    <v-row>
                                        <p class="desc-profil"><v-icon color="red">mdi-home</v-icon> : {{address}}</p>
                                    </v-row>
                                </div>
                            </div>
                            <v-btn class="btn" color="red darken-3" dark @click="dialogEdit = true"> Edit Akun </v-btn>
                        </v-card-text>
                    </v-card>
                    <v-dialog v-model="dialogEdit" persistent max-width="600px">
                        <v-card>
                            <v-card-title>
                                <span class="headline">Update Profil</span>
                            </v-card-title>
                                
                            <v-card-text>
                                <v-container>
                                    <v-text-field
                                        v-model="user.name"
                                        label="Name">
                                    </v-text-field>
                                        
                                    <v-text-field
                                        v-model="user.email"
                                        label="Email">
                                    </v-text-field>

                                    <v-text-field
                                        v-model="user.address"
                                        label="Address">
                                    </v-text-field>
                                        
                                    <v-text-field
                                        v-model="user.phone_num"
                                        label="Phone Number">
                                    </v-text-field>

                                    <div class="preview" v-if="preview">
                                        <p>Preview: </p>
                                        <img :src="preview" width="200" height="150">
                                    </div>
                                    <div>
                                        <input type="file" ref="file" id="file" @change="fileImage"> 
                                    </div>
                                </v-container>
                            </v-card-text>
                            
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="cancel">
                                    Cancel
                                </v-btn>
                                <v-btn color="blue darken-1" text @click="saveData">
                                    Save
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>{{ error_message }}</v-snackbar>
                </v-flex>      
            </v-layout>
        </v-container>
    </v-main>
</template>



<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Serif:wght@700&display=swap');
    .red--text{
        font-family: 'Noto Serif', serif;
    }

    .bg {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        background-image: url('https://www.highstreet.co.id/UserFiles/Image/bariuma/IKP_5792.jpg');;
        background-color: white;
        background-size: cover;
    }

    .posisinya{
        
        left: 0;
        right: 0;
    }

    .img{
        margin: 20px;
        width: 260px;
        height: 260px;
        border-radius: 50%;
    }

    .container-grid{
        display: grid;
        grid-template-columns: 300px 600px;
        align-items: center;
        vertical-align: middle;
    }

    .text-container{
        margin-left: 40px;
    }

    .nama-profil{
        margin-bottom: 60px;
        font-size: 45px;
    }

    .desc-profil{
        font-size: 25px;
    }

    .btn{
        position: absolute;
        right: 20px;
        bottom: 20px;
    }
</style>

<script>
import {
    mdiAccount,
    mdiPencil,
    mdiShareVariant,
    mdiDelete,
} from '@mdi/js'

export default {
    name: "List",
    data() {        
        return {
            icons: {
                mdiAccount,
                mdiPencil,
                mdiShareVariant,
                mdiDelete,
            },
            name: localStorage.getItem('name'),
            email: localStorage.getItem('email'),
            address: localStorage.getItem('address'),
            phone_num: localStorage.getItem('phone_num'),
            load: false,
            snackbar: false,
            error_message: '',
            color: '',
            dialogEdit: false,
            user: new FormData,
            users: [],
            form: 
            {
                name: null,
                email: null,
                address: null,
                phone_num: null,
            },
            deleteId: '',
            editId: '',
            images: [],
            imgPrev: null,
            imagef:null,
            preview:null,
            files:[],
        };
    },
    methods: {
        //ubah data user dan password
        saveData(){
            let newData = {
                name: this.user.name,
                email: this.user.email,
                address: this.user.address,
                phone_num: this.user.phone_num,
            }
            var url = this.$api + '/user/' + localStorage.getItem('id');
            this.load = true;
            this.$http.put(url, newData, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                
                this.error_message=response.data.message;
                this.color="green"
                this.snackbar=true;
                this.load = false;
                this.dialogEdit = false;
                localStorage.setItem('name', this.user.name);
                localStorage.setItem('email', this.user.email);
                localStorage.setItem('address', this.user.address);
                localStorage.setItem('phone_num', this.user.phone_num);
                this.name = localStorage.getItem('name');
                this.email= localStorage.getItem('email');
                this.address = localStorage.getItem('address');
                this.phone_num = localStorage.getItem('phone_num');
                this.foto =  this.form.image;
                this.imagef = null;
                this.preview = null;
                this.$refs.file.value = null;
                this.upload();
                this.resetForm();
                this.readData();
            }).catch(error => {
                this.error_message=error.response.data.message;
                this.color="red"
                this.snackbar=true;
                this.load = false;
            })
        },

        fileImage(event){   
                    this.imagef = event.target.files[0];
                    this.preview = URL.createObjectURL(event.target.files[0]);

                    const files = event.target.files;
                    Array.from(files).forEach(file => this.addImage(file));
                },
        
        cancel(){
            this.$refs.file.value = null;
            this.imagef = null;
            this.preview = null;
            this.dialogEdit = false;
            this.resetForm();
            this.readData();
            
        },

        resetForm() {
            this.form = {
                name: null,
                email: null,
                address: null,
                phone_num: null,
            };
        },

        upload() {
            const formData = new FormData();
            
            this.files.forEach(file => {
                formData.append('images[]', file, file.name);
            });

            var url = this.$api + '/image/' + localStorage.getItem('id');
            this.load = true
            this.$http.post(url, formData, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            this.images = [];
            this.files = [];
        },

        addImage(file) {
            if (!file.type.match('image.*')) {
                return;
            }

            this.files.push(file);

            const reader = new FileReader();

            reader.onload = (e) => this.images.push(e.target.result);
            
            reader.readAsDataURL(file);

        },
    },
    
    computed: {
        formTitle() {
            return this.inputType
        },
    },
    
    mounted() {
        this.user.name = localStorage.getItem('name');
        this.user.email= localStorage.getItem('email');
        this.user.address = localStorage.getItem('address');
        this.user.phone_num = localStorage.getItem('phone_num');
    },
};
</script>
