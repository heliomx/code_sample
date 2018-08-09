<template>
<div>
    <transition name="fade" mode="out-in">
        <v-container v-if="!$global.loading" fluid>

            <h1 class="headline">Lista de usuários administradores</h1>

            <search-box>
                <v-text-field 
                  v-model.lazy="search" 
                  box 
                  append-icon="search" 
                  label="Buscar por nome ou email do usuário" 
                  single-line 
                  hide-details 
                  debounce="500">
                </v-text-field>
            </search-box>
            <v-data-table :headers="headers" :items="items" :pagination.sync="pagination" :total-items="totalItems" :loading="loading" class="elevation-1">
                <template slot="items" slot-scope="props">
                    
                    <td>
                        <router-link :to="{ name: 'editUser', params: { id: props.item.id }}">
                            {{ props.item.name }}
                        </router-link>
                    </td>
                    <td>
                        {{ props.item.email }}
                    </td>
                    <td class="line-actions">
                        <v-btn @click="showConfirmDelete( props.item )" flat small>
                            <v-icon>delete</v-icon>
                        </v-btn>
                    </td>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    Sua busca "{{ search }}" não teve nenhum resultado.
                </v-alert>
            </v-data-table>
        </v-container>
    </transition>

    <confirm-dialog 
      title="Remover?" 
      ref="confirmDialog"
      :body="`Deseja remover o usuário?`" 
      confirmLabel="Apagar" 
      cancelLabel="Cancelar" 
      @confirm="deleteUser">
    </confirm-dialog>
    <div class="actions">
        <v-btn @click="addNew" color="primary" fab small dark>
            <v-icon>add</v-icon>
        </v-btn>
    </div>
    <message-dialog ref="messageDialog"></message-dialog>

</div>
</template>

<script>
import debounce from "../../lib/Debounce";
import SearchBox from "../../components/SearchBox";
import ConfirmDialog from "../../components/ConfirmDialog";
import MessageDialog from "../../components/MessageDialog2";

export default {
  components: {
    SearchBox,
    ConfirmDialog,
    MessageDialog
  },

  data() {
    return {
      headers: [
        
        {
          text: "Nome do usuário",
          align: "left",
          value: "name"
        },

        {
          text: "E-mail",
          value: "email"
        },
        {
          text: "",
          align: "left",
          sortable: false
        }
      ],
      search: "",
      pagination: {
        rowsPerPage: 10
      },
      deletedUser: null,
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
    pagination: {
      handler() {
        this.fetchData();
      },
      deep: true
    },
    search() {
      this.debounceSearch();
    }
  },
  methods: {
    fetchData() {

      this.loading = true;
      //this.pagination.q = this.search;
      let params = {
        q: this.search
      };
      Object.assign(params, this.pagination);
      this.$http
        .get("users", {
          params: params
        })
        .then(response => {
          this.items = response.data.items;
          this.totalItems = response.data.total;
          this.loading = false;
        });
    },
    showConfirmDelete( user ) {
      this.deletedUser = user;
      this.$refs.confirmDialog.show();
    },
    deleteUser() {
      console.log(this.deletedUser);
      this.$global.loading = true;
      this.$http.delete(`users/${this.deletedUser.id}`).then(r => {
        console.log(this.$refs.messageDialog);
        this.$global.loading = false;
        this.$refs.messageDialog.show(
          "Apagado",
          `O usuário ${this.deletedUser.name} foi apagado com sucesso`,
          () => {
            this.fetchData();
          }
        );
      });
    },
    addNew() {
      this.$router.push("usuarios/cadastrar");
    }
  }
};
</script>

<style>
small {
  color: #d0d0d0;
}
</style>
