<template>
<transition name="fade" mode="out-in">
<div v-if="!$global.loading" style="margin: 0 60px">
    
    <h1 v-if="!editing" class="headline">Cadastrar Novo Cliente</h1>
    <v-layout row wrap v-if="editing">
        <v-flex xs9>
            <h2 class="headline">Editando {{ form.radio_name }}</h2>
        </v-flex>
        <v-flex xs3 v-if="editing && $auth.check('A')">
            <v-btn @click="showConfirmDelete">
                Remover cliente <v-icon>delete</v-icon>
            </v-btn>
        </v-flex>
    </v-layout>
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
                    <v-flex xs8 v-if="editing">
                        <v-text-field :rules="validationRules.email" required v-model="form.user.email" label="E-mail (usado para login no sistema)">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs8 v-if="!editing">
                        <v-text-field :rules="validationRules.email" required v-model="form.user.email" :error-messages="emailErrors" @change="checkEmail" label="E-mail (usado para login no sistema)">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs4>
                        <v-text-field v-model="form.user.password" type="password" :rules="validationRules.password" label="Senha">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs9>
                        <v-select :items="radioList" :rules="validationRules.required" label="Tipo de cadastro" required v-model="form.radio_type" single-line>
                        </v-select>
                    </v-flex>
                    <v-flex xs3 v-if="$auth.check('A')">
                        <v-select :items="statusList" :rules="validationRules.required" v-model="form.status" required label="Status" single-line>
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
                        <v-select :items="ufs" :rules="validationRules.required" label="UF" required v-model="form.address_uf" >
                        </v-select>
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
                        <v-text-field v-model="form.tel_mobile" mask="(##) #####-####" @change="refreshValidation" :rules="validationRules.tel_mobile"  label="Telefone Celular">
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
                                    <div class="activation-board" v-if="$auth.check('A')">
                                        <div v-if="program.pivot">
                                            <v-switch :label="`Ativo`" @change="updatePivot(program.pivot)" v-model="program.pivot.active"></v-switch>
                                        </div>
                                    </div>
                                </v-flex>
                                
                            </v-layout>
                        </v-flex>
                    </div>
                    <v-flex xs10>
                        <small v-if="!valid">* Verifique o preenchimento de todos os campos obrigatórios antes de enviar o formulário</small>
                    </v-flex>
                    <v-flex xs12 v-if="$auth.check('A')">
                        <v-expansion-panel
                            expand
                            class="xpanel"
                            v-model="panel"
                            >
                            <v-expansion-panel-content
                                
                                :key="1"
                            >
                                <div slot="header">Anotações</div>
                                <v-card>
                                <v-card-text>
                                    <v-text-field
                                        v-model="form.annotations"    
                                        box
                                        multi-line
                                        ></v-text-field>
                                </v-card-text>
                                </v-card>
                            </v-expansion-panel-content>
                            <v-expansion-panel-content
                                
                                :key="2"
                            >
                                <div slot="header">Histórico de downloads</div>
                                <v-card>
                                <v-card-text>
                                    <v-data-table 
                                    :items="downloadHistory" 
                                    :hide-headers='true'
                                    :pagination.sync="paginationHistory" 
                                    :total-items="totalHistoryItems"
                                    :rows-per-page-text='"Itens por página:"'
                                    :disable-initial-sort="true">
                                        <template slot="items" slot-scope="props">
                                            <td width="180">{{ props.item.download_date | dateformat('DD/MM/YYYY - HH:mm:ss')}}</td>
                                            <td>{{ formatFileName(props.item.program_file.file_name) }}</td>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                                </v-card>
                            </v-expansion-panel-content>
                            </v-expansion-panel>
                        
                        
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
</transition>
</template>

<script>
import Vue from "vue";
import MessageDialog from '../../components/MessageDialog.vue';
import { EventBus } from '../../event-bus';
import { validaCPF, validaCNPJ, required, email } from '../../lib/ValidationFunctions';
import { ufList } from '../../lib/UfList';
import dateformat from '../../filters/DateFormatFilter';

