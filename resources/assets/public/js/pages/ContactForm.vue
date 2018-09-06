<template>
    <transition name="fade" appear mode="out-in">
        <section id="program" v-if="!$global.loading" class="content-area">
            <div>
                <h2>Fale Conosco</h2>
                <div class="form-margin">
                    <v-form v-model="valid">
                        <v-container fluid grid-list-lg="true">
                            <v-layout row wrap>
                                <v-flex xs3>
                                    <v-subheader>* Seu nome</v-subheader>
                                </v-flex>
                                <v-flex xs8>
                                    <v-text-field required :rules="validationRules.required" v-model="form.name">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3>
                                    <v-subheader>* Seu e-mail</v-subheader>
                                </v-flex>
                                <v-flex xs8>
                                    <v-text-field required :rules="validationRules.email" v-model="form.email">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3>
                                    <v-subheader>* Assunto</v-subheader>
                                </v-flex>
                                <v-flex xs8>
                                    <v-text-field required :rules="validationRules.required" v-model="form.subject">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3>
                                    <v-subheader>* Mensagem</v-subheader>
                                </v-flex>
                                <v-flex xs8>
                                    <v-text-field multi-line required :rules="validationRules.required" v-model="form.message">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-form>
            </div>
            <div class="btn-area clear-fix">
                    <v-btn 
                    :loading="loading"
                    class="clearfix" 
                    color="secondary" 
                    large 
                    :disabled="!valid || loading" 
                    @click="submit">Enviar</v-btn>
                </div>
            </div>
            <message-dialog ref="messageDialog"></message-dialog>
        </section>
    </transition>
</template>

<script>
import { required, email } from '../../../admin/js/lib/ValidationFunctions';
import MessageDialog from '../../../admin/js/components/MessageDialog2';

export default {
    components: {
        MessageDialog
    },

    data() {
        return {
            form: {
                name: '',
                email: '',
                subject: '',
                message: ''
            },
            loading: false,
            valid: false,
            validationRules: 
            {
                required: [
                    v => required(v),
                ],
                email: [
                    v => required(v),
                    v => email(v)
                ]
            },
        }
    },
    methods: {
        submit() {
            this.loading = true;
            
            this.$http.post(`contacts`, this.form)
                .then( r => {
                    this.$refs.messageDialog.show(
                        'Fale conosco',
                        `Sua mensagem foi enviada. Em breve entraremos em contato.`,
                        () => this.$router.push('/')
                    )
                }).catch( err => {
                    this.$refs.messageDialog.show(
                        'Erro',
                        `Ocorreu um erro processando sua solicitação. Tente novamente mais tarde.`
                    )
                })
                .then( () => {
                    this.loading = false;
                });
        }
    }
}
</script>
<style lang="scss">
@import '../../sass/variables';

#program>div {
    @include clearfix;

    &>img {
        float: left;
        margin-right: 20px;
        margin-bottom: 20px
    }

    &>span {
        font-size: 16px;
        font-family: $title-font;
    }

    

}
</style>

