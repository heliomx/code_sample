<template>
    <v-layout row justify-center>
        <v-dialog v-if="editedProgram" v-model="visible" persistent max-width="60%">
            <v-card>
                <v-card-title class="headline">Editar Programa</v-card-title>
                <v-card-text>
					<v-container fluid grid-list-lg="true">
						<v-layout row wrap>
							<v-flex xs4>
								<v-subheader>Programa</v-subheader>
							</v-flex>
							<v-flex xs8>
								<v-select
                                    :items="availablePrograms"
                                    v-model="editedProgram"
                                    label="Selecione"
                                    single-line
                                    item-text="name"
                                    >
                                </v-select>
							</v-flex>
						</v-layout>
					</v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="darken-1" flat @click="cancel">Cancelar</v-btn>
                    <v-btn color="darken-1" flat @click="confirm">Confirmar</v-btn>
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
    props: ['availablePrograms'],
    data() {
        return {
		   editedProgram: null,
		   originalProgram: null,
		   visible: false,
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
        edit(program, successCallback, cancelCallback) {
			this.originalProgram = program;
			this.editedProgram = program;
			this.visible = true;
			this.successCallback = successCallback;
			this.cancelCallback = cancelCallback;
		},
		
		cancel() {
			this.visible = false;
			if (this.cancelCallback) this.cancelCallback(this.originalProgram);
		},

		confirm() {
			this.visible = false;
			if (this.successCallback) this.successCallback(this.editedProgram);
		},

		
    }
}
</script>

<style>

</style>
