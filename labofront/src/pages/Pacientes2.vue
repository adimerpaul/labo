<template>
<q-page class="q-pa-xs">
  <div class="row">
    <div class="col-12">
      <iframe id="docpdf" src="" frameborder="0" style="width: 100%;height: 100vh"></iframe>
    </div>
    <div class="col-12">
      <q-table title1="Listado de pacientes" :rows="pacientes" :columns="columspaciente" :filter="filter">
        <template v-slot:body-cell-seguro="props">
          <q-td :props="props">{{ props.row.seguro.nombre}}</q-td>
        </template>
        <template v-slot:body-cell-laboratorios="props">
          <q-td :props="props" auto-width>
            <ul style="border: 0px;margin: 0px;margin: 0px;list-style: none">
              <li style="border: 0px;margin: 0px;margin: 0px" v-for=" l in props.row.laboratorios" :key="l.id">
                <q-btn @click="imprimirlaboratorio(props.row,l)" size="xs" flat round color="info" icon="print" />
                {{l.fechatoma}}
                {{l.tipo.nombre}}
              </li>
            </ul>
          </q-td>
        </template>
        <template v-slot:body-cell-opciones="props">
          <q-td :props="props" auto-width>
            <q-btn @click="frmlaboratorio(props.row)" size="xs"  icon="science" color="primary" label="agregar" />
          </q-td>
        </template>
        <template v-slot:body-cell-paciente="props">
          <q-td :props="props" auto-width>
            {{ props.row.paciente }}
          </q-td>
        </template>
        <template v-slot:top-right>
          <q-btn color="primary" icon="add_circle" label="crear paciente" />
          <q-input outlined dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
      </q-table>
    </div>
  </div>
  <q-dialog v-model="dialoglaboratorio" full-width full-height>
    <q-card>
      <q-card-section >
        <div class="row">
          <div class="col-5"><div class="text-subtitle2">{{paciente.paciente}}</div></div>
          <div class="col-7"><q-select dense outlined :options="tipos" label="Laboratorio" v-model="tipo" required></q-select></div>
        </div>
      </q-card-section>
      <q-separator/>
      <q-card-section>
        <q-form @submit="createLaboratorio">
        <div class="row">
          <div class="col-12 col-sm-6">
            <q-select dense outlined :options="doctors" label="Doctor" v-model="doctor" required></q-select>
          </div>

          <div class="col-12 col-sm-6">
            <q-input dense outlined label="Tipo Muestra" v-model="laboratorio.tipomuestra" />
          </div>

          <template v-if="tipo.label=='HEMOGRAMA COMPLETO'">
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d1" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Tiempo de cuagulacion" v-model="laboratorio.d2" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Hemoglobina" v-model="laboratorio.d3" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Tiempo de sangria" v-model="laboratorio.d4" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Hematocrito" v-model="laboratorio.d5" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Tiempo de Protrombina" v-model="laboratorio.d6" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d7" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d8" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d9" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d10" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d11" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d12" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d13" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d14" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d15" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d16" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d17" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d18" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d19" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d20" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d21" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d22" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d23" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d24" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d25" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d26" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d27" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d28" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d29" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d30" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d31" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d32" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d33" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d34" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d35" /></div>
          <div class="col-6 col-sm-2"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d36" /></div>
          </template>
          <div class="col-12">
            <q-btn label="Guardar" type="submit" class="full-width" icon="add_circle" color="positive" />
          </div>
        </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-dialog>
</q-page>
</template>
<script>
import {date} from "quasar";
import {jsPDF} from "jspdf";
import $ from 'jquery'


