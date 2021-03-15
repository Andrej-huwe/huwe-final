<template>
  <div class="question-box-container">
    <b-jumbotron class="text-center">
      <h1 class="question"
          v-if="hideElements">
        <b-button @click="speechLoudQuestion()" id='btnSpeak'
                  :style="chooseCharacter ? 'color: #622161': 'color: #2f7fb7'"
                  :disabled="allowSpeechQuestion"
        ><i class="fas fa-volume-up fa-2x"></i></b-button>
        Ako povieš: {{ currentQuestion.question }}
      </h1>
      <div>
      </div>
      <b-list-group
          horizontal="md"
          v-if="hideElements">
        <b-button id="btnGiveCommand"
                  class="btnSpeech"
                  v-if="!typeQuestion"
                  @click="submitSpeech(),  doLoading()"
                  v-bind:style="btnAnswerSpeech"
                  :disabled="answered === true">
          {{btnAnswerText}}
          <loading :active.sync="isLoading"
                   :can-cancel="true"
                   :color="color"
                   style="margin: auto"
                   :loader="loader"
                   :background-color="backgroundColor"
                   :is-full-page="fullPage"></loading>
        </b-button>

        <b-list-group-item
            v-if="typeQuestion"
            v-for="(answer, index) in shuffledAnswers"
            :key="answer"
            @click="selectAnswer(index), submitAnswer(), allowSpeechLoudData?speechLoudAnswer(index): '', onOpen()"
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
              @click="next(),  showHideElements(), onClose()"
              :disabled="answered === false"
              v-if="hideElements"
              :style="chooseCharacter ? 'background: #622161': 'background: #2f7fb7'"
              href="">Pokračovať</b-button>
          <transition name="bounce" mode="out-in">
            <div class="final-part" v-if="showElements">
              <h1 class="finish-text" v-if="upNumber">Výborne, test ukočený!</h1>
              <h1 class="finish-text" v-if="downNumber">Ešte na sebe musíš veľa pracovať, <br> test ukočený!</h1>
              <h3 class="text-center">Správne: {{ numCorrect  }}/ {{ numTotal }} </h3>
              <button type="submit"
                      class="finish"
                      :style="chooseCharacter ? 'background: #622161': 'background: #2f7fb7'"
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
      chooseCharacter: false,
      sectionOfSite: window.location.href.split('/'),
      color: '#622161',
      backgroundColor: 'none',
      loader: 'bars',
      isLoading: false,
      fullPage: false,

      shuffledAnswers: [],
      correctIndex: null,
      answered: false,
      showElements: false,
      upNumber: false,
      downNumber: false,
      hideElements: true,
      numberTest: 100,
      //"form" na vkladanie do DB
      form: new Form({
        score: "",
        actualScore: "",
        completedSteps: "",
      }),

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
      idOfUser: Vue.prototype.$userId = document.querySelector("meta[name='user_id']").getAttribute('content'),
      // let user = Vue.prototype.$userId = document.querySelector("meta[name='user_id']").getAttribute('content'); // Získanie "id" uživateľa

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
      btnAnswerText: 'Klepnutím hovoríte',

    }
  },
  components: {
    Loading
  },
  props: {
    questions: Object,
    currentQuestion: Object,
    next: Function,
    increment: Function,
    index: Number,
    numCorrect: Number,
    numTotal: Number,
    onClose: Function,
    onOpen: Function,
    randomNumber: Number,
    typeQuestion: Boolean,
    allowSpeechLoudData: Boolean,
    allowSpeechQuestion: Boolean
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
    this.checkCharacter()
    this.getDataSteps()
    this.changeIdOfSite()
    this.getDataScore()
  },
  methods: {
    checkCharacter(){
      if(this.sectionOfSite[3] == "words"){
        this.chooseCharacter = true
      } else {
        this.chooseCharacter = false
        this.color = "#2f7fb7"
      }
    },
    updateData(){
      this.updateDataSteps()
      this.updateDataDisable()
      this.updateDataScore()
      this.updateBadges()
    },
    getDataScore(){
      let index = this.idOfUser
      index--
      //kontrola, či "completed_steps" = 10, ak áno, odošle 10 do tabuľky automaticky
      axios.get('/api/score').then((res) =>{
        this.score = res.data[index].total_score
        this.words_score = res.data[index].words_score
        this.sentences_score = res.data[index].sentences_score
        this.idOfScore = res.data[index].id
      }).catch((error) =>{
        console.log(error)
      })
    },
    updateDataScore(){
      // dáta ukladáme podľa "id" uživateľa
      let id = this.idOfUser
      this.form.score = this.numCorrect
      let newScore = this.form.score + this.score
      let dataScore = new FormData();
      let wordsScore = this.numCorrect + this.words_score
      let sentencesScore = this.numCorrect + this.sentences_score


      dataScore.append('_method', 'PATCH')
      dataScore.append('total_score', newScore)
      if(this.typeOfSite == "words"){
        dataScore.append('words_score', wordsScore)
      }
      if(this.typeOfSite == "sentences"){
        dataScore.append('sentences_score', sentencesScore)
      }
      axios.post('/api/score/'+ id, dataScore)
          .catch((error) => {
            this.form.errors.record(error.response.data.errors)
          })
    },
    //Načítať údaje si môžme až po skončení quizu, nepotrebujem v priebehu quizu s nimi pracovať
    updateBadges(){
      let index = 0
      axios.get('/api/badges').then((res) => {
        this.badges.length = res.data.length
        //Vďaka "for" mi načíta všetky údaje, nie iba posledný
        for(index = 0; index <= this.badges.length; index++){
          this.badges.status = res.data[index].status
          this.badges.unlock_at = res.data[index].unlock_at
          this.badges.userId =res.data[index].userId
          this.badges.id = res.data[index].id
          //Zavoláme si funkciu, aby sme pracovali s "for". Inakšie by nám odoslalo iba jeden údaj, takto nám odošle všetky
          this.setBadges(this.badges.userId, this.badges.id, this.badges.unlock_at)
          this.setBadgesNew(this.badges.userId, this.badges.id, this.badges.unlock_at)
        }
      }).catch((error) =>{
        console.log(error)
      })
    },
    setBadges(userIdDB, id, unlock){
      let newScore = this.form.score + this.score
      let userId = this.idOfUser
      let dataBadges = new FormData()
      //Ak uživateľské ID sa musí rovnať s "userId" v "badges"
      if(userIdDB == userId){
        dataBadges.append('_method', 'PATCH')
        //Ak scóre je väčšie ako "unlock_at"
        if(newScore >= unlock){
          dataBadges.append('status', 1)
        } else {
          dataBadges.append('status', 0)
        }
        dataBadges.append('new_award', 0)
        axios.post('/api/badges/'+ id, dataBadges)
            .catch((error) => {
              this.form.errors.record(error.response.data.errors)
            })
      }
    },
    setBadgesNew(userIdDB, id, unlock){
      let score = this.score
      let count
      let newScore = this.form.score + this.score
      let dataBadges = new FormData()
      dataBadges.append('_method', 'PATCH')
      //skontroluje mi čísla od starého po nové skóre, ak jedno z tých čísel sa rovná "unlock" zapíše mi do "new_award" = 1
      for(count = score ; count <= newScore; count++ ) {
        if(count == unlock) {
          dataBadges.append('new_award', 1)
          axios.post('/api/badges/' + id, dataBadges)
        }
      }
    },
    getDataSteps(){
      let index = this.idOfSite
      index--
      this.typeOfSite = this.typeOfSite[3]
      if(this.typeOfSite == "words"){
        //kontrola, či "completed_steps" = 10, ak áno, odošle 10 do tabuľky automaticky
        axios.get('/api/words').then((res) =>{
          this.completedSteps = res.data[index].completed_steps
        }).catch((error) =>{
          console.log(error)
        })
      }
      if(this.typeOfSite == "sentences"){
        //kontrola, či "completed_steps" = 10, ak áno, odošle 10 do tabuľky automaticky
        axios.get('/api/sentences').then((res) =>{
          this.completedSteps = res.data[index].completed_steps
        }).catch((error) =>{
          console.log(error)
        })
      }
    },
    updateDataSteps(){
      this.form.completedSteps = this.numCorrect
      let id = this.idOfSite
      let dataSteps = new FormData();

      if(this.typeOfSite == "words"){
        dataSteps.append('_method', 'PATCH')
        // Ak správne odpovede ako 10 (číže už som urobil na plný počet daný level)
        // a ak moje aktuálne kroky sú menšie ako moje predošlé, uloží mi moje správne odpovede
        if(this.form.completedSteps > this.completedSteps){
          dataSteps.append('completed_steps', this.form.completedSteps)
        } else {
          dataSteps.append('completed_steps', this.completedSteps)
        }
        axios.post('/api/words/'+ id, dataSteps)
            .catch((error) => {
              this.form.errors.record(error.response.data.errors)
            })
      }

      if(this.typeOfSite == "sentences"){
        dataSteps.append('_method', 'PATCH')
        if(this.completedSteps < 10){
          dataSteps.append('completed_steps', this.form.completedSteps)
        } else {
          dataSteps.append('completed_steps', 10)
        }
        axios.post('/api/sentences/'+ id, dataSteps)
            .catch((error) => {
              this.form.errors.record(error.response.data.errors)
            })
      }


    },
    changeIdOfSite(){
      //prepísanie "id" stránky = id + 1
      let id = this.idOfSite
      id++
      this.nextIdOfSite = id
    },
    updateDataDisable(){
      let id = this.nextIdOfSite
      let dataTotal = new FormData();

      if(this.typeOfSite == "words"){
        dataTotal.append('_method', 'PATCH')
        if(this.numCorrect == 10){
          dataTotal.append('disable', 1)
        }
        axios.post('/api/words/'+ id, dataTotal)
            .catch((error) => {
              this.form.errors.record(error.response.data.errors)
            })
      }
      if(this.typeOfSite == "sentences"){
        dataTotal.append('_method', 'PATCH')
        if(this.numCorrect == 10){
          dataTotal.append('disable', 1)
        }
        axios.post('/api/sentences/'+ id, dataTotal)
            .catch((error) => {
              this.form.errors.record(error.response.data.errors)
            })
      }
    },
    selectAnswer(index) {
      this.selectedIndex = index
    },
    speechLoudQuestion(){
      let question = this.currentQuestion.question

      if ('speechSynthesis' in window) {
        var msg = new SpeechSynthesisUtterance();
        msg.lang = 'Daniel';
        msg.text = question
        window.speechSynthesis.speak(msg);
      } else {
        alert("Pardon, ale Váš prehliadač nepodporuje text do hlasu!");
      }
    },
    speechLoudAnswer(index){
      let answer = this.currentQuestion.correct_answer

      if ('speechSynthesis' in window) {
        var msg = new SpeechSynthesisUtterance();
        msg.lang = 'Daniel';
        if(this.answered == true && this.correctIndex !== index){
          msg.text = answer
        } else if(this.answered == true && this.correctIndex === index){
          //Ak správne odpoviem, nič nevykoná táto funkcia
        }
        window.speechSynthesis.speak(msg);
      } else {
        alert("Váš prehliadač nepodporuje text do hlasu... 😢");
      }

    },
    submitSpeech(){
      if ("speechSynthesis" in window) {
        let answer = this.currentQuestion.correct_answer
        let SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
        let recognition = new SpeechRecognition();

        recognition.onstart = function() {
          setTimeout(() => {
            bus.$emit('answered', 0);
            recognition.stop()
          }, 8000)
        };

        recognition.onresult = function(event) {
          var transcript = event.results[0][0].transcript;
          console.log(transcript);
          if(transcript.toLowerCase() === answer.toLowerCase()){ // answer.toLowerCase()
            bus.$emit('answered', 1);
          } else {
            bus.$emit('answered', 0);
          }
        };

        recognition.start();
      } else {
        console.log("Hlasovné rozpoznávanie nie je podporované... 😢");
      }
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
    answerClassSpeech(data){
      if(data == 1){
        this.btnAnswerSpeech = 'background-color: #38c172; color: white;'
      } else if(data == 0){
        this.btnAnswerSpeech = 'background-color: #bd3231; color: white;'
      }
      return this.btnAnswerSpeech
    },
    doLoading() {
      this.btnAnswerText = null
      this.isLoading = true;
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
        if(this.numCorrect >= 5) {
          this.upNumber = true
        } else {
          this.downNumber = true
        }
      }
    },
  },
  created() {
    bus.$on('answered', (data) => {
      this.increment(data)
      this.answered = true
      this.isLoading = false
      this.btnAnswerText = 'Tap to speak'
      this.answerClassSpeech(data)
    })
  },
}
</script>

<style scoped>
.vld-overlay {
  top: 36px;
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