export default {
    filters: {
        dateformat
    },
    components: {
        MessageDialog
    },
    data() {
        return {
            panel:[true, false],
            loadingHistory: false,
            paginationHistory: { rowsPerPage: 5 },
            downloadHistory: [],
            totalHistoryItems: 0,
            headersHistory: [
                {
                    text: "Nome do arquivo",
                    align: "left",
                    value: "program_file.file_name",
                    sortable: false
                },
                {
                    text: "Data",
                    value: "download_at"
                },
            ],
            message: {
                visible: false,
                title: '',
                info: '',
                callback: ()=>{}
            },
            clientEdit: false,
            emailErrors: [],
            valid: true,
            confirmDeletion: false,
            editing: false,
            ufs: ufList(),
            radioList: [{
                    text: "Rádio Web",
                    value: "W"
                },
                {
                    text: "Rádio AM",
                    value: "A"
                },
                {
                    text: "Rádio FM",
                    value: "F"
                },
                {
                    text: "TV",
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
                },
                {
                    text: "Migrado",
                    value: "M"
                }
            ],
            validationRules: 
            {
                tel: [
                    v => (!!this.form.tel || !!this.form.tel_mobile) || 'Preencha pelo menos um telefone',
                    v => (!v || v.length >= 10) || 'Preencha o número corretamente',
                ],
                tel_mobile: [
                    v => (!!this.form.tel || !!this.form.tel_mobile) || 'Preencha pelo menos um telefone',
                    v => (!v || v.length >= 11) || 'Preencha o número corretamente',
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
                    v => email(v),
                    v => this.emailErrors.length == 0 || this.emailErrors[0] 
                ]
            },
            form: this.blankForm(),
            programsList: []
        };
    },
    methods: {

        formatFileName(fname){
            let re = /(.+)\/(.+\.zip)/;
            return re.exec(fname)[2];
        },

        checkEmail()
        {
            this.$http.post('clients/checkEmail', {email: this.form.user.email})
                .then( r => {
                    if (r.data.used)
                    {
                        this.emailErrors = [
                            'Este email já está cadastrado no sistema. Utilize outro.'
                        ]
                        this.$refs.form.validate();
                    } else {
                        this.emailErrors = [];
                        this.$refs.form.validate();
                    }
                    
                })
        },
        
        showConfirmDelete(){
            this.confirmDeletion = true;
        },
        refreshValidation(){
            this.$refs.form.validate();
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
                    name: '',
                    password: ''
                },
                radio_type: '',
                radio_name: '',
                business_category: '',
                cpf: '',
                cnpj: '',
                address: '',
                address_city: '',
                address_uf: '',
                address_cep: '',
                address_complement: '',
                tel: '',
                tel_mobile: '',
                tel_mobile_carrier: '',
                site: '',
                status: "A",
                annotations: '',
                programs: []
            };
        },
        submit() {
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
            console.log('aqui', this.form.programs);
            if (this.editing) {
                this.$http.patch(`clients/${this.form.id}`, this.form).then(r => {
                    
                    this.message.visible = true;
                    this.message.title = 'Alteração';
                    this.message.info = `O cliente ${this.form.radio_name} foi alterado com sucesso`;
                    this.message.callback = () => {
                        if (this.clientEdit) {
                            this.$router.push('/dashboard');
                        } else {
                            this.$router.push('/clientes');
                        }
                    }
                });
            } else {
                this.$http.post("clients", this.form).then(r => {
                    this.message.visible = true;
                    this.message.title = 'Criado';
                    this.message.info = `O cliente ${this.form.radio_name} foi criado com sucesso`;
                    this.message.callback = () => {
                        if (this.clientEdit) {
                            this.$router.push('/dashboard');
                        } else {
                            this.$router.push('/clientes');
                        }
                    }
                });
            }
        }, 
        updatePivot(pivot)
        {
            Vue.set(pivot, 'status', pivot.active ? 'A' : 'D');
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
            let clientId;
            this.loadDownloadHistory();
            if (this.$route.name == 'clientForm' || this.$route.name == 'editClient')
            {
                this.editing = true;
                this.clientEdit = this.$route.name == 'clientForm';
                clientId = this.$route.name == 'clientForm' ? 
                    this.$auth.user().client_id : 
                    this.$route.params.id;
            } else {
                this.editing = false;
            }
            this.$http.get("programs").then(response => {
                this.programsList = response.data.data;

                if (this.editing) {
                    
                    console.log("editing");
                    this.$http.get(`clients/${clientId}`).then(r => {
                        this.form = r.data.data;
                        for (let k = 0; k < this.form.programs.length; k++) {
                            for (let i = 0; i < this.programsList.length; i++) {
                                if (this.programsList[i].id == this.form.programs[k].id) {
                                    Vue.set(this.programsList[i], "checked", true);
                                    Vue.set(this.programsList[i], "pivot", this.form.programs[k].pivot);
                                    Vue.set(this.programsList[i].pivot, "active", this.form.programs[k].pivot.status == 'A');
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
        },
        loadDownloadHistory(){
            this.loadingHistory = true;
            this.$http.get('clients/' + this.$route.params.id + '/download-history', { params: this.paginationHistory
             })
                .then( r => {
                    this.downloadHistory = r.data.items;
                    this.totalHistoryItems = r.data.total;
                    this.loadingHistory = false;
                });
        }
    },
    watch: {
        // call again the method if the route changes
        $route: "fetchData",
        paginationHistory: {
            handler() {
                this.loadDownloadHistory();
            },
            deep: true
        }
        
    },
    created() {
        
        this.fetchData();
    }
};
</script>

<style lang="scss">
.input-group label {
    overflow: visible !important;
}
.activation-board {
    height: 50px;
}

.xpanel {
    margin-bottom: 40px;

    .card__text {
        padding: 0 16px;

        .input-group {
            padding: 0;
            
            .input-group__input {
                padding: 0;
            }
        }
    }


}


.programs-area 
{
    width: 100%;

    .input-group label{
        font-size:13px !important;
    }
    .input-group__details {
        display: none !important;
    }

    .switch {
        
         
        &.input-group--dirty label {
            color: rgb(111, 132, 252) !important;
        }

        label {
            color: rgba(116, 116, 116, 0.54) !important; 
            font-size: 12px;
        }
    }
}

</style>
