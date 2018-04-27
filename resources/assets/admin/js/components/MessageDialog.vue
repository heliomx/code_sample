<template>
<v-layout row justify-center>
    <v-dialog v-model="visible" persistent max-width="390">
        <v-card>
            <v-card-title class="headline">{{ title }}</v-card-title>
            <v-card-text>
                <p>{{ info }}</p>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" flat @click="dispatchDone">Ok</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['visible', 'title', 'info'],
    methods: {
        dispatchDone(){
            this.visible = false;
            this.$emit('done');
        }
    }
}
</script>

<style>

</style>
