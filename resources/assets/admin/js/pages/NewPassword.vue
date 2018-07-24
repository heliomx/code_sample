<template>

    <v-app id="inspire">
    <v-content>
      
        <v-container fluid fill-height>
          
          
            <v-layout align-center justify-center :key="2">
              <v-flex xs12 sm10 md6>
                <v-form v-model="valid" ref="formPwd" @submit.prevent="send">
                  <v-card class="elevation-12">   
                  <v-toolbar dark color="primary">
                    <v-toolbar-title>Recuperar senha</v-toolbar-title>
                  </v-toolbar>
                  <v-card-text>
                      <p>Olá {{ user.name }}</p>
                      <p>Digite sua nova senha:</p>
                      <v-text-field :rules="passwordRules" prepend-icon="lock" v-model="password" label="Senha" id="password" type="password" name="password" required></v-text-field>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn 
                      type="submit" 
                      :loading="loading"
                      :disabled="!valid || loading" 
                      color="primary">
                        Recuperar senha
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
            user: {},
            valid: true,
            password: '',
            loading: false,
            passwordRules: [
                v => !!v || 'Senha é obrigatória',
            ],
        }
    },

    created() {
        this.fetchData();
    },

    methods: {
        fetchData() {
            this.token = this.$route.query.token;
            this.$http.post('userByToken', {pwd_token: this.token})
                .then( 
                    r => {
                        if(r.data.success)
                        {
                            this.user = r.data.data;
                        } else {
                            this.$refs.messageDialog.show(
                                'Recuperação de senha',
                                `Ocorreu um erro processando sua solicitação. Reinicie o processo de recuperação de senha.`,
                                () => {
                                    this.$router.push('/');
                                }
                            );
                        }   
                    }
                )
        },
        send() {
            this.loading = true;
            this.$http.post('updatePassword', {pwd_token: this.token, password: this.password})
                .then(
                    r => {
                        if(r.data.success){
                            this.$refs.messageDialog.show(
                                'Recuperação de senha',
                                `Sua senha foi alterada com sucesso. Você pode efetuar o login com a nova senha.`,
                                () => {
                                    this.$router.push('/');
                                }
                            );
                        } else {
                            this.$refs.messageDialog.show(
                                'Recuperação de senha',
                                `Ocorreu um erro processando sua solicitação. Reinicie o processo de recuperação de senha.`,
                                () => {
                                    this.$router.push('/');
                                }
                            );
                        }
                        
                    },
                    error => {
                        this.loading = false;
                        this.$refs.messageDialog.show(
                            'Recuperação de senha',
                            `Ocorreu um erro processando sua solicitação. Reinicie o processo de recuperação de senha.`,
                            () => {
                                this.$router.push('/');
                            }
                        );
                    }
                )
        }
    }
}
</script>

<style>

</style>
