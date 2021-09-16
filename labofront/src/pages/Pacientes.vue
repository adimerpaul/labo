<template>
  <q-page class="q-pa-xs">
    <q-card>
        <q-btn  label="Registrar Paciente" color="positive" @click="alert = true" icon="add_circle" class="q-mb-xs" />
    <q-dialog v-model="alert">
      <q-card>
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Registro</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <q-form
            @submit="onSubmit"
            @reset="onReset"
            class="q-gutter-md"
          >
            <q-input
              filled
              v-model="dato.ci"
              label="Cedula Identidad"
              hint="Numero de carnet"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
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
              lazy-rules
              :rules="[ val => val && val.length > 0 && val<=actual || 'Por favor ingresa datos']"
            />
            <q-radio v-model="dato.sexo" val="Masculino" label="Masculino" />
            <q-radio v-model="dato.sexo" val="Femenino" label="Femenino" />
            <q-input
              filled
              v-model="dato.celular"
              label="Celular (Whatsapp)"
              hint="Numero de celular"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <div>
              <q-btn label="Crear" type="submit" color="positive" icon="add_circle"/>
                <q-btn  label="Cancelar" icon="delete" color="negative" v-close-popup />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

      <q-table
        title="Pacientes"
        :columns="columns"
        :rows="pacientes"
      >
        <template v-slot:top-right>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>

              <template v-slot:body-cell-opcion="props">
        <q-tr :props="props">
          <q-td key="opcion" :props="props">
              <q-btn  dense round flat color="accent" @click="labRow(props)" icon="biotech"></q-btn>
              <q-btn  dense round flat color="yellow" @click="editRow(props)" icon="edit"></q-btn>
              <q-btn  dense round flat color="red" @click="deleteRow(props)" icon="delete"></q-btn>
          </q-td>
        </q-tr>
      </template>
      </q-table>
      
      <q-dialog v-model="dialog_mod">
      <q-card>
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Modificar Datos</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <q-form
            @submit="onMod"
            class="q-gutter-md"
          >
            <q-input
              filled
              v-model="dato2.ci"
              label="Cedula Identidad"
              hint="Numero de carnet"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="dato2.nombre"
              label="Nombre "
              hint="Ingresar Nombre Completo"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="dato2.paterno"
              label="Ap paterno"
              hint="Ingresar Paterno"
            />
            <q-input
              filled
              v-model="dato2.materno"
              label="Ap Materno"
              hint="Ingresar Materno"
            />
            <q-input
              filled
              type="date"
              v-model="dato2.fechanac"
              label="Fecha Nac"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-radio v-model="dato2.sexo" val="Masculino" label="Masculino" />
            <q-radio v-model="dato2.sexo" val="Femenino" label="Femenino" />
            <q-input
              filled
              v-model="dato2.celular"
              label="Celular (Whatsapp)"
              hint="Numero de celular"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <div>
              <q-btn label="Modificar" type="submit" color="positive" icon="add_circle"/>
                <q-btn  label="Cancelar" icon="delete" color="negative" v-close-popup />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

     <q-dialog v-model="dialog_lab" maximized>
      <q-card >
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Formularios</div>
        </q-card-section>
        <q-card-section >
         <q-select v-model="tab" :options="formulario"  label="FORMULARIOS" />

        <q-separator />
              <q-tab-panels v-model="tab.value" animated class="shadow-2 rounded-borders">
        <q-tab-panel name="hemograma">
            <q-form @submit="onHemograma" >
        <table style="width: 100%;color: black">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="../assets/natividad.png" alt="Logo Clinica" srcset="" style="height: 3cm; width:8cm;"></td>
                <td style="color: blue; text-align:center; height:0.5cm;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Telf: 5254721 Fax: 52-83667 </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Emergencia las 24 horas del dia. </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Bolivar Nº 753 entre Arica e Iquique </td>                
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center"><h3>HEMOGRAMA COMPLETO</h3></td>
                <td>Form. 001</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.age}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td>
                    <q-select borderless  :options="doctors" v-model="requerido" style="width:100%"/>
                </td>
                
                <td style="color: darkblue">SEXO </td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td><q-input borderless  type="text" style="width: 100%" placeholder="Tipo muestra" v-model="hemograma.tipomuestra"/></td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">METODO</td>
                <td colspan="3">
                    Contador Hematologico MINDRAY BC 5130
                    Hematocrito (Metodo Manual) Hemoglobina (Clanmetahemoglobina reactivo drabking)
                </td>
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3"></td>
                <td>REFERENCIA</td>
                <td colspan="2"></td>
                <td>REFERENCIA</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Globulos Rojos</td>
                <td><input borderless type="text" placeholder="00" v-model="hemograma.d1"   style="width: 100%"/></td>
                <td>x10 <sup>12 </sup>/L</td>
                <td>Varon 5.1-5.7x10 <sup>12</sup>/L <br> Mujer 4.8-5.4x10 <sup>12</sup>/L</td>
                <td class="bg-negative text-white">Tiempo de cuagulacion</td>
                <td><input borderless type="text" placeholder="00"  style="width: 100%" v-model="hemograma.d2"/></td>
                <td>5-10 min</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Hemoglobina</td>
                <td><input borderless type="text" placeholder="00" name="d3"   style="width: 100%" v-model="hemograma.d3"/></td>
                <td>g/L</td>
                <td>Varon 151-175 g/L <br> Mujer Mujer 141-165 g/L</td>
                <td class="bg-negative text-white">Tiempo de sangria</td>
                <td><input type="text" placeholder="00" name="d4"   style="width: 100%" v-model="hemograma.d4"/></td>
                <td>1-3 min</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Hematocrito</td>
                <td><input type="text" placeholder="00" name="d5"   style="width: 100%" v-model="hemograma.d5"/></td>
                <td>L/L</td>
                <td>Varon 0.51-0.57 L/L <br> Mujer 0.46-0.53 L/L</td>
                <td class="bg-negative text-white">Tiempo de Protrombina</td>
                <td><input type="text" placeholder="00" name="d6"   style="width: 100%" v-model="hemograma.d6"/></td>
                <td>12-13 seg</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">V.E.S.</td>
                <td><input type="text" placeholder="00" name="d7"   style="width: 100%" v-model="hemograma.d7"/></td>
                <td>mm.</td>
                <td>Varon 15 mm/hora <br> Mujer 20 mm/hora</td>
                <td class="bg-negative text-white">% Actividad</td>
                <td><input type="text" placeholder="00" name="d8"   style="width: 100%" v-model="hemograma.d8"/></td>
                <td>95-100%</td>
            </tr>

            <tr>
                <td class="bg-negative text-white">V.C.M.</td>
                <td><input type="text" placeholder="00" name="d9"   style="width: 100%" v-model="hemograma.d9"/></td>
                <td>ft.</td>
                <td>Varon 83.0-97.0 ft</td>
                <td class="bg-negative text-white">INR</td>
                <td><input type="text" placeholder="00" name="d10"   style="width: 100%" v-model="hemograma.d10"/></td>
                <td>0.97-1.04</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Hb.C.M.</td>
                <td><input type="text" placeholder="00" name="d11"   style="width: 100%" v-model="hemograma.d11"/></td>
                <td>pg.</td>
                <td>27.0-31.0 pg.</td>
                <td class="bg-negative text-white">Grupofactor</td>
                <td colspan="2"><input type="text" placeholder="00" name="d12"   style="width: 100%" v-model="hemograma.d12"/></td>
            </tr>
            <tr>
                <td class="bg-negative text-white">C. Hb.C.M.</td>
                <td><input type="text" placeholder="00" name="d13"   style="width: 100%" v-model="hemograma.d13"/></td>
                <td>%</td>
                <td>32-36%</td>
                <td class="bg-negative text-white">Reticulocitos</td>
                <td><input type="text" placeholder="00" name="d14"   style="width: 100%" v-model="hemograma.d14"/></td>
                <td>0.5-2%</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Globulos Blancos</td>
                <td><input type="text" placeholder="00" name="d15"   style="width: 100%" v-model="hemograma.d15"/></td>
                <td>10 <sup>9</sup>/L</td>
                <td> 4.5-10.5x10 <sup>9</sup>/L</td>
                <td class="bg-negative text-white">IPR</td>
                <td><input type="text" placeholder="00" name="d16"   style="width: 100%" v-model="hemograma.d16"/></td>
                <td></td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Plaquetas</td>
                <td><input hide-bottom-space type="text" placeholder="00" style="width: 100%" v-model="hemograma.d17"/></td>
                <td>x10 <sup>9 </sup>/L</td>
                <td>105-400x10 <sup>9</sup> /L</td>
                <td colspan="3"></td>
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="5"></td>
                <td colspan="2">VALOR REFERENCIAL</td>
            </tr>
            <tr>
                <td class="bg-negative text-white"></td>
                <td colspan="2">RELATIVA</td>
                <td colspan="2">ABSOLUTA</td>
                <td>RELATIVA</td>
                <td>ABSOLUTA</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Cayados</td>
                <td><input type="text" placeholder="00" name="d18"   style="width: 100%" v-model="hemograma.d18"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d19"   style="width: 100%" v-model="hemograma.d19"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>0-3%</td>
                <td>0.00-0.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Neutrofilos</td>
                <td><input type="text" placeholder="00" name="d20"   style="width: 100%" v-model="hemograma.d20"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d21"   style="width: 100%" v-model="hemograma.d21"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>50-70%</td>
                <td>2.50-7.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Eosinofilos</td>
                <td><input type="text" placeholder="00" name="d22"   style="width: 100%" v-model="hemograma.d22"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d23"   style="width: 100%" v-model="hemograma.d23"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>0-3%</td>
                <td>0.00-0.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Basofilos</td>
                <td><input type="text" placeholder="00" name="d24"   style="width: 100%" v-model="hemograma.d24"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d25"   style="width: 100%" v-model="hemograma.d25"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>0-1%</td>
                <td>0.00-0.15x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Linfocitos</td>
                <td><input type="text" placeholder="00" name="d26"   style="width: 100%" v-model="hemograma.d26"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d27"   style="width: 100%" v-model="hemograma.d27"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>25-40%</td>
                <td>1.25-4.200x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Monocitos</td>
                <td><input type="text" placeholder="00" name="d28"   style="width: 100%" v-model="hemograma.d28"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d29"   style="width: 100%" v-model="hemograma.d29"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>4-8%</td>
                <td>2.00-8.40x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">BLASTOS</td>
                <td><input type="text" placeholder="00" name="d30"   style="width: 100%" v-model="hemograma.d30"></td>
                <td>%</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="2">MORFOLOGIA DE FRONTIS DE SANGRE PERIFERICA</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Serie Rojas:</td>
                <td><input type="text" placeholder="00" name="d31"   style="width: 100%" v-model="hemograma.d31"></td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Serie Blancas:</td>
                <td><input type="text" placeholder="00" name="d32"   style="width: 100%" v-model="hemograma.d32"></td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Serie Plaquetarias:</td>
                <td><input type="text" placeholder="00" name="d33"   style="width: 100%" v-model="hemograma.d33"></td>
            </tr>
            <tr>
                <td class="bg-negative text-white">FECHA DE TOMA DE MUESTRA:</td>
                <td><input type="date" name="fechatoma"   style="width: 100%" v-model="hemograma.fechatoma"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button class="btn btn-success btn-block" type="submit"><i class="fa fa-save"></i> REGISTRAR</button>
                </td>
            </tr>
        </table>
    </q-form>
        </q-tab-panel>

        <q-tab-panel name="sanguinea">
          <div class="text-h6">Alarms</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
        </q-tab-panel>

        <q-tab-panel name="orina">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
        </q-tab-panel>

                <q-tab-panel name="uretral">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
        </q-tab-panel>

                <q-tab-panel name="vaginal">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
        </q-tab-panel>

                <q-tab-panel name="hece">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
        </q-tab-panel>

          <q-tab-panel name="simple">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
          </q-tab-panel>

          <q-tab-panel name="seriado">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
          </q-tab-panel>

          <q-tab-panel name="serologia">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
          </q-tab-panel>

          <q-tab-panel name="labserologia">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
          </q-tab-panel>

          <q-tab-panel name="reserologia">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
          </q-tab-panel>
          <q-tab-panel name="ensayo">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
          </q-tab-panel>

      </q-tab-panels>
        </q-card-section>
                      <q-card-actions align="right" class="text-primary">
          <q-btn flat label="Cancelar" v-close-popup />
        </q-card-actions>
      </q-card>

    </q-dialog>

    </q-card>
  </q-page>
