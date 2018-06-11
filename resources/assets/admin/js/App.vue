<template>
    <router-view></router-view>
</template>

<script>
export default {
    created() {
        this.$auth.ready( () => {
            if (this.$auth.check())
            {
                
            } else {
                this.$router.push('/login');
            }
        })
    }
}
</script>

<style lang="scss">
    .form-margin {
        margin-right: 10vw
    }

</style>