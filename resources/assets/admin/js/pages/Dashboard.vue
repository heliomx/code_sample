<template>
    <div style="width:100%">
        <h1 class="headline">Bem vindo, {{ firstName }} :)</h1>
        <UserAlertsUi></UserAlertsUi>
        <dashboard-ui v-if="$auth.check('A')"></dashboard-ui>
    </div>
</template>

<script>
import DashboardUi from '../components/dashboard/DashboardUi';
import UserAlertsUi from '../components/dashboard/UserAlertsUi';

export default {
  components: { DashboardUi, UserAlertsUi },
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
