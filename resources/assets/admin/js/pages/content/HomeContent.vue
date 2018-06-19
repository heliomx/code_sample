<template>
<div id="homeContent">
	<v-container
		v-if="!$global.loading"
		fluid
	>
		<h1 class="headline">Conteúdo da Home</h1>
		<section class="slides">
			<h2>Destaques</h2>
			
			<v-list three-line>
				<draggable v-model="content.slides">
					
						<v-list-tile 
							class="separator bigline"
							v-if="slide != null"
							v-for="slide in content.slides"
							:key="slide.id"
							avatar
						>
							<v-tooltip top >
								<v-list-tile-avatar slot="activator">
									<v-icon color="grey lighten-1">drag_indicator</v-icon>
								</v-list-tile-avatar>
								<span>Clique e arraste para mudar a ordem</span>
							</v-tooltip>
							<v-list-tile-content >
								<img :src="slide.img" >
								<div>
									<v-list-tile-title>{{ slide.title }}</v-list-tile-title>
									<v-list-tile-sub-title>{{ slide.description }}</v-list-tile-sub-title>
								</div>
							</v-list-tile-content>
							<v-list-tile-action>
								<v-btn
									icon
									ripple
									@click="editSlide(slide)"
								>
									<v-icon color="grey lighten-1">create</v-icon>
									</v-btn>
								<v-btn
									icon
									ripple
									@click="deleteSlide(slide)"
								>
									<v-icon color="grey lighten-1">delete</v-icon>
									</v-btn>
							</v-list-tile-action>
						</v-list-tile>
				</draggable>
				
				
			</v-list>

			<div class="actions">
				<v-btn @click="addNewSlide" color="primary" fab small dark>
					<v-icon>add</v-icon>
				</v-btn>
			</div>

			<slide-editor ref='slideEditor'></slide-editor>

		</section>

		<section>
			<h2>Bem-vindo</h2>
			<wysiwyg v-model="content.welcome" />
		</section>

		<section class="programs">
			<h2>Nossos programas</h2>
			<h4>Texto de abertura</h4>
			<wysiwyg v-model="content.ourPrograms" />
			<h4>Programas em destaque</h4>
			<v-list class="half">
				<draggable v-model="form.programsHighlight">
						<v-list-tile 
							class="separator"
							:key="program.id"
							v-if="program != null"
							v-for="program in form.programsHighlight"
							avatar
						>
							<v-list-tile-avatar>
								<v-icon color="grey lighten-1">drag_indicator</v-icon>
							</v-list-tile-avatar>
							<v-list-tile-content>
								<v-list-tile-title>{{ program.name }}</v-list-tile-title>
							</v-list-tile-content>
							<v-list-tile-action>
								<v-btn
									icon
									ripple
									@click="editProgram(program)"
								>
									<v-icon color="grey lighten-1">create</v-icon>
									</v-btn>
								<v-btn
									icon
									ripple
									@click="deleteProgram(program)"
								>
									<v-icon color="grey lighten-1">delete</v-icon>
									</v-btn>
							</v-list-tile-action>
						</v-list-tile>
					
				</draggable>
			</v-list>
			<div class="actions half">
				<v-btn @click="addNewProgram" color="primary" fab small dark>
					<v-icon>add</v-icon>
				</v-btn>
			</div>
			<program-editor :availablePrograms="availablePrograms" ref="programEditor"></program-editor>
		</section>

		<section>
			<h2>Conheça também</h2>
			<v-list three-line>
				<draggable v-model="content.seeAlso">
					
						<v-list-tile 
							class="separator bigline"
							v-if="slide != null"
							v-for="(slide) in content.seeAlso"
							:key="slide.id"
							avatar
						>
							<v-list-tile-avatar>
								<v-icon color="grey lighten-1">drag_indicator</v-icon>
							</v-list-tile-avatar>
							<v-list-tile-content>
								<img :src="slide.img">
								<v-list-tile-title>{{ slide.title }}</v-list-tile-title>
							</v-list-tile-content>
							<v-list-tile-action>
								<v-btn
									icon
									ripple
									@click="editSeeAlso(slide)"
								>
									<v-icon color="grey lighten-1">create</v-icon>
									</v-btn>
								<v-btn
									icon
									ripple
									@click="deleteSeeAlso(slide)"
								>
									<v-icon color="grey lighten-1">delete</v-icon>
									</v-btn>
							</v-list-tile-action>
						</v-list-tile>
				</draggable>
				
				
			</v-list>

			<div class="actions">
				<v-btn @click="addNewSeeAlso" color="primary" fab small dark>
					<v-icon>add</v-icon>
				</v-btn>
			</div>

			<slide-editor ref='slideEditor'></slide-editor>

		</section>

		<div class="page-actions">
			<v-btn color="secondary" @click="save()">Salvar</v-btn>
		</div>
		
	</v-container>
	<message-dialog ref="messageDialog"></message-dialog>
