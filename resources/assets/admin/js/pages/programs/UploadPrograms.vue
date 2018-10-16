<template>
        <v-container grid-list-lg="true" align-start="true">
            <v-form v-model="formValid">
            <v-layout row wrap>
                
                    <v-flex xs12>
                        <h1 class="headline">Enviar programas</h1>
                    </v-flex>
                    
                        <v-flex v-if="!sent" xs12 sm6 md4>
                            <v-menu
                                ref="menu"
                                :close-on-content-click="false"
                                v-model="menu"
                                :nudge-right="40"
                                :return-value.sync="date"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                min-width="290px"
                            >
                                <v-text-field
                                slot="activator"
                                v-model="computedDateFormatted"
                                required
                                :rules="validationRules.required"
                                label="Data de publicação"
                                prepend-icon="event"
                                :disabled="sending"
                                readonly
                                ></v-text-field>
                                <v-date-picker v-model="date" @input="$refs.menu.save(date)" locale="pt-br" no-title scrollable>
                                </v-date-picker>
                            </v-menu>
                        </v-flex>
                        
                        <v-flex xs12 sm6 md4 v-if="date && !sent">
                            Arquivo .zip:<br>
                            <input :disabled="sending" required type="file" label="Escolha o arquivo" @change="setFile($event)" ><br>
                            <small> {{ fileSize | filesize(2) }} </small>
                        </v-flex>
                        <v-flex xs12 v-if="!sending && !sent">
                            <v-btn @click="submit" :disabled="!valid">
                                Enviar
                            </v-btn>
                        </v-flex>
                    
                    <v-flex xs12 v-if="sending && !sent">
                        Enviando. Caso a conexão seja interrompida o envio irá continuar de onde parou.<br>
                        <v-progress-circular
                            :size="100"
                            :width="15"
                            :rotate="360"
                            :value="progress"
                            color="teal"
                            >
                            {{ progress }}%<br>
                            
                        </v-progress-circular>
                        
                        <div><small>{{ bytesUploaded | filesize(1) }}</small></div>
                    </v-flex>
                    
                    <v-flex xs12 v-if="sent">
                        <h3 v-if="uploadPackage.status == 'D'">Seu pacote foi enviado com sucesso</h3>
                        <h3 v-if="uploadPackage.status == 'E'">O envio falhou</h3>
                        <p v-if="!uploadPackage">O pacote já está sendo processado...</p>
                        <div v-if="uploadPackage">
                            <p v-if="uploadPackage.status == 'U'">Descompactando arquivos. Essa operação pode levar alguns minutos...</p>
                            <p v-if="uploadPackage.status == 'P'">
                                Associando arquivos à programação. 
                                Diretórios processados: 
                                {{ uploadPackage.meta.folders_processed }}/{{ uploadPackage.meta.folders }}
                            </p>
                            <p v-if="uploadPackage.status == 'D'">Processamento concluído.</p>
                        </div>
                        <v-progress-circular v-if="!uploadPackage || (uploadPackage.status != 'D' && !uploadPackage.status != 'E')" 
                            indeterminate 
                            color="teal">
                        </v-progress-circular>

                        <span v-if="uploadPackage">
                            <div class="result" v-if="uploadPackage.status == 'D' || uploadPackage.status == 'E'">
                                Arquivo enviado: <strong>{{ uploadPackage.upload.filename }}</strong><br>
                                Tamanho do pacote: <strong>{{ uploadPackage.upload.size | filesize(2) }}</strong><br>
                                Data de publicação: <strong>{{ uploadPackage.upload.metadata.publish_on }}</strong><br>
                                Arquivos para download criados: <strong>{{ uploadPackage.meta.program_files_created.length }}</strong><br>
                                Arquivos para download atualizados: <strong>{{ uploadPackage.meta.program_files_updated.length }}</strong><br>
                                Programas atualizados: <strong>{{ uploadPackage.meta.programs_updated.length }}</strong><br>
                                <br>
                                <p v-if="uploadPackage.meta.errors.length > 0">Erros:
                                <ul>
                                    <li v-for="(item, index) in uploadPackage.meta.errors" :key="index">
                                        {{item}}
                                    </li>
                                </ul>
                                </p>
                            </div>
                        </span> 
                    </v-flex>
                    
                    
                
            </v-layout>
        
        </v-form>
        </v-container>
    
</template>

<script>
import MessageDialog from '../../components/MessageDialog.vue';
import { required } from '../../lib/ValidationFunctions';
import Tus from 'tus-js-client';
import filesize from '../../filters/FileSizeFilter';

export default {
    filters: {
      filesize
    },
    data() {
        return {
            date: "",
            menu: false,
            file: null,
            fileSize: 0,
            sent: false,
            formValid: false,
            valid: false,
            sending: false,
            progress: 0,
            bytesTotal: 0,
            bytesUploaded: 0,
            uploadId: null,
            uploadPackage: {
                status: null
            },
            validationRules: {
                required: [
                    v => required(v),
                ]
            },
        }
    },

    computed: {
      computedDateFormatted () {
        return this.formatDate(this.date)
      },
    },

    watch:{
        file: 'fileValidation',
        formValid: 'fileValidation'
    },

    methods: {
        fileValidation(){
            this.valid = this.formValid && (this.file != null)
        },
        formatDate (date) {
            if (!date) return null

            const [year, month, day] = date.split('-')
            return `${day}/${month}/${year}`
        },
        setFile(e)
        {
            this.file = e.target.files[0];
            this.fileSize = this.file.size;
        },
        updatePackageStatus() {
            this.$http.get(`/packages/${this.uploadId}`)
                .then( r => {
                    console.log(r.data)
                    this.uploadPackage = r.data.data;
                    if (!this.uploadPackage || !this.uploadPackage['status'] || (this.uploadPackage.status != 'D' && this.uploadPackage.status != 'E'))
                    {
                        setTimeout( () => this.updatePackageStatus(), 3000 );
                    }
                });
        },

        submit(){
            this.sending = true;
            const file = this.file;
            console.log(this.$auth.user());
            this.uploadId = 'program_file' + file.name;
            this.$http.post('/uploadjobs', {
                id: this.uploadId,
                filesize: file.size,
                filename: file.name,
                filetype: file.type,
                publish_on: this.computedDateFormatted
            }).then( r => {
                this.$localStorage.set('upload_token', r.data.upload_token);
                this.uploadFile(false, r) 
            })
            .catch( error => {
                console.log( error.response );
                if (error.response.status == 422 && 
                    error.response.data.errors && 
                    error.response.data.errors.id[0] == 'The id has already been taken.' )
                {
                    this.uploadFile(true, error.response);    
                }

            })
        },

        uploadFile(resuming, r){
            const upload_token = resuming ? this.$localStorage.get('upload_token') : r.data.upload_token;
            const file = this.file;
            // Create a new tus upload
            var upload = new Tus.Upload(file, {
            endpoint: "/tus/uploads/",
            retryDelays: [0, 1000, 3000, 5000],
            chunkSize: 1024 * 1024 * 5,
            metadata: {
                upload_request_id: this.uploadId,
                filename: file.name,
                filesize: file.size,
                filetype: file.type,
                token: upload_token
            },
            onError: function(error) {
                console.log("Failed because: " + error)
            },
            onProgress: (bytesUploaded, bytesTotal) => {
                var percentage = (bytesUploaded / bytesTotal * 100);
                this.bytesUploaded = bytesUploaded;
                this.bytesTotal = bytesTotal;
                this.progress = Math.round(percentage);
                console.log(bytesUploaded, bytesTotal, percentage)
            },
            onSuccess: () => {
                console.log("Download %s from %s", upload.file.name, upload.url)
                setTimeout( () => {
                    this.sent = true;
                    this.sending = false;
                }, 500)
                this.$localStorage.remove('upload_token');
                this.updatePackageStatus();
            }
            })

            // Start the upload
            upload.start()
        }
    }
}



</script>

<style>
    #select-files{
        width: 500px;
        height: 500px;
        background: #a0a0a0;
    }
    .result {
        font-size: 12px;
        color: rgb(48, 48, 48);
    }
</style>

