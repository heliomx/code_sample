<template>
    <transition name="fade" appear mode="out-in">
        <section v-if="!$global.loading" class="content-area" id="whoWeAre">
            <div>
                <h2>Quem somos</h2>
                <div class="section-text">
                    <img :src="content.img" align="left">
                    <span v-html="content.body"></span>
                </div>
            </div>
        </section>
    </transition>
</template>

<script>
export default {
    data() {
        return {
            content: {}
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
            
            this.$http.get(`contents/whoWeAre`)
                .then( r => {
                    this.$global.loading = false;
                    this.content = r.data.data.doc;
                    this.$global.loading = false;
                });
        }
    }
}
</script>

<style lang="scss">
#whoWeAre {
    img {
        margin-right: 20px;
        margin-bottom: 20px;
    }
}
</style>