</div>
</template>

<script>
import draggable from 'vuedraggable'
import SlideEditor from './HomeSlideEditor' 
import ProgramEditor from './HomeProgramsEditor'
import MessageDialog from '../../components/MessageDialog2'
import { uuid } from 'vue-uuid'
import "vue-wysiwyg/dist/vueWysiwyg.css";

export default {
    components: {
		draggable,
		SlideEditor,
		ProgramEditor,
		MessageDialog,
    },

    data() {
        return {
			content: {
				slides: [],
				welcome: '',
				ourPrograms: '',
				selectedPrograms: [],
				seeAlso: []
			},

			form: {
				programsHighlight: []
			},

			availablePrograms: [],
			
			slideEditorVisible: false,
			selectedSlide: null,
        };
	},

	created() {
		this.fetchData();
		window._page = this;
	},
	
	methods: {
		fetchData() {
			this.$global.loading = true;
			this.$http.get('contents/home')
				.then( response => {
					this.content = response.data.data.doc;
					
					return this.$http.get('programs')
						.then( response => {
							this.availablePrograms = response.data.data;
							this.form.programsHighlight = this.content.selectedPrograms
								.map(p => 
								{
									return this.availablePrograms[this.availablePrograms.findIndex(e => e.id == p)];
								})
							this.$global.loading = false;
						})
				});
		},
		save() {
			this.$global.loading = true;
			this.content.selectedPrograms = this.form.programsHighlight.map( v => {
				return v.id;
			});
			this.$http.post( 'contents/home', { doc:this.content } )
				.then( response => {
					this.$refs.messageDialog.show(
						'Alteração',
						'O conteúdo da Home foi alterado com sucesso'
					)
					this.$global.loading = false;
				});
		},
		editSlide( slide )
		{
			this.$refs.slideEditor.edit(slide, newSlide => {
				this.$set(this.content.slides, this.content.slides.indexOf(slide), newSlide);
			});
		},
		addNewSlide()
		{
			let newSlide = {
					id: uuid.v1(),
					img: '',
					title: '',
					description: '',
					link: '',
			};

			this.$refs.slideEditor.edit(newSlide, 
				(slide) => {
					this.content.slides.push(slide)
				},
				null,
				true
			);
		},
		deleteSlide(slide){
			this.content.slides.splice(this.content.slides.indexOf(slide), 1);
		},

		deleteProgram(program) {
			this.form.programsHighlight.splice(this.form.programsHighlight.indexOf(program), 1);
		},
		editProgram(program) {
			this.$refs.programEditor.edit(program, p => {
				this.$set(this.form.programsHighlight, this.form.programsHighlight.indexOf(program), p);
			});
		},

		addNewProgram() {
			let newProgram = this.availablePrograms[0];
			this.$refs.programEditor.edit(newProgram, p => {
				this.$set(this.form.programsHighlight, this.form.programsHighlight.length, p);
			});
		},

		editSeeAlso( slide )
		{
			this.$refs.slideEditor.edit(slide, newSeeAlso => {
				this.$set(this.content.seeAlso, this.content.seeAlso.indexOf(slide), newSeeAlso);
			});
		},
		addNewSeeAlso()
		{
			let newSeeAlso = {
					id: uuid.v1(),
					img: '',
					title: '',
					link: '',
			};

			
			this.$refs.slideEditor.edit(newSeeAlso, 
				(slide) => {
					this.content.seeAlso.push(slide)
				},
				null,
				true
			);
		},
		deleteSeeAlso(slide){
			this.content.seeAlso.splice(this.content.seeAlso.indexOf(slide), 1);
		},
	}
};
</script>

<style lang="scss">
#homeContent
{
	section {
		margin: 60px 0;
		.actions {
			text-align: right;
			margin-top: 20px;
		}
		padding: 30px 0;
		border-bottom: 2px solid #d0d0d0;

		&:last-of-type {
			border-bottom: none;
		}

		&:not(:first-child) {
			margin-top: 20px;
		}

		h4 {
			margin-top: 20px;
		}

		
		.list__tile img {
			margin-right: 20px;
			width: 200px;
		}

		.list__tile__content {
			
			flex-direction: row;
			justify-content: flex-start;
			align-items: center;

		}
	}

	.half {
		width:50%;
	}

	.list {
		padding: 0;
	}

	.separator {
		border-bottom: 1px solid #f0f0f0;
	}

	.bigline {
		height: 142px;

		.list__tile {
			height: 142px;
		}
	}

	.programs .list__tile__action {
		flex-direction: row;
	}
}

</style>
