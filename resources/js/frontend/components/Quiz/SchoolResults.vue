<template>
  <div class="container">
    <h1 class="text-center">Školy - Vyhodnotenie testov</h1>
    <table class="table">
      <thead>
      <tr>
        <th>#</th>
        <th>Číslo testu</th>
        <th>Meno</th>
        <th>Priezvisko</th>
        <th>Body</th>
        <th>Ukončenie testu</th>
      </tr>
      </thead>
      <tbody>
        <tr v-for="data in schoolData" :key="index">
          <th scope="row">{{data.id}}</th>
          <td>{{data.school_id}}</td>
          <td>{{data.user_name}}</td>
          <td>{{data.user_surname}}</td>
          <td>{{data.points}}</td>
          <td>{{data.endTime}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
export  default {
  data(){
    return {
      schoolData: []
    }
  },
  methods: {
    getDataSchool(){
      axios.get('/api/school').then((res) =>{
        this.schoolData = res.data
      }).catch((error) =>{
        console.log(error)
      })

    }
  },
  mounted() {
    this.getDataSchool()
  },
}
</script>