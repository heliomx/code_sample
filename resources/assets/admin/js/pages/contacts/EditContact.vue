<template>
<div id="editContact">
    <transition name="fade" mode="out-in">
    <div  v-if="!$global.loading">

        <h1 class="headline">Mensagem de {{ form.name }}</h1>
        <v-select class="status" :items="statusList" :rules="validationRules.required" label="Status" required v-model="form.status" >
        </v-select>
        <p>Data: <strong>{{ form.created_at | dateformat}}</strong></p>
        <p>Assunto: <strong> {{ form.subject }}</strong></p>
        <p>{{ form.message }}</p>
        <v-text-field 
            class="annotaion"
            v-model="form.annotations" 
            required 
            :rules="validationRules.required" 
            label="Anotações sobre o contato" 
            multi-line>
        </v-text-field>
        
        
        <div class="page-actions">
            <v-btn color="secondary" @click="submit" :disabled="!valid">
                Atualizar
            </v-btn>
        </div>
        

        
    </div>
    </transition>
    <message-dialog ref="messageDialog"></message-dialog>
</div>
</template>

<script>

import MessageDialog from '../../components/MessageDialog2.vue';
import { required } from '../../lib/ValidationFunctions';
import dateformat from '../../filters/DateFormatFilter';

export default {
    filters: {
        dateformat
    },
    components: {
        MessageDialog,
    },

    data() {
        return {
            message: {
                visible: false,
                title: '',
                info: '',
                callback: () => {}
            },
            validationRules: {
                required: [
                    v => required(v),
                ]
            },
            statusList: [{
                    text: 'Novo',
                    value: 'N'
                },
                {
                    text: 'Resolvido',
                    value: 'R'
                }
            ],
            valid: true,
            
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
                this.$http.get(`contacts/${this.$route.params.id}`)
                    .then( r => {
                        this.$global.loading = false;
                        this.form = r.data.data;
                    });

            }
        },
        blankForm() {
            return {
                name: '',
                email: '',
                annotations: '',
                subject: '',
                status: 'N'
            }
        },
        
        submit() {
            this.$global.loading = true;

			this.$http.post( 'contacts/' + this.form.id, this.form )
				.then( response => {
					this.$refs.messageDialog.show(
						'Atualização',
						'Os dados deste contato foram atualizados'
					)
					this.$global.loading = false;
				});
        }
    }
}
</script>

<style lang="scss">
#editContact {
    width: 100%;
    margin-bottom: 60px;

    .annotation {
        width: 80%;
    }

    .status {
        width: fit-content;
    }
}
    
</style>

