<template>
  <div>
    <h1 class="headline">Lista de clientes</h1>
    <v-data-table
      :headers="headers"
      :items="items"
      hide-actions
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.name }}</td>
        <td class="text-xs-right">{{ props.item.city }}</td>
        <td class="text-xs-right">{{ props.item.uf }}</td>
        <td class="text-xs-right">{{ props.item.tel }}</td>
        <td class="text-xs-right">{{ props.item.status }}</td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
export default {
    data() {
        return {
            headers: [
                {
                  text: 'Nome da rádio',
                  align: 'left',
                  value: 'name'
                },
                {
                  text: 'Cidade',
                  value: 'city'
                },
                {
                  text: 'UF',
                  value: 'uf'
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
            items: [
              {
                name: 'A Voz da Cultura WEB',
                email: 'jel.veloso@hotmail.com',
                city: 'Jequie',
                uf: 'BA',
                tel: '(42) - 8447-2342',
                status: 'Ativo'
              }
            ]
        }
    }
};
</script>
