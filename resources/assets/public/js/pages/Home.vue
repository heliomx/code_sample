<template>
    <div id="home">
        <section class="content-area highlight">
            <div>
                
                <carousel 
                    :perPage="1"  
                    :paginationEnabled="false" 
                    :navigationEnabled="true" 
                    navigationNextLabel="&#10217;" 
                    navigationPrevLabel="&#10216;"
                >
                    <slide>
                        <img src="/img/mock/amado hi.jpg" width="100%">
                        
                        <div class="slide-label">
                            Amado Batista
                            <div class="description">
                                Este programa já está sendo distribuído pela Rádio Estúdio Brasil 
                                para mais de 800 rádios cadastradas no Brasil e no exterior
                            </div>
                        </div>
                    </slide>
                    <slide>
                        <img src="/img/mock/edelson hi.jpg" width="100%">
                        <div class="slide-label">
                            Cantores do Brasil
                            <div class="description">
                                Cantores do Brasil é um programa semanal, com apresentação de Edelson Moura.
                            </div>
                        </div>
                    </slide>
                </carousel>
                <div class="mask"></div>    
            </div>   
            
        </section>
        <section class="content-area radio">
            <div>
                <h2>Bem-vindo a <strong>Rádio Estúdio Brasil</strong></h2>
                <p>A 10 Anos no mercado a Rádio Estúdio Brasil vem inovando sempre
                    nos seus conceitos e nos seus produtos. Nossa empresa oferece 
                    programas de áudio produzidos, gravados e editados para mais de 1.200 
                    Emissoras de Rádio no Brasil e no Mundo. 
                </p>
                <p><router-link to="quemsomos">Nossa equipe</router-link> cuida de todos 
                    os detalhes: Edição de Áudio , Sonoplastia. <br>Tudo para lhe oferecer 
                    de forma Gratuita conteúdo para sua emissora de Rádio.
                </p>

                <v-btn class="clearfix" color="secondary" large to="cadastro">Cadastre-se e receba nosso conteúdo gratuito</v-btn>
                
            </div>   
        </section>
        <section class="content-area programs">
            <div>
                <h2>Nossos <strong>Programas</strong></h2>
                <p>São diversos programas a escolha da sua emissora: programas gratuitos, 
                    com conteudos jornalísticos, programas diários com duração de uma ou duas horas  
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
                                        <v-card-media :src="program.full_img_path" height="220px">
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
                
                <v-btn class="bt-ver-mais" color="secondary" @click="toggleHilight" large right>
                    Ver
                    {{ showHilight ? 'Todos' : 'Menos' }}
                </v-btn>
            </div>   
        </section>
        <section class="content-area advertisement">
            <div>
                <h2>Conheça também</h2>
                <carousel 
                    :perPage="1" 
                    :paginationEnabled="false" 
                    :navigationEnabled="true" 
                    navigationNextLabel="&#10217;" 
                    navigationPrevLabel="&#10216;"
                >
                    <slide>
                        <img src="/img/bannerTvEstudioBrasil.jpg">
                    </slide>
                    <slide>
                        <img src="/img/mock/saudeComBeleza.jpg">
                    </slide>
                </carousel>
                
            </div>
        </section>
    </div>
</template>

<script>
import { Carousel, Slide } from 'vue-carousel';

export default {
    components: {
        Carousel,
        Slide
    },

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
            this.$router.push('/programas/' + program.id);
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

<style lang="scss">
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


#home {
    section {

        &.highlight {
            background: #000;
            height: 400px;
            position: relative;
            z-index: 0;

            * {
                color: #fff;
            }

            .VueCarousel-navigation-button {
                font-size: 70px !important;
                color: #fff !important;
                top: 45%;
            }

            .mask { 
                /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#000000+0,000000+100&1+0,0+5,0+90,1+100 */
                background: -moz-linear-gradient(left, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 5%, rgba(0,0,0,0) 95%, rgba(0,0,0,1) 100%); /* FF3.6-15 */
                background: -webkit-linear-gradient(left, rgba(0,0,0,1) 0%,rgba(0,0,0,0) 5%,rgba(0,0,0,0) 95%,rgba(0,0,0,1) 100%); /* Chrome10-25,Safari5.1-6 */
                background: linear-gradient(to right, rgba(0,0,0,1) 0%,rgba(0,0,0,0) 5%,rgba(0,0,0,0) 95%,rgba(0,0,0,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                
            } 

            &.content-area > div {
                background-color: transparent;
                padding: 0;
                position: relative;

                
            }

            .VueCarousel-pagination {
                display: none;
            }

            .VueCarousel-slide {
                height: 400px;
                position: relative;

                .slide-label {
                    position: absolute;
                    top: 100px;
                    left: 500px;
                    background: #00000090;
                    color: #fff;
                    font-family: $title-font;
                    font-size: 40px;
                    padding: 0 20px;

                    .description {
                        font-size: 16px;
                        padding-bottom: 20px;
                    }

                    
                }
            }

        }
        
        &.radio {
            background: url(/img/waves2.png) no-repeat;
            background-position: center;
            background-size: 100%;
            
            .btn {
                float:right;
                margin-top: 40px;
            }

            &.content-area > div {
                background-color: #ffffffd0;
                @include clearfix;
            }
        }

        &.programs
        {
            padding-bottom: 80px;

            .bt-ver-mais {
                float: right;
            }

            &.content-area > div {
                background-color: transparent;
            }

            background-color: $primary-dark;

            p {
                color: $body-color-light;
            }

            .container.grid-list-xl .layout .flex {
                padding: 22px;
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
                    padding-top: 15px;

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

        &.advertisement {
            .VueCarousel-navigation-button {
                font-size: 70px !important;
                top: 45%;
            }

             .VueCarousel-slide {
                text-align: center;
            }
        }

        .VueCarousel-navigation--disabled {
            opacity: 0.1;
        }
       
    }
}
</style>
