import Vue from 'vue';

export const SharedData = new Vue({data:{ loading: false }});

SharedData.install = function(){
  Object.defineProperty(Vue.prototype, '$global', {
    get () { return SharedData }
  })
}
