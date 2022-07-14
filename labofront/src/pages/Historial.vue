


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
          <q-btn label="imrpesion" color="info" icon="print" @click="impresion"/>
          <q-btn label="elemento" color="cyan" icon="print" @click="conelemento"/>
          <q-btn label="R Laboratorio" color="deep-purple-6" icon="print" @click="reslabo"/>
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
    reslabo(){
      this.$axios.post(process.env.API+'/resumenlab',this.dato).then(res=>{
        console.log(res.data)
                var doc = new jsPDF('P','mm','letter')
        doc.setFont("arial");
        var img = new Image()
                let x=0
        let y=0
        function head(fechas){
        doc.setFontSize(10);
        img.src = 'img/natividad.jpeg'
        doc.addImage(img, 'jpg', 5, 2, 70, 20)

        //inicio datos paciete
        doc.setDrawColor(120);
        doc.rect(x+5, y+27, 205, 1)
        doc.setFont(undefined, 'bold')
        doc.setTextColor(57,73,171)
        doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
        doc.setTextColor(0,0,0)
        doc.text('REPORTE RESUMEN LABORATORIO: '+fechas.ini +' al '+ fechas.fin,x+100, y+35,'center')
        doc.setTextColor(0,0,0)

        doc.setTextColor(0,0,20)
        doc.text('FECHA ',x+10, y+40,'left')
        doc.text('CODIGO ',x+25,y+40,'left')
        doc.text('PACIENTE ',x+45,y+40,'left')
        doc.text('LABORATORIO ',x+95,y+40,'left')
        doc.text('DOCTOR ',x+150,y+40,'left')
        y=40
        }
        head(this.dato)
        doc.setFontSize(6);
        doc.setFont(undefined, 'normal')
        res.data.forEach(r => {
          y+=5
          doc.text(r.fechatoma,x+7, y,'left')
          doc.text(r.solicitud+'',x+25,y,'left')    
          doc.text(r.paciente.nombre+' '+r.paciente.paterno+' '+ r.paciente.materno,x+45,y,'left')    
          doc.text(r.tipo.nombre+'',x+95,y,'left')    
          doc.text(r.doctor.nombre+' '+r.doctor.paterno+' '+ r.doctor.materno,x+150,y,'left')    
          if(y+5>270){
            y=0
            doc.addPage()
            head(this.dato)
          doc.setFont(undefined, 'normal')
          doc.setFontSize(6);
          }      
        });


        doc.output('save','ReporteElemento.pdf');
      })

    },
        conelemento(){
       this.$axios.post(process.env.API+'/repelemento',this.dato).then(res=>{
        console.log(res.data)
        var doc = new jsPDF('P','mm','letter')
        doc.setFont("arial");
        var img = new Image()
                let x=0
        let y=0
        function head(fechas){
        doc.setFontSize(10);
        img.src = 'img/natividad.jpeg'
        doc.addImage(img, 'jpg', 5, 2, 70, 20)

        //inicio datos paciete
        doc.setDrawColor(120);
        doc.rect(x+5, y+27, 205, 1)
        doc.setFont(undefined, 'bold')
        doc.setTextColor(57,73,171)
        doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
        doc.setTextColor(0,0,0)
        doc.text('REPORTE DE ELEMENTO CANTIDAD POR LABORATORIO: '+fechas.ini +' al '+ fechas.fin,x+100, y+35,'center')
        doc.setTextColor(0,0,0)

        doc.setTextColor(0,0,20)
        doc.text('DETALLE ',x+25, y+40,'center')
        doc.text('CANTIDAD ',x+150,y+40,'center')
        y=40
        }
        head(this.dato)
        doc.setFont(undefined, 'normal')
        res.data.forEach(r => {
          y+=5
          doc.text(r.nombre,x+25, y,'left')
          doc.text(r.cantidad+'',x+150,y,'left')    
          if(y+5>270){
            y=0
            doc.addPage()
            head(this.dato)
          }      
        });


        doc.output('save','ReporteElemento.pdf');
      })

    },
    buscar(){
      this.formularios=[];
      this.total=[];
      this.reporte=[];
     this.$axios.post(process.env.API+'/reporte',this.dato).then(res=>{
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
        doc.text('REPORTE DE CANTIDAD DE FORMULARIOS FECHA: '+this.dato.ini +' al '+ this.dato.fin,x+100, y+35,'center')
        doc.setTextColor(0,0,0)

        doc.setTextColor(0,0,20)
        doc.text('FORMULARIO ',x+25, y+40,'center')
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