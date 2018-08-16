<template>
<transition name="fade" mode="out-in">
  <v-container v-if="!$global.loading" fluid >
    
    <h1 class="headline">Lista de clientes
      </h1>
      <search-box>
      <v-text-field
        v-model.lazy="search"
        box
        append-icon="search"
        label="Buscar por nome da rádio, cidade ou UF"
        single-line
        hide-details
        debounce="500"
      ></v-text-field>
    </search-box>
    <v-data-table
      :headers="headers"
      :items="items"
      :pagination.sync="pagination"
      :total-items="totalItems"
      :loading="loading"
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.id }}</td>
        <td>
          <router-link 
          :to="{ name: 'editClient', params: { id: props.item.id }}">
            {{ props.item.radio_name }}
          </router-link>
        </td>
        <td>{{ props.item.radio_type | dict('RadioType')}}</td>
        <td :alt="props.item.qt_signatures_active">
          {{ props.item.qt_signatures_active }}/{{ props.item.qt_signatures }} <br>
        </td>
        <td>{{ props.item.address_city }}</td>
        <td>{{ props.item.address_uf }}</td>
        <td>
          {{ props.item.tel | telephone }} 
          <br v-if="!!props.item.tel && !!props.item.tel_mobile">
          {{ props.item.tel_mobile | telephone }}
        </td>
        <td>{{ props.item.status | dict('ClientStatus')}}</td>
        <td>{{ props.item.created_at.date | dateformat('DD/MM/YYYY HH:MM:ss') }}</td>
      </template>
      <v-alert slot="no-results" :value="true" color="error" icon="warning">
        Sua busca "{{ search }}" não teve nenhum resultado.
      </v-alert>
    </v-data-table>
    <div class="actions">
      <v-btn @click="addNew" color="primary" fab small dark>
        <v-icon>add</v-icon>
      </v-btn>
    </div>
  </v-container>
</transition>
</template>

<script>
import dict from "../../filters/DictFilter";
import { telephone } from "../../filters/NumberFormatFilter";
import debounce from "../../lib/Debounce";
import SearchBox from "../../components/SearchBox";
import dateformat from '../../filters/DateFormatFilter';


export default {
  components: {
    SearchBox
  },

  filters: {
    dict,
    telephone,
    dateformat
  },
  data() {
    return {
      headers: [
        {
          text: "id",
          align: "left",
          value: "id"
        },
        {
          text: "Nome da rádio",
          align: "left",
          value: "radio_name"
        },

        {
          text: "Tipo da rádio",
          value: "radio_type"
        },

        {
          text: "Assinaturas",
          value: "qt_signatures",
          sorteable: false
        },
        {
          text: "Cidade",
          value: "address_city"
        },
        {
          text: "UF",
          value: "address_uf"
        },
        {
          text: "Telefones",
          value: "tel",
          sortable: false
        },
        {
          text: "Status",
          value: "status"
        },
        {
          text: "Criação",
          value: "created_at"
        }
      ],
      search: "",
      pagination: { rowsPerPage: 50 },
      items: [],
      totalItems: 0,
      loading: true,
      debounceSearch: debounce(() => {
        this.pagination.page = 1;
        this.fetchData();
        //this.pagination.totalItems = 0;
      }, 500)
    };
  },
  watch: {
    search() {
      this.debounceSearch();
    },
    pagination: {
      handler() {
        this.fetchData();
      },
      deep: true
    }
  },
  methods: {
    fetchData() {
      this.loading = true;
      //this.pagination.q = this.search;
      let params = { q: this.search };
      Object.assign(params, this.pagination);
      this.$http.get("clients", { params: params }).then(response => {
        this.items = response.data.items;
        this.totalItems = response.data.total;
        this.loading = false;
      });
    },
    addNew() {
      this.$router.push("clientes/cadastrar");
    }
  }
};
</script>

<style>
small {
  color: #d0d0d0;
}
</style>
