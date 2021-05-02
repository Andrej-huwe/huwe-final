
import '../bootstrap';
import '../plugins';
import Vue from 'vue';
import Form from '../Form'
window.Form = Form


import BootstrapVue from "bootstrap-vue"

Vue.use(BootstrapVue)
window.Vue = Vue;

Vue.config.productionTip = false
export const bus = new Vue();


Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('choose-cards', require('./components/chooseCards.vue').default);
Vue.component('level-vue', require('./components/Level.vue').default);
Vue.component('quiz-body', require('./components/Quiz/Body.vue').default);
Vue.component('quiz-questions', require('./components/Quiz/Questions.vue').default);
Vue.component('school-results', require('./components/Quiz/SchoolResults.vue').default);
Vue.component('awards', require('./components/Awards.vue').default);
Vue.component('awards-new', require('./components/AwardsNew.vue').default);
Vue.component('quiz-header', require('./components/Quiz/Header.vue').default);
Vue.component('grammar-vue', require('./components/Grammar.vue').default);
Vue.component('grammar-single', require('./components/Single.vue').default);
Vue.component('check-width', require('./components/ChangeWidth.vue').default);
Vue.component('footer-vue', require('./components/Footer.vue').default);
Vue.component('quiz-school', require('./components/Quiz/School.vue').default);
Vue.component('send-behind', require('./components/SendBehind.vue').default);
Vue.component('quiz-dyslexia', require('./components/Dyslexia/Dyslexia.vue').default);
Vue.component('dyslexia-body', require('./components/Dyslexia/Body.vue').default);
Vue.component('dyslexia-questions', require('./components/Dyslexia/Questions.vue').default);




const app = new Vue({
    el: '#app',
});

