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
          
          <transition name="fade" mode="out-in">
            <v-layout v-if="!recoverUI" align-center justify-center :key="1">
              <v-flex xs12 sm10 md6>
                <v-form v-model="valid" ref="form" @submit.prevent="login">
                <v-card class="elevation-12">   
                  <v-toolbar dark color="primary">
                    <v-toolbar-title>Login Administração</v-toolbar-title>
                  </v-toolbar>
                  <v-card-text>
                      <v-text-field :rules="emailRules" prepend-icon="person" v-model="email" label="E-mail" placeholder="nome@exemplo.com.br" name="email" type="email" required></v-text-field>
                      <v-text-field :rules="passwordRules" prepend-icon="lock" v-model="password" label="Senha" id="password" type="password" name="password" required></v-text-field>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn flat small @click="recoverUI = true">Esqueci a senha</v-btn>
                    <v-btn flat small @click="showRegister">Faça seu cadastro</v-btn>
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
            <v-layout v-if="recoverUI" align-center justify-center :key="2">
              <v-flex xs12 sm10 md6>
                <v-form v-model="validPwd" ref="formPwd" @submit.prevent="recoverPwd">
                  <v-card class="elevation-12">   
                  <v-toolbar dark color="primary">
                    <v-toolbar-title>Recuperar senha</v-toolbar-title>
                  </v-toolbar>
                  <v-card-text>
                      <v-text-field :rules="emailRules" prepend-icon="person" v-model="email" label="E-mail" placeholder="nome@exemplo.com.br" name="email" type="email" required></v-text-field>
                      <p>Um link com instruções de recuperação de senha será enviado para o email cadastrado no nosso sistema.</p>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn flat small @click="recoverUI = false">Cancelar</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn 
                      type="submit" 
                      :loading="loading"
                      :disabled="!validPwd || loading" 
                      color="primary">
                        Recuperar senha
                    </v-btn>
                  </v-card-actions>
                </v-card>
                </v-form>
              </v-flex>
            </v-layout>
          </transition>
          
        </v-container>
      
    </v-content>
    <v-footer app color="secondary">
        <div>
            Em caso de dúvidas com o seu acesso ligue <strong>(61) 3532-6993</strong> | 
                    <img class="ico-wpp" src="/img/wpp-icon.png" width="19"> <strong>(61) 9 9966-9557</strong>
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
        validPwd: true,
        loading: false,
        recoverUI: false
      }
    },
    methods: {
      recoverPwd(){
        this.loading = true;
        var app = this;
        if (this.$refs.formPwd.validate()) {
          this.$http.post('forgotPassword', {
            email: app.email
          }).then( (r) => {
              if (r.data.success)
              {
                this.$refs.messageDialog.show(
                    'Recuperação de senha',
                    `Um e-mail com as instruções de recuperação de senha foi enviado para ${this.email}. Verifique sua caixa de e-mail.`
                  );
                this.loading = false;
                this.recoverUI = false;
              } else {
                this.$refs.messageDialog.show(
                    'Recuperação de senha',
                    `Ocorreu um erro ao tentar recuperar sua senha. 
                      Verifique se o e-mail foi preenchido corretamente e 
                      tente novamente. Caso o problema persista 
                      ligue para(61) 3532-6993.`
                  );
                this.loading = false;
              }
            },
            (r) => {
              this.$refs.messageDialog.show(
                    'Recuperação de senha',
                    `Ocorreu um erro ao tentar recuperar sua senha. 
                      Verifique se o e-mail foi preenchido corretamente e 
                      tente novamente. Caso o problema persista 
                      ligue para(61) 3532-6993.`
                  );
                this.loading = false;
            }
          );
        }   
        return false;   
      },
      showRegister(){
        window.open('/#cadastro');
      },
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
    font-size: 19px;
  }

  .ico-wpp {
    position: relative;
    top:3px;
  }
</style>
