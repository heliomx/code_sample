<template>
    <transition name="fade" appear mode="out-in">
        <div v-if="!$global.loading" id="home">
            <section class="content-area highlight">
                <div>
                    
                    <carousel 
                        v-if="content.slides.length > 0"
                        :perPage="1"  
                        :paginationEnabled="false" 
                        :navigationEnabled="true" 
                        navigationNextLabel="&#10217;" 
                        navigationPrevLabel="&#10216;"
                    >
                        <slide v-for="slide in content.slides" :key="slide.id">
                            <a :href="slide.link">
                            <img :src="slide.img" width="100%">
                            
                            <div class="slide-label">
                                {{ slide.title }}
                                <div class="description">
                                    {{ slide.description }}
                                </div>
                            </div>
                            </a>
                        </slide>
                    </carousel>
                    <div class="mask"></div>    
                </div>   
                
            </section>
            <section class="content-area radio">
                <div>
                    <h2 v-html="content.welcome.title"></h2>
                    <div class="section-text" v-html="content.welcome.body"></div>

                    <v-btn class="clearfix" color="secondary" large to="cadastro">Cadastre-se e receba nosso conteúdo gratuito</v-btn>
                    
                </div>   
            </section>
            <section class="content-area programs">
                <div>
                    <h2 v-html="content.ourPrograms.title"></h2>
                    <div class="section-text" v-html="content.ourPrograms.body"></div>
                        
                        <v-container grid-list-xl>
                            <v-layout row wrap>
                                
                                    <v-flex xs4 v-for="program in filteredPrograms" :key="program.id">
                                        <div @click="openProgram(program)">
                                            <v-card >
                                                <v-card-media :src="program.full_img_path" height="288px">
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
                    <h2 v-html="content.seeAlso.title"></h2>
                    <carousel 
                        v-if="content.seeAlso.slides.length > 0"
                        :perPage="1" 
                        :paginationEnabled="false" 
                        :navigationEnabled="true" 
                        navigationNextLabel="&#10217;" 
                        navigationPrevLabel="&#10216;"
                    >
                        <slide v-for="slide in content.seeAlso" :key="slide.id">
                            <a :href="slide.link" :title="slide.title" target="_blank">
                                <img :src="slide.img">
                            </a>
                        </slide>
                        
                    </carousel>
                    
                </div>
            </section>
        </div>
    </transition>

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
            content: {
				slides: [],
				welcome: '',
				ourPrograms: '',
				selectedPrograms: [],
				seeAlso: []
			},
            availablePrograms:[],
            programsHighlight: [],
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
                this.filteredPrograms = this.programsHighlight;
            } else {
                this.filteredPrograms = this.availablePrograms;
            }
        },
        
        fetchData() {
			this.$global.loading = true;
			this.$http.get('contents/home')
				.then( response => {
					this.content = response.data.data.doc;
					
					return this.$http.get('programs')
						.then( response => {
							this.availablePrograms = response.data.data;
							this.programsHighlight = this.content.selectedPrograms
								.map(p => 
								{
									return this.availablePrograms[this.availablePrograms.findIndex(e => e.id == p)];
								})
                            this.$global.loading = false;
                            this.filterPrograms();
                            window._ref = this;
						})
				});
		},
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

        .section-text {
            margin-left: 25px;
            width: 80%;
            font-size: 17px;
            font-family: $title-font;
        }

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
                pointer-events:none;
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

            .section-text {
                color: $body-color-light;
            }

            .container.grid-list-xl 
            {
                padding: 0;

                .layout .flex {
                    padding: 15px;
                }
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