export default {
  data(){
    return{
      dialoglaboratorio:false,
      filter:'',
      pacientes:[],
      paciente:{},
      tipos:[],
      tipo:{},
      doctors:[],
      doctor:{},
      laboratorio:{
        tipomuestra:'COMPLETA',
        fechatoma:date.formatDate(new Date(),'YYYY-MM-DD'),
        horatoma:date.formatDate(new Date(),'HH:mm:ss'),
        d1:'',
        d2:'',
        d3:'',
        d4:'',
        d5:'',
        d6:'',
        d7:'',
        d8:'',
        d9:'',
        d10:'',
        d11:'',
        d12:'',
        d13:'',
        d14:'',
        d15:'',
        d16:'',
        d17:'',
        d18:'',
        d19:'',
        d20:'',
        d21:'',
        d22:'',
        d23:'',
        d24:'',
        d25:'',
        d26:'',
        d27:'',
        d28:'',
        d29:'',
        d30:'',
        d31:'',
        d32:'',
        d33:'',
        d34:'',
        d35:'',
        d36:'',
        d37:'',
        d38:'',
        d39:'',
        d40:'',
        d41:'',
        d42:'',
        d43:'',
        d44:'',
        d45:'',
        tipo_id:'',
        paciente_id:'',
        user_id:this.$store.state.login.user.id,
        doctor_id:'',
      },
      columspaciente:[
        {name:'opciones',field:'opciones',label:'opciones'},
        {name:'paciente',field:'paciente',label:'paciente'},
        {name:'laboratorios',field:'laboratorios',label:'laboratorios'},
        {name:'celular',field:'celular',label:'celular'},
        {name:'seguro',field:'seguro',label:'seguro'},
        {name:'edad',field:'edad',label:'edad'},
      ]
    }
  },
  mounted() {
    var doc = new jsPDF('landscape',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 190, 3, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+195, y+27, 155, 23)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+320, y+10,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+225, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 001',x+323, y+30)
    doc.setTextColor(57,73,171)
    doc.text('HEMOGRAMA COMPLETO',x+270, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA','METODO'],x+196, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text(['ADIEMR PAUL CHAMBI AJATA','ADIMER PAUL CHAMBI AJATA A','COMPLRETA'],x+268, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+305, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+305, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.text(['9999 ','MACULJONO','11'],x+337, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.text('CONTADOR Hematologico MINDRAY BC 5130',x+300, y+47,'center')
    //fin datos paciete
    //inicio datos
    doc.rect(x+195, y+51, 155, 77)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('REFERENCIA',x+253,y+55)
    doc.setFont(undefined, 'normal')
    doc.text(['Globulos rojos',' ','Hemoglobina',' ','Hematocrito',' ','V.E.S.',' ','V.C.M.',' ','Hb.C.M.',' ','C.Hb.C.M.',' ','Globulos Blancos',' ','Plaquetas'],x+209,y+60,'center')
    doc.setTextColor(0,0,0)
    doc.text(['10',' ','20',' ','10',' ','20',' ','10',' ','20',' ','10',' ','20',' ','10'],x+226,y+60,'center')
    doc.setTextColor(57,73,171)
    doc.text(['x10^(12)/L',' ','g/L',' ','L/L',' ','mm.',' ','ft.',' ','pg.',' ','%',' ','x10^(9)L',' ','x10^(9)L'],x+240,y+60,'center')
    doc.text(['Varon 5.1x10^(12)L','Mujer 5.1x10^(12)L','Varon 151-175 g/L','Mujer 141-165 g/L','Varon 0.51-0.57 L/L','Mujer 0.46-0.53 L/L','Varon 15 mm/hora','Mujer 20mm/hora','Varon 83.0-97.0 ft','','27.0-31.0 pg.','','32-36%','','4.5-10.5x10^(9)/L','','105-400x10^(9)L'],x+265,y+60,'center')

    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('REFERENCIA',x+325,y+55)
    doc.setFont(undefined, 'normal')
    doc.text(['Tiempo cuagulacion',' ','Tiempo sangria',' ','Tiempo Protrombina',' ','% Actividad',' ','INR',' ','Grupo factor',' ','Reticulocitos',' ','IPR'],x+296,y+60,'center')
    doc.setTextColor(0,0,0)
    doc.text(['8m 53s',' ','20',' ','10',' ','20',' ','10',' ','20',' ','10',' ','20',' '],x+318,y+60,'center')
    doc.setTextColor(57,73,171)
    doc.text(['5-10min',' ','1-3min',' ','12-13seg',' ','95-100%',' ','0.97-1.04.',' ','',' ','0.5-2%'],x+337,y+60,'center')

    doc.rect(x+195, y+129, 155, 40)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('VALOR REFERENCIAL',x+300,y+133)
    doc.text('RELATIVA               ABSOLUTA               RELATIVA               ABSOLUTA',x+220,y+138)
    doc.setFont(undefined, 'nomral')
    doc.text(['Cayados','Neutrofilos','Eosinofilos','Basofilos','Linfocitos','Monocitos','BLASTOS'],x+209,y+143,'center')
    doc.setTextColor(0,0,0)
    doc.text(['10','10','10','10','10','10','10'],x+225,y+143,'center')
    doc.setTextColor(57,73,171)
    doc.text(['%','%','%','%','%','%','%'],x+233,y+143,'center')
    doc.setTextColor(0,0,0)
    doc.text(['10','10','10','10','10','10','10'],x+256,y+143,'center')
    doc.setTextColor(57,73,171)
    doc.text(['x10^(9)L','x10^(9)L','x10^(9)L','x10^(9)L','x10^(9)L','x10^(9)L'],x+268,y+143,'center')
    doc.text(['0-3%','50-70%','0-3%','0-1%','25-40%','4-8%'],x+295,y+143,'center')
    doc.text(['0.00-0.35x10^(9)L','2.50-7.35x10^(9)L','0.00-0.35x10^(9)L','0.00-0.15x10^(9)L','1.25-4.20x10^(9)L','2.00-8.40x10^(9)L'],x+327,y+143,'center')
    // doc.html('<div style="border:1px solid black;width: 50px;font-size: 3px"><b><i>Adimer paul chambi ajata</i></b></div>', {
    //   callback: function (doc) {
    //     window.open(doc.output('bloburl'), '_blank');
    //   },
    //   x: 10,
    //   y: 10,
    // });
    // doc.save("Pago"+date.formatDate(Date.now(),'DD-MM-YYYY')+".pdf");
    // window.open(doc.output('bloburl'), '_blank');

    $( '#docpdf' ).attr('src', doc.output('datauristring'));
  },
  created() {
    this.mispacientes()
    this.$axios.get(process.env.API+'/tipo').then(res=> {
      this.tipos=[]
      res.data.forEach(r=>{
        let d=r
        d.label=r.nombre
        this.tipos.push(d)
      })
      this.tipo=this.tipos[0]
    })
    this.$axios.get(process.env.API+'/doctor').then(res=> {
      this.doctors=[]
      res.data.forEach(r=>{
        let d=r
        d.label=r.paterno+' '+r.materno+' '+r.nombre
        this.doctors.push(d)
      })
      this.doctor=this.doctors[0]
    })
  },
  methods:{
    imprimirlaboratorio(p,l){
      // console.log(l)
      var doc = new jsPDF('landscape',undefined,'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 190, 3, 70, 20)
      let x=0
      let y=0
      doc.setDrawColor(120);
      doc.rect(195, 27, 155, 30);
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57,73,171);
      doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+320, y+10,'center')
      doc.setTextColor(211,47,47);
      doc.text('N Registro CODEDLAB 000045',x+225, y+25)
      doc.setTextColor(57,73,171);
      doc.text('HEMOGRAMA COMPLETO',x+280, y+30,'center')
      doc.setTextColor(211,47,47);
      doc.text('Form. 001',x+323, y+30)
      doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA','METODO'],x+221, y+33)
      doc.setTextColor(0,0,0);
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente,l.doctor.paterno+' '+l.doctor.materno+' '+l.doctor.nombre,l.tipomuestra],x+275, y+33,'center')
      doc.setTextColor(57,73,171);
      doc.text('CONTADOR Hematologico MINDRAY BC 5130',x+305, y+44,'center')
      // doc.html('<div style="border:1px solid black;width: 50px;font-size: 3px"><b><i>Adimer paul chambi ajata</i></b></div>', {
      //   callback: function (doc) {
      //     window.open(doc.output('bloburl'), '_blank');
      //   },
      //   x: 10,
      //   y: 10,
      // });

      // doc.save("Pago"+date.formatDate(Date.now(),'DD-MM-YYYY')+".pdf");
      // window.open(doc.output('bloburl'), '_blank');
      $( '#docpdf' ).attr('src', doc.output('datauristring'));

    },
    createLaboratorio(){
      this.laboratorio.tipo_id=this.tipo.id
      this.laboratorio.paciente_id=this.paciente.id
      this.laboratorio.doctor_id=this.doctor.id
      this.$axios.post(process.env.API+'/laboratorio',this.laboratorio).then(res=> {
        // console.log(res.data)
        this.mispacientes()
      })
    },
    frmlaboratorio(paciente){
      this.paciente=paciente
      this.dialoglaboratorio=true
    },
    mispacientes(){
      this.$q.loading.show()
      this.$axios.get(process.env.API+'/paciente').then(res=>{
        this.$q.loading.hide()
        this.pacientes=[]
        res.data.forEach(r=>{
          let d=r
          // console.log(r)
          d.paciente=r.paterno+' '+r.materno+' '+r.nombre
          this.pacientes.push(d)
        })
      })
    }
  }
}
</script>

<style scoped>

</style>
