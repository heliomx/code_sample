<template>
    <v-container>
        <h1 class="headline">Programas disponíveis para baixar</h1>
        <v-container fluid grid-list-md>
            <v-layout row wrap>
                
                <v-flex xs12 sm7 v-for="program in programs" :key="program.id">
                    <v-card>
                        <v-card-title>
                            <h2>{{ program.name }}</h2>
                        </v-card-title>
                        <v-card-text>
                            <ul>
                                <li v-for="file in program.files" :key="file.id">
                                    <small>{{ file.air_on | dateformat}}</small><br>
                                    <a target="_blank" href="#" @click.prevent="download(file)" >Programa {{ file.program_number }}</a>
                                    
                                </li>
                            </ul>  
                        </v-card-text>
                        <img :src="program.full_img_path">
                    </v-card>

                </v-flex>
                
                <v-flex v-if="waitingApproval && waitingApproval.length > 0" xs12 sm7>
                    <h2>Assinaturas aguardando aprovação:</h2>
                    <p v-for="program in waitingApproval" :key="program.id">
                        {{ program.name }}
                    </p>
                </v-flex>
            </v-layout>
        </v-container>
    </v-container>
</template>

<script>
import dateformat from '../../filters/DateFormatFilter';

export default {
    filters: {
        dateformat
    },
    data() {
        return {
            programs: null,
            waitingApproval: null
        }
    },

    created() {
        this.fetchData();
    },

    methods: {
        download(file)
        {
            window.open('/api/programs/downloads/' + file.id + '?token=' + this.$auth.token(), '_blank');
        },
        fetchData()
        {
            this.$global.loading = true;
            this.$http.get('programs?downloads=true')
                .then( r => {
                    this.$global.loading = false;
                    if (this.$auth.check('A'))
                    {
                        this.programs = r.data.data;
                    } else {
                        this.programs = r.data.data.actives;
                        this.waitingApproval = r.data.data.waiting;
                    }
                    
                    
                });
        }
    }
}
</script>

<style lang="scss" scoped>
.program {
    margin-bottom: 30px;
}
ul {
    margin-left: 30px;

    li:hover {
        background-color: #F0F0F0;
    }
}
.card 
{
    min-height: 288px;
    img {
        position:absolute;
        right: 0;
        top: 0;
    }
}

h2 { 
    margin-top: 50px;
}
</style>
