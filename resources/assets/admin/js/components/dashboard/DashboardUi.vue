<template>
<transition name="fade" mode="out-in">
    <div v-if="!$global.loading" style="margin-top: 40px">
        <v-container fluid grid-list-lg="true">
            <v-layout row wrap>
                <v-flex xs12 md3 class="text-xs-center">
                    <h2 class="text-xs-center">Dashboard</h2>
                    <v-btn :loading="loading" small @click="updateData">Atualizar</v-btn>
                </v-flex>
                <v-flex xs4 md3>
                    <v-card>
                        <div class="d-card">
                            <div class="ico"><v-icon>contacts</v-icon></div>
                            <div class="data">
                                <div class="title">Clientes</div>
                                <div class="value">{{ stats.activeClients }}</div>
                                <div class="description">Ativos</div>
                            </div>
                        </div>
                    </v-card>
                </v-flex>
                <v-flex xs4 md3>
                    <v-card>
                        <div class="d-card">
                            <div class="ico"><v-icon>edit</v-icon></div>
                            <div class="data">
                                <div class="title">Assinaturas</div>
                                <div class="value">{{ stats.activeSignatures }}</div>
                                <div class="description">Ativas</div>
                            </div>
                        </div>
                    </v-card>
                </v-flex>
                <v-flex xs4 md3>
                    <v-card>
                        <div class="d-card">
                            <div class="ico"><v-icon>get_app</v-icon></div>
                            <div class="data">
                                <div class="title">Downloads</div>
                                <div class="value">{{ stats.downloads }}</div>
                                <div class="description">Total</div>
                            </div>
                        </div>
                    </v-card>
                </v-flex>
                <v-flex md4 xs12>
                    <v-card>
                        <div class="d-card">
                            <div class="data">
                                <div class="title left">Clientes por UF</div>
                                <div class="value">
                                    <v-data-table 
                                        :headers="headersStates" 
                                        :items="stats.clientsPerState" 
                                        :pagination.sync="paginationUF" 
                                        :disable-initial-sort="true"
                                        :rows-per-page-text='""'>
                                        <template slot="items" slot-scope="props">
                                            <td class="text-xs-center">{{ props.item.address_uf }}</td>
                                            <td>{{ props.item.qt }}</td>
                                        </template>
                                    </v-data-table>
                                </div>
                            </div>
                        </div>
                    </v-card>

                </v-flex>

                <v-flex xs12 md8>
                    <v-card>
                        <div class="d-card">
                            <div class="data">
                                <div class="title left">Estatística dos Programas</div>
                                <div class="value">
                                    <v-data-table 
                                    :headers="headersPrograms" 
                                    :items="stats.programs" 
                                    :pagination.sync="paginationPrograms" 
                                    :rows-per-page-text='"Itens por página:"'
                                    :disable-initial-sort="true">
                                        <template slot="items" slot-scope="props">
                                            <td class="text-xs-left">
                                                <router-link :to="{ name: 'editProgram', params: { id: props.item.id }}">
                                                    {{ props.item.name }}
                                                </router-link>
                                            </td>
                                            <td>{{ props.item.qt_signatures }} / {{ props.item.signatures_count }}</td>
                                            <td>{{ props.item.files_count }}</td>
                                            <td>{{ props.item.downloads_count }}</td>
                                        </template>
                                    </v-data-table>
                                </div>
                            </div>
                        </div>
                    </v-card>

                </v-flex>
            </v-layout>
        </v-container>
    </div>
</transition>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            paginationUF: {
                rowsPerPage: 5
            },
            paginationPrograms: {
                rowsPerPage: 5
            },
            headersStates: [{
                    text: 'UF',
                    align: 'center',
                    value: 'address_uf'
                },
                {
                    text: 'Quantidade',
                    align: 'right',
                    value: 'qt'
                }
            ],
            headersPrograms: [{
                    text: 'Nome',
                    align: 'left',
                    value: 'name'
                },
                {
                    text: 'Assinaturas',
                    value: 'signatures_count',
                    align: 'right',
                },
                {
                    text: 'Arquivos',
                    value: 'files_count',
                    align: 'right',
                },
                {
                    text: 'Downloads',
                    value: 'downloads_count',
                    align: 'right',
                }
            ],
            stats: {
                activeClients: 0,
                activeSignatures: 0,
                clientsPerState: [],
                downloads: 0,
                programs: [],
            }
        }
    },
    methods: {
        fetchData() {
            this.$global.loading = true;
            this.updateData();
        },
        updateData()
        {
            this.loading = true;
            this.$http.get('dashboard')
            .then(r => {
                this.loading = false;
                this.stats = r.data;
                this.$global.loading = false;
            });
        }
    },
    created() {
        this.fetchData();
    }
}
</script>

<style lang="scss" scoped>
.d-card {
    padding: 10px;
    .title,
    .value,
    .description {
        text-align: right;
    }
    .value {
        font-size: 30px;
    }

    .ico {
        float:left;
        padding: 10px;

        .icon {
            font-size:60px;
        }
    }
}
</style>
