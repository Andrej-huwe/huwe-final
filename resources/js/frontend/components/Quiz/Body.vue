<template>
  <b-row>
    <transition
        name="custom-classes-transition"
        enter-active-class="animated bounceInLeft"
        leave-active-class="animated bounceOutLeft">
      <b-col v-if="characterAreaData" col
             class="character-area"
             :style="hideCharacterArea">
        <b-img v-bind:src="chooseCharacter? imageBg : imageBg2" fluid-grow></b-img>
        <div :class="chooseCharacter ? 'characterCorrect' : 'characterInCorrect'" :style="inlineStyle"></div>
        <div id="tooltip-button-show-event" class="character-message">.</div>
        <b-tooltip class="character-tooltip" placement="bottom" ref="tooltip" target="tooltip-button-show-event" :title=changeText>
        </b-tooltip>
      </b-col>
    </transition>

    <transition name="quiz-area-animation">
      <b-col col sm="12" lg="7" class="quiz-area">
        <quiz-header
            :numTotal="numTotal"
            :school="school"
            :hideCharacter="hideCharacterByButton"
            :characterAreaData="characterAreaData"
            :allowSpeechLoud="allowSpeechLoud"
            :allowSpeechLoudData="allowSpeechLoudData"
            :typeOfSite="typeOfSite"
            :chooseCharacter="chooseCharacter"
        ></quiz-header>
        <b-container  class="bv-example-row">
          <b-row>
            <b-col>

              <quiz-school
                  v-show="Object.keys(questions).length > 0, school"
                  :currentQuestion="questions[index]"
                  :questions="questions"
                  :next="nextForSchool"
                  :prev="prev"
                  :increment="increment"
                  :index="index"
                  :questionIndex="index"
                  :numCorrect="numCorrect"
                  :numTotal="numTotal"
                  :userResponses="userResponses"
                  :correctAnswers="correctAnswers"
                  :toFirstQuestion="toFirstQuestion"
                  :chooseCharacter="chooseCharacter">
              </quiz-school>

              <quiz-questions
                  v-if="Object.keys(questions).length > 0, !school"
                  :currentQuestion="questions[index]"
                  :next="next"
                  :increment="increment"
                  :index="index"
                  :numCorrect="numCorrect"
                  :numTotal="numTotal"
                  :angry="angry"
                  :idle="idle"
                  :onClose="onClose"
                  :onOpen="onOpen"
                  :typeQuestion="typeQuestion"
                  :allowSpeechLoudData="allowSpeechLoudData"
                  :allowSpeechQuestion="allowSpeechQuestion">
              </quiz-questions>
            </b-col>
          </b-row>
        </b-container>
      </b-col>
    </transition>

    <transition name="fade">
      <div v-if="PeteModalCorrectDefault && showModalPeteCorrect()"
           v-on:click="PeteModalCorrectDefault = !PeteModalCorrectDefault" class="modalForPete">
        <div role="dialog" aria-labelledby="bv-modal-example___BV_modal_title_"
             class="modal fade show" aria-modal="true" style="display: block;">
          <div class="modal-dialog">
            <span tabindex="0"></span>
            <div tabindex="-1" class="modal-content">
              <div class="modal-body">
                <div class="d-block text-center">
                  <b-row align-v="stretch">
                    <b-col>
                      <h2 class="modalForPeteText">Tvoje scóre doposiaľ je: <br>{{numCorrect}}/10</h2>
                    </b-col>
                    <b-col>
                      <div class="motivationText">
                        <h5>{{textCorrect}}</h5>
                      </div>
                      <b-img class="modalCharacter"
                             id="tooltip-button-1"
                             v-b-tooltip.focus.left
                             :style="charaCorrectStyle"
                             src="https://huwe.test/images/respo-chara-correct.png?caec35523b37c9b45d7a9f0c0d47744d"></b-img>
                    </b-col>
                  </b-row>
                </div>
              </div><!----></div><span tabindex="0"></span>
          </div>
        </div>
      </div>
    </transition>

    <transition name="fade">
      <div v-if="PeteModalInCorrectDefault && showModalPeteInCorrect()"
           v-on:click="PeteModalInCorrectDefault = !PeteModalInCorrectDefault" class="modalForPete">
        <div role="dialog" aria-labelledby="bv-modal-example___BV_modal_title_"
             class="modal fade show" aria-modal="true" style="display: block;">
          <div class="modal-dialog">
            <span tabindex="0"></span>
            <div tabindex="-1" class="modal-content">
              <div class="modal-body">
                <div class="d-block text-center">
                  <b-row align-v="stretch">
                    <b-col>
                      <h2 class="modalForPeteText">Tvoje scóre doposiaľ je: <br>{{numCorrect}}/10</h2>
                    </b-col>
                    <b-col>
                      <div class="motivationText">
                        <h5>{{textMotivation}}</h5>
                      </div>
                      <b-img class="modalCharacter"
                             id="tooltip-button-2"
                             v-b-tooltip.focus.left
                             :style="charaCorrectStyle"
                             aria-describedby="__bv_tooltip_19__ __bv_tooltip_16__"
                             src="https://huwe.test/images/respo-chara-inCorrect.png?6fdd62a151826df5d93d55e95bbee9f3"></b-img>
                    </b-col>
                  </b-row>
                </div>
              </div><!----></div><span tabindex="0"></span>
          </div>
        </div>
      </div>
    </transition>

  </b-row>
