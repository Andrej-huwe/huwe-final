<template>
  <div class="container">
    <div v-if="grammarData.length">
      <div v-for="single in grammarData" :key="single.id">
        <div v-if="single.id == idOfSite">
          <h1 class="text-center">{{single.name}}</h1>
          <p>{{grammarText}}</p> <!-- namiesto "grammarText" má byť "single.desc" -->
        </div>
      </div>
      <b-button href="/grammar">Spať na gramatiku!</b-button>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      idOfSite: window.location.href.split('/').pop(),
      index: this.idOfSite - 1,
      grammarData: [],
      grammarText: "Budúci čas jednoduchý odkazuje na \"čas neskôr ako teraz\" a vyjadruje isté fakty. V tomto prípade neexistuje žiadny 'postoj'.\n" +
          "\n" +
          "Budúci čas jednoduchý sa používa na:\n" +
          "\n" +
          "Predpovedanie budúcej udalosti:\n" +
          "It will rain tomorrow.\n" +
          "S \"I\" alebo \"we\" na vyjadrenie spontánneho rozhodnutia:\n" +
          "I'll pay for the tickets by credit card.\n" +
          "Vyjadrenie ochoty:\n" +
          "I'll do the washing-up.\n" +
          "He'll carry your bag for you.",

    }
  },
  mounted(){
    this.getGrammar()
  },
  methods: {
    getGrammar(){
      axios.get('/api/grammar').then((res) => {
          this.grammarData = res.data
      }).catch((error) => {
        console.log(error)
      })
    }
  },
}
</script>