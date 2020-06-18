import Vue from 'vue'
import vueShell from './vue-shell'
import * as uiv from 'uiv'
import draggable from 'vuedraggable'

Vue.use(uiv)

Vue.component('draggable', draggable)

window.Vue = Vue
