<template>
    <section class="content-area">
        <div>
            <h2>Programa {{ program.name }}</h2>
            
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            program: {}
        }
    },
    created(){
        this.fetchData();
    },
    watch: {
        // call again the method if the route changes
        $route: "fetchData"
    },
    methods: {
        fetchData() {
            this.$global.loading = true;
            
            this.$http.get(`programs/${this.$route.params.id}`)
                .then( r => {
                    this.$global.loading = false;
                    this.program = r.data.data;
                });
        }
    }
}
</script>
