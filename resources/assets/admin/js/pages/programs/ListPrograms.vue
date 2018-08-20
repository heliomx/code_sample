<template>
<transition name="fade" mode="out-in">
  <v-container v-if="!$global.loading" fluid >
    
    <h1 class="headline">Lista de programas</h1>
    <v-data-table
      :headers="headers"
      :items="items"
      hide-actions
      :rows-per-page-text="'Itens por página:'"
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.id }}</td>
        <td>
          <router-link 
          :to="{ name: 'editProgram', params: { id: props.item.id }}">
            {{ props.item.name }}
          </router-link>
        </td>
        <td>{{ props.item.qt_signatures }}</td>
        <td>{{ props.item.program_type | dict('ProgramType') }}</td>
      </template>
    </v-data-table>
  </v-container>
</transition>
</template>

<script>
import dict from '../../filters/DictFilter';

export default {
    filters: {
      dict
    },
    data() {
        return {
            headers: [
                {
                  text: 'ID',
                  align: 'left',
                  value: 'id'
                },
                {
                  text: 'Nome do programa',
                  align: 'left',
                  value: 'name'
                },
                {
                  text: 'Assinaturas ativas',
                  value: 'qt_signatures'
                },
                {
                  text: 'Tipo',
                  value: 'program_type'
                }
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
        this.$http.get('programs')
        .then( r => {
          this.$global.loading = false;
          this.items = r.data.data;
        });
      }
    }
};
</script>
