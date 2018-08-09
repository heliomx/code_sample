<template>
<div>
    <transition name="fade" mode="out-in">
    <div v-if="!$global.loading">

        <h1 v-if="!editing" class="headline">Cadastrar Novo Usuário Administrador</h1>
        <v-layout row wrap v-if="editing">
            <v-flex xs9>
                <h2 class="headline">Editando {{ form.name }}</h2>
            </v-flex>
            <v-flex xs3>
                <v-btn @click="showConfirmDelete">
                    Remover usuário
                    <v-icon>delete</v-icon>
                </v-btn>
            </v-flex>
        </v-layout>
        <div class="form-margin">
            <v-form v-model="valid">
                <v-container fluid grid-list-lg="true">
                    <v-layout row wrap>
                        <v-flex xs4>
                            <v-subheader>* Nome do Usuário</v-subheader>
                        </v-flex>
                        <v-flex xs8>
                            <v-text-field required :rules="validationRules.required" v-model="form.name">
                            </v-text-field>
                        </v-flex>
                        
                        <v-flex xs4>
                            <v-subheader>* E-mail</v-subheader>
                        </v-flex>
                        <v-flex xs5>
                            <v-text-field required :rules="validationRules.email" v-model="form.email">
                            </v-text-field>
                        </v-flex>

                        <v-flex xs4 v-if="editing">
                            <v-subheader>Senha</v-subheader>
                        </v-flex>
                        <v-flex xs5 v-if="editing">
                            <v-text-field :rules="validationRules.passwordOptional" v-model="form.password">
                            </v-text-field>
                        </v-flex>

                        <v-flex xs4 v-if="!editing">
                            <v-subheader>* Senha</v-subheader>
                        </v-flex>
                        <v-flex xs5 v-if="!editing">
                            <v-text-field required :rules="validationRules.password" v-model="form.password">
                            </v-text-field>
                        </v-flex>
                        
                        <v-flex xs10>
                            <small class="aviso" v-if="!valid">* Verifique o preenchimento de todos os campos obrigatórios antes de enviar o formulário</small>
                        </v-flex>

                        
                    </v-layout>
                    
                </v-container>
            </v-form>
        </div>
        <div class="page-actions">
            <v-btn color="secondary" @click="submit" :disabled="!valid">
                <span v-if="!editing">Cadastrar</span>
                <span v-if="editing">Atualizar</span>
            </v-btn>
        </div>
        

    </div>
    </transition>

    <confirm-dialog 
        title="Remover?"
        :body="`Deseja remover o usuário ${form.name}?`"
        :visible="confirmDeletion"
        confirmLabel="Apagar"
        cancelLabel="Cancelar"
        @confirm="deleteUser"
    ></confirm-dialog>
    <message-dialog ref="messageDialog"></message-dialog>
</div>
</template>

<script>
import MessageDialog from '../../components/MessageDialog2.vue';
import ConfirmDialog from '../../components/ConfirmDialog.vue'
import PictureInput from 'vue-picture-input';
import FormData from '../../lib/FormData';
import { required, email } from '../../lib/ValidationFunctions';

export default {
    components: {
        MessageDialog,
        ConfirmDialog,
        PictureInput
    },

    data() {
        return {
            message: {
                visible: false,
                title: '',
                info: '',
                callback: () => {}
            },
            valid: true,
            validationRules: {
                required: [
                    v => required(v),
                ],
                email: [
                    v => required(v),
                    v => email(v)
                ],
                password: [
                    v => !(v.length < 6) || 'A senha deve ter no mínimo 6 caracteres'
                ],
                passwordOptional: [
                    v => ((v == null || v == '') || !(v.length < 6)) || 'A senha deve ter no mínimo 6 caracteres'
                ]
            },

            confirmDeletion: false,
            editing: false,
            form: this.blankForm(),
        }
    },
    created(){
        this.fetchData();
    },
    watch: {
        // call again the method if the route changes
        $route: "fetchData"
    },
    methods: {
        fetchData(){
            this.$global.loading = true;
            if (this.$route.params.id != null)
            {
                this.editing = true;
                this.$http.get(`users/${this.$route.params.id}`)
                    .then( r => {
                        this.$global.loading = false;
                        this.form = r.data.data;
                    });

            } else {
                this.editing = false;
                this.$global.loading = false;
                this.form = this.blankForm();
            }
        },
        showConfirmDelete() {
            this.confirmDeletion = true;
        },
        blankForm() {
            return {
                name: '',
                email: ''
            }
        },
        deleteUser() {

            this.$http.delete(`users/${this.form.id}`)
                .then(r => {
                    this.$refs.messageDialog.show(
                        'Apagado',
                        `O usuário ${this.form.name} foi apagado com sucesso`,
                        () => {
                            this.$router.go();
                        }
                    );
                });
        },

        submit() {
            this.$global.loading = true;
            let submitData = this.editing ? 
                { 
                    url: 'users/' + this.form.id, 
                    title: 'Atualização',
                    info: 'Usuário atualizado com sucesso'
                }  : 
                {
                    url: 'users',
                    title: 'Novo Usuário',
                    info: 'Usuário criado com sucesso'
                };


            this.$http.post( submitData.url, this.form )
            .then( response => {
                this.$refs.messageDialog.show(
                    submitData.title,
                    submitData.info,
                    () => {
                        this.$router.push('/usuarios');
                    }
                )
                this.$global.loading = false;
            });
        }
    }
}
</script>

<style scoped>
    .picture-input {
        position: relative;
        z-index: 1;
    }

    .preview-container {
        margin: 0 !important;
    }

    .aviso {
        display: block;
        text-align: right;
        margin-top: 20px;
    }

    .img-field {
        align-items: flex-start;
        flex-direction: column;
    }
</style>

