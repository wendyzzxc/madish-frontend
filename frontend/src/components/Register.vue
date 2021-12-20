<template> 
    <main> 
        <v-container fluid fill-height class="posisinya"> 
            <v-layout flex align-center justify-center> 
                <v-flex xs12 sm6 elevation-6> 
                    <v-toolbar class="red darken-3"> 
                        <v-toolbar-title class="white--text">
                            <h1>Register Form</h1> 
                        </v-toolbar-title> 
                    </v-toolbar> 
                    <v-card> 
                        <v-card-text class="pt-4"> 
                            <div> 
                                <v-form v-model="valid" ref="form">
                                    <v-text-field label="Name" v-model="name" :rules="nameRules" required></v-text-field> 
                                    <v-text-field label="Username" v-model="username" :rules="usernameRules" required></v-text-field> 
                                    <v-text-field label="E-mail" v-model="email" :rules="emailRules" required></v-text-field> 
                                    <v-text-field label="Password" v-model="password" type="password" min="8" :rules="passwordRules" counter required></v-text-field>
                                    <v-layout>
                                        <v-btn @click="cancel" class="grey darken-3 white--text">Login</v-btn>
                                        <v-layout justify-end>
                                            <v-btn class="mr-2" @click="submit" :class=" { 'grey darken-1 white--text' : valid, disabled: !valid }">Register</v-btn>
                                            <v-btn @click="clear" class="grey darken-3 white--text">Clear</v-btn> 
                                        </v-layout> 
                                    </v-layout> 
                                </v-form> 
                            </div> 
                        </v-card-text> 
                    </v-card>
                    <V-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>{{ error_message }}</v-snackbar> 
                </v-flex> 
            </v-layout> 
        </v-container> 
    </main> 
</template>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Genos:wght@600&display=swap');
    .grey--text{
        font-family: 'Genos', sans-serif;
    }
    .posisinya{
        position: absolute; 
        top: 20px; 
        left: 0; 
        right: 0;
    }
</style>

<script> 
    export default {
        name: "Register", 
        data() {
            return {
            load: false,
            snackbar: false,
            error_message: '',
            user: new FormData, 
            color: '',
            valid: false,
            password: '',
            passwordRules: [
                (v) => !!v || 'Password tidak boleh kosong :(',
            ],
            email: '',
            emailRules: [
                (v) => !!v || 'E-mail tidak boleh kosong :(',
            ],
            name: '',
            nameRules: [
                (v) => !!v || 'Name tidak boleh kosong :(',
            ],
            username: '',
            usernameRules: [
                (v) => !!v || 'Username tidak boleh kosong :(',
            ]
            };
        },

        methods: { 
            submit() {
                this.user.append('name', this.name); 
                this.user.append('email', this.email); 
                this.user.append('password', this.password);
                this.user.append('username', this.username);

                if(this.$refs.form.validate()) {
                    this.load = true; 
                    this.$http.post(this.$api + '/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        username: this.username
                    }).then(response => {
                        localStorage.setItem('token', response.data.access_token); 
                        this.error_message = response.data.message; 
                        this.color = "green"; 
                        this.snackbar = true; 
                        this.load = false; 
                        this.clear();
                        var url = this.$api + '/email/resend';
                        this.$http.get(url, {
                        headers: {
                        'Authorization' : 'Bearer ' + localStorage.getItem('token')
                        }
                        });
                        this.$router.push({
                            name: 'Login', 
                        }); 
                    }).catch(error => {
                        this.error_message = error.response.data.message; 
                        this.color = "red"; 
                        this.snackbar = true;  
                        this.load = false;
                    })
                }
            },

            clear() {
                this.$refs.form.reset()
            },

            cancel(){
                this.$router.push({
                    name: 'Login', 
                }); 
            }
        },
    };
</script>