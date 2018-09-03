<template>
<div style="width:100%">
    <h1 class="headline">Gerar relatório</h1>
    <div v-if="!reportReady && !generating">
        <v-flex xs12 sm6 md4>
            <v-date-picker type="month" locale="pt-br" v-model="reportDate"></v-date-picker>
        </v-flex>
        <v-flex xs12 v-if="!sending && !sent">
            <v-btn @click="generate" :disabled="!reportDate">
                Gerar
            </v-btn>
        </v-flex>
    </div>
    <div v-if="generating">
        <p>
            <v-progress-circular 
                v-if="!reportReady" 
                indeterminate 
                v-bind:width="1" color="gray">
            </v-progress-circular> 
            {{ status.msg }}
        </p>
        <v-progress-circular
        v-bind:size="100"
        v-bind:width="15"
        v-bind:rotate="360"
        v-bind:value="progress"
        color="teal"
        >
        {{ progress }}
        </v-progress-circular>
        <v-flex xs12 v-if="reportReady">
            <v-btn  @click="download">
                Download do Relatório
            </v-btn>
        </v-flex>
    </div>

</div>
</template>

<script>
export default {
    data() {
        return {
            reportDate: '',
            status: {
                msg: '...'
            },
            reportReady: false,
            generating: false,
            progress:0,

        }
    },
    methods: {
        generate() {
            let date = this.reportDate.split('-');
            this.$http.get('/report', {
                params: {
                    month: date[1],
                    year: date[0]
                }
            });

            this.generating = true;
            
            this.updateStatus();
        },
        download()
        {
            window.open('/api/report/download/?token=' + this.$auth.token(), '_blank');
        },
        updateStatus() {
            this.$http.get(`/report/status`)
                .then(r => {
                    console.log(r.data)
                    this.status = r.data;
                    if (this.status.status != '3') {
                        setTimeout(() => this.updateStatus(), 1000);
                    } else {
                        setTimeout( () => this.reportReady = true, 1000);
                    }
                    if(this.status.totalSteps > 0)
                    {
                        this.progress = Math.round( (this.status.step / this.status.totalSteps) * 100 );
                    }
                });
        }
    }

}
</script>
