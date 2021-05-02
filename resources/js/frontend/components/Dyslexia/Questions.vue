<template>
  <div class="question-box-container">
    <b-jumbotron class="text-center">
      <h1 class="question"
          v-if="hideElements">
        Aká farby je plocha na ľavo?
      </h1>
      <div>
      </div>
      <b-list-group
          horizontal="md"
          v-if="hideElements">
        <b-list-group-item
            v-for="(answer, index) in shuffledAnswers"
            :key="answer"
            @click="selectAnswer(index), submitAnswer()"
            :disabled="answered === true"
            :class="answerClass(index)"
        >
          {{answer}}
        </b-list-group-item>
      </b-list-group>
      <form @submit=" updateData()" action="/home">
        <div class="quiz-button">
          <b-button
              class="next"
              @click="next(),  showHideElements()"
              :disabled="answered === false"
              v-if="hideElements"
              href="">Pokračovať</b-button>
          <transition name="bounce" mode="out-in">
            <div class="final-part" v-if="showElements">
              <h3 class="text-center">Správne: {{ numCorrect  }}/ {{ numTotal }} </h3>
              <button type="submit"
                      class="finish"
              >Pokračovať</button>
            </div>
          </transition>
        </div>
      </form>
    </b-jumbotron>
  </div>
</template>

<script>
import _ from 'lodash'
import { bus } from '../../app'
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
  data() {
    return {
      sectionOfSite: window.location.href.split('/'),
      shuffledAnswers: [],
      correctIndex: null,
      answered: false,
      showElements: false,
      hideElements: true,

      //Id stránky
      idOfSite: window.location.href.split('/').pop(),
      typeOfSite: window.location.href.split('/'),
      nextIdOfSite: null,

      idOfUser: Vue.prototype.$userId = document.querySelector("meta[name='user_id']").getAttribute('content'),
    }
  },
  props: {
    questions: Object,
    currentQuestion: Object,
    next: Function,
    increment: Function,
    index: Number,
    numCorrect: Number,
    numTotal: Number,
    randomNumber: Number,
  },
  computed: {
    answers() {
      let answers = [...this.currentQuestion.incorrect_answers] // "..." vytvárame kópiu array namiesto odkazovania
      answers.push(this.currentQuestion.correct_answer)
      return answers
    },
  },
  watch: {
    currentQuestion: {
      immediate: true, // "immediate" a "handler" nám spustia funkciu "shuffle" hneď po načitání stránky, inakšie by to bolo až po tom ako sa zmení index
      handler() {
        this.selectedIndex = null
        this.answered = false
        this.shuffleAnswers()
      }
    }
  },
  mounted() {
    this.changeIdOfSite()
  },
  methods: {
    updateData(){

    },
    changeIdOfSite(){
      //prepísanie "id" stránky = id + 1
      let id = this.idOfSite
      id++
      this.nextIdOfSite = id
    },
    selectAnswer(index) {
      this.selectedIndex = index
    },

    submitAnswer() {
      let isCorrect = false
      if(this.selectedIndex === this.correctIndex) {
        isCorrect = true
      }
      this.answered = true
      this.increment(isCorrect)

    },
    shuffleAnswers() {
      let answers = [...this.currentQuestion.incorrect_answers, this.currentQuestion.correct_answer]
      this.shuffledAnswers = _.shuffle(answers) // pracujeme s algoritmom z Lodash
      this.correctIndex = this.shuffledAnswers.indexOf(this.currentQuestion.correct_answer)  // "indexOf" nájde index správnej odpovede
      console.log('correctIndex: ' + this.correctIndex + ' ' + this.shuffledAnswers)
    },

    answerClass(index) {
      let answerClass = ''
      if (this.answered && this.correctIndex === index) {
        answerClass = 'correct-list'
      } else if (this.answered && this.selectedIndex === index && this.correctIndex !== index) {
        answerClass = 'incorrect-list'
      }
      return answerClass
    },
    showHideElements(){
      let index = this.questionIndex
      index++
      if(this.index === 9) { //originíl '9'
        this.showElements = true
        this.hideElements = false
      }
    },
  },
}
</script>

<style scoped>
.vld-overlay {
  top: 25px;
}
.bounce-enter-active {
  animation: bounce-in .5s;
}
.bounce-leave-active {
  animation: bounce-in .5s reverse;
}
@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}
.question-box-container {
  margin-top: 15px;
}
.jumbotron {
  background-color: white;
}
.question {
  margin-top: 5%;
  min-height: 135px;
}
.list-group {
  margin-bottom: 15px;
  min-height: 139px;
}
.list-group-item {
  width: 24%;
  margin: 5% 0.95%;
  border-radius: 12px;
  border: 3px solid rgba(0,0,0, 0.125);
  font-family: 'Poppins', sans-serif;
  font-weight: 500;
  background-color: #f7f7f7;
}
.list-group-horizontal-md > .list-group-item:first-child,
.list-group-horizontal-md > .list-group-item:last-child{
  border-top-right-radius: 12px;
  border-bottom-left-radius: 12px;
  border-top-left-radius: 12px;
  border-bottom-right-radius: 12px;
}
.list-group-item:hover {
  border: 3px solid grey;
  cursor: pointer;
}

.list-group-horizontal-md > .list-group-item + .list-group-item,
.list-group-item + .list-group-item{
  border-top-width: 3px;
  border-left-width: 3px;
}
.btn {
  margin: 0 5px;
}
.correct-list {
  background-color: #38c172;
  color: white;
}
.incorrect-list {
  background-color: #bd3231;
  color: white;
}
.correct-button {
  background-color:  #38c172;
}
.incorrect-button {
  background-color: #bd3231;
}
.next,
.finish {
  width: 50%;
  height: 50px;
  border-radius: 12px;
  font-family: 'Poppins', sans-serif;
  margin-top: 8%;
  color: white;
}
.btn-success {
  border-color: white;
}
.finish-text {
  margin: 10% 0;
}
.btnSpeech {
  margin: 0 auto;
  width: 50%;
  font-size: 1.4rem;
  border-radius: 12px;
  border: 3px solid rgba(0,0,0, 0.125);
  font-weight: 300;
  background-color: #f7f7f7;
  color: #212529;
  font-family: 'Poppins', sans-serif;
}
.btnSpeech:hover {
  border: 3px solid grey;
  cursor: pointer;
}
#btnSpeak {
  background: none;
  border: none;
  color: #622161;
}
@media only screen and (max-width: 900px){
  .list-group-item {
    width: 25%;
  }
  .jumbotron {
    padding: 0px;
  }
  .question {
    min-height: 50%;
    font-size: 1rem !important;
  }
  .list-group {
    min-height: 0;
    margin-bottom: 0;
  }
  .finish-text {
    font-size: 1.5rem;
  }
  .next {
    margin-top: 3%;
    height: 38px;
  }
  .finish-text {
    margin: 5% 0;
  }
  .btnSpeech {
    width: 80%;
    margin: 2% 10%;
    padding: 5%;
  }
}
@media only screen and (max-width: 675px){
  .list-group-item {
    width: 100%;
    margin: 0;
    padding: 1%;
  }
  .btnSpeech {
    width: 80%;
    margin: 0% 10%;
    padding: 1%;
  }
}
</style>
