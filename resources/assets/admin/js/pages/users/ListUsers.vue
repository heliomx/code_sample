<template>
<transition name="fade" mode="out-in">
  <v-container v-if="!$global.loading" fluid >
    
    <h1 class="headline">Lista de usuários
      <v-spacer></v-spacer>
      <v-text-field
        v-model.lazy="search"
        append-icon="search"
        label="Buscar por nome ou email do usuário"
        single-line
        hide-details
        debounce="500"
      ></v-text-field>
    </h1>
      
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
          :to="{ name: 'editUser', params: { id: props.item.id }}">
            {{ props.item.name }}
          </router-link>
        </td>
        <td>
            {{ props.item.email }}
        </td>
        
      </template>
      <v-alert slot="no-results" :value="true" color="error" icon="warning">
        Sua busca "{{ search }}" não teve nenhum resultado.
      </v-alert>
    </v-data-table>
  </v-container>
</transition>
</template>

<script>
import debounce from '../../lib/Debounce';

export default {

    data() {
        return {
            headers: [
                {
                  text: 'Nome do usuário',
                  align: 'left',
                  value: 'name'
                },

                {
                  text: 'E-mail',
                  value: 'email'
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
      pagination: {
        handler () {
          this.fetchData();
            
        },
        deep: true
      },
      search(){
        this.debounceSearch();
      }
    },
    methods: {
      fetchData()
      {
        this.loading = true;
        //this.pagination.q = this.search;
        let params = { q: this.search };
        Object.assign(params, this.pagination);
        this.$http.get('users', {params: params})
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
