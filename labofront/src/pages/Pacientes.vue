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
              <q-btn  dense round flat color="accent" @click="editRow(props)" icon="biotech"></q-btn>
              <q-btn  dense round flat color="yellow" @click="editRow(props)" icon="edit"></q-btn>
              <q-btn  dense round flat color="red" @click="deleteRow(props)" icon="delete"></q-btn>
          </q-td>
        </q-tr>
      </template>
      </q-table>
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
      columns:[
        { name: 'nombre', label: 'nombre', field: 'nombre',align: 'center',},
        { name: 'fechanac', label: 'fechanac', field: 'fechanac', align: 'center',},
        { name: 'sexo', label: 'sexo', field: 'sexo',align: 'center',},
        { name: 'celular', label: 'celular', field: 'celular',align: 'center',},
        { name: 'opcion', label: 'opcion', field: 'opcion',align: 'right',},
      ]
    }
  },
  created(){
    this.$axios.get(process.env.API+'/paciente').then(res=>{
       console.log(res.data)
       this.pacientes=res.data
     })
  },
  methods: {
    onSubmit(){
      this.$axios.post(process.env.API+'/paciente',this.dato).then(res=>{
         $q.notify({
          message: 'Se registro correctamente',
          color: 'green'
        })
      })

    }
  },
}
</script>
