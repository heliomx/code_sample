<template>
<v-app id="inspire">

    <v-navigation-drawer fixed :clipped="true" app mobile-break-point="1024" v-model="drawer">
        <v-list dense>
            <template v-for="item in items">
          <v-layout
            row
            v-if="item.heading"
            align-center
            :key="item.heading"
          >
            <v-flex xs6>
              <v-subheader v-if="item.heading">
                {{ item.heading }}
              </v-subheader>
            </v-flex>
            <v-flex xs6 class="text-xs-center">
              <a href="#!" class="body-2 black--text">EDIT</a>
            </v-flex>
          </v-layout>
          <v-list-group
            v-else-if="$auth.check(item.roles) && item.children"
            v-model="item.model"
            :key="item.text"
            :prepend-icon="item.model ? item.icon : item['icon-alt']"
            append-icon=""
          >
            <v-list-tile slot="activator">
              <v-list-tile-content>
                <v-list-tile-title>
                  {{ item.text }}
                </v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-list-tile class="ml-5"
              v-for="(child, i) in item.children"
              :key="i"
              v-if="$auth.check(child.roles)"
              @click="open(child)"
            >
              <v-list-tile-action v-if="child.icon">
                <v-icon>{{ child.icon }}</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>
                  {{ child.text }}
                </v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
          </v-list-group>
          <v-list-tile v-else-if="$auth.check(item.roles)" :key="item.text" @click="open(item)">
            <v-list-tile-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>
                <router-link v-if="item.link" :to="item.link">{{ item.text }}</router-link>
                <span v-if="!item.link">{{ item.text }}</span>
              </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </template>
        </v-list>
    </v-navigation-drawer>
    <v-toolbar color="primary" dark app :clipped-left="true" fixed>
        <v-toolbar-title class="ml-0 pl-3">
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <span class="hidden-sm-and-down">Rádio Estúdio Brasil</span>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat @click="$auth.logout()">Sair</v-btn>
    </v-toolbar>

    <v-content>
        <transition name="fade" mode="out-in">
            <v-progress-linear v-if="$global.loading" :indeterminate="true"></v-progress-linear>
        </transition>
        <v-container fluid >
            <v-layout>
                <transition name="fade" appear mode="out-in">
                    <router-view></router-view>
                </transition>
            </v-layout>
        </v-container>
    </v-content>

</v-app>
</template>

<script>


export default {
    data: () => ({
        dialog: false,
        drawer: null,
        items: [
            {
                icon: "insert_chart_outlined",
                text: "Dashboard",
                link: "/dashboard",
                roles: [ 'A' ]
            },
            {
                icon: "assessment",
                text: "Relatório",
                link: "/relatorio",
                roles: [ 'A' ]
            },
            {
                icon: "contacts",
                text: "Alterar cadastro",
                link: "/cadastro",
                roles: [ 'C' ]
            },
            {
                icon: "get_app",
                text: "Download de programas",
                link: "/programas/downloads",
                roles: [ 'C' ]
            },
            {
                icon: "keyboard_arrow_up",
                "icon-alt": "keyboard_arrow_down",
                text: "Clientes",
                roles: ['A'],
                children: [{
                        icon: "contacts",
                        text: "Listar",
                        link: "/clientes"
                    },
                    {
                        icon: "add",
                        text: "Cadastrar",
                        link: "/clientes/cadastrar",
                    },

                ]
            },
            {
                icon: "keyboard_arrow_up",
                "icon-alt": "keyboard_arrow_down",
                text: "Programas",
                roles: [ 'A' ],
                children: [
                    {
                        icon: "get_app",
                        text: "Downloads",
                        link: "/programas/downloads",
                    },
                    {
                        icon: "cloud_upload",
                        text: "Enviar",
                        link: "/programas/enviar",
                    },
                    {
                        icon: "radio",
                        text: "Listar",
                        link: "/programas"
                    },
                    {
                        icon: "add",
                        text: "Cadastrar",
                        link: "/programas/cadastrar"
                    },

                ]
            },
            {
                icon: "keyboard_arrow_up",
                "icon-alt": "keyboard_arrow_down",
                text: "Conteúdo",
                roles: [ 'A' ],
                children: [
                    {
                        text: "Home",
                        link: "/conteudo/home",
                    },
                    {
                        text: "Quem somos",
                        link: "/conteudo/quemsomos",
                    },
                    {
                        text: "Alertas para Usuário",
                        link: "/conteudo/alertaUsuario",
                    }
                    
                ]
            },
            {
                icon: "perm_phone_msg",
                text: "Fale Conosco",
                link: "/faleconosco",
                roles: [ 'A' ]
            },
            {
                icon: "keyboard_arrow_up",
                "icon-alt": "keyboard_arrow_down",
                text: "Usuários",
                roles: ['A'],
                children: [{
                        icon: "group",
                        text: "Listar",
                        link: "/usuarios"
                    },
                    {
                        icon: "add",
                        text: "Cadastrar",
                        link: "/usuarios/cadastrar",
                    },

                ]
            },
        ]
    }),
    props: {
        source: String
    },
    created() {
        console.log(this.$auth.user());
        window._auth = this.$auth;
    },
    methods: {
        open(item) {
            if (item.link) this.$router.push(item.link);
        }
    }
};
</script>

<style lang="scss">

</style>

