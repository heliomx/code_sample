<template>
    <v-layout row justify-center>
        <v-dialog v-if="editedSlide" v-model="visible" persistent max-width="60%">
            <v-card>
                <v-card-title class="headline">Editar Destaque</v-card-title>
                <v-card-text>
					<v-container fluid grid-list-lg="true">
						<v-layout row wrap>
							<v-flex xs4>
								<v-subheader>Título</v-subheader>
							</v-flex>
							<v-flex xs8>
								<v-text-field required :rules="validationRules.required" v-model="editedSlide.title">
								</v-text-field>
							</v-flex>
							<v-flex xs4 v-if="!isSeeAlso">
								<v-subheader>Descrição</v-subheader>
							</v-flex>
							<v-flex xs8 v-if="!isSeeAlso">
								<v-text-field required multi-line :rules="validationRules.required" v-model="editedSlide.description">
								</v-text-field>
							</v-flex>
							<v-flex xs4>
								<v-subheader>Link</v-subheader>
							</v-flex>
							<v-flex xs8>
								<v-text-field required :rules="validationRules.required" v-model="editedSlide.link">
								</v-text-field>
							</v-flex>
							<v-flex xs4>
								<v-subheader>Imagem</v-subheader>
								<div>
								<small v-if="!isSeeAlso">1000 x 670 pixels (72dpi)</small>
								<small v-if="isSeeAlso">800 x 500 pixels (72dpi)</small>
								</div>
							</v-flex>
							
							<v-flex xs8>
								 <picture-input
									ref="slidePictureInput"
									:width="288"
									:removable="true"
									:prefill="editedSlide.img"
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
							</v-flex>
						</v-layout>
					</v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="darken-1" flat @click="cancel">Cancelar</v-btn>
                    <v-btn 
					:loading="loading"
      				:disabled="loading"
					color="darken-1" 
					flat 
					@click="confirm">Confirmar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
import PictureInput from 'vue-picture-input';
import { required } from '../../lib/ValidationFunctions';
import FormData from '../../lib/FormData';

export default {
    components: { PictureInput, required },
    data() {
        return {
		   editedSlide: null,
		   originalSlide: null,
		   visible: false,
		   loading: false,
		   isSeeAlso: false,
		   successCallback: null,
		   cancelCallback: null,
           validationRules: {
				required: [
                    v => required(v),
                ],
			}
        }
    },

    methods: {
        edit(slide, successCallback, cancelCallback, isSeeAlso) {
			this.originalSlide = slide;
			this.editedSlide = JSON.parse(JSON.stringify(slide));
			this.visible = true;
			this.successCallback = successCallback;
			this.cancelCallback = cancelCallback;
			this.isSeeAlso = isSeeAlso ? true : false;
		},
		
		cancel() {
			this.visible = false;
			if (this.cancelCallback) this.cancelCallback(this.originalSlide);
		},

		confirm() {
			this.visible = false;
			if (this.successCallback) this.successCallback(this.editedSlide);
		},

		uploadImg(image)
		{
			this.loading = true;
			let className = this.isSeeAlso ? 'seeAlso' : 'slides';
			
			let formData = new FormData(this.$http, `contents/images/home`);
			formData.inputs.append('id', this.editedSlide.id);
			formData.inputs.append('class', className);
			formData.inputs.append('img', this.$refs.slidePictureInput.file, this.$refs.slidePictureInput.file.name);
			formData.post().
				then( r => {
					this.editedSlide.img = r.data.data;
					this.loading = false;
					console.log('done');
				});
		}
    }
}
</script>

<style>

</style>
