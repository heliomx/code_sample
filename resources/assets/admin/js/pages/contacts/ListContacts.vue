<template>
<transition name="fade" mode="out-in">
  <v-container v-if="!$global.loading" fluid >
    
    <h1 class="headline">Lista de solicitações</h1>
    <v-data-table
      :headers="headers"
      :items="items"
      hide-actions
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td>
          <router-link 
          :to="{ name: 'editContact', params: { id: props.item.id }}">
            {{ props.item.name }}
          </router-link>
        </td>
        <td>{{ props.item.email }}</td>
        <td>{{ props.item.subject }}</td>
        <td>{{ props.item.status | dict('ContactStatus') }}</td>
        <td>{{ props.item.created_at | dateFormat }}</td>
      </template>
    </v-data-table>
  </v-container>
</transition>
</template>

<script>
import dict from '../../filters/DictFilter';
import dateFormat from '../../filters/DateFormatFilter.js';

export default {
    filters: {
      dateFormat,
      dict
    },
    data() {
        return {
            headers: [
                {
                  text: 'Nome',
                  align: 'left',
                  value: 'name'
                },
                {
                  text: 'E-mail',
                  value: 'email'
                },
                {
                  text: 'Assunto',
                  value: 'subject'
                }
                ,
                {
                  text: 'Status',
                  value: 'status'
                },
                {
                  text: 'Data',
                  value: 'created_at'
                },

            ],
            items: []
        }
    },
    
    created() {
      this.fetchData();
    },
    methods: {
      fetchData()
      {
        this.$global.loading = true;
        this.$http.get('contacts')
        .then( r => {
          this.$global.loading = false;
          this.items = r.data.data;
        });
      }
    }
};
</script>
