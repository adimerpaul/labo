


<template>
  <q-page class="q-pa-xs">
    <q-card>

    <q-form @submit.prevent="buscar" class="q-gutter-md" >
    <div class="row">


    
      <div class="col-3">
      <q-input type="date" label="Fecha ini" v-model="dato.ini" lazy-rules 
        :rules="[ val => val && val.length > 0 || 'Ingrese fecha']"/>
      </div>
      <div class="col-3">
      <q-input type="date" label="Fecha fin" v-model="dato.fin" lazy-rules 
        :rules="[ val => val && val >= dato.ini || 'Ingrese fecha']"/>
      </div>

          <q-btn label="Buscar" type="submit" color="primary" />
    </div>
    </q-form>

    <div>
  <q-card class="bg-white q-pa-none full-width">
    <q-card-section class="bg-blue-grey-8">
      <div class="row items-center no-wrap">
        <div class="col">
          <div class="text-h6 text-white text-center">Formularios</div>
        </div>
      </div>
    </q-card-section>
    <q-card-section>
      <canvas id="bar-chart"></canvas>
    </q-card-section>
  </q-card>
    </div>
    </q-card>
  </q-page>
</template>

<script>
import {date} from 'quasar'
    import Chart from 'chart.js/auto';

export default {
  data(){
    return{
      filter:'',
      total:[],
      formularios:[],
      dato:{ini:date.formatDate(Date.now(),'YYYY-MM-DD'),fin:date.formatDate(Date.now(),'YYYY-MM-DD')},

    }
  },
  created(){

  },
  methods: {
    buscar(){
      this.formularios=[];
      this.total=[];
     this.$axios.post(process.env.API+'/reporte',this.dato).then(res=>{
       res.data.forEach(element => {
         this.formularios.push(element.formulario);
         this.total.push(element.total);
       });
       this.createChart('bar-chart');
     })

    },
    createChart (chartId) {
      const ctx = document.getElementById(chartId);
      const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: this.formularios,
          datasets: [
            {
              label: 'Formularios (cantidad)',
              backgroundColor: 'green',
              //['red','yellow','green','blue','orange','Chocolate','LightCyan'],
              data: this.total
            }
          ]
        },
        options: {
          legend: {
            display: false
          },
          title: {
            display: true,
            text: 'Total de Formularios registraados'
          }
        }
      })
      return myChart;
    }

  
  }
}
</script>