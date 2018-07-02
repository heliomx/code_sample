<template>
    <transition name="fade" appear mode="out-in">
        <section id="program" v-if="!$global.loading" class="content-area">
            <div>
                <h2>Programa <strong>{{ program.name }}</strong></h2>
                <img :src="program.full_img_path">
                <span>{{program.description}}</span>
                <div class="btn-area clear-fix">
                    <v-btn class="clearfix" color="secondary" to="cadastro">Cadastre-se e receba nosso conteúdo gratuito</v-btn>
                </div>
            </div>
        </section>
    </transition>
</template>

<script>
export default {
    data() {
        return {
            program: {
                name: '',
                full_img_path: '',
                description: ''
            }
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
<style lang="scss">
@import '../../sass/variables';

#program>div {
    @include clearfix;

    &>img {
        float: left;
        margin-right: 20px;
        margin-bottom: 20px
    }

    &>span {
        font-size: 16px;
        font-family: $title-font;
    }

    

}
</style>

