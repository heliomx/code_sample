<template>
    <div style="width:100%">
        <h1 class="headline">Bem vindo, {{ firstName }} :)</h1>
        <dashboard-ui v-if="$auth.check('A')"></dashboard-ui>
    </div>
</template>

<script>
import DashboardUi from '../components/dashboard/DashboardUi';

export default {
  components: { DashboardUi },
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
