<template>
<div v-if="!$global.loading">
    
    <h1 v-if="!editing" class="headline">Cadastrar Novo Cliente</h1>
    <v-layout row wrap v-if="editing">
        <v-flex xs9>
            <h2 class="headline">Editando {{ form.radio_name }}</h2>
        </v-flex>
        <v-flex xs3>
            <v-btn @click="showConfirmDelete">
                Remover cliente <v-icon>delete</v-icon>
            </v-btn>
        </v-flex>
    </v-layout>
    <v-form v-model="valid" >
        <v-container fluid grid-list-lg="true" >
            <v-layout row wrap >
                <v-flex xs8>
                    <v-text-field v-model="form.user.email" label="E-mail">
                    </v-text-field>
                </v-flex>
                <v-flex xs4>
                    <v-text-field v-model="form.user.password" label="Senha">
                    </v-text-field>
                </v-flex>
                <v-flex xs6>
                    <v-select :items="radioList" label="Tipo de cadastro" v-model="form.radio_type" single-line>
                    </v-select>
                </v-flex>
                <v-flex xs6>
                    <v-select :items="statusList" v-model="form.status" label="Status" single-line>
                    </v-select>
                </v-flex>
                <v-flex xs12>
                    <v-text-field v-model="form.radio_name" label="Nome da rádio">
                    </v-text-field>
                </v-flex>
                <v-flex xs12>
                    <v-text-field v-model="form.user.name" label="Nome completo do responsável">
                    </v-text-field>
                </v-flex>
                <v-flex xs6>
                    <v-text-field v-model="form.business_category" label="Razão social">
                    </v-text-field>
                </v-flex>
                <v-flex xs4>
                    <v-text-field label="CPF" v-model="form.cpf">
                    </v-text-field>
                </v-flex>
                <v-flex xs4>
                    <v-text-field v-model="form.cnpj" label="CNPJ">
                    </v-text-field>
                </v-flex>
                <v-flex xs6>
                    <v-text-field v-model="form.address_city" label="Cidade">
                    </v-text-field>
                </v-flex>
                <v-flex xs2>
                    <v-text-field v-model="form.address_uf" label="UF">
                    </v-text-field>
                </v-flex>
                <v-flex xs4>
                    <v-text-field v-model="form.address_cep" label="CEP">
                    </v-text-field>
                </v-flex>
                <v-flex xs12>
                    <v-text-field v-model="form.address" label="Endereço">
                    </v-text-field>
                </v-flex>
                <v-flex xs12>
                    <v-text-field v-model="form.address_complement" label="Complemento">
                    </v-text-field>
                </v-flex>
                <v-flex xs4>
                    <v-text-field v-model="form.tel" label="Telefone fixo">
                    </v-text-field>
                </v-flex>
                <v-flex xs4>
                    <v-text-field v-model="form.tel_mobile" label="Telefone Celular">
                    </v-text-field>
                </v-flex>
                <v-flex xs4>
                    <v-text-field v-model="form.tel_mobile_carrier" label="Operadora">
                    </v-text-field>
                </v-flex>
                <v-flex xs6>
                    <v-text-field v-model="form.site" label="Site">
                    </v-text-field>
                </v-flex>
                <v-flex xs12>
                    <h3>Programas</h3>
                </v-flex>
                <v-flex xs12>
                    <v-layout row wrap>
                        <v-flex xs4 v-for="program in programsList" :key="program.id">
                            <v-checkbox v-model="program.checked" :label="program.name"></v-checkbox>

                        </v-flex>
                    </v-layout>
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
    <v-layout row justify-center>
        <v-dialog v-model="confirmDeletion" persistent max-width="290">
            <v-card>
                <v-card-title class="headline">Remover?</v-card-title>
                <v-card-text>
                    <p>Você tem certeza que quer remover {{ form.radio_name }}?</p>
                    <p>Essa operação não pode ser desfeita.</p>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="darken-1" flat @click.native="confirmDeletion = false">Cancelar</v-btn>
                    <v-btn color="darken-1" flat @click="deleteClient">Apagar <v-icon>delete</v-icon></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
    <message-dialog 
        :visible="message.visible" 
        :title="message.title"
        :info="message.info"
        @done="message.callback"
    ></message-dialog>
