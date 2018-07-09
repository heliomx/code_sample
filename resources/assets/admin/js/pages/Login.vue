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
              <v-form v-model="valid" ref="form" @submit.prevent="login">
              <v-card class="elevation-12">   
                <v-toolbar dark color="primary">
                  <v-toolbar-title>Administração</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-text-field :rules="emailRules" prepend-icon="person" v-model="email" label="E-mail" placeholder="nome@exemplo.com.br" name="email" type="email" required></v-text-field>
                    <v-text-field :rules="passwordRules" prepend-icon="lock" v-model="password" label="Senha" id="password" type="password" name="password" required></v-text-field>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn 
                    type="submit" 
                    :loading="loading"
      				      :disabled="!valid || loading" 
                    color="primary">
                      Acessar
                  </v-btn>
                </v-card-actions>
              </v-card>
              </v-form>
            </v-flex>
          </v-layout>
          
        </v-container>
      
    </v-content>
    <v-footer app color="secondary">
        <div>
            Em caso de dúvidas com o seu acesso ligue <strong>(61) 3532-6993</strong>
        </div>
    </v-footer>
    <message-dialog ref="messageDialog"></message-dialog>
  </v-app>
</template>

<script>
  import MessageDialog from '../components/MessageDialog2.vue';

  export default {
    components: {
        MessageDialog,
    },
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
        valid: true,
        loading: false
      }
    },
    methods: {
      login(){
        this.loading = true;
        var app = this;
        if (this.$refs.form.validate()) {
          this.$auth.login({
              params: {
                email: app.email,
                password: app.password
              }, 
              success:  (r) => this.loading = false,
              error: (r) => {
                if(r.response.data.status && r.response.data.error == 'invalid.credentials')
                {
                  this.$refs.messageDialog.show(
                    'Usuário ou senha inválidos',
                    'Verifique o preenchimento do e-mail e senha e tente novamente.'
                  )
                } else {
                  this.$refs.messageDialog.show(
                    'Erro',
                    'Não foi possível efetuar o login. Verifique sua conexão e tente novamente.'
                  )
                }

                this.loading = false;
              },
              rememberMe: true,
              redirect: '/dashboard',
              fetchUser: true,
          }); 
        }   
        return false;   
      },
    }
  } 
</script>
<style lang="scss" scoped>
  footer div {
    width: 100%;
    color: #fff;
    text-align: center;
  }
</style>
