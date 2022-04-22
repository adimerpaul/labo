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
          <q-btn color="primary" icon="add_circle" label="crear paciente" @click="alert = true"  />
          <q-input outlined dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
      </q-table>
    </div>
  </div>
      <q-dialog v-model="alert">
      <q-card>
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Registro</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <q-form
            @submit.prevent="onSubmit"
            class="q-gutter-md"
          >
            <q-input
              filled
              v-model="dato.ci"
              label="Cedula Identidad"
              hint="Numero de carnet"

            />
            <q-input
              filled
              v-model="dato.nombre"
              label="Nombre "
              hint="Ingresar Nombre "
                  style="text-transform: uppercase;"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="dato.paterno"
              label="Ap Paterno "
              hint="Ingresar Paterno "
                  style="text-transform: uppercase;"
            />
            <q-input
              filled
              v-model="dato.materno"
              label="Ap Materno "
              hint="Ingresar Materno "
                  style="text-transform: uppercase;"
            />
            <q-input
              filled
              type="date"
              v-model="dato.fechanac"
              label="Fecha Nac"

            />
                        <q-input
              filled
              type="number"
              v-model="dato.edad"
              label="Edad"

            />
            <q-radio v-model="dato.sexo" val="Masculino" label="Masculino" />
            <q-radio v-model="dato.sexo" val="Femenino" label="Femenino" />
            <q-input
              filled
              v-model="dato.celular"
              label="Celular (Whatsapp)"
              hint="Numero de celular"

            />
            <q-select  outlined v-model="seguro" :options="seguros" label="Seguro" />
            <div>
              <q-btn label="Crear" type="submit" color="positive" icon="add_circle"/>
                <q-btn  label="Cancelar" icon="delete" color="negative" v-close-popup />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

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
          <div class="col-6 col-sm-6"><q-input dense outlined label="Globulos rojos" v-model="laboratorio.d1" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Tiempo de cuagulacion" v-model="laboratorio.d2" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Hemoglobina" v-model="laboratorio.d3" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Tiempo de sangria" v-model="laboratorio.d4" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Hematocrito" v-model="laboratorio.d5" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Tiempo de Protrombina" v-model="laboratorio.d6" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="V.E.S." v-model="laboratorio.d7" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="% Actividad" v-model="laboratorio.d8" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="V.C.M." v-model="laboratorio.d9" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="INR" v-model="laboratorio.d10" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Hb.C.M." v-model="laboratorio.d11" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Grupo Factor" v-model="laboratorio.d12" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="C.Hb.C.M." v-model="laboratorio.d13" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Reticulocitos" v-model="laboratorio.d14" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Globolus Blancos" v-model="laboratorio.d15" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="IPR" v-model="laboratorio.d16" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Plaquetas" v-model="laboratorio.d17" /></div>
          <div class="col-6 col-sm-6"></div>

          <div class="col-6 col-sm-6"><q-input dense outlined label="Cayados" v-model="laboratorio.d18" @keyup="valcien" type="number"/></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="x10^9/L" v-model="laboratorio.d19" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Neutrofilos" v-model="laboratorio.d20" @keyup="valcien" type="number"/></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="x10^9/L" v-model="laboratorio.d21" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Eosinofilos" v-model="laboratorio.d22" @keyup="valcien" type="number"/></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="x10^9/L" v-model="laboratorio.d23" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Basofilos" v-model="laboratorio.d24" @keyup="valcien" type="number"/></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="x10^9/L" v-model="laboratorio.d25" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Linfocitos" v-model="laboratorio.d26" @keyup="valcien" type="number"/></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="x10^9/L" v-model="laboratorio.d27" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Monocitos" v-model="laboratorio.d28" @keyup="valcien" type="number"/></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="x10^9/L" v-model="laboratorio.d29" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="BLASTOS" v-model="laboratorio.d30" @keyup="valcien" type="number"/></div>

          <div class="col-6 col-sm-12"><q-input dense outlined label="Serie Rojas" v-model="laboratorio.d31" /></div>
          <div class="col-6 col-sm-12"><q-input dense outlined label="Serie Blancas" v-model="laboratorio.d32" /></div>
          <div class="col-6 col-sm-12"><q-input dense outlined label="Serie Plaquetarias" v-model="laboratorio.d33" /></div>

          <div class="col-6 col-sm-6"><q-select dense outlined :options="usuarios" label="Responsable" v-model="user" required></q-select></div>


          <div class="col-6 col-sm-3"><q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma" /></div>
          <div class="col-6 col-sm-3"><q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma" /></div>
          </template>
                              <template v-if="tipo.label=='QUIMICA SANGUINIA'">
          <div class="col-6 col-sm-6"><q-input dense outlined label="Glicemia" v-model="laboratorio.d1" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Fosfatasa alcalina" v-model="laboratorio.d2" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Creatina" v-model="laboratorio.d3" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Fosfatasa alcalina" v-model="laboratorio.d4" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Urea" v-model="laboratorio.d5" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Transamisas GOT" v-model="laboratorio.d6" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="NUS-BUN" v-model="laboratorio.d7" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Transamisas GPT" v-model="laboratorio.d8" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Acido Urico" v-model="laboratorio.d9" /></div>
          <div class="col-6 col-sm-6"></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Proteinas Totales" v-model="laboratorio.d10" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Trigliceridos" v-model="laboratorio.d11" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Albumina" v-model="laboratorio.d12" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Colesterol Total" v-model="laboratorio.d13" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Globulina" v-model="laboratorio.d14" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="HDL-Col" v-model="laboratorio.d15" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Amilasa" v-model="laboratorio.d16" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="LDL-Col." v-model="laboratorio.d17" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Lipasa" v-model="laboratorio.d18" /></div>
          <div class="col-6 col-sm-6"></div>

          <div class="col-6 col-sm-6"><q-input dense outlined label="Bilirrubina Total" v-model="laboratorio.d19" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Sodio" v-model="laboratorio.d20" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Bilirrubina Directa" v-model="laboratorio.d21" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Cloro" v-model="laboratorio.d22" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Bilirrubina Indirecta" v-model="laboratorio.d23" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Potasio" v-model="laboratorio.d24" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="CK-MB" v-model="laboratorio.d25" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Calcio" v-model="laboratorio.d26" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="LDH" v-model="laboratorio.d27" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Magnesio" v-model="laboratorio.d28" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Hierro" v-model="laboratorio.d29" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Fosforo" v-model="laboratorio.d30" /></div>
          <div class="col-6 col-sm-12"><q-input dense outlined label="Observaciones" v-model="laboratorio.d31" /></div>

          <div class="col-6 col-sm-6"><q-select dense outlined :options="usuarios" label="Responsable" v-model="user" required></q-select></div>           

          <div class="col-6 col-sm-3"><q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma" /></div>
          <div class="col-6 col-sm-3"><q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma" /></div>
          </template>

                                        <template v-if="tipo.label=='EXAMEN GENERAL DE ORINA'">
          <div class="col-6 col-sm-6"><q-input dense outlined label="Color" v-model="laboratorio.d1" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Proteinas" v-model="laboratorio.d2" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Olor" v-model="laboratorio.d3" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Glucosa" v-model="laboratorio.d4" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Aspecto" v-model="laboratorio.d5" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="C cetonicos" v-model="laboratorio.d6" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Espuma" v-model="laboratorio.d7" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Bilirrubina" v-model="laboratorio.d8" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Deposito" v-model="laboratorio.d9" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Hemoglogina" v-model="laboratorio.d10" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Densidad" v-model="laboratorio.d11" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Urobilina" v-model="laboratorio.d12" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Reaccion" v-model="laboratorio.d13" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Nitrinos" v-model="laboratorio.d14" /></div>
          <div class="col-12 col-sm-12"></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Celulas epiteleales" v-model="laboratorio.d15" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Hialino" v-model="laboratorio.d16" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Celulas de transicion" v-model="laboratorio.d17" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Granuloso" v-model="laboratorio.d18" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Celulas clave" v-model="laboratorio.d19" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Epiteliales" v-model="laboratorio.d20" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Leucocitos" v-model="laboratorio.d21" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Eritrocitario" v-model="laboratorio.d22" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Eritrocis" v-model="laboratorio.d23" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Leucositario" v-model="laboratorio.d24" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Bacterias" v-model="laboratorio.d25" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Cereos" v-model="laboratorio.d26" /></div>
          <div class="col-6 col-sm-6"></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Mixtos" v-model="laboratorio.d27" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Uratros amorfos" v-model="laboratorio.d28" /></div>
          <div class="col-6 col-sm-6"></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Fosfato amorfo" v-model="laboratorio.d29" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Filamento mucoso" v-model="laboratorio.d30" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Oxalato de calcio" v-model="laboratorio.d31" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Piocitos" v-model="laboratorio.d32" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Fosfato de calcio" v-model="laboratorio.d33" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Levaduras" v-model="laboratorio.d34" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Acido Urico" v-model="laboratorio.d35" /></div>
          <div class="col-6 col-sm-6"><q-input dense outlined label="Esporas micoticas" v-model="laboratorio.d36" /></div>
          <div class="col-6 col-sm-12"><q-input dense outlined label="Observaciones" v-model="laboratorio.d37" /></div>

          <div class="col-6 col-sm-6"><q-select dense outlined :options="usuarios" label="Responsable" v-model="user" required></q-select></div>           

          <div class="col-6 col-sm-3"><q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma" /></div>
          <div class="col-6 col-sm-3"><q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma" /></div>
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
      alert:false,
      dato:{},
      pacientes:[],
      paciente:{},
      tipos:[],
      tipo:{},
      doctors:[],
      doctor:{},
      user:{},
      laboratorio:{
        tipomuestra:'COMPLETA',
        fechatoma:date.formatDate(new Date(),'YYYY-MM-DD'),
        horatoma:date.formatDate(new Date(),'HH:mm'),
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
        responsable:'',
        tipo_id:'',
        paciente_id:'',
        user_id:this.$store.state.login.user.id,
        doctor_id:'',
        usuarios:[]
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


    //this.sanguinea();
    this.listusers();
    this.listseguro();
  },

  created() {
    this.embarazo;
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
        sanguinea(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 002',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('QUIMICA SANGUINEA',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.text([p.edad+'',p.sexo,p.id+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+48, 205, 73)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('          PRUEBA                      VALOR                   REFRENCIA                   PRUEBA                     VALOR                 VALOR',x+6,y+52)
    doc.setFont(undefined, 'normal')
    doc.text(['Glicemia','Creatina','Urea','NUS-BUN','Acido Urico','Proteinas Totales','Albumina','Globulina','Amilasa','Lipasa','Bilirrubina Total','Bilirrubina Directa','Bilirrubina Indirecta','CK-MB','LDH','Hierro'],x+8,y+58) 
    doc.text(['70-105mg/dl','0.7-1.5mmg/dl','15-45mg/dl','7-18mg/dl','2.6-7.2mg/dl','6.2-8.5g/dl','3.5-5.3g/dl','2.8-3.5g/dl','menor a 120UI/L','10-150UI/L','hasta 1.2 mg/dl','hasta 0.3 mg/dl','hasta 0.9 mg/dl','0-24 UI/L','200-480 UI/L','50-170ug/dl'],x+80,y+58,'left')
    doc.text(['Fosfatasa alcalina','Fosfatasa alcalina','Transamisa GOT','Transamisas GPT','LIPIDOGRAMA','Trigliceridos','Colesterol Total','HDL-Col','LDL-Col','ELECTROLITOS','Sodio','Cloro','Potasio','Calcio','Magnesio','Fosforo'],x+110,y+58,'left')
    doc.text(['adultos hasta 100UI/L','niños 100-400UI/L','hasta 40UI/L','hasta 41UI/L','','10-160mg/dl','menor 200mg/dl','35-65mg/dl','hasta 150mg/dl','','135-155mEq/L','98-106 mEq/L','3.4-5.3 mEq/L','8.5-10.5mg/dl','1.7-2.4mg/dl','2.5-4.5mg/dl'],x+175,y+58,'left')
    doc.setTextColor(0,0,0)
    doc.text([l.d1,l.d3,l.d5,l.d7,l.d9,l.d10,l.d12,l.d14,l.d16,l.d18,l.d19,l.d21,l.d23,l.d25,l.d27,l.d29],x+55,y+58) 
    doc.text([l.d2,l.d4,l.d6,l.d8,'',l.d11,l.d13,l.d15,l.d17,'',l.d20,l.d22,l.d24,l.d26,l.d28,l.d30],x+160,y+58) 


    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('OBSERVACIONES:',x+6,y+125)
    doc.setFont(undefined, 'normal')
    doc.text(l.d31,x+6,y+128,'left')

    doc.rect(x+5, y+122, 205, 25)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+135)
    doc.setFont(undefined, 'NORMAL')
    doc.text(l.responsable,x+8,y+140)
    doc.setFont(undefined, 'normal')
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+135,'center')
    doc.setTextColor(0,0,0)
    doc.text([l.fechatoma,l.horatoma,date.formatDate(new Date(),'YYYY-MM-DD')],x+170,y+135,'left')

    $( '#docpdf' ).attr('src', doc.output('datauristring'));
    },
        valcien(){
              let total=0
        if (this.laboratorio.d18 == undefined || this.laboratorio.d18=='') this.laboratorio.d18=0;
        if (this.laboratorio.d20 == undefined || this.laboratorio.d20=='') this.laboratorio.d20=0;
        if (this.laboratorio.d22 == undefined || this.laboratorio.d22=='') this.laboratorio.d22=0;
        if (this.laboratorio.d24 == undefined || this.laboratorio.d24=='') this.laboratorio.d24=0;
        if (this.laboratorio.d26 == undefined || this.laboratorio.d26=='') this.laboratorio.d26=0;
        if (this.laboratorio.d28 == undefined || this.laboratorio.d28=='') this.laboratorio.d28=0;
        if (this.laboratorio.d30 == undefined || this.laboratorio.d30=='') this.laboratorio.d30=0;

        total=parseFloat(this.laboratorio.d18) + parseFloat(this.laboratorio.d20) + parseFloat(this.laboratorio.d22) + parseFloat(this.laboratorio.d24) + parseFloat(this.laboratorio.d26)
        + parseFloat(this.laboratorio.d28) + parseFloat(this.laboratorio.d30)
    if(total!=100 )
    {
         this.$q.notify({
              message: 'No suma el 100 % ',
              icon: 'info',
             color:'red'
            })
    }
    },
          serologia(){
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
    doc.text('Form. 005',x+323, y+30)
    doc.setTextColor(57,73,171)
    doc.text('INMUNOENSAYO DE FLUORESCENCIA',x+270, y+30,'center')
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
    doc.setTextColor(211,47,47)
    doc.text('CONTADOR Hematologico MINDRAY BC 5130',x+260, y+47,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+195, y+53, 155, 125)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(211,47,47)
    doc.text('',x+233,y+55)
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.text('       UNIDAD         TIPO MUESTRA      VALOR REFE',x+250,y+60)
    doc.setFontSize(12);

    doc.setFont(undefined, 'normal')
    doc.setFontSize(11);
    doc.text(['DIMEROS D',
      '',
      'FERRITINA',
      '',
      'IL-6',
      '',
      'PSA CUANTITATIVO',
      '',
      'PCR CUANTITATIVO',
      '',
      'TROPONINA I ',
      ' ',
      ' ',
      'B - HCG ',
      ' ',
      ' ',
      'PROCALCITONINA',
    ],x+197,y+65,'left')
    doc.setTextColor(0,0,0)
    doc.setFontSize(11);
    doc.setFont(undefined, 'normal')
    doc.setFontSize(11);
    doc.text([
      'DAS',
      '',
      'DAS',
      '',
      'DAS',
      '',
      'DAS',
      '',
      'DAS',
      '',
      'DAS',
      '',
      ' ',
      'DAS',
      ' ',
      ' ',
      'DAS',
      '',
    ],x+241,y+65,'center')
    doc.setTextColor(57,73,171)
    doc.text([
      'ng/ml ',
      '',
      'ng/ml ',
      '',
      'ng/ml',
      '',
      'pg/ml',
      '',
      'ng/ml',
      '',
      'mg/L',
      '',
      '',
      'mlU/ml ',
      '',
      ' ',
      'ng/ml',
      '',
    ],x+254,y+65,'center')
    doc.text([
      'Plasma Citratado',
      '',
      'Suero',
      '',
      'Suero/plasma',
      '',
      'Suero',
      '',
      'Sangre Entera ',
      '',
      'Suero',
      '',
      '',
      'Suero',
      '',
      ' ',
      'Suero',
      '',
    ],x+275,y+65,'center')
    doc.setFontSize(11);
    doc.text([
      'Hasta 500 ng/ml',
      '',
      '30-350 ng/ml Varon',
      '20-250 ng/ml Mujer',
      '7 pg/ml',
      '',
      'Menor a 4 ng/ml',
      '',
      'Menor a 10 mg/L',
      '',
      '0.0 - 0.11 ng/ml',
      '',
      'Mujer no Embarazada',
      'menor a 10 mlU/ml',
      'Mujer en postmenopausia',
      'menor a 10 mlU/ml',
      'Suero',
      '',
    ],x+315,y+65,'center')
    doc.setFontSize(11);
5

    doc.rect(x+195, y+180, 155, 30)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+205,y+185,'left')
    doc.setFontSize(10);
    doc.setLineHeightFactor(1.5)
    doc.text(['Fecha toma de Muestra','Fecha Entrega de Resultado','Hora toma Muestra'],x+280,y+185,'left')

    $( '#docpdf' ).attr('src', doc.output('datauristring'));
      },
      hemograma(p,l){
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
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+268, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+305, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+305, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.text([p.edad+'',p.sexo,p.id+''],x+337, y+35,'center')
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
    doc.text([l.d1,' ',l.d3,' ',l.d5,' ',l.d7,' ',l.d9,' ',l.d11,' ',l.d13,' ',l.d15,' ',l.d17],x+226,y+60,'center')
    doc.setTextColor(57,73,171)
    doc.text(['x10^(12)/L',' ','g/L',' ','L/L',' ','mm.',' ','ft.',' ','pg.',' ','%',' ','x10^(9)L',' ','x10^(9)L'],x+240,y+60,'center')
    doc.text(['Varon 5.1x10^(12)L','Mujer 5.1x10^(12)L','Varon 151-175 g/L','Mujer 141-165 g/L','Varon 0.51-0.57 L/L','Mujer 0.46-0.53 L/L','Varon 15 mm/hora','Mujer 20mm/hora','Varon 83.0-97.0 ft','','27.0-31.0 pg.','','32-36%','','4.5-10.5x10^(9)/L','','105-400x10^(9)L'],x+265,y+60,'center')

    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('REFERENCIA',x+325,y+55)
    doc.setFont(undefined, 'normal')
    doc.text(['Tiempo cuagulacion',' ','Tiempo sangria',' ','Tiempo Protrombina',' ','% Actividad',' ','INR',' ','Grupo factor',' ','Reticulocitos',' ','IPR'],x+296,y+60,'center')
    doc.setTextColor(0,0,0)
    doc.text([l.d2,' ',l.d4,' ',l.d6,' ',l.d8,' ',l.d10,' ',l.d12,' ',l.d14,' ',l.d16,' '],x+318,y+60,'center')
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
    doc.text([l.d18,l.d20,l.d22,l.d24,l.d26,l.d28,l.d30],x+225,y+143,'center')
    doc.setTextColor(57,73,171)
    doc.text(['%','%','%','%','%','%','%'],x+233,y+143,'center')
    doc.setTextColor(0,0,0)
    doc.text([l.d19,l.d21,l.d23,l.d25,l.d27,l.d29],x+256,y+143,'center')
    doc.setTextColor(57,73,171)
    doc.text(['x10^(9)L','x10^(9)L','x10^(9)L','x10^(9)L','x10^(9)L','x10^(9)L'],x+268,y+143,'center')
    doc.text(['0-3%','50-70%','0-3%','0-1%','25-40%','4-8%'],x+295,y+143,'center')
    doc.text(['0.00-0.35x10^(9)L','2.50-7.35x10^(9)L','0.00-0.35x10^(9)L','0.00-0.15x10^(9)L','1.25-4.20x10^(9)L','2.00-8.40x10^(9)L'],x+327,y+143,'center')
    doc.rect(x+195, y+170, 155, 15)

    doc.text('MORFOLOGIA DE FROTIS DE SANGRE PERIFERICA',x+265,y+173,'center')
    doc.text(['Serie Rojas','Serie Blancas','Serie Plaquetarias'],x+223,y+176,'right')
    doc.setTextColor(0,0,0)
    doc.text([l.d31,l.d32,l.d33],x+228,y+176,'left')
    doc.setTextColor(57,73,171)
    doc.rect(x+195, y+186, 155, 15)

    doc.text('RESPONSABLE',x+205,y+190,'left')
    doc.text(l.responsable,x+215,y+195,'center')
    doc.text(['Fecha de Toma de Muestra','Hora de Toma de Muestra:','Fecha de Entrega Resultado'],x+265,y+190,'left')
    doc.text([l.fechatoma,l.horatoma,date.formatDate(new Date(),'YYYY-MM-DD')],x+310,y+190,'left')
   // doc.text(l.responsable,x+235,y+178,'right')

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
      ensayo(){
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
    doc.text('Form. 0010',x+323, y+30)
    doc.setTextColor(57,73,171)
    doc.text('',x+270, y+30,'center')
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
    doc.setTextColor(211,47,47)
    doc.text('CONTADOR Hematologico MINDRAY BC 5130',x+260, y+47,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+195, y+51, 155, 110)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(211,47,47)
    doc.text('METODO: INMUNOENSAYO DE FLUORESCENCIA (FIA)',x+233,y+55)
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.text('UNIDAD   TIPO MUESTRA   VALOR REF',x+260,y+60)
    doc.setFontSize(12);

    doc.setFont(undefined, 'normal')
    doc.setFontSize(12);
    doc.text(['DIMEROS D',' ','FERRITINA',' ','IL-6',' ','PSA CUANTITATIVO',' ','PCR CUANTITATIVO',' ','TROPONINA I',' ',' ','B - HCG',' ',' ',' ','PROCALCITONINA'],x+200,y+65,'left')
    doc.setTextColor(0,0,0)
        /*if(($row->d1<0 || $row->d1>500)&& $row->d1!='')
        doc.setTextColor(195,47,47)
        else
        doc.setTextColor(0,0,0)

        if($row->paciente->sexo=='Masculino' && ($row->d2<30 || $row->d2>350)&& $row->d2!='')$cd2='color:#ff726f;';
        if($row->paciente->sexo=='Femenino' && ($row->d2<20 || $row->d2>250)&& $row->d2!='')$cd2='color:#ff726f;';
        if(($row->d3<0 || $row->d3>7)&& $row->d3!='')$cd3='color:#ff726f;';
        if(($row->d4<0 || $row->d4>4)&& $row->d4!='')$cd4='color:#ff726f;';
        if(($row->d5<0 || $row->d5>10)&& $row->d5!='')$cd5='color:#ff726f;';
        if(($row->d6<0 || $row->d6>0.11)&& $row->d6!='')$cd6='color:#ff726f;';
        if(($row->d7<0 || $row->d7>10)&& $row->d7!='')$cd7='color:#ff726f;';*/
    doc.text(['100',' ','100',' ','100',' ','100',' ','100',' ','100',' ',' ','100',' ',' ',' ','100'],x+250,y+65,'center')
    doc.text(['ng/ml',' ','ng/ml',' ','pg/ml',' ','ng/ml',' ','mg/ml',' ','ng/ml',' ',' ','mlU/ml',' ',' ',' ','ng/ml'],x+270,y+65,'center')
    doc.setFontSize(10);
    doc.text(['Plasma Citratado',' ','Suero',' ',' ','Suero/plasma',' ','Suero',' ',' ','Sangre Entera',' ','Suero',' ',' ',' ','Suero',' ',' ',' ','Suero'],x+290,y+65,'center')
    doc.setTextColor(57,73,171)
    doc.setFontSize(8);
    doc.text(['Hasta 500 ng/ml','','30-350 ng/ml Varon','20-250 ng/ml Mujer','',' ','7 pg/ml','',' ','Menor a 4 ng/ml','',' ','<10 mg/L','',' ','0.0 - 0.11 ng/ml','',' ',' ','Mujer No Embarazada < 10 mlU/ml','Mujer en postmenopausia <10 mlU/ml','','PCT < 0.5 Es posible infeccion' ,'  bacteriana local','PCT 0.5 - 2 Posible infeccion','PCT 2 -10 Es muy probable ',' infeccion(sepsia) a menores que se ','conozcan otras causas','PCT > 10 Sepsia Bacteriana severa',' o shock septico'],x+305,y+65,'left')
    doc.setFontSize(10);


    doc.rect(x+195, y+160, 155, 30)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+205,y+165,'left')
    doc.setFontSize(10);
    doc.text(['Fecha toma de Muestra','Fecha Entrega de Resultado','Hora toma Muestra'],x+280,y+165,'left')

    $( '#docpdf' ).attr('src', doc.output('datauristring'));
      },
      reserologia(){
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
    doc.text('Form. 005',x+323, y+30)
    doc.setTextColor(57,73,171)
    doc.text('',x+270, y+30,'center')
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
    doc.setTextColor(211,47,47)
    doc.text('CONTADOR Hematologico MINDRAY BC 5130',x+260, y+47,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+195, y+51, 155, 25)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(211,47,47)
    doc.text('PRUEBA RAPIDA ANTIGENOS SARS COV 2',x+233,y+55)
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.text('METODO:  INMUNOGRAMATOGRAFIA CUALITATIVA',x+220,y+60)
    doc.setFontSize(12);

    doc.setFont(undefined, 'normal')
    doc.setFontSize(10);
    doc.setTextColor(211,47,47)
    doc.text(['ANTIGENO','SARS COV 2'],x+250,y+70,'left')
    doc.setTextColor(57,73,171)
    doc.text('POSITIVO',x+285,y+73,'left')
    doc.setTextColor(0,0,0)
    doc.rect(x+195, y+75, 155, 72)

    doc.text('FUNDAMENTO E INTERPRETACION DE LOS RESULTADO',x+220,y+80)
    doc.text(['La prueba rapida de Antigeno Nasal puede revelar una Infeccion activa de SARS-COV-2.','El test proporciona un resultado de "POSITIVO" o "NEGATIVO"','Los antigenos son parte de la estructura del virus, como las proteinas de espiga.'],x+205,y+85)
    doc.text(['La muestra de Hisopado nasofaringeo pasa por una linea que detecta antigenos y cambia de color, ','durante la prueba el anticuerpo monocianal de raton anti-SARS-CoV-2 en la muestra interactua con ','el Anticuerpo monocianal lgG anti-COVID-19 conjugando con particulas de color que forman un ','complejo de particulas de antigeno-anticuerpo. Este complejo migra en la menbrana por accion ','capilar hasta la linea de prueba, donde aera capturado por el anticuerpo por el anticuerpo monocianal ','anti-SARS-CoV-2 de raton. Una linea de prueba coloreada seria visible en la ventana de resultados ','si los antigenos del SARS-CoV-2 estan presentes en la muestra.'],x+205,y+100)
    doc.text(['Funciona mejor en la etapa inicial con una CARGA VIRAL ALTA y sintomalogia hasta los 10 dias.','Los resultados Negativos indica ausencia de Antigenos detectables de SARS-CoV-2 y cuando la ','carga viral o la cantidad de antigeno presente se encuentra debajo del limite de deteccion.'],x+205,y+130)

    doc.text('Los resultados Psitivos no diferencian entre SARS-COV y SARS-COV-2',x+205,y+145)
    doc.rect(x+195, y+148, 155, 10)
    doc.text(['NOTA: Las pruebas rapidas para COVID-19 NO SON CONFIRMATORIAS Los Resultados deben ','ser interpretados en funcion de la Clinica del paciente y dias de evolucion de la enfermedad'],x+205,y+152)

   //doc.text('El test proporciona un resultado de "POSITIVO" o "NEGATIVO"',x+205,y+85)
    doc.setFontSize(10);


    doc.rect(x+195, y+160, 155, 10)
    doc.text('OBSERVACION',x+200,y+165)
    doc.rect(x+195, y+170, 155, 30)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+205,y+175,'left')
    doc.setFontSize(10);
    doc.text(['Fecha toma de Muestra','Fecha Entrega de Resultado','Hora toma Muestra'],x+280,y+175,'left')

    $( '#docpdf' ).attr('src', doc.output('datauristring'));
      },
      embarazo() {
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 23)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 008',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('PRUEBA RAPIDA INMUNOCROMATOGRAFICA',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA','METODO'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text(['ADIEMR PAUL CHAMBI AJATA','ADIMER PAUL CHAMBI AJATA A','COMPLRETA'],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.text(['9999 ','MACULJONO','11'],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.text('PRUEBA RAPIDA INMUNOCROMATOGRAFICA',x+100, y+47,'center')
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+51, 205, 50)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('TEST EMBARAZO EN SANGRE',x+25,y+60)
    doc.setFont(undefined, 'normal')
    doc.text('POSITIVO',x+100,y+65,'center')
    doc.setTextColor(0,0,0)
    doc.text('F.U.M.',x+45,y+75,'center')
    doc.setTextColor(57,73,171)
    doc.text('2022-04-17',x+100,y+80,'center')

    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('OBSERVACIONES:',x+20,y+90)
    doc.setFont(undefined, 'normal')
    doc.text('SADASDASDADADSADSDSDADASDASDASD',x+30,y+95,'left')

    doc.rect(x+5, y+102, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+110)
    doc.setFont(undefined, 'NORMAL')
    doc.text('RELATIVA RELATIVA  ABSOLUTA',x+8,y+115)
    doc.setFont(undefined, 'normal')
    doc.text(['FECHA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+110,'center')
    doc.setTextColor(0,0,0)

    $( '#docpdf' ).attr('src', doc.output('datauristring'));
  },
    imprimirlaboratorio(p,l){
      if(l.tipo_id==1)
        this.hemograma(p,l)
      if(l.tipo_id==2)
        this.sanguinea(p,l)
       console.log(p)
       console.log(l)
      return false
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
      this.laboratorio.responsable=this.user
      this.$axios.post(process.env.API+'/laboratorio',this.laboratorio).then(res=> {
        // console.log(res.data)
        this.mispacientes()
      })
    },
    frmlaboratorio(paciente){
      this.paciente=paciente
      this.dialoglaboratorio=true
    },
        onSubmit(){
        this.dato.seguro=this.seguro.r.id
      this.$q.loading.show()
      this.$axios.post(process.env.API+'/paciente',this.dato).then(res=>{
        this.dato={}
         this.$q.notify({
          message: 'Se registro correctamente',
          color: 'success'
        })
        this.$q.loading.hide()
        this.alert=false;
        this.listado();
      }).catch(err=>{
        this.$q.loading.hide()
        this.$q.notify({
          message:err.response.data.message,
          color:'red',
          icon:'error'
        })
      })
    },
        listdoctor(){
        this.doctors=[];
          this.$axios.get(process.env.API+'/doctor').then(res=>{
            // console.log(res.data);
            res.data.forEach(e => {
                this.doctors.push({label:e.nombre+' '+e.paterno+' '+e.materno,value:e.id})
             });
                this.requerido=this.doctors[0];
          })
    },
          listusers(){
          this.usuarios=[]
          this.$axios.post(process.env.API+'/listuser').then(res=>{
             res.data.forEach(element => {
                 this.usuarios.push(element.name);
             });
             this.user=this.usuarios[0]
          //console.log(this.usuarios)
          })

      },
          listseguro(){
          this.seguros=[]
          this.seguro={label:''}
          this.$axios.get(process.env.API+'/seguro').then(res=>{
              res.data.forEach(r => {
                  this.seguros.push({label:r.nombre,r});
              });

          })

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
