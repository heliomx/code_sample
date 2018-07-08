<template>
<transition name="fade" mode="out-in">
<div v-if="!$global.loading">

    <h1 v-if="!editing" class="headline">Cadastrar Novo Programa</h1>
    <v-layout row wrap v-if="editing">
        <v-flex xs9>
            <h2 class="headline">Editando {{ form.name }}</h2>
        </v-flex>
        <v-flex xs3>
            <v-btn @click="showConfirmDelete">
                Remover programa
                <v-icon>delete</v-icon>
            </v-btn>
        </v-flex>
    </v-layout>
    <div class="form-margin">
        <v-form v-model="valid">
            <v-container fluid grid-list-lg="true">
                <v-layout row wrap>
                    <v-flex xs4>
                        <v-subheader>* Nome do Programa</v-subheader>
                    </v-flex>
                    <v-flex xs8>
                        <v-text-field required :rules="validationRules.required" v-model="form.name">
                        </v-text-field>
                    </v-flex>
                    
                    <v-flex xs4 v-if="editing">
                        <v-subheader>Quantidade de assinaturas ativas</v-subheader>
                    </v-flex>
                    <v-flex xs5 v-if="editing">
                        <v-text-field style="width:fit-content" v-model="form.qt_signatures" :disabled="true">
                        </v-text-field>
                    </v-flex>
                    
                    <v-flex xs4>
                        <v-subheader>* Tipo</v-subheader>
                    </v-flex>
                    <v-flex xs5>                    
                        <v-select :items="typeList" required :rules="validationRules.required" v-model="form.program_type" >
                        </v-select>
                    </v-flex>

                    <v-flex xs4>
                        <v-subheader>* Descrição</v-subheader>
                    </v-flex>
                    <v-flex xs8>
                        <v-text-field v-model="form.description" required :rules="validationRules.required" label="Descrição detalhada do programa de rádio" multi-line></v-text-field>
                    </v-flex>

                    <v-flex xs4>
                        <v-subheader>* Quantidade de Dias em publicação</v-subheader>
                    </v-flex>
                    <v-flex xs8>
                        <v-text-field v-model="form.publication_days" required :rules="validationRules.required" type="number" label="Dias"></v-text-field>
                    </v-flex>

                    <v-flex xs4>
                        <v-subheader class="img-field">
                            <div>Imagem</div>
                            <div>
                            <small>288 x 288 pixels (72dpi) | 300Kb máximo</small>
                            </div>
                        </v-subheader>
                        
                    </v-flex>
                    <v-flex xs8>
                        <picture-input
                            ref="pictureInput"
                            :width="288"
                            :removable="true"
                            :prefill="form.img ? form.full_img_path : ''"
                            removeButtonClass="btn picture-input-btn"
                            :height="288"
                            accept="image/jpeg, image/png, image/gif"
                            buttonClass="btn primary picture-input-btn"
                            size="0.3"
                            :customStrings="{
                                change: 'Alterar imagem', 
                                remove: 'Remover imagem',
                                upload: '<h1>Enviar</h1>',
                                drag: 'Arraste e solte a imagem aqui',
                                fileSize: 'O arquivo excede o tamanho permitido',
                                fileType: 'Esse tipo de arquivo não é permitido',
                            }">

                            </picture-input>
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
    <confirm-dialog 
        title="Remover?"
        :body="`Deseja remover o programa ${form.name}?`"
        :visible="confirmDeletion"
        confirmLabel="Apagar"
        cancelLabel="Cancelar"
        @confirm="deleteProgram"
    ></confirm-dialog>

    <message-dialog 
        :visible="message.visible" 
        :title="message.title"
        :info="message.info"
        @done="message.callback"
    ></message-dialog>
</div>
</transition>
</template>

<script>
import MessageDialog from '../../components/MessageDialog.vue';
import ConfirmDialog from '../../components/ConfirmDialog.vue'
import PictureInput from 'vue-picture-input';
import FormData from '../../lib/FormData';
import { required } from '../../lib/ValidationFunctions';

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
            typeList: [{
                    text: 'Semanal',
                    value: 'S'
                },
                {
                    text: 'Diário',
                    value: 'D'
                }
            ],
            valid: true,
            validationRules: {
                required: [
                    v => required(v),
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
                this.$http.get(`programs/${this.$route.params.id}`)
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
                description: '',
                program_type: 'D',
                img: '',
            }
        },
        deleteProgram() {

            this.$http.delete(`programs/${this.form.id}`)
                .then(r => {
                    this.message.visible = true;
                    this.message.title = 'Apagado';
                    this.message.info = `O programa ${this.form.name} foi apagado com sucesso`;
                    this.message.callback = () => {
                        this.$router.push('/programas');
                    }
                });
        },

        submit() {
            let formData = new FormData(this.$http, 
                this.editing ? `programs/${this.form.id}` : 'programs' 
            );
            formData.inputs.append('name', this.form.name);
            formData.inputs.append('description', this.form.description);
            formData.inputs.append('program_type', this.form.program_type);
            formData.inputs.append('publication_days', this.form.publication_days);
            if (this.$refs.pictureInput.file)
            {
                formData.inputs.append('img', this.$refs.pictureInput.file, this.$refs.pictureInput.file.name);
            }
            
            
            if (this.editing)
            {
                formData.post().
                then( r => {
                    this.message.visible = true;
                    this.message.title = 'Alteração';
                    this.message.info = `O programa "${this.form.name}" foi alterado com sucesso`;
                    this.message.callback = () => {
                        this.$router.push('/programas');
                    }
                });
            } else {
                formData.post().
                then( r => {
                    this.message.visible = true;
                    this.message.title = 'Criação';
                    this.message.info = `O programa "${this.form.name}" foi criado com sucesso`;
                    this.message.callback = () => {
                        this.$router.push('/programas');
                    }
                });
            }
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

