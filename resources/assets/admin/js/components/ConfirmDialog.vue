<template>
    <v-layout row justify-center>
        <v-dialog v-model="visible" persistent max-width="390">
            <v-card>
                <v-card-title class="headline">{{ title }}</v-card-title>
                <v-card-text>
                    <p>{{ body }}</p>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="darken-1" flat @click="dispatchCancel">{{ cancelLabel }}</v-btn>
                    <v-btn color="darken-1" flat @click="dispatchConfirm">{{ confirmLabel }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
export default {
    props: ['title', 'body', 'visible', 'confirmLabel', 'cancelLabel'],
    methods: {
        dispatchConfirm(){
            this.visible = false;
            this.$emit('confirm');
        },
        dispatchCancel(){
            this.visible = false;
            this.$emit('cancel');
        }
    }
}
</script>
