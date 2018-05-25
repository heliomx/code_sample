<template>
    <v-container>
        <h1 class="headline">Programas disponíveis para baixar</h1>
        <v-container fluid grid-list-md>
            <v-layout row wrap>
                <v-flex xs12 sm7 v-for="program in programs" :key="program.id">
                    <v-card>
                        <v-card-title>
                            <h2 class="subheading">{{ program.name }}</h2>
                        </v-card-title>
                        <v-card-text>
                            <ul>
                                <li v-for="file in program.files" :key="file.id">
                                    <small>{{ file.air_on | dateformat}}</small><br>
                                    <a target="_blank" :href="'/api/programs/downloads/' + file.id">Programa {{ file.program_number }}</a>
                                    
                                </li>
                            </ul>  
                        </v-card-text>
                    </v-card>

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
            programs: null
        }
    },

    created() {
        this.fetchData();
    },

    methods: {
        fetchData()
        {
            this.$global.loading = true;
            this.$http.get('programs?downloads=true')
                .then( r => {
                    this.$global.loading = false;
                    this.programs = r.data.data;
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
</style>
