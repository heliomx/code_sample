<template>
    <div class="search-box">
        <v-layout wrap align-center justify-center row fill-height>
        <v-flex sm12 md6>
        
            <slot></slot>

        </v-flex>
        </v-layout>
    </div>
</template>
<style lang="scss" scoped>
.search-box {
  margin: 10px 0;

  .input-group__input {
    background-color: rgba(0, 149, 135, 0.1) !important;
  }
}

</style>
