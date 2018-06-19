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
    data() {
        return {
            callbackDone: null,
            visible: false,
            info: '',
            title: '',
        }
    },

    methods: {
        dispatchDone(){
            this.visible = false;
            if ( this.callbackDone ) this.callbackDone();
        },

        show( title, info, callbackDone ) {
            this.title = title;
            this.info = info;
            this.callbackDone = callbackDone;
            this.visible = true;
        }
    }
}
</script>

<style>

</style>
