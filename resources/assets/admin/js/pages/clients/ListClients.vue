<template>
<transition name="fade" mode="out-in">
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
    </v-data-table>
  </v-container>
</transition>
</template>

<script>
import dict from '../../filters/DictFilter';
import {telephone} from '../../filters/NumberFormatFilter';


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
                  value: 'qt_signatures'
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

<style>
  small {
    color: #d0d0d0;
  }
</style>
