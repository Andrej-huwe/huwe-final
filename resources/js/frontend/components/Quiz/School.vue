<template>
  <div class="question-box-container vertical-center">
    <div class="modals-info">
      <b-modal ref="modal-info" id="modal-info-1" size="lg" title="Začiatočné informácie" ok-only no-stacking>
        <div class="d-block text-center">
          <div class="modal-schools" v-if="typeOfSite[3] === 'schools'">
            <h3>Na čo slúži <strong>quiz pre školy</strong>:</h3>
            <p class="my-2">Rozdielne školy dostanú možnosť vytvoriť svojím študentom quizy cez administratívnu zónu. Po tom ako vytvorí quiz, učiteľovi sa vytvorí link, ktorý odošle študentom.</p>
            <b-button v-b-modal.modal-info-2>Ďalšie infomrácie</b-button>
          </div>
          <div class="modal-user" v-if="typeOfSite[4] === 'quiz'">
            <h3>Na čo slúži <strong>quiz pre registrovaného uživateľa</strong>:</h3>
            <p class="my-2">Každý registrovaná užívateľ bude mať možnosť zdokonaľovať sa v cudzom jazyku. V quize užívateľ bude odpoveď na otázky za účelom, aby získal nové vedomosti v danom jazyku. Odpovede sa budú môcť označovať a taktiež diktovať. Je tu taktiež aj možnosť nadstavenia quizu </p>
          </div>
        </div>
      </b-modal>

      <b-modal id="modal-info-2" size="lg" title="Začiatočné informácie" ok-only no-stacking>
        <div class="dblock text-center">
          <div class="modal-schools" v-if="typeOfSite[3] === 'schools'">
            <p class="my-2">Po otvorení linku, študentovi sa zobrazí formulár na vyplnenie základných informácii.   </p>
            <b-img center :src="modalImgSchForm" fluid></b-img>
          </div>
          <b-button v-b-modal.modal-info-3>Ďalšie infomrácie</b-button>
        </div>
      </b-modal>

      <b-modal id="modal-info-3" size="lg" title="Začiatočné informácie" ok-only no-stacking>
        <div class="dblock text-center">
          <div class="modal-schools" v-if="typeOfSite[3] === 'schools'">
            <p class="my-2">Po otvorení linku, študentovi sa zobrazí formulár na vyplnenie základných informácii.   </p>
            <b-img center :src="modalImgSchInfo" fluid></b-img>
          </div>
          <b-button v-b-modal.modal-info-4>Ďalšie infomrácie</b-button>
        </div>
      </b-modal>

      <b-modal id="modal-info-4" size="lg" title="Začiatočné informácie" ok-only no-stacking>
        <div class="dblock text-center">
          <div class="modal-schools" v-if="typeOfSite[3] === 'schools'">
            <p class="my-2">Keď študent vyplní formulár, zobrazia sa mu informácie o danom quize. Každý quiz bude mať nadstavený čas, kedy ho môže začať vypracovávať.  Medzi otázka študent môže prechádzať.</p>
            <b-img center :src="modalImgSchQuiz" fluid></b-img>
          </div>
          <b-button v-b-modal.modal-info-5>Ďalšie infomrácie</b-button>
        </div>
      </b-modal>

      <b-modal id="modal-info-5" size="lg" title="Začiatočné informácie" ok-only no-stacking>
        <div class="dblock text-center">
          <p class="my-2">Otázky sa vyhodnocujú, až po ukončení quiz. Quiz sa môže ukončiť ako náhle ubehne čas na vypracovanie. Všetky dáta z daného quizu uvidí učiteľ v administrátorskej zóne.</p>
          <b-img center :src="modalImgSchFinish" fluid></b-img>
        </div>
      </b-modal>
    </div>
    <b-jumbotron class="text-center">
      <div>
        <b-form @submit.prevent="toInformation" v-if="showForm">
          <h1>Huwe - školský test</h1>
          <h2>Pre začatie testu musíte vyplniť následujúce údaje:</h2>
          <b-form-group
              label="Meno:"
              label-for="input-group-name">
            <b-form-input
                id="input-name"
                v-model="schoolData.name"
                placeholder="Vložte meno"
                required
            ></b-form-input>
          </b-form-group>
          <b-form-group
              label="Priezvisko:"
              label-for="input-group-surname">
            <b-form-input
                id="input-surname"
                v-model="schoolData.surname"
                placeholder="Vložte priezvisko"
                required
            ></b-form-input>
          </b-form-group>
          <b-button type="submit" class="finish">Pokračovať</b-button>
        </b-form>
        <div v-if="showInfo">
          <h1>Dôležité informácie</h1>
          <h1 v-if="school" class="schoolQuizName">Školský test: {{nameOfTest}}</h1>
          <h2>Čas začatia testu: {{timeStartQuiz}}</h2>
          <h2>Váš čas na vypracovanie: {{timeLeft}} min</h2>
          <h2>Celkový počet otázok: {{questions.length}}</h2>
          <b-button @click="toQuiz" :disabled="canStart !== true" class="finish">Pokračovať na quiz</b-button>
        </div>
      </div>
      <div v-if="!showForm && !showInfo">
        <h3><b>{{ timeLeft }}</b></h3>
        <h1 class="question"
            v-if="hideElements">
          Otázka č. {{numberQuestion}}: {{ currentQuestion.question }}
        </h1>
        <div>
        </div>
        <b-list-group
            horizontal="md"
            v-if="hideElements">
          <b-list-group-item
              v-for="(answer, index) in answers"
              :key="answer"
              @click="selectAnswer(index, answer), submitAnswer()"
              :class="{'correct-list': userResponses[questionIndex] == index}"
          >
            {{answer}}
          </b-list-group-item>
        </b-list-group>
        <form @submit="saveDataSchool()" action="/home">
          <div>
            <b-row align-h="between" class="button-group" v-if="hideElements">
              <b-col cols="3">
                <b-button
                    class="button"
                    @click="prev(),(shuffleActive = false)"
                    :disabled="index == 0"
                    href="">Spať</b-button>
              </b-col>
              <b-col cols="3">
                <b-button
                    v-if="(index == (questions.length - 1))"
                    class="button"
                    @click="toFirstQuestion(),(shuffleActive = false)"
                    href="">Na prvú otázku</b-button>
              </b-col>
              <b-col cols="3">
                <b-button
                    class="button"
                    :class="(userResponses[questionIndex]==null)?'':'is-active'"
                    @click="next(),  showHideElements(), (shuffleActive = true)"
                    :disabled="questionIndex>=questions.length"
                    href="">{{ (userResponses[questionIndex]==null)? 'Preskočiť': 'Ďalšia' }}</b-button>
              </b-col>
            </b-row>
            <transition name="bounce" mode="out-in">
              <div class="final-part" v-if="showElements">
                <h1 class="finish-text">{{formUser.name}}</h1>
                <h1 class="finish-text">Získali ste: {{quizScore}}%</h1>
                <h1>Čas ukončenia: {{endQuizTime}}</h1>
                <button type="submit" href="/"  class="finish">Ukončiť</button>
              </div>
            </transition>
          </div>
        </form>
      </div>
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
      modalImgSchForm: require('../../../../img/quiz-info/schools/form.png'),
      modalImgSchInfo: require('../../../../img/quiz-info/schools/inf.png'),
      modalImgSchQuiz: require('../../../../img/quiz-info/schools/quiz.png'),
      modalImgSchFinish: require('../../../../img/quiz-info/schools/finish.png'),
      modalImgCharacter: require('../../../../img/quiz-info/user/character.png'),
      modalImgSpeechLoud: require('../../../../img/quiz-info/user/speechLoud.png'),
      modalImgQuestions: require('../../../../img/quiz-info/user/questions.png'),
      modalImgButton: require('../../../../img/quiz-info/user/buttonSettings.png'),
      modalImgSettings: require('../../../../img/quiz-info/user/settings.png'),
      modalImgSpeech: require('../../../../img/quiz-info/user/speechSubmit.png'),
      modalImgFinish: require('../../../../img/quiz-info/user/finish.png'),
      nameOfTest: 'Teória z informatiky č.7',
      //Formulár
      shuffleActive: true,
      formUser: {
        name: '',
      },
      canStart: false,
      beginQuizTime: 0,
      waitQuizTime: null,
      timerQuiz: null,
      endQuizTime: null,
      startQuizTime: null,
      numberQuestion: null,
      quizScore: 0,
      showForm: true,
      showInfo: false,
      color: '#622161',
      backgroundColor: 'none',
      loader: 'bars',
      isLoading: false,
      fullPage: false,
      //Timer
      time: 360, //v sekudnách
      timer: null,
      timeStart: false,



      shuffledAnswers: [],
      correctIndex: null,
      answered: false,
      showElements: false,
      hideElements: true,
      numberTest: 100,
      //"form" na vkladanie do DB
      schoolData: new Form({
        name: "",
        surname: "",
        answers: this.userResponses,
        points: "",
        endTime: ""
      }),
      //Quiz

      //Awards Data, odosielanie do DB
      awardOne: false,
      awardTwo: false,
      awardThree: false,
      awardFour: false,
      awardFive: false,
      awardSix: false,
      //Id stránky
      idOfSite: window.location.href.split('/').pop(),
      typeOfSite: window.location.href.split('/'),
      nextIdOfSite: null,
      //Get data
      completedSteps: [],
      score: [],
      words_score: [],
      sentences_score: [],
      idOfScore: [],
      idOfUser: this.$userId,
      badges: {
        length: [],
        data: [],
        userId: [],
        status: [],
        unlock_at: {},
        id: [],
      },
      //Speech to text
      btnAnswerSpeech: '',
      btnAnswerText: 'Tap to speak'
    }
  },
  components: {
    Loading
  },
  props: {
    questions: Array,
    currentQuestion: Object,
    next: Function,
    prev: Function,
    increment: Function,
    index: Number,
    questionIndex: Number,
    numCorrect: Number,
    numTotal: Number,
    randomNumber: Number,
    typeQuestion: Boolean,
    userResponses: Array,
    correctAnswers: Array,
    toFirstQuestion: Function,
  },
  computed: {
    timeLeft () {
      return `${this.minutes} : ${this.seconds} `

    },
    minutes () {
      return String(Math.floor(this.time / 60)).padStart(2, '0')
    },
    seconds () {
      return String(this.time % 60).padStart(2, '0')
    },
    timeStartQuiz(){
      return `${this.hoursStart}:${this.minutesStart}:${this.secondsStart} `
    },
    hoursStart(){
      return String(Math.floor(this.beginQuizTime/3600)).padStart(2, '0')
    },
    minutesStart(){
      let letTime = this.beginQuizTime - this.hoursStart*3600
      return String(Math.floor(letTime/60)).padStart(2, '0')
    },
    secondsStart(){
      return String(this.beginQuizTime % 60).padStart(2, '0')
    },
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
        this.selectedAnswer = null
        this.answered = false
        this.numberQuestionMethod()
        this.shuffleAnswers()
      }
    }
  },
  mounted() {
    this.openModalInfo()
    this.changeIdOfSite()
  },
  created () {
    this.timer = setInterval(this.decrementOrAlert, 1000)
    this.timerQuiz =  setInterval(this.toQuizTime, 1000)
  },
  methods: {
    openModalInfo(){
      this.$refs['modal-info'].show()
    },
    hideModalInfo(){
      this.$refs['modal-info'].hide()
    },
    saveDataSchool(){
      let data = new FormData();
      let schoolId = this.idOfSite
      if(schoolId.charAt(0) === '!'){
        schoolId = schoolId.slice(1)
      }
      data.append('user_name', this.schoolData.name)
      data.append('school_id', schoolId)
      data.append('user_surname', this.schoolData.surname)
      data.append('endTime', this.schoolData.endTime)
      data.append('points',   this.schoolData.points)
      data.append('answers', this.schoolData.answers)
      axios.post('/api/school', data)
    },
    numberQuestionMethod(){
      this.numberQuestion = this.index
      this.numberQuestion++
      return this.numberQuestion
    },
    //Formulár
    toQuizTime(){
      let actualTime = new Date()
      let time = actualTime.getHours()*3600 + actualTime.getMinutes()*60 + actualTime.getSeconds()
      this.waitQuizTime = this.beginQuizTime - time

      if(this.waitQuizTime > 0){
        this.waitQuizTime--
        return
      }
      this.canStart = true
    },
    toQuiz(){
      this.timeStart = true
      this.showInfo = false
      this.startQuizTime = new Date()
    },
    toInformation(){
      this.showForm = false
      this.showInfo = true
    },
    decrementOrAlert () {
      if(this.timeStart == true){
        if (this.time > 0) {
          this.time--
          return
        }
        this.endQuiz()
        this.endQuizTime = new Date()
        clearInterval(this.timer)
      }

    },
    changeIdOfSite(){
      //slúži na ocenenia
      //prepísanie "id" stránky = id + 1
      let id = this.idOfSite
      id++
      this.nextIdOfSite = id
    },
    selectAnswer(index, answer) {
      this.selectedIndex = index
      this.selectedAnswer = answer
    },

    submitAnswer() {
      this.increment()
      Vue.set(this.userResponses, this.questionIndex, this.selectedIndex);
      console.log("User Responses" + this.userResponses);

    },
    shuffleAnswers() {
      /*
      //Po stlačený "spať" aby sa nesputil "shuffle"
      if(this.shuffleActive == true){
        let answers = [...this.currentQuestion.incorrect_answers, this.currentQuestion.correct_answer]
        this.shuffledAnswers = _.shuffle(answers) // pracujeme s algoritmom z Lodash
        this.correctIndex = this.shuffledAnswers.indexOf(this.currentQuestion.correct_answer)  // "indexOf" nájde index správnej odpovede
        Vue.set(this.correctAnswers, this.questionIndex, this.correctIndex)
        console.log('Correct Answers: ' + this.correctAnswers)
      }

       */

    },
    endQuiz(){
      //Vyhodnotenie až po dokončení quizu
      for(let i = 0; i < this.userResponses.length; i++){
        if(this.correctAnswers[i] === this.userResponses[i]){
          this.quizScore++
        }
      }
      this.quizScore = (this.quizScore / this.questions.length) * 100
      this.schoolData.points = this.quizScore
      this.showElements = true
      this.hideElements = false
      return this.quizScore
    },
    showHideElements(){
      let index = this.questionIndex
      index++
      if(index == this.questions.length) {
        clearInterval(this.timer)
        this.endQuizTime = new Date().toLocaleTimeString()
        this.schoolData.endTime = this.endQuizTime
        this.endQuiz()
      }
      console.log("index: "+ this.index + " length: " + this.questions.length)
    },
  },
}
</script>

<style scoped>
img {
  box-shadow: 0 0 10px grey;
  margin: 1.5rem 0;
}

.vertical-center {
  min-height: 100%;
  min-height: 100vh;

  display: flex;
  align-items: center;
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
  margin: auto;
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
  background-color: grey;
  color: white;
}

.correct-button {
  background-color:  #38c172;
}
.button {
  width: 100%;
  border-radius: 12px;
  border: 2px solid #622161;
  background: none;
  font-family: 'Poppins', sans-serif;
  color: black;
  margin-top: 5%;
}
.button.is-active {
  background: #622161;
  color: white;
  border-color: transparent;
}
.finish {
  width: 50%;
  height: 50px;
  border-radius: 12px;
  background-color: #622161;
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
