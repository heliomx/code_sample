<template>
<div id="whoContent">
	<transition name="fade" mode="out-in">
	<v-container 
		v-if="!$global.loading"
		fluid
	>
        <h1 class="headline">Conteúdo Quem Somos</h1>

        <div class="form-margin">
        
            <h4>Imagem de destaque</h4>
            <picture-input
                ref="pictureInput"
                :width="288"
                :removable="true"
                :prefill="content.img"
                removeButtonClass="btn picture-input-btn"
                :height="288"
                accept="image/jpeg, image/png, image/gif"
                buttonClass="btn primary picture-input-btn"
                @change="uploadImg"
                :customStrings="{
                    change: 'Alterar imagem', 
                    remove: 'Remover imagem',
                    upload: '<h1>Enviar</h1>',
                    drag: 'Arraste e solte a imagem aqui'
                }">

                </picture-input>
                    
                
                <h4>Texto principal</h4>
                <wysiwyg v-model="content.body" />
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
import PictureInput from 'vue-picture-input';
import { uuid } from 'vue-uuid'
import FormData from '../../lib/FormData';
import "vue-wysiwyg/dist/vueWysiwyg.css";

export default {
    components: {
        MessageDialog,
        PictureInput
    },
    data(){
        return {
            content: {
                body:'',
                img: ''
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
			this.$http.get('contents/whoWeAre')
				.then( response => {
                    this.content = response.data.data.doc;
                    this.$global.loading = false;
				});
        },

        save() {
			this.$global.loading = true;
			
			this.$http.post( 'contents/whoWeAre', { doc:this.content } )
				.then( response => {
					this.$refs.messageDialog.show(
						'Alteração',
						'O conteúdo da Home foi alterado com sucesso'
					)
					this.$global.loading = false;
				});
        },
        
        uploadImg(image)
		{
			this.loading = true;
			let formData = new FormData(this.$http, `contents/images/whoWeAre`);
			formData.inputs.append('id', uuid.v1());
			formData.inputs.append('img', this.$refs.pictureInput.file, this.$refs.pictureInput.file.name);
			formData.post().
				then( r => {
					this.content.img = r.data.data;
					this.loading = false;
					console.log('done');
				});
		}


    }
}
</script>

<style lang="scss">
h4 {
    margin-top: 40px;
}
.preview-container {
    margin:0 !important;

    canvas {
        z-index: 1 !important;
    }
}
</style>
