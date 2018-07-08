<template>
<transition name="fade" mode="out-in">
  <v-container v-if="!$global.loading" fluid >
    
    <h1 class="headline">Lista de clientes
      <v-spacer></v-spacer>
      <v-text-field
        v-model.lazy="search"
        append-icon="search"
        label="Buscar por nome da rádio, cidade ou UF"
        single-line
        hide-details
        debounce="500"
      ></v-text-field></h1>
    <v-data-table
      :headers="headers"
      :items="items"
      :pagination.sync="pagination"
      :total-items="totalItems"
      :loading="loading"
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td>
          <router-link 
          :to="{ name: 'editClient', params: { id: props.item.id }}">
            {{ props.item.radio_name }}
          </router-link>
        </td>
        <td>{{ props.item.radio_type | dict('RadioType')}}</td>
        <td>
          {{ props.item.qt_signatures }} <br>
          <small>
            Ativas: {{ props.item.qt_signatures_active }} / 
            Não ativas: {{ props.item.qt_signatures_not_active }}
          </small>
        </td>
        <td>{{ props.item.address_city }}</td>
        <td>{{ props.item.address_uf }}</td>
        <td>
          {{ props.item.tel | telephone }} 
          <br v-if="!!props.item.tel && !!props.item.tel_mobile">
          {{ props.item.tel_mobile | telephone }}
        </td>
        <td>{{ props.item.status | dict('ClientStatus')}}</td>
      </template>
      <v-alert slot="no-results" :value="true" color="error" icon="warning">
        Sua busca "{{ search }}" não teve nenhum resultado.
      </v-alert>
    </v-data-table>
  </v-container>
</transition>
</template>

<script>
import dict from '../../filters/DictFilter';
import {telephone} from '../../filters/NumberFormatFilter';
import debounce from '../../lib/Debounce';


export default {
    filters: {
      dict,
      telephone
    },
    data() {
        return {
            headers: [
                {
                  text: 'Nome da rádio',
                  align: 'left',
                  value: 'radio_name'
                },

                {
                  text: 'Tipo da rádio',
                  value: 'radio_type'
                },

                {
                  text: 'Assinaturas',
                  value: 'qt_signatures',
                  sorteable: false
                },
                {
                  text: 'Cidade',
                  value: 'address_city'
                },
                {
                  text: 'UF',
                  value: 'address_uf'
                },
                {
                  text: 'Telefones',
                  value: 'tel',
                  sorteable: false,
                },
                {
                  text: 'Status',
                  value: 'status'
                }
            ],
            search:'',
            pagination: { rowsPerPage: 10 },
            items: [],
            totalItems: 0,
            loading: true,
            debounceSearch: debounce( () => {
              this.pagination.page = 1;
              this.fetchData();
              //this.pagination.totalItems = 0;
            }, 500)
        }
    },
    watch: {
      search(){
        this.debounceSearch();
      },
      pagination: {
        handler () {
          this.fetchData();
            
        },
        deep: true
      }
    },
    methods: {
      fetchData()
      {
        this.loading = true;
        //this.pagination.q = this.search;
        let params = { q: this.search };
        Object.assign(params, this.pagination);
        this.$http.get('clients', {params: params})
          .then(response => {
            this.items = response.data.items;
            this.totalItems = response.data.total;
            this.loading = false;
          });
      }
  }
}
</script>

<style>
  small {
    color: #d0d0d0;
  }
</style>
