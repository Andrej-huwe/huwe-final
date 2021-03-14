<template>
  <div class="container  vertical-center">
    <div class="modals-info">
      <b-modal ref="modal-info" id="modal-info-1" size="lg" title="Začiatočné informácie" ok-only>
        <div class="d-block text-center">
          <h3>Porucha učenia:</h3>
          <p class="my-2">Sekcia pre ľúdi s poruchou učenia (presnejšie ľudí s Dyslexiou), slúži na trénovanie čítania. Systém funguje tak, že užívateľ si myšou na počítači označí text, ktorý chce, aby systém "prečítal". Vďaka tomuto môže trénovať čitateľské schopnosti.</p>
          <b-img center :src="modalImgExplanation" fluid></b-img>
        </div>
      </b-modal>
    </div>
    <div class="text-center">
      <h1>Príbeh: wolf, wolf, wolf.</h1>
      <p id="chooseText" class="word_split" @click="getSelection()">{{textToRead}}</p>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      textToRead: "Once upon a time, there lived a shepherd boy who was bored watching his flock of sheep on the hill. To amuse himself, he shouted, “Wolf! Wolf! The sheep are being chased by the wolf!” The villagers came running to help the boy and save the sheep. They found nothing and the boy just laughed looking at their angry faces.  “Don’t cry ‘wolf’ when there’s no wolf boy!”, they said angrily and left. The boy just laughed at them.  After a while, he got bored and cried ‘wolf!’ again, fooling the villagers a second time. The angry villagers warned the boy a second time and left. The boy continued watching the flock. After a while, he saw a real wolf and cried loudly, “Wolf! Please help! The wolf is chasing the sheep. Help!”  But this time, no one turned up to help. By evening, when the boy didn’t return home, the villagers wondered what happened to him and went up the hill. The boy sat on the hill weeping. “Why didn’t you come when I called out that there was a wolf?” he asked angrily. “The flock is scattered now”, he said.An old villager approached him and said, “People won’t believe liars even when they tell the truth. We’ll look for your sheep tomorrow morning. Let’s go home now”.",
      modalImgExplanation:require('../../../img/dylsexia/explanation.png'),
    }
  },
  mounted() {
    this.openModalInfo()
  },
  methods: {
    openModalInfo(){
      this.$refs['modal-info'].show()
    },
    speechLoud(txt){
      if ('speechSynthesis' in window) {
        var msg = new SpeechSynthesisUtterance();
        msg.lang = 'Daniel';
        msg.text = txt
        window.speechSynthesis.speak(msg);
      } else {
        alert("Pardon, ale Váš prehliadač nepodporuje text do hlasu!");
      }
    },
    getSelection(){
      let txt = ''
      if(window.getSelection()){
        txt = window.getSelection().toString()
        this.hover = true
        this.speechLoud(txt)
      } else if (document.selection) {
        txt = document.selection.createRange().text;
        this.hover = false
      }
    },
  }
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
.word_split::selection {
  background-color: #fcf8e3;
}
h2 {
  min-height: 50px;
}
p {
  font-size: 1.5rem;
}
h1 {
  padding-bottom: 2.5%;
}
#btnSpeak {
  margin-bottom: 2.5%;
}
</style>