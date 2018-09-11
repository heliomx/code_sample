<template>
<transition name="fade" mode="out-in">
  <v-container v-if="!$global.loading" fluid >
    
    <h1 class="headline">Lista de solicitações</h1>
    <v-data-table
      :headers="headers"
      :items="items"
      :rows-per-page-text="'Itens por página:'"
      hide-actions
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td :class="{newItem: props.item.status == 'N'}">
          <router-link 
          :to="{ name: 'editContact', params: { id: props.item.id }}">
            {{ props.item.name }}
          </router-link>
        </td>
        <td :class="{newItem: props.item.status == 'N'}">{{ props.item.email }}</td>
        <td :class="{newItem: props.item.status == 'N'}">{{ props.item.subject }}</td>
        <td :class="{newItem: props.item.status == 'N'}">{{ props.item.status | dict('ContactStatus') }}</td>
        <td :class="{newItem: props.item.status == 'N'}">
          {{ props.item.created_at | dateFormat }}<br>
          {{ props.item.created_at | dateFormat('HH:mm') }}
          </td>
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
                  sortable: false,
                  align: 'left',
                  value: 'name'
                },
                {
                  sortable: false,
                  text: 'E-mail',
                  value: 'email'
                },
                {
                  sortable: false,
                  text: 'Assunto',
                  value: 'subject'
                }
                ,
                {
                  sortable: false,
                  text: 'Status',
                  value: 'status'
                },
                {
                  sortable: false,
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
<style lang="scss" scoped>
.newItem {
  font-weight: bold;
}
</style>

