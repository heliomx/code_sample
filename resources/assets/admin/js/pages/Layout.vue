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
            v-else-if="item.children"
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
          <v-list-tile v-else :key="item.text" @click="open(item)">
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
            <span class="hidden-sm-and-down">Rádio Brasil</span>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat @click="$auth.logout()">Sair</v-btn>
    </v-toolbar>

    <v-content>
        <v-progress-linear v-if="$global.loading" :indeterminate="true"></v-progress-linear>
        <v-container fluid>
            <v-layout>
                <router-view></router-view>
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
        items: [{
                icon: "keyboard_arrow_up",
                "icon-alt": "keyboard_arrow_down",
                text: "Clientes",
                children: [{
                        icon: "contacts",
                        text: "Listar",
                        link: "/clientes"
                    },
                    {
                        icon: "add",
                        text: "Cadastrar",
                        link: "/clientes/cadastrar"
                    },

                ]
            },
            {
                icon: "keyboard_arrow_up",
                "icon-alt": "keyboard_arrow_down",
                text: "Programas",
                children: [
                    {
                        icon: "upload",
                        text: "Enviar",
                        link: "/programas/enviar"
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
            // {
            //     icon: "keyboard_arrow_up",
            //     "icon-alt": "keyboard_arrow_down",
            //     text: "Relatórios",
            //     children: [{
            //             text: "Clientes"
            //         },
            //         {
            //             text: "Programas"
            //         },

            //     ]
            // },
            // {
            //     icon: "radio",
            //     text: "Programas",
            // },

        ]
    }),
    props: {
        source: String
    },
    created() {

    },
    methods: {
        open(item) {
            if (item.link) this.$router.push(item.link);
        }
    }
};
</script>