</div>
</template>

<script>
import Vue from "vue";
import MessageDialog from '../../components/MessageDialog.vue';
import { EventBus } from '../../event-bus';

export default {
    components: {
        MessageDialog
    },
    data() {
        return {
            message: {
                visible: false,
                title: '',
                info: '',
                callback: ()=>{}
            },
            valid: true,
            confirmDeletion: false,
            editing: false,
            radioList: [{
                    text: "Rádio Web",
                    value: "W"
                },
                {
                    text: "Rádio Convencional com CNPJ",
                    value: "C"
                }
            ],
            statusList: [{
                    text: "Ativo",
                    value: "A"
                },
                {
                    text: "Inativo",
                    value: "I"
                }
            ],
            form: this.blankForm(),
            programsList: []
        };
    },
    methods: {
        showConfirmDelete(){
            this.confirmDeletion = true;
        },
        deleteClient() {
            this.confirmDeletion = false;
            this.$http.delete(`clients/${this.form.id}`)
                .then( r => {
                    this.message.visible = true;
                    this.message.title = 'Apagado';
                    this.message.info = `O cliente ${this.form.radio_name} foi apagado com sucesso`;
                    this.message.callback = () => {
                        this.$router.push('/clientes');
                    }
                });
        },
        blankForm() {
            return {
                user: {
                    name: "",
                    password: ""
                },
                radio_type: "",
                radio_name: "",
                business_category: "",
                cpf: "",
                cnpj: "",
                address: "",
                address_city: "",
                address_uf: "",
                address_cep: "",
                address_complement: "",
                tel: "",
                tel_mobile: "",
                tel_mobile_carrier: "",
                site: "",
                status: "",
                programs: []
            };
        },
        submit() {
            let programs = this.programsList
                .filter((elm, i, arr) => {
                    return elm.checked;
                })
                .map((elm, i, arr) => {
                    return elm.id;
                });

            this.form.programs = programs;
            if (this.editing) {
                this.$http.patch(`clients/${this.form.id}`, this.form).then(r => {
                    this.message.visible = true;
                    this.message.title = 'Alteração';
                    this.message.info = `O cliente ${this.form.radio_name} foi alterado com sucesso`;
                    this.message.callback = () => {
                        this.$router.push('/clientes');
                    }
                });
            } else {
                this.$http.post("clients", this.form).then(r => {
                    this.message.visible = true;
                    this.message.title = 'Criado';
                    this.message.info = `O cliente ${this.form.radio_name} foi apagado com sucesso`;
                    this.message.callback = () => {
                        this.$router.push('/clientes');
                    }
                });
            }
        }, 
        fetchData() {
            this.$global.loading = true;
            this.$http.get("programs").then(response => {
                this.programsList = response.data.data;

                if (this.$route.params.id != null) {
                    this.editing = true;
                    console.log("editing");
                    this.$http.get(`clients/${this.$route.params.id}`).then(r => {
                        this.form = r.data.data;
                        for (let k = 0; k < this.form.programs.length; k++) {
                            for (let i = 0; i < this.programsList.length; i++) {
                                if (this.programsList[i].id == this.form.programs[k].id) {
                                    Vue.set(this.programsList[i], "checked", true);
                                    break;
                                }
                            }
                        }
                        this.$global.loading = false;
                    });
                } else {
                    this.$global.loading = false;
                    this.form = this.blankForm();
                }
            });
        }
    },
    watch: {
        // call again the method if the route changes
        $route: "fetchData"
    },
    created() {
        
        this.fetchData();
    }
};
</script>

<style scoped>
.input-group label {
    overflow: visible !important;
}
</style>
