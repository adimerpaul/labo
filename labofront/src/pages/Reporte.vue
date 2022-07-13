


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
          <q-btn label="impresion" color="info" icon="print" @click="impresion"/>
          <q-btn label="elemento" color="cyan" icon="print" @click="conelemento"/>
    </div>
    </q-form>

    <div>
  <q-card class="bg-white q-pa-none full-width">
    <q-card-section class="bg-blue-grey-8">
      <div class="row items-center no-wrap">
        <div class="col">
          <div class="text-h6 text-white text-center">SEGUROS</div>
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
import {jsPDF} from "jspdf";
import $ from 'jquery'
export default {
  data(){
    return{
      filter:'',
      total:[],
      formularios:[],
      reporte:[],
      dato:{ini:date.formatDate(Date.now(),'YYYY-MM-DD'),fin:date.formatDate(Date.now(),'YYYY-MM-DD')},

    }
  },
  created(){

  },
  methods: {
    conelemento(){
      this.$axios.post(process.env.API+'/repelemento',this.dato).then(res=>{
        console.log(res.data)
        var doc = new jsPDF('P','mm','letter')
        doc.setFont("arial");
        doc.setFontSize(10);
        var img = new Image()
        img.src = 'img/natividad.jpeg'
        doc.addImage(img, 'jpg', 5, 2, 70, 20)
        let x=0
        let y=0
        //inicio datos paciete
        doc.setDrawColor(120);
        doc.rect(x+5, y+27, 205, 1)
        doc.setFont(undefined, 'bold')
        doc.setTextColor(57,73,171)
        doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
        doc.setTextColor(0,0,0)
        doc.text('REPORTE DE ELEMENTO CANTIDAD POR LABORATORIO: '+this.dato.ini +' al '+ this.dato.fin,x+100, y+35,'center')
        doc.setTextColor(0,0,0)

        doc.setTextColor(0,0,20)
        doc.text('DETALLE ',x+25, y+40,'center')
        doc.text('CANTIDAD ',x+150,y+40,'center')
        y=40
        doc.setFont(undefined, 'normal')
        res.data.forEach(r => {
          y+=5
          doc.text(r.nombre,x+25, y,'left')
          doc.text(r.cantidad+'',x+150,y,'left')          
        });


        doc.output('save','ReporteElemento.pdf');
      })

    },
    buscar(){
      this.formularios=[];
      this.total=[];
      this.reporte=[];
     this.$axios.post(process.env.API+'/repseguro',this.dato).then(res=>{
      this.reporte=res.data;
       res.data.forEach(element => {
        
         this.formularios.push(element.nombre);
         this.total.push(element.cantidad);
       });
       this.createChart('bar-chart');
     })

    },
    impresion(){
        var doc = new jsPDF('P','mm','letter')
        doc.setFont("arial");
        doc.setFontSize(10);
        var img = new Image()
        img.src = 'img/natividad.jpeg'
        doc.addImage(img, 'jpg', 5, 2, 70, 20)
        let x=0
        let y=0
        //inicio datos paciete
        doc.setDrawColor(120);
        doc.rect(x+5, y+27, 205, 1)
        doc.setFont(undefined, 'bold')
        doc.setTextColor(57,73,171)
        doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
        doc.setTextColor(0,0,0)
        doc.text('REPORTE DE CANTIDAD POR SEGURO FECHA: '+this.dato.ini +' al '+ this.dato.fin,x+100, y+35,'center')
        doc.setTextColor(0,0,0)

        doc.setTextColor(0,0,20)
        doc.text('SEGURO ',x+25, y+40,'center')
        doc.text('CANTIDAD ',x+150,y+40,'center')
        y=40
        doc.setFont(undefined, 'normal')
        this.reporte.forEach(r => {
          y+=5
          doc.text(r.nombre,x+25, y,'left')
          doc.text(r.cantidad+'',x+150,y,'left')          
        });


        doc.output('save','ReporteFormularios.pdf');
    },
    createChart (chartId) {
      const ctx = document.getElementById(chartId);
      const myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: this.formularios,
          datasets: [
            {
              label: 'Formularios (cantidad)',
              backgroundColor: 
              ['red','yellow','green','blue','orange','Chocolate','LightCyan'],
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