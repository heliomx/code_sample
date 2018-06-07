<template>
    <div>
        <section class="content-area">
            <div>
                <img src="/img/bannerTvEstudioBrasil.jpg">
            </div>
        </section>
        <section class="content-area radio">
            <div>
                <h2>A <strong>Rádio</strong></h2>
                
            </div>   
        </section>
        <section class="content-area programs">
            <div>
                <h2>Nossos <strong>Programas</strong></h2>
                <p>São diversos programas a escolha da sua emissora: programas gratuitos, 
                    com conteudos jornalisticos, programas diários com duração de 01 ou 2 horas  
                    e programas semanais. 
                    </p>
                    <p>
                        <strong>Confira o conteúdo que preparamos para sua emissora.</strong>
                    </p>
                    
                    <v-container grid-list-xl>
                        <v-layout row wrap>
                            <v-flex xs4 v-for="program in filteredPrograms" :key="program.id">
                                <div @click="openProgram(program)">
                                    <v-card >
                                        <v-card-media :src="program.full_img_path" height="280px">
                                        </v-card-media>
                                        <v-card-title primary-title>
                                        <div>
                                            <h3>{{ program.name }}</h3>
                                            <div></div>
                                        </div>
                                        </v-card-title>
                                    </v-card>
                                </div>
                            </v-flex>
                        </v-layout>
                    </v-container>
                
                <v-btn color="secondary" @click="toggleHilight" large right>
                    Ver
                    {{ showHilight ? 'Todos' : 'Menos' }}
                </v-btn>
            </div>   
        </section>
    </div>
</template>

<script>

export default {
    data(){
        return {
            programs:[],
            filteredPrograms: [],
            showHilight: true
        }
    },
    methods: {
        toggleHilight(){
            this.showHilight = !this.showHilight;
            this.filterPrograms();
        },

        openProgram(program){
            console.log(program);
        },

        filterPrograms(){
            console.log('aqui');
            if (this.showHilight)
            {
                this.filteredPrograms = this.programs.slice(0,3);
            } else {
                this.filteredPrograms = this.programs;
            }
        },
        
        fetchData(){
            this.$global.loading = true;
            this.$http.get('programs')
            .then( r => {
                this.$global.loading = false;
                this.programs = r.data.data;
                this.filterPrograms();
            });
        }
    },
    created() {
        this.fetchData();
    }
}
</script>

<style lang="scss" scoped>
@import '../../sass/variables';

.list-complete-item {
  transition: all 1s;
}
.list-complete-enter
{
  opacity: 0;
  transform: translateY(-20px);
}
.list-complete-leave-to
{
  opacity: 0;
  transform: translateY(20px);
}
.list-complete-leave-active {
  position: absolute;
}

section {

    &.content-area > div {
        padding: 30px;
    }

    h2 {
        color: $title-color;
        font-size: 35px;
        line-height: 31px;
        font-family: $title-font;
        font-weight: 300;
        margin-bottom: 20px;

        strong {
            font-weight: 700;
              
            display: block;
            margin-left: 63px;        
        }
    }

    p {
        margin-left: 25px;
    }

    &.programs
    {
        &.content-area > div {
            background-color: transparent;
        }

        background-color: $primary-dark;

        p {
            color: $body-color-light;
            width: 80%;
            font-size: 17px;
            font-family: $title-font;
        }

        .card {
            cursor: pointer;
            background-color: $primary-light2;

            &:hover {
                .card__title {
                    background-color: $primary-light2;
                    transition: 0.5s;

                    h3 {
                        color: $title-color;
                        transition: 0.5s;
                    }
                }
            }

            .card__title {
                background-color: $primary-light;
                transition: 0.5s;

                h3 {
                    color: $primary-light2;
                    font-family: $title-font;
                    font-weight: normal;
                    transition: 0.5s;
                }
            }

            .card__media__content {
                background-color: transparent;
            }
        }

        

        h2 {
            color: $primary-light2;

            strong {
                color: $secondary-color;
            }
        }        
    }
    


}
</style>