</template>

<script>


import { bus } from '../../app'
export default {
  data() {
    return {
      chooseCharacter: false,
      hideCharacterArea: '',
      characterAreaData: true,
      allowSpeechLoudData: true,
      allowSpeechQuestion: false,
      //Quiz
      userResponses: null,
      correctAnswers: null,

      imageBg: "https://andywork.site/images/quiz-background.png?4c8accaf28fac92cf21bc4fa2ca0de57",
      sterilizedImageBg: null,
      imageBg2: "https://andywork.site/images/quiz-background-2.jpg?2c7afdbd1effccb15507ffc3b47c71b8",

      //Character Respo Correct
      charaImageCorrect: "https://andywork.site/images/respo-chara-correct.png?caec35523b37c9b45d7a9f0c0d47744d",
      charaCorrectStyle: "margin-top: 20%",
      //Type of site
      typeOfSite: window.location.href.split('/').pop(),
      sectionOfSite: window.location.href.split('/'),
      //Quiz
      questions: [],
      questionsWordOdd: [
        {
          question:"Otec",
          correct_answer:"Father",
          incorrect_answers:[
            "Mother",
            "Dog",
            "Cat"
          ]
        },
        {
          question:"Dievča",
          correct_answer:"Girl",
          incorrect_answers:[
            "Boy",
            "Sister",
            "Brother"
          ]
        },
        {
          question:"Chlapec",
          correct_answer:"Boy",
          incorrect_answers:[
            "Girl",
            "Mother",
            "GrandFather"
          ]
        },
        {
          question:"Posteľ",
          correct_answer:"Bed",
          incorrect_answers:[
            "Pillow",
            "Blanket",
            "Mattress"
          ]
        },
        {
          question:"Hasič",
          correct_answer:"Fireman",
          incorrect_answers:[
            "Policeman",
            "Hunter",
            "Politics"
          ]
        },
        {
          question:"Koláč",
          correct_answer:"Cake",
          incorrect_answers:[
            "Carrot",
            "Sofa",
            "Cup"
          ]
        },
        {
          question:"Mrkva",
          correct_answer:"Carrot",
          incorrect_answers:[
            "potatoes",
            "Computer",
            "Coffee"
          ]
        },
        {
          question:"Ráno",
          correct_answer:"Morning",
          incorrect_answers:[
            "Evening",
            "Salat",
            "Cake"
          ]
        },
        {
          question:"Auto",
          correct_answer:"Car",
          incorrect_answers:[
            "Bike",
            "Shoes",
            "Bus"
          ]
        },
        {
          question:"Ahoj",
          correct_answer:"Hello",
          incorrect_answers:[
            "Goodbye",
            "See you again!",
            "See you later!"
          ]
        },
      ],
      questionsWordEven: [
        {
          question:"Hello",
          correct_answer:"Ahoj",
          incorrect_answers:[
            "Dovideia",
            "Dobrý deň",
            "Dobré ráno"
          ]
        },
        {
          question:"Car",
          correct_answer:"Auto",
          incorrect_answers:[
            "Bicykel",
            "Topánky",
            "Kolobežka"
          ]
        },
        {
          question:"Morning",
          correct_answer:"Ráno",
          incorrect_answers:[
            "Večer",
            "Obed",
            "Zajtra"
          ]
        },
        {
          question:"Cake",
          correct_answer:"Koláč",
          incorrect_answers:[
            "Mrkva",
            "Petržlen",
            "Pec"
          ]
        },
        {
          question:"Carrot",
          correct_answer:"Mrkva",
          incorrect_answers:[
            "Zemiak",
            "Počítač",
            "Káva"
          ]
        },
        {
          question:"Fireman",
          correct_answer:"Hasič",
          incorrect_answers:[
            "Policajt",
            "Poľovník",
            "Politik"
          ]
        },
        {
          question:"Bed",
          correct_answer:"Posteľ",
          incorrect_answers:[
            "Vankúš",
            "Plachta",
            "Madrac"
          ]
        },
        {
          question:"Boy",
          correct_answer:"Chlapec",
          incorrect_answers:[
            "Dievča",
            "Otec",
            "Matka"
          ]
        },
        {
          question:"Girl",
          correct_answer:"Dievča",
          incorrect_answers:[
            "Chlapec",
            "Sestra",
            "Brat"
          ]
        },
        {
          question:"Father",
          correct_answer:"Otec",
          incorrect_answers:[
            "Matka",
            "Pes",
            "Dom"
          ]
        },

      ],
      questionsSenteOdd: [
        {
          question:"Ako sa máš?",
          correct_answer:"How are you doing?",
          incorrect_answers:[
            "What are you doing?",
            "Where are you going?",
            "What is your plan?"
          ]
        },
        {
          question:"Ako sa voláš?",
          correct_answer:"What is your name?",
          incorrect_answers:[
            "Where do you live?",
            "Where are your parents?",
            "Who are you?"
          ]
        },
        {
          question:"Toto je skvelý deň.",
          correct_answer:"This is a great day.",
          incorrect_answers:[
            "I am at home",
            "I have a lot of friends",
            "This is my parents"
          ]
        },
        {
          question:"Kde bývaš?",
          correct_answer:"Where do you live?",
          incorrect_answers:[
            "Where is your grandmother?",
            "Is this a car?",
            "Why have you done it?"
          ]
        },
        {
          question:"Moje meno je Benjamin",
          correct_answer:"My name is Benjamin",
          incorrect_answers:[
            "I am looking for Benjamin",
            "I knew Benjamin",
            "Benjamin lives here"
          ]
        },
        {
          question:"Čo robíš?",
          correct_answer:"What are you doing",
          incorrect_answers:[
            "Where you sleeping?",
            "What are you looking for?",
            "How are you doing?"
          ]
        },
        {
          question:"Toto je chlapec",
          correct_answer:"This is a boy",
          incorrect_answers:[
            "This is a girl",
            "They are girls",
            "They are boys"
          ]
        },
        {
          question:"Môj otec je skvelý!",
          correct_answer:"My father is great!",
          incorrect_answers:[
            "My mom is not at home",
            "My mom is great",
            "I have terrible father"
          ]
        },
        {
          question:"Páči sa mi toto auto",
          correct_answer:"I like this car",
          incorrect_answers:[
            "I want this car",
            "I would like to have this car",
            "I never had a car"
          ]
        },
        {
          question:"Tu je obchod",
          correct_answer:"Here is a shop",
          incorrect_answers:[
            "Here is a house",
            "Here is a church",
            "There is a mosque."
          ]
        },
      ],
      questionsSenteEven: [
        {
          question:"How are you doing?",
          correct_answer:"Ako sa máš?",
          incorrect_answers:[
            "Čo robíš?",
            "Kam ideš?",
            "Čo máš na pláne?"
          ]
        },
        {
          question:"What is your name?",
          correct_answer:"Ako sa voláš?",
          incorrect_answers:[
            "Kde bývaš?",
            "Kde sú tvoji rodičia?",
            "Ako sa máš?"
          ]
        },
        {
          question:"This is a great day",
          correct_answer:"Toto je skvelý deň",
          incorrect_answers:[
            "Zajtra pôjdem vonka",
            "Mám veľa kamarátov",
            "Toto sú moji priatelia"
          ]
        },
        {
          question:"Where do you live?",
          correct_answer:"Kde bývaš?",
          incorrect_answers:[
            "Kde je tvoja babka?",
            "Odkiaľ je toto auto?",
            "Prečo si to urobil?"
          ]
        },
        {
          question:"My name is Benjamin",
          correct_answer:"Moje meno je Benjamin",
          incorrect_answers:[
            "Hľadám Benjamina",
            "Poznám Benjamina",
            "Benjamin žije so mnou"
          ]
        },
        {
          question:"What are you doing?",
          correct_answer:"Čo robíš?",
          incorrect_answers:[
            "Hľadáš niečo?",
            "Nevieš kam som to položil?",
            "Ako sa ti darí?"
          ]
        },
        {
          question:"This is a boy",
          correct_answer:"Toto je chlapec",
          incorrect_answers:[
            "Toto je dievča",
            "Oni sú dievčatá",
            "Oni sú chlapci"
          ]
        },
        {
          question:"My father is a great!",
          correct_answer:"Môj otec je výborný!",
          incorrect_answers:[
            "Môj otec je zlý!",
            "Moja mama nie je doma.",
            "Tvoja mama je zlatá!"
          ]
        },
        {
          question:"I like this car",
          correct_answer:"Páči sa mi toto auto",
          incorrect_answers:[
            "Kúpim si toto auto",
            "Premýšlam nad autom",
            "Páči sa mi tento kôň."
          ]
        },
        {
          question:"Here is a shop",
          correct_answer:"Tu je obchod",
          incorrect_answers:[
            "Tu je dom",
            "Tu je kostol",
            "Tu je mešita"
          ]
        },
      ],
      index: 0,
      numCorrect: 0,
      numTotal: 0,

      //School
      school: false,

      //Animation
      stopAnimationDelay: 200,
      angryAnimationDelay: 1440,
      waitAnimationDelay: 5000,
      sadAnimationDelay: 6250,
      angry: 'animation-name: angry',
      idle: 'animation-name: idle',
      sad: 'animation-name: sad',
      currentAnimation: null,
      afterAnimation: null,
      animation: null,
      numAnimation: 0,
      numWaitAnimation: false,
      animationPressed: true,
      checkAnimation: true,
      time: false,

      //Text v Lg
      textWrong: null,
      textGood: null,
      //Text v Sm
      textNumber: null,
      textInCorrect: null,
      textInCorrectOne: 'ÁÁÁÁÁÁÁÁÁÁ!!!',
      textInCorrectTwo: 'Ajajajaj...',
      textInCorrectThree: 'Bla Bla Bla Bla!!!',
      textCorrect:null,
      textCorrectOne: 'Ide ti to skvele!',
      textCorrectTwo: 'To už vieš toľko slovíčok?!',
      textCorrectThree: 'Well done!!',
      textMotivation: null,
      textMotivationOne: 'Makaj viacej!',
      textMotivationTwo: 'Nevzdávaj sa',
      textMotivationThree: 'Nevadí, pokračuj ďalej',
      //NumAnimation v Sm
      smNumAnimation: null,

      //Modal Pete Correct
      showPeteModalCorrect: false,
      PeteModalCorrectDefault: true,
      //Modal Pete inCorrect
      showPeteModalInCorrect: false,
      PeteModalInCorrectDefault: true,
      //Zmena zo Speech-to-text na obyčajný quiz
      typeQuestion: true, //"true" = quiz, "false" = speech

      number: null,
      date: new Date(),

    }
  },
  computed: {
    inlineStyle() {
      if (this.numAnimation == 2 && this.checkAnimation == true && this.time == true) {
        this.updateAnimation(this.angry, this.idle)
        return this.animation
      } else if(this.numWaitAnimation === true && this.animationPressed === true && this.checkAnimation === true && this.time === true && this.numAnimation !== 2){
        this.waitAnimation(this.sad, this.idle)
        return this.animation
      } else if (this.numTotal == 10){
        this.animation = this.idle
        return this.animation
      }
      else {
        this.animation = this.idle
        this.timeCheck()
        return this.animation
      }
    },
    changeText() {
      this.textWrong = this.numAnimation
      if(this.textWrong === 2) {
        this.smChangeText()
        return this.textInCorrect
      } else if (this.textGood === this.number) {
        return this.textCorrect
      } else {
        return this.textMotivation
      }
    }
  },
  methods: {
    changeQuestions() {
      let id = this.typeOfSite % 2
      let site = this.sectionOfSite[3]
      if (site === "schools") {

        this.characterAreaData = false
        this.allowSpeechLoudData = false

        fetch("https://opentdb.com/api.php?amount=10&category=27&type=multiple", {
          method: 'get'
        })
            .then((response) => {
              return response.json()
            })
            .then((jsonData) => {
              this.questions = jsonData.results
              this.userResponses = Array(jsonData.results.length).fill(null)
              this.correctAnswers = Array(jsonData.results.length).fill("undefined")
            })
      } else {
        if (id == 0) {
          if(site == "words"){
            this.questions = this.questionsWordEven
          } else {
            this.questions = this.questionsSenteEven
          }
          this.allowSpeechLoudData = false
        } else {
          if(site == "words"){
            this.questions = this.questionsWordOdd
          } else {
            this.questions = this.questionsSenteOdd
          }
          this.allowSpeechQuestion = true
        }
      }
    },

    checkCharacter(){
      if(this.sectionOfSite[3] == "words"){
        this.chooseCharacter = true
      } else {
        this.chooseCharacter = false
        this.angry = 'animation-name: angry-blue'
        this.sad = "animation-name: sad-blue"
      }
    },
    hideCharacterByButton(){
      this.characterAreaData = !this.characterAreaData
    },
    allowSpeechLoud(){
      this.allowSpeechLoudData = !this.allowSpeechLoudData
    },
    checkLink(){
      //Nadstavenie quizu či už pre školy alebo pre normálneho uživateľa
      let str = this.typeOfSite.toString();
      let res = str.charAt(0)
      if(res == "!"){
        this.school = true
      } else {
        this.school = false
      }
    },
    date_function() {
      var currentDate = new Date()
    },
    showModalPeteCorrect(){
      if(this.textGood === this.number && window.innerWidth < 900){
        this.showPeteModalCorrect = true
        return this.showPeteModalCorrect
      } else {
        this.showPeteModalCorrect = false
        return this.showPeteModalCorrect
      }
    },
    showModalPeteInCorrect(){
      if(this.smNumAnimation === 2 && window.innerWidth < 900){
        this.showPeteModalInCorrect = true
        return this.showPeteModalInCorrect
      }else if(this.smNumAnimation === 3 && window.innerWidth < 900){
        this.smNumAnimation = 0
        return this.smNumAnimation
      } else  {
        this.showPeteModalInCorrect = false
        return this.showPeteModalInCorrect
      }
    },
    smChangeText(){
      this.textNumber = Math.floor(Math.random() * 3) + 1;
      if(this.textNumber === 1) {
        this.textInCorrect = this.textInCorrectOne
        this.textCorrect = this.textCorrectOne
        this.textMotivation = this.textMotivationOne
      } else if (this.textNumber === 2) {
        this.textInCorrect = this.textInCorrectTwo
        this.textCorrect = this.textCorrectTwo
        this.textMotivation = this.textMotivationTwo
      } else if(this.textNumber === 3) {
        this.textInCorrect = this.textInCorrectThree
        this.textCorrect = this.textCorrectThree
        this.textMotivation = this.textMotivationThree
      }
    },
    randomNumber() {
      //this.number = Math.floor(Math.random() * 9) + 1;
      this.number = 5
      console.log(this.number)
    },
    onOpen() {
      this.textWrong = this.numAnimation
      if(this.textWrong === 2) {
        this.$refs.tooltip.$emit('open')
      } else if(this.textGood === this.number) {
        this.$refs.tooltip.$emit('open')
      }
      else {
        this.onClose()
      }
    },
    onClose() {
      this.$refs.tooltip.$emit('close')
    },
    updateAnimation(currentAnimation, afterAnimation){
      setTimeout(() => {
        this.animation = currentAnimation
      }, this.stopAnimationDelay)

      setTimeout(() => {
        this.animation = afterAnimation
        this.numAnimation = 0
        this.checkAnimation = false
      }, this.angryAnimationDelay)
    },
    waitAnimation(currentAnimation, afterAnimation){
      setTimeout(() => {
        this.animation = currentAnimation
      }, this.waitAnimationDelay)

      setTimeout(() => {
        this.animation = afterAnimation
        this.numWaitAnimation = false
        if(this.numAnimation === 1){
          this.checkAnimation = true
        } else {
          this.checkAnimation = false
        }
      }, this.sadAnimationDelay)
    },
    timeCheck(){
      this.date_function()
      if(this.time === false) {
        setTimeout(() => {
          this.time = true
        }, 4000)
      } else {
        this.time = false
      }
    },
    next() {
      this.index++
      this.numWaitAnimation = true
      this.animationPressed = true
      this.checkAnimation = true
      this.time = false
      if(this.numTotal === this.number && this.typeOfSite%2 !==  0){
        this.typeQuestion = false

      } else {
        this.typeQuestion = true
      }
    },
    nextForSchool(){
      this.index++
    },
    toFirstQuestion(){
      this.index = 0
    },
    prev(){
      if(this.index > 0){
        this.index--
      } else if(this.index <= this.questions.length){
        this.index--
      }
    },
    increment(isCorrect) { //funkcia na zachytávanie dát pri odpovedi na otázku
      if (isCorrect) {
        this.numCorrect++
        this.numAnimation--
        this.textGood++
      }
      this.numTotal++

      this.numAnimation++
      this.smNumAnimation++
      this.animationPressed = false
    },
    getDataQuiz(){
      axios.get('/api/quizSetUp').then((res) => {
        this.questions = res.data
        console.log(this.testQuiz)
      }).catch((error) => {
        console.log(error)
      })
    },
  },
  mounted(){
    //https://opentdb.com/api.php?amount=10&category=27&type=multiple

    this.changeQuestions()
    this.date_function()
    this.randomNumber()
    this.smChangeText()
    this.checkLink()
    this.checkCharacter()
  //  this.getDataQuiz()
  }

}
</script>
<style >
body {
  background-color: white;
  height: 100%;
}
.py-4 {
  padding-top: 0 !important;
  padding-bottom: 0 !important;
}
h1 {
  font-family: 'Poppins', sans-serif;
}.characterCorrect {
   background-image: url("https://andywork.site/images/character-animation.png?89c7b6042500dcb8c4a67cb272eccb87");
   position: absolute;
   z-index: 100;
   width: 93%;
   height: 744px;
   margin-top: -744px;
   margin-left: 3px;
   background-size: 11758.5px;
   animation-iteration-count: infinite;
   animation-timing-function: steps(24);
   animation-duration: 1.2s;
 }
