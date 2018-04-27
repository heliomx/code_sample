<template>
    <!-- <div>
        <div class="alert alert-danger" v-if="error">
            <p>There was an error, unable to sign in with those credentials.</p>
        </div>
        <form autocomplete="off" @submit.prevent="login" method="post">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" v-model="password" required>
            </div>
            <button type="submit" class="btn btn-default">Sign in</button>
        </form>
    </div> -->
    <v-app id="inspire">
    <v-content>
      
        <v-container fluid fill-height>
          
          <v-layout align-center justify-center>
            <v-flex xs12 sm10 md6>
              <v-form v-model="valid" ref="form">
              <v-card class="elevation-12">
                <v-toolbar dark color="primary">
                  <v-toolbar-title>Administração</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-text-field :rules="emailRules" prepend-icon="person" v-model="email" label="E-mail" placeholder="nome@exemplo.com.br" type="email" required></v-text-field>
                    <v-text-field :rules="passwordRules" prepend-icon="lock" v-model="password" label="Senha" id="password" type="password" required></v-text-field>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn @click="login" color="primary">Acessar</v-btn>
                </v-card-actions>
              </v-card>
              </v-form>
            </v-flex>
          </v-layout>
          
        </v-container>
      
    </v-content>
  </v-app>
</template>

<script>
  export default {
    data(){
      return {
        email: '',
        emailRules: [
          v => !!v || 'E-mail é obrigatório',
          v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail inválido'
        ],
        password: '',
        passwordRules: [
          v => !!v || 'Senha é obrigatória',
        ],
        valid: true
      }
    },
    methods: {
      login(){
        var app = this;
        if (this.$refs.form.validate()) {
          this.$auth.login({
              params: {
                email: app.email,
                password: app.password
              }, 
              success: function () {},
              error: function () {},
              rememberMe: true,
              redirect: '/clientes',
              fetchUser: true,
          }); 
        }      
      },
    }
  } 
</script>