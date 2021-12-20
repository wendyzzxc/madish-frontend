<template>
    <main>
        <span class="bg-profil"></span>
        <v-container fluid fill-height class="profil">
            <v-layout flex-column align-center justify-center>
                <v-flex sm8 elevation-6>
                    <v-toolbar class="red darken-4">
                        <v-toolbar-title class="red--text">
                            <h1 class="white--text">Profil Akun Anda</h1>
                        </v-toolbar-title>
                    </v-toolbar>
                    <v-card>
                        <v-card-text class="pt-4">
                            <div class="container-grid">
                                    <v-img class="img" :src="foto" alt="foto-profil"></v-img>
                                <div class="text-container">
                                    <v-row>
                                        <h3 class="nama-profil"> {{ name }} </h3>
                                    </v-row>
                                    <v-row>
                                        <p class="desc-profil"><v-icon>mdi-email</v-icon> {{ email }}</p>
                                    </v-row>
                                    <v-row>
                                        <p class="desc-profil"><v-icon>mdi-phone</v-icon> No Telp</p>
                                    </v-row>
                                    <v-row>
                                        <p class="desc-profil"><v-icon>mdi-home</v-icon> Alamat</p>
                                    </v-row>
                                </div>   
                            </div>
                            <v-btn class="btn" color="black darken-3" dark @click="logout"> Logout </v-btn>
                            <v-btn class="btn" color="red darken-3" dark @click="dialogEdit = true"> Edit Akun </v-btn>
                        </v-card-text>
                    </v-card>
                    <v-dialog v-model="dialogEdit" persistent max-width="600px">
            <v-card>
                
                <v-card-text>
                    <v-container>
                        <v-text-field
                            v-model="form.name"
                            label="Name"
                            required
                        ></v-text-field>

                        <v-text-field
                            v-model="form.email"
                            label="Email"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="form.image"
                            label="Image"
                            required
                            disabled
                        ></v-text-field>


                        <div class="preview" v-if="preview">
        <p>Preview: </p>
        <img :src="preview" width="200" height="150">
    </div>
                        <div>
                        <input type="file" ref="file" id="file" @change="fileImage"> 
                        </div>

                    </v-container>
                </v-card-text>
                <v-card-action>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="cancel"> Cancel </v-btn>
                    <v-btn color="blue darken-1" text @click="update"> Save </v-btn>
                </v-card-action>
            </v-card>
        </v-dialog>
                    <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>{{ error_message }}</v-snackbar>
                </v-flex>      
            </v-layout>
        </v-container>
    </main>
</template>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Serif:wght@700&display=swap');
    .red--text{
        font-family: 'Noto Serif', serif;
    }

    .bg-profil {
        width: 100%;
        height: 120%;
        position: absolute;
        top: 0;
        left: 0;
        background-image: url('https://www.highstreet.co.id/UserFiles/Image/bariuma/IKP_5792.jpg');;
        background-color: white;
        background-size: cover;
    }

    .profil{
        position: absolute;
        left: 0;
        right: 0;
        top: 30px;
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
    
    export default {
        name: "Profil",
        data(){
            
            return {
                load:false,
                dialogEdit: false,
                imagef:null,
                preview:null,

                name: localStorage.getItem('name'),
                email: localStorage.getItem('email'),
                user: new FormData,
                users: [],
                form: { name: null, email: null, image:null },
                editId: localStorage.getItem('id'),
            };
        },
        methods: {
                update(){
                    let newData = {
                    name : this.form.name,
                    email : this.form.email,
                    image : this.form.image,
                };
                var url = this.$api + '/user/' + this.editId;
                this.load = true;
                this.$http.put(url, newData, {
                    headers: {
                        'Authorization' : 'Bearer ' + localStorage.getItem('token'),
                    }
                }).then(response => {
                    this.error_message = response.data.message;
                    this.color = "green";
                    this.snackbar = true;
                    this.dialogEdit = false;
                    this.name = this.form.name;
                    this.email = this.form.email;
                    this.foto =  this.form.image;
                    this.form = { name: null, email: null, image:null };
                    this.imagef = null;
                    this.preview = null;
                    this.$refs.file.value = null;
                }).catch(error => {
                    this.error_message = error.response.data.message;
                    this.color = "red";
                    this.snackbar = true;
                    this.load = true;
                });
                },

                fileImage(event){   
                    this.imagef = event.target.files[0];
                    this.preview = URL.createObjectURL(event.target.files[0]);
                    this.form.image = event.target.files[0].name;
                },

                cancel(){
                    this.dialog = false;
                    this.dialogEdit=false;
                    this.form = { name: null, email: null, image:null };
                    this.imagef = null;
                    this.preview = null;
                    this.$refs.file.value = null;
                },

                logout(){
                    localStorage.removeItem('token');
                    localStorage.removeItem('name');
                    localStorage.removeItem('email');
                    localStorage.removeItem('id');
                    localStorage.removeItem('token');
                    
                }
            }
    };

    
</script>