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
              label="Nombre Completo"
              hint="Ingresar Nombre Completo"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              type="date"
              v-model="dato.fechanac"
              label="Fecha Nac"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
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
              label="Nombre Completo"
              hint="Ingresar Nombre Completo"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
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

     <q-dialog v-model="dialog_lab" style="width:80%">
      <q-card >
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Formularios</div>
        </q-card-section>
        <q-card-section >
        <q-tabs
          v-model="tab"
          dense
          class="text-grey"
          active-color="primary"
          indicator-color="primary"
          align="justify"
          narrow-indicator
        >
          <q-tab name="hemograma" label="Hemograma" />
          <q-tab name="sanguinea" label="sanguinea" />
          <q-tab name="orina" label="orina" />
          <q-tab name="uretral" label="uretral" />
          <q-tab name="vaginal" label="vaginal" />
          <q-tab name="hece" label="hece" />
          <q-tab name="simple" label="simple" />
          <q-tab name="seriado" label="seriado" />
          <q-tab name="serologia" label="serologia" />
          <q-tab name="labserologia" label="labserologia" />
          <q-tab name="reserologia" label="reserologia" />
          <q-tab name="ensayo" label="ensayo" />
        </q-tabs>

        <q-separator />
        </q-card-section>
      </q-card>
    </q-dialog>

    </q-card>
  </q-page>
</template>

<script>
import { useQuasar } from 'quasar'
export default {
  data(){
    return{
      filter:'',
      $q : useQuasar(),
      pacientes:[],
      rows:[],
      dato:{},
      alert:false,
      dialog_mod:false,
      dialog_lab:false,
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
      ensayo:{},
      tab:'hemograma',
      columns:[
        { name: 'ci', label: 'ci', field: 'ci',align: 'center',},
        { name: 'nombre', label: 'nombre', field: 'nombre',align: 'center',},
        { name: 'fechanac', label: 'fechanac', field: 'fechanac', align: 'center',},
        { name: 'sexo', label: 'sexo', field: 'sexo',align: 'center',},
        { name: 'celular', label: 'celular', field: 'celular',align: 'center',},
        { name: 'opcion', label: 'opcion', field: 'opcion',align: 'center',},
      ]
    }
  },
  created(){
    this.listado();
  },
  methods: {
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