</template>

<script>
import { useQuasar } from 'quasar'
import {date} from "quasar";
export default {
  data(){
    return{
      filter:'',
      $q : useQuasar(),
      pacientes:[],
      rows:[],
      dato:{},
      doctors:[],
      alert:false,
      dialog_mod:false,
      dialog_lab:false,
      formulario:[
         {value:"hemograma"        ,label:"Hemograma completo" },
         {value:"sanguinea"        ,label:"Quimica sanguinea" },
         {value:"orina"        ,label:"Examen general de orina" },
         {value:"uretral"        ,label:"Analisis de secrecion uretral" },
         {value:"vaginal"        ,label:"Analisis de secrecion Vaginal" },
         {value:"hece"        ,label:"Analisis de Heces" },
         {value:"simple"        ,label:"Copraparasitologico Simple" },
         {value:"seriado"        ,label:"Copraparasitologico Seriado" },
         {value:"serologia"        ,label:"Serologia" },
         {value:"labserologia"        ,label:"Laboratorio Serologia" },
         {value:"reserologia"        ,label:"Resultado Serologia" },
         {value:"ensayo"        ,label:"Inmunoensayo de Fluorescencia" }
      ],
      hemograma:{},
      orina:{},
      sanguinia:{},
      uretral:{},
      vaginal:{},
      hece:{},
      simple:{},
      seriado:{},
      serologia:{},
      labserologia:{},
      reserologoia:{},
      requerido:{},
       actual:date.formatDate(Date.now(),'YYYY-MM-DD'),
      ensayo:{},
      tab:{},
      columns:[
        { name: 'ci', label: 'ci', field: 'ci',align: 'center',},
        { name: 'nombre', label: 'nombre', field: 'nombre',align: 'center',},
        { name: 'paterno', label: 'paterno', field: 'paterno',align: 'center',},
        { name: 'materno', label: 'materno', field: 'materno',align: 'center',},
        { name: 'fechanac', label: 'fechanac', field: 'fechanac', align: 'center',},
        { name: 'sexo', label: 'sexo', field: 'sexo',align: 'center',},
        { name: 'celular', label: 'celular', field: 'celular',align: 'center',},
        { name: 'opcion', label: 'opcion', field: 'opcion',align: 'center',},
      ]
    }
  },
  created(){
    this.listado();
    this.listdoctor();
    this.tab={value:"hemograma",label:"Hemograma completo" };
  },
  methods: {
    listdoctor(){
        this.doctors=[];
          this.$axios.get(process.env.API+'/doctor').then(res=>{

       console.log(res.data)
        
         res.data.forEach(element => {
            this.doctors.push({label:element.nombre,value:element.id});
           
         });
         this.requerido=this.doctors[0];
          })
    },
    listado(){
          this.$axios.get(process.env.API+'/paciente').then(res=>{
       console.log(res.data)
       this.pacientes=res.data
     })
    },
    editRow(props){
      this.dato2=props.row;
      this.dialog_mod=true;
    },
    deleteRow(props){
      this.dato2=props.row;
      console.log(this.dato2);

      this.$q.dialog({
        title: 'Eliminar Paciente',
        message: 'Esta Seguro de Eliminar ?',
        cancel: true,
      }).onOk(() => {
          this.$axios.delete(process.env.API+'/paciente/'+this.dato2.id).then(res=>{
         this.$q.notify({
          message: 'Se elimino correctamente',
          color: 'green'
        })
        this.listado();
          })
      })
    },
    onSubmit(){
      this.$axios.post(process.env.API+'/paciente',this.dato).then(res=>{
         this.$q.notify({
          message: 'Se registro correctamente',
          color: 'green'
        })
        this.alert=false;
        this.listado();
      })
    },
    onHemograma(){
        this.$axios.post(process.env.API+'/hemograma',{paciente:this.dato2,doctor:this.requerido.value,hemograma:this.hemograma}).then(res=>{
          console.log(res.data)
        })
    },
    labRow(props){
      this.dato2=props.row;
      this.dialog_lab=true;
    },
    onMod(){
      console.log(this.dato2)
      this.$axios.put(process.env.API+'/paciente/'+this.dato2.id,this.dato2).then(res=>{
         this.$q.notify({
          message: 'Se modifico correctamente',
          color: 'green'
        })
      })
      this.dialog_mod=false;
      this.listado();
    }
  },
}
</script>
<style>
table, td, th {
  border-collapse: collapse;
}
</style>