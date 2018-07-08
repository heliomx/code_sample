<template>
    <transition name="fade" appear mode="out-in">
        <section id="register" v-if="!$global.loading" class="content-area">
            <div>
                <h2>Cadastre-se</h2>
                <div class="form-margin">
                    <v-form ref="form" v-model="valid" >
                        <v-container fluid grid-list-lg="true" >
                            <v-layout row wrap >
                                <v-flex xs6>
                                    <v-text-field v-model="form.radio_name" :rules="validationRules.required" required label="Nome da rádio">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6>
                                    <v-text-field v-model="form.user.name" :rules="validationRules.required" required label="Nome completo do responsável">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs8>
                                    <v-text-field :rules="validationRules.email" required v-model="form.user.email" label="E-mail (usado para login no sistema)">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="form.user.password" required :rules="validationRules.required" label="Senha">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs9>
                                    <v-select :items="radioList" :rules="validationRules.required" label="Tipo de rádio" required v-model="form.radio_type" single-line>
                                    </v-select>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field label="CPF" mask="###.###.###-##" @change="refreshValidation" required :rules="validationRules.cpf" v-model="form.cpf">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="form.cnpj" mask="##.###.###/####-##" @change="refreshValidation" required :rules="validationRules.cnpj" label="CNPJ">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6>
                                    <v-text-field v-model="form.address_city" :rules="validationRules.required" required label="Cidade">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="form.address_uf" :rules="validationRules.required" required label="UF">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="form.address_cep" mask="##.###-###" label="CEP">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs8>
                                    <v-text-field v-model="form.address" label="Endereço">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="form.address_complement" label="Complemento">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="form.tel" mask="(##) ####-####" @change="refreshValidation" :rules="validationRules.tel" label="Telefone fixo">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="form.tel_mobile" mask="(##) #####-####" @change="refreshValidation" :rules="validationRules.tel"  label="Telefone Celular">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6>
                                    <v-text-field v-model="form.site" label="Site">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12>
                                    <h3>Programas*</h3>
                                </v-flex>
                                <div class="programs-area">
                                    <v-flex xs12>
                                        <v-layout row wrap>
                                            <v-flex xs4 v-for="program in programsList" :key="program.id">
                                                <v-checkbox @change="updateProgram(program)" v-model="program.checked">
                                                    <div slot="label">
                                                        {{ program.name }}
                                                    </div>
                                                </v-checkbox>
                                            </v-flex>
                                            
                                        </v-layout>
                                    </v-flex>
                                </div>
                                <v-flex xs10>
                                    <small v-if="!valid">* Verifique o preenchimento de todos os campos obrigatórios antes de enviar o formulário</small><br>
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
                    @click="submit">Cadastrar</v-btn>
                </div>
            </div>
            <message-dialog ref="messageDialog"></message-dialog>
        </section>
        
    </transition>
</template>

<script>
import { validaCPF, validaCNPJ, required, email } from '../../../admin/js/lib/ValidationFunctions';
import MessageDialog from '../../../admin/js/components/MessageDialog2';

import Vue from "vue";

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
            clientEdit: false,
            valid: true,
            loading: false,
            confirmDeletion: false,
            editing: false,
            radioList: [{
                    text: "Rádio Web",
                    value: "W"
                },
                {
                    text: "Rádio Convencional AM com CNPJ",
                    value: "A"
                },
                {
                    text: "Rádio Convencional FM com CNPJ",
                    value: "F"
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
            validationRules: 
            {
                tel: [
                    v => (!!this.form.tel || !!this.form.tel_mobile) || 'Preencha pelo menos um telefone',
                ],
                cpf: [
                    v => (!!this.form.cpf || !!this.form.cnpj) || 'Preencha um CPF ou CNPJ',
                    v => validaCPF(v),
                ],
                cnpj: [
                    v => (!!this.form.cpf || !!this.form.cnpj) || 'Preencha um CPF ou CNPJ',
                    v => validaCNPJ(v),
                ],
                required: [
                    v => required(v),
                ],
                email: [
                    v => required(v),
                    v => email(v)
                ]
            },
            form: this.blankForm(),
            programsList: []
        };
    },

    
    created() {
        
        this.fetchData();
    },

    methods: {
        
        showConfirmDelete(){
            this.confirmDeletion = true;
        },
        refreshValidation(){
            this.$refs.form.validate();
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
                status: "I",
                programs: []
            };
        },
        submit() {
            this.loading = true;
            let programs = this.programsList
                .filter((elm, i, arr) => {
                    return elm.checked;
                })
                .map((elm, i, arr) => {

                    return {program_id: elm.id, status: elm.pivot.status};
                });

            this.form.programs = programs;
            
            this.$http.post("clients", this.form).then(r => {
                this.loading = false;
                this.$refs.messageDialog.show(
                    'Cadastro',
                    `O seu cadastro foi enviado com sucesso. Sua solicitação de programação 
                    será avaliada pela nossa equipe. Em breve entraremos em contato.`,
                    () => this.$router.push('/')
                )
            });
        }, 

        updateProgram(program)
        {
            if (program.checked)
            {
                Vue.set(program, 'pivot', {active: false, status:'D'})
            } else {
                Vue.delete(program, 'pivot');
            }
        },
        fetchData() {
            this.$global.loading = true;
            
            this.$http.get("programs").then(response => {
                this.programsList = response.data.data;

                this.$global.loading = false;
                this.form = this.blankForm();
            });
        }
    },
}
</script>

<style>

</style>