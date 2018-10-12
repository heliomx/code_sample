<template>
<v-app>
    <div class="header-info">
        <div class="header-content">
            <v-layout row fluid>
                <v-flex xs12 sm8>
                    <a href="mailto:contato@radioestudiobrasil.com.br">contato@radioestudiobrasil.com.br</a> 
                    | <strong>(61) 3532-6993</strong> | 
                    <img src="/img/wpp-icon.png" width="19"> <strong>(61) 9 9966-9557</strong>
                </v-flex>
                <v-spacer></v-spacer>
                    <a href="/admin">ÁREA RESTRITA</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<router-link to="/cadastro">CADASTRE-SE</router-link>
                
            </v-layout>
        </div>
    </div>
    <div id="player-container">
        <img src="/img/logo-sm.png">
        <iframe id="player" src="/player.html" frameborder="0" width="60%" height="69" scrolling="no"></iframe>
        <div class="adpter"></div>
        <div class="subtitle text-xs-center">Rádio Estúdio Brasil<br>Não existe distância, nós chegamos lá.</div>
    </div>
    <div class="header">
        <div class="bg"></div>
        <div class="header-content">
            <img src="/img/LogoRadio.png">
            <nav>
                <ul>
                    <li>
                        <router-link to="/">Home</router-link>
                    </li>
                    <li>
                        <router-link to="/quemsomos">Quem somos</router-link>
                    </li>
                    <li>
                        <router-link to="/programas">Programas Gratuitos</router-link>
                    </li>
                    <li>
                        <router-link to="/cadastro">Cadastre-se</router-link>
                    </li>
                    <li>
                        <router-link to="/faleconosco">Fale Conosco</router-link>
                    </li>
                    <!-- <li>
                        <a href="http://www.radioestudiobrasil.com.br/" target="_blank">Edelson Moura na TV</a>
                    </li> -->
                </ul>
            </nav>
        </div>
        
    </div>
    <v-content>
        <v-container fluid>
            <transition name="fade" appear mode="out-in">
                <router-view></router-view>
            </transition>
        </v-container>
    </v-content>
    
    <v-footer :fixed="false" app color="#c0c0c0">
        
        <div>
            Radio Estúdio Brasil - <a href="mailto:contato@radioestudiobrasil.com.br">contato@radioestudiobrasil.com.br</a> 
                    | <strong>(61) 3532-6993</strong>
        </div>
    </v-footer>
</v-app>
</template>

<script>
  import AudioPlayer from './components/AudioPlayer'
 
  export default {
    components: {
      AudioPlayer
    },
 
    data () {
      return {
        audioSources: [
          "http://23.235.255.26:8101/stream",
        ]
      }
    }
  }
</script>
<style lang="scss">
// Global CSS and Variables
@import '../sass/app.scss';

* {
    font-family: $title-font;
}

body {
    margin-top: 30px;
}

footer {
    position: relative !important;
}

#player-container{
    position: fixed;
    z-index: 9;
    width: 268px;
    top: 30px;
    right: -15px;
    border-radius: 0px 0px 10px 10px;
    background: #048d01;
    padding: 5px;
    box-shadow: 0px 1px 9px 0px rgba(0, 0, 0, 0.18);
    height:124px;

    iframe {
        position: relative;
        top: -5px;
    }

    img {
        float: left;
    }

    .adpter {
        background: #048d01;
        position: absolute;
        width: 158px;
        height: 40px;
        top: -7px;
        left: 68px;
    }

    .subtitle {
        color: #b0e4af;
        font-size: 12px;
        text-align: center;
    }
}

.header-info {
    background-color: $primary-light;
    top: 0px;
    color: #fff;
    font-size: 16px;
    -webkit-box-shadow: 0px 1px 9px 0px rgba(0, 0, 0, 0.18);
    -moz-box-shadow: 0px 1px 9px 0px rgba(0, 0, 0, 0.18);
    box-shadow: 0px 1px 9px 0px rgba(0, 0, 0, 0.18);
    width:100%;
    position:fixed;

    img {
        position:relative;
        top: 5px;
    }

    a {
        color: #fff;
    }
    z-index: 10;
}
.header {
    
    background-color: $primary-color; // Old browsers
    
    /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#005b03+0,2d870c+17,2d870c+83,005b03+100 */
    background: -moz-linear-gradient(left, #005b03 0%, #2d870c 17%, #2d870c 83%, #005b03 100%); /* FF3.6-15 */
    background: -webkit-linear-gradient(left, #005b03 0%,#2d870c 17%,#2d870c 83%,#005b03 100%); /* Chrome10-25,Safari5.1-6 */
    background: linear-gradient(to right, #005b03 0%,#2d870c 17%,#2d870c 83%,#005b03 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#005b03', endColorstr='#005b03',GradientType=1 ); /* IE6-9 */
    -webkit-box-shadow: 0px 1px 9px 0px rgba(0, 0, 0, 0.18);
    -moz-box-shadow: 0px 1px 9px 0px rgba(0, 0, 0, 0.18);
    
    box-shadow: 0px 1px 9px 0px rgba(0, 0, 0, 0.18);
    position:relative;
    padding-top:20px;

    .bg {
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: url('/img/transmission.png');
        top:0;
        left: 0;
        z-index: 1;
        background-position: 825px;
        opacity: 0.2;
    }
    
    a {
        color: #fff;
        display: inline-block;
        padding: 10px;
        text-decoration: none;
        transition: 0.5s;  
        


        &:hover {
            background-color: $primary-light;
            transition: 0.5s;
        }
    }

    nav {
        display: flex;
        flex-basis: 100%;
        flex-direction: row-reverse;
        text-transform: uppercase;

        ul {
            list-style-type: none;
            
            li {
                float:left;
            }
        }
    }

    .header-content {
        padding: 0;
        position: relative;
        z-index: 2;
        
    }
}
.header-content {
    max-width: $content-width;
    display: flex;
    flex-wrap: wrap;
    padding: 3px 0;
    margin: 0 auto;
}

footer div{
    width: $content-width;
    margin: 0 auto;
    padding: 0 10px;
    font-size: 19px;

}

.btn-area {
    text-align: right;
    margin-top: 40px;
}

.fade-enter {
        opacity: 0;
    }

    .fade-enter-active {
        transition: opacity 0.3s ease;
    }

    .fade-leave {

    }

    .fade-leave-active {
        transition: opacity 0.3s ease;
        opacity: 0;
    }
</style>

