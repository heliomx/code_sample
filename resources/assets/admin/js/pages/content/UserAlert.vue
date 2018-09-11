<template>
<div id="userAlertContent">
	<transition name="fade" mode="out-in">
	<v-container 
		v-if="!$global.loading"
		fluid
	>
        <h1 class="headline">Alerta para Usuário</h1>

        <div class="form-margin">
        
            <h4>Mensagem</h4>
            <v-switch :label="`Mostrar`" v-model="content.active"></v-switch>
            <wysiwyg v-model="content.msg" />
        </div>

        <div class="page-actions">
            <v-btn color="secondary" @click="save()">Salvar</v-btn>
        </div>
            
	</v-container>
	</transition>
    <message-dialog ref="messageDialog"></message-dialog>
</div>
</template>

<script>
import MessageDialog from '../../components/MessageDialog2'
import "vue-wysiwyg/dist/vueWysiwyg.css";

export default {
    components: {
        MessageDialog
    },
    data(){
        return {
            content: {
                msg:'',
                active: false
            }
        }
    },

    created() {
		this.fetchData();
		window._page = this;
	},
	
	methods: {
		fetchData() {
			this.$global.loading = true;
			this.$http.get('contents/userAlert')
				.then( response => {
                    this.content = response.data.data.doc == null ? { msg:'', active: false } : response.data.data.doc;
                    this.$global.loading = false;
				});
        },

        save() {
			this.$global.loading = true;
			
			this.$http.post( 'contents/userAlert', { doc:this.content } )
				.then( response => {
					this.$refs.messageDialog.show(
						'Alteração',
						'O conteúdo foi alterado com sucesso'
					)
					this.$global.loading = false;
				});
        },
        


    }
}
</script>

<style lang="scss">
h4 {
    margin-top: 40px;
}
#userAlertContent {
    width: 100%;
}
</style>
