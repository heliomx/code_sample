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
                                    <v-text-field type="password" v-model="form.user.password" required :rules="validationRules.required" label="Senha">
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
                                            <v-flex 
                                                v-if="(program.id == 22 && ['T', 'V'].indexOf(form.radio_type) >= 0) || 
                                                (program.id != 22 && ['F', 'A', 'W'].indexOf(form.radio_type) >= 0)" 
                                                xs4 
                                                v-for="program in programsList" 
                                                :key="program.id"
                                                >
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
                    text: "Rádio Convencional AM",
                    value: "A"
                },
                {
                    text: "Rádio Convencional FM",
                    value: "F"
                },
                {
                    text: "TV Convencional",
                    value: "T"
                },
                {
                    text: "TV Web",
                    value: "V"
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
                status: "A",
                programs: []
            };
        },
        submit() {
            this.loading = true;
            let programs = this.programsList
                .filter((elm, i, arr) => {
                    return elm.checked && (
                        (elm.id == 22 && ['T', 'V'].indexOf(this.form.radio_type) >= 0) || 
                        (elm.id != 22 && ['F', 'A', 'W'].indexOf(this.form.radio_type) >= 0)
                    );
                })
                .map((elm, i, arr) => {

                    return {program_id: elm.id, status: elm.pivot.status};
                });

            this.form.programs = programs;
            
            this.$http.post("clients", this.form).then(r => {
                
                this.$refs.messageDialog.show(
                    'Cadastro',
                    `O seu cadastro foi enviado com sucesso. Entre na nossa página www.radioestudiobrasil.com.br/admin e acesse a Área Restrita com os dados cadastrados.`,
                    () => this.$router.push('/')
                )
            })
            .catch( err => {
                this.$refs.messageDialog.show(
                    'Erro',
                    `Ocorreu um erro tentando registrar o seu cadastro. Tente novamente mais tarde. Caso o problema persista, entre em contato com a nossa equipe.`,
                )
            })
            .then( () => {
                this.loading = false;
            })
        }, 

        updateProgram(program)
        {
            if (program.checked)
            {
                Vue.set(program, 'pivot', {active: true, status:'A'})
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