.characterInCorrect{
  background-image: url("https://andywork.site/images/character-animation-2.png?a8e6b0a81d8199ebf8474b406fb27aa4");
  position: absolute;
  z-index: 100;
  width: 93%;
  height: 744px;
  margin-top: -744px;
  margin-left: 3px;
  background-size: 11782px;
  animation-iteration-count: infinite;
  animation-timing-function: steps(24);
  animation-duration: 1.2s;
}
.character-message {
  position: absolute;
  z-index: 200;
  margin: -55% 43%;
}

.tooltip .tooltip-inner{
  padding: 14px 55px;
  background: white;
  font-family: 'Poppins', sans-serif;
  color: black;
  border-radius: 12px;
  border: 3px solid grey;
}
.bs-tooltip-bottom .arrow::before {
  border-bottom-color: grey;
}
@keyframes idle {
  from { background-position: 0 0; }
  to { background-position: -11782px 0; }
}
@keyframes angry {

  from {
    background-position: 0 1494px;
  }
  to { background-position: -11782px 1494px;}
}
@keyframes sad {
  from {
    background-position: 0 743px;

  }
  to {
    background-position: -11782px 742px;
  }
}
@keyframes angry-blue {
  from {
    background-position: 0 1494px;
    margin-top: -667px;
    height: 667.8px;
  }
  to {
    background-position: -11782px 1494px;
    margin-top: -667px;
    height: 667.8px;
  }
}
@keyframes sad-blue {
  from {
    background-position: 0 743px;
    margin-top: -702px;
    height: 702px;
  }
  to {
    background-position: -11782px 742px;
    margin-top: -702px;
    height: 702px;
  }
}
.character-area {
  flex: 0 0 495px;
  max-width: 495px;
  margin: auto;
  transition: opacity .5s;
}
.quiz-area {
  margin: auto;
}
.quiz-area-animation {
  transition: all 1s ease;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
.modalForRespo .modal-dialog {
  max-width: 96% !important;
  margin: 0% !important;
  height: 100%;
}
.modalForRespo .modal-content {
  height: 100%;
}
.modalForRespo .modal-body {
  margin-top: 50%;
}
.modalForPete .modal-content {
  height: 100%;
  border: none;
}
.modalForPete .modal-dialog {
  max-width: 96%;
  margin: 0%;
  height: 100%;
}
.modalCharacter {
  width: 136%;
}
.modalForPeteText {
  margin-top: 38%;
}
.modalForPete .arrow {
  left: 160px !important;
}
.motivationText {
  width: 100px;
  height: 50px;
  background-color: green;
  position: relative;
}

.motivationText {
  margin-top: 40%;
  width: 49%;
  position: absolute;
  background: #ffffff;
}
.motivationText h5 {
  padding: 10px;
  border: 3px solid #622161;
  border-radius: 11px;
}
.motivationText:after, .motivationText:before {
  left: 98.5%;
  top: 50%;
  border: solid transparent;
  content: "";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
}

.motivationText:after {
  border-color: rgba(255, 255, 255, 0);
  border-left-color: #ffffff;
  border-width: 11px;
  margin-top: -11px;
}
.motivationText:before {
  border-color: rgba(98, 33, 97, 0);
  border-left-color: #622161;
  border-width: 15px;
  margin-top: -15px;
}
@media only screen and (max-width: 900px){
  .character-area {
    display: none;
  }
}
</style>
