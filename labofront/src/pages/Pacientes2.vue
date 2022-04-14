<template>
<q-page class="q-pa-xs">
  <div class="row">
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
    imprimirlaboratorio(paciente,laboratorio){
      var doc = new jsPDF('landscape','cm','letter')
      doc.setFont("arial");
      doc.setFontSize(9);
      // var img = new Image()
      // img.src = 'img/natividad.jpeg'
      // doc.addImage(img, 'jpg', 0.5, -0.3, 7, 3)
      let x=0
      let y=0
      doc.setDrawColor(0);
      doc.setFillColor(255,0,0);
      doc.rect(0, 20, 10, 10, 'FD');
      doc.setFont(undefined, 'bold')
      doc.rect(20, 20, 10, 10);
      doc.text(x+13, y+1,'SERVICIO DE LABORATORIO','center')
      doc.text(x+13, y+1.3,'Bolivar N°753 entre Arica e Iquique','center')
      doc.text(x+13, y+1.6,'Telf: 5254721 Fax: 52-83667','center')
      doc.text(x+13, y+1.9,'Emergencia las 24 horas del dia.','center')
      doc.setTextColor(0,0,0);
      doc.setFont(undefined, 'normal')
      doc.text(x+5, y+2,'PRUEBA')
      // doc.save("Pago"+date.formatDate(Date.now(),'DD-MM-YYYY')+".pdf");
      window.open(doc.output('bloburl'), '_blank');
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
          console.log(r)
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
