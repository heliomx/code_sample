<template>
    <!-- <div class="panel panel-default">
        <div class="panel-heading">
            <nav>
                <ul class="list-inline">
                    <li>
                        <router-link :to="{ name: 'home' }">Home</router-link>
                    </li>
                    <li v-if="!$auth.check()" class="pull-right">
                        <router-link :to="{ name: 'login' }">Login</router-link>
                    </li>
                    <li v-if="!$auth.check()" class="pull-right">
                        <router-link :to="{ name: 'register' }">Register</router-link>
                    </li>
                    <li v-if="$auth.check()" class="pull-right">
                        <a href="#" @click.prevent="$auth.logout()">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="panel-body">
            
        </div>
    </div> -->
    <router-view></router-view>
</template>

<script>
export default {
    created() {
        this.$auth.ready( () => {
            if (this.$auth.check())
            {
                this.$router.push('/dashboard');
            } else {
                this.$router.push('/login');
            }
        })
    }
}
</script>