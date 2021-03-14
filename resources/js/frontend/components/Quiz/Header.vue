<template>
  <div class="container text-center">
    <div v-if="showSettings">
      <b-button class="settings mt-3" :style="chooseCharacter ? 'background: #622161': 'background: #2f7fb7'" @click="$bvModal.show('settings')">...</b-button>
      <b-modal id="settings" hide-footer>
        <template #modal-title>
          Nadstavenie Quizu
        </template>
        <div class="d-block row text-center">
          <b-col>
            <b-form-checkbox v-model="allowSpeechLoudData" @change="allowSpeechLoud" name="check-button" switch>
              Prečítanie spravnej odpovedi
            </b-form-checkbox>
          </b-col>
          <b-col>
            <b-form-checkbox v-model="checkCharacter" @change="hideCharacter" name="check-button" switch>
              Zobrazenie charakteru
            </b-form-checkbox>
          </b-col>

        </div>
        <b-button class="mt-3" block @click="$bvModal.hide('settings')">Zatvoriť</b-button>
      </b-modal>
    </div>
    <div class="text-center">
      <b-progress v-if="!school" :max="max">
        <b-progress-bar :style="chooseCharacter ? 'background-color: #622161' : 'background-color: #2f7fb7;'" class="progress-bar" :value=" numTotal"></b-progress-bar>
      </b-progress>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    numTotal: Number,
    school: Boolean,
    hideCharacter: Function,
    characterAreaData: Boolean,
    allowSpeechLoud: Function,
    allowSpeechLoudData: Boolean,
    typeOfSite: String,
    chooseCharacter: Boolean
  },
  data() {
    return {
      max: 10,
      checkCharacter: true,
      checkSpeechLoud: true,
      showSettings: true
    }
  },
  methods: {
    checkTypeOfSite(){
      if(this.typeOfSite.charAt(0) === "!"){
        this.showSettings = false

      } else {
        this.showSettings = true
      }
    },
  },
  mounted() {
    this.checkTypeOfSite()
    console.log(this.checkCharacter)
  }
}
</script>
<style scoped>
.schoolQuizName {
  margin-top: 5%;
}
.settings {
  border-radius: 12px;
  color: white;
}
.modal-content {
  border-radius: 25px;
}
.progress {
  margin-top: 5%;
  height: 1.5rem;
}
.header-top-text {
  margin-top: 5%;
}
.name-of-category {
  color: #622161;
}
@media only screen and (max-width: 900px){
  .progress {
    height: 1rem;
  }
}
</style>