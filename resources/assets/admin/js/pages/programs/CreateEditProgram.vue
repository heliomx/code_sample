<template>
<div v-if="!$global.loading">

    <h1 v-if="!editing" class="headline">Cadastrar Novo Programa</h1>
    <v-layout row wrap v-if="editing">
        <v-flex xs9>
            <h2 class="headline">Editando {{ form.radio_name }}</h2>
        </v-flex>
        <v-flex xs3>
            <v-btn @click="showConfirmDelete">
                Remover programa
                <v-icon>delete</v-icon>
            </v-btn>
        </v-flex>
    </v-layout>
    <v-form v-model="valid">
        <v-container fluid grid-list-lg="true">
            <v-layout row wrap>
                <v-flex xs4>
                    <v-subheader>Nome do Programa</v-subheader>
                </v-flex>
                <v-flex xs8>
                    <v-text-field v-model="form.name">
                    </v-text-field>
                </v-flex>
                
                <v-flex xs4 v-if="editing">
                    <v-subheader>Quantidade de assinaturas</v-subheader>
                </v-flex>
                <v-flex xs8 v-if="editing">
                    <v-text-field style="width:fit-content" v-model="form.qt_signatures" disabled="true">
                    </v-text-field>
                </v-flex>
                
                <v-flex xs4>
                    <v-subheader>Tipo</v-subheader>
                </v-flex>
                <v-flex xs8>                    
                    <v-select :items="typeList" v-model="form.program_type" >
                    </v-select>
                </v-flex>

                <v-flex xs4>
                    <v-subheader>Descrição</v-subheader>
                </v-flex>
                <v-flex xs8>
                    <v-text-field v-model="form.description" label="Descrição detalhada do programa de rádio" multi-line></v-text-field>
                </v-flex>

                <v-flex xs4>
                    <v-subheader>Imagem</v-subheader>
                </v-flex>
                <v-flex xs8>
                    <picture-input
                        ref="pictureInput"
                        :width="500"
                        :removable="true"
                        :prefill="form.full_img_path"
                        removeButtonClass="ui red button"
                        :height="500"
                        accept="image/jpeg, image/png, image/gif"
                        buttonClass="ui button primary"
                        :customStrings="{
                        upload: '<h1>Enviar</h1>',
                        drag: 'Arraste e solte a imagem aqui'}">

                        </picture-input>
                </v-flex>
                <v-flex xs2 offset-xs10>
                    <v-btn @click="submit" :disabled="!valid">
                        <span v-if="!editing">Cadastrar</span>
                        <span v-if="editing">Atualizar</span>
                    </v-btn>
                </v-flex>

            </v-layout>
        </v-container>
    </v-form>
    <message-dialog 
        :visible="message.visible" 
        :title="message.title"
        :info="message.info"
        @done="message.callback"
    ></message-dialog>
</div>
</template>

<script>
import MessageDialog from '../../components/MessageDialog.vue';
import PictureInput from 'vue-picture-input';
import FormData from '../../components/FormData';

export default {
    components: {
        MessageDialog,
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
            this.confirmDeletion = false;
            this.$http.delete(`programs/${this.form.id}`)
                .then(r => {
                    this.message.visible = true;
                    this.message.title = 'Apagado';
                    this.message.info = `O programa ${this.form.name} foi apagado com sucesso`;
                    this.message.callback = () => {
                        this.$router.push('/clientes');
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
            formData.inputs.append('img', this.$refs.pictureInput.file, this.$refs.pictureInput.file.name);
            console.log(this.$refs.pictureInput.file);
            if (this.editing)
            {
                formData.post().
                then( r => {
                    console.log(r);
                });
            } else {
                formData.post().
                then( r => {
                    console.log(r);
                });
            }
        }
    }
}
</script>
