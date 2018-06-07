<template>
<v-app>
    <div class="header-info">
        <div class="header-content">
            <v-layout row fluid>
                <v-flex xs12 sm6>
                    <a href="mailto:contato@radioestudiobrasil.com.br">contato@radioestudiobrasil.com.br</a> 
                    | (61) 3532-6993
                </v-flex>
                <v-spacer></v-spacer>
                
                    <a href="/admin">ÁREA RESTRITA</a>&nbsp;|&nbsp;<router-link to="/cadastro">CADASTRE-SE</router-link>
                
            </v-layout>
        </div>
    </div>
    <div class="header">
        <div class="header-content">
            <img src="/img/LogoRadio.png">
            
            <nav>
                <ul>
                    <li>
                        <router-link to="home">Home</router-link>
                    </li>
                    <li>
                        <router-link to="quemsomos">Quem somos</router-link>
                    </li>
                    <li>
                        <router-link to="programas">Programas Gratuitos</router-link>
                    </li>
                    <li>
                        <router-link to="cadastro">Cadastre-se</router-link>
                    </li>
                    <li>
                        <router-link to="faleconosco">Fale Conosco</router-link>
                    </li>
                    <li>
                        <a href="http://tvestudiobrasil.com/" target="_blank">TV Estúdio Brasil</a>
                    </li>
                </ul>
            </nav>
        </div>
        
    </div>
    <v-content>
        <v-container fluid>
            <router-view></router-view>
        </v-container>
    </v-content>
    <v-footer app color="#c0c0c0">
        <div class="">
            Radio Estúdio Brasil
        </div>
    </v-footer>
</v-app>
</template>

<style lang="scss">
// Global CSS and Variables
@import '../sass/app.scss';


.header-info {
    background-color: $primary-light;
    color: #fff;
    font-size: 12px;
    -webkit-box-shadow: 0px 1px 9px 0px rgba(0, 0, 0, 0.18);
    -moz-box-shadow: 0px 1px 9px 0px rgba(0, 0, 0, 0.18);
    box-shadow: 0px 1px 9px 0px rgba(0, 0, 0, 0.18);
    position: relative;

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

        ul {
            list-style-type: none;
            
            li {
                float:left;
            }
        }
    }

    .header-content {
        padding: 0;
    }
}
.header-content {
    max-width: $content-width;
    display: flex;
    flex-wrap: wrap;
    padding: 10px 0;
    margin: 0 auto;
}
</style>

