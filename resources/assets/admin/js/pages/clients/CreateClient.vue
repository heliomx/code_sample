<template>
  <div grid-list-md>
      <h1 class="headline">Cadastrar Novo Cliente</h1>
      <v-form v-model="valid">
          <v-container fluid grid-list-lg="true">
            <v-layout row wrap>
                <v-flex xs6>
                    <v-select
                        :items="items"
                        v-model="e1"
                        label="Tipo de cadastro"
                        single-line
                        ></v-select>
                </v-flex>
                <v-flex xs6>
                    <v-select
                        :items="items"
                        v-model="e1"
                        label="Status"
                        single-line
                        ></v-select>
                </v-flex>
            </v-layout>
        </v-container>
      </v-form>
  </div>
</template>

<script>
export default {
    data(){
        return {
            items: [
                { text: 'Rádio Web' },
                { text: 'Rádio Convencional com CNPJ' }
            ]
        }
    }

}
</script>

Tipo de cadastro 
Status 
Nome da rádio 
Nome completo do responsável 
Razão social 
CPF 
xxx.xxx.xxx-xx
CNPJ 
xx.xxx.xxx/xxxx-xx
CEP

xxxxx-xxx
Endereço 
Rua de exemplo
Numero 
Compl. 
sala 1
Cidade 
Estado 
XX
Telefone Fixo 
(xx) xxxx-xxxx
Celular 
(xx) xxxx-xxxx
Operadora 
Site 
www.radio.com.br
E-mail 
Senha 
Programas
Amado Batista
Ligacao Nacional
Caminhando com Jesus Cristo
Caminho Santo
Mundo Sertanejo
Justica em Cena
Vivendo Com Otimismo
Forro Brasilis
Cantores do Brasil
Culto Brasil
Palavras de Fe
Saude Com Beleza
Brasil Brega Show
As Melhores do Dia
Luiz Gonzaga
Reginaldo Sodre
Comentario direto de Brasilia
Escute Deus Pela Palavra
Encontro Com Ari Santos
Consagração a Nossa Senhora
Macho Vei
Country Brasil
Eu Voce e a Jovem Guarda
Radio Escuta
Roberto Carlos e Companhia