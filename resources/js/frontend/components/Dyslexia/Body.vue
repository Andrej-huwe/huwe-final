<template>
  <b-row>
    <b-col>
      <div class="character-area" :style="setColor">
      </div>
    </b-col>
      <b-col col sm="12" lg="7" class="quiz-area vertical-center">
        <b-container  class="bv-example-row">
              <dyslexia-questions
                  v-if="Object.keys(questions).length > 0"
                  :currentQuestion="questions[index]"
                  :next="next"
                  :increment="increment"
                  :index="index"
                  :numCorrect="numCorrect"
                  :numTotal="numTotal">
              </dyslexia-questions>
        </b-container>
      </b-col>
  </b-row>
</template>

<script>


import { bus } from '../../app'
export default {
  data() {
    return {
      //Character Respo Correct
      charaImageCorrect: "https://andywork.site/images/respo-chara-correct.png?caec35523b37c9b45d7a9f0c0d47744d",
      //Type of site
      typeOfSite: window.location.href.split('/').pop(),
      sectionOfSite: window.location.href.split('/'),
      //Quiz
      questions: [ {
        question: "Default Questions"
      }],
      questionsColor: [
        {
          correct_answer:"Modrá",
          incorrect_answers:[
            "Zelená",
            "Fialová",
            "Červená"
          ]
        },
        {
          correct_answer:"Ružová",
          incorrect_answers:[
            "Hnedá",
            "Sivá",
            "Oranžová"
          ]
        },
        {
          correct_answer:"Fialová",
          incorrect_answers:[
            "Zelená",
            "Žltá",
            "Čierna"
          ]
        },
        {
          correct_answer:"Bed",
          incorrect_answers:[
            "Pillow",
            "Blanket",
            "Mattress"
          ]
        },
        {
          correct_answer:"Fireman",
          incorrect_answers:[
            "Policeman",
            "Hunter",
            "Politics"
          ]
        },
        {
          correct_answer:"Cake",
          incorrect_answers:[
            "Carrot",
            "Sofa",
            "Cup"
          ]
        },
        {
          correct_answer:"Carrot",
          incorrect_answers:[
            "potatoes",
            "Computer",
            "Coffee"
          ]
        },
        {
          correct_answer:"Morning",
          incorrect_answers:[
            "Evening",
            "Salat",
            "Cake"
          ]
        },
        {
          correct_answer:"Car",
          incorrect_answers:[
            "Bike",
            "Shoes",
            "Bus"
          ]
        },
        {
          correct_answer:"Hello",
          incorrect_answers:[
            "Goodbye",
            "See you again!",
            "See you later!"
          ]
        },
      ],
      index: 0,
      numCorrect: 0,
      numTotal: 0,
      actualColor: null,
      color1: "background-color: blue",
      color2: "background-color: pink",
      color3: "background-color: purple",
      color4: "background-color: red",
      color5: "background-color: yellow",
      color6: "background-color: green",
      color7: "background-color: brown",
      color8: "background-color: grey",
      color9: "background-color: black",
      color10: "background-color: orange",


    }
  },
  computed: {
    setColor(){
     if(this.index == 0){
       return this.color1;
     }
     else if(this.index == 1){
       return this.color2;
     }
     else if(this.index == 2){
       return this.color3;
     }
     else if(this.index == 3){
       return this.color4;
     }
     else if(this.index == 4){
       return this.color5;
     }
     else if(this.index == 5){
       return this.color6;
     }
     else if(this.index == 6){
       return this.color7;
     }
     else if(this.index == 7){
       return this.color8;
     }
     else if(this.index == 8){
       return this.color9;
     }
     else if(this.index == 9){
       return this.color10;
     } else {
       return this.color10
     }
    },
  },
  methods: {
    randomNumber() {
      //this.number = Math.floor(Math.random() * 9) + 1;
      this.number = 5
      console.log(this.number)
    },
    next() {
      this.index++

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
    this.randomNumber()
    //  this.getDataQuiz()
    this.questions = this.questionsColor
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
}

.vertical-center {
  min-height: 100%;
  min-height: 100vh;

  display: flex;
  align-items: center;
}
.character-area {
  height: 100%;
  flex: 0 0 495px;
  max-width: 495px;
  margin: auto;
  transition: opacity .5s;
}
.quiz-area {
  margin: auto;
}

@media only screen and (max-width: 900px){
  .character-area {
    display: none;
  }
}
</style>
