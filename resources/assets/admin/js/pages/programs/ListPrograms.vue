<template>
  <v-container v-if="!$global.loading" fluid >
    
    <h1 class="headline">Lista de clientes</h1>
    <v-data-table
      :headers="headers"
      :items="items"
      hide-actions
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td>
          <router-link 
          :to="{ name: 'editProgram', params: { id: props.item.id }}">
            {{ props.item.name }}
          </router-link>
        </td>
        <td>{{ props.item.qt_signatures }}</td>
        <td>{{ props.item.program_type }}</td>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
export default {
    data() {
        return {
            headers: [
                {
                  text: 'Nome do programa',
                  align: 'left',
                  value: 'name'
                },
                {
                  text: 'Assinaturas',
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
