<template>
    <v-app>
        <h1 class="headline">Bem vindo, {{ firstName }} :)</h1>
    </v-app>
</template>

<script>
export default {
  data(){
      return {
          firstName: ''
      }
  },
  created(){
      this.firstName = this.$auth.user().name.split(' ')[0];
  }

}
</script>
