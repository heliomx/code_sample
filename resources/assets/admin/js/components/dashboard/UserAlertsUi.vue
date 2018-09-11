<template>
    <transition name="fade" mode="out-in">
    <div v-if="visible">
        <v-alert
        :value="true"
        type="warning"
        >
            <div v-if="userAlert.active" class="msg-content" v-html="userAlert.msg"></div>
            <div v-if="$auth.check('A') && contacts > 0" class="msg-content">
                <span v-if ="contacts > 1">Existem {{ contacts }} mensagens novas no 
                <router-link to="/faleconosco">Fale Conosco</router-link></span>
                <span v-if ="contacts == 1">Existe {{ contacts }} mensagem nova no 
                <router-link to="/faleconosco">Fale Conosco</router-link></span>
            </div>
        </v-alert>
    </div>
    </transition>
</template>
<script>
export default {
    data(){
        return {
            userAlert: { msg: '', active: false},
            visible: false,
            contacts: 0
        }
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData(){
            this.$http.get('contents/userAlert')
                .then( r => {
                    this.userAlert = r.data.data.doc;
                    if (this.userAlert && this.userAlert.active)
                    {
                        this.show();
                    }
                });
            
            if (this.$auth.check('A'))
            {
                this.$http.get('contacts/countNew')
                .then( r => {
                    this.contacts = r.data.data;
                    if(this.contacts > 0)
                    {
                        this.show();

                    }
                });
            }
            
            
        },
        show(){
            this.visible = true;
        }
    }

}
</script>
<style lang="scss" scoped>
.msg-content {
    font-size: 19px;
    color:#864c00;
}
</style>



