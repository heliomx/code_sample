<template>
  <v-container v-if="!$global.loading" fluid grid-list-lg="true">
    
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
          :to="{ name: 'editClient', params: { id: props.item.id }}">
            {{ props.item.radio_name }}
          </router-link>
        </td>
        <td>{{ props.item.address_city }}</td>
        <td>{{ props.item.address_uf }}</td>
        <td>{{ props.item.tel }}</td>
        <td>{{ props.item.status }}</td>
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
                  text: 'Nome da rádio',
                  align: 'left',
                  value: 'radio_name'
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
                  text: 'Telefone',
                  value: 'tel'
                },
                {
                  text: 'Status',
                  value: 'status'
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
        this.$http.get('clients')
        .then( r => {
          this.$global.loading = false;
          this.items = r.data.data;
        });
      }
      
    }
};
</script>
