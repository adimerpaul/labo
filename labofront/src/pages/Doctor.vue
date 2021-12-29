<template>
  <q-page class="q-pa-xs">
    <q-card>
            <q-btn  label="Registrar Doctor" color="positive" @click="alert = true" icon="add_circle" class="q-mb-xs" />
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
              v-model="dato.especialidad"
              label="Especialidad "
              hint="Ingresar Especialidad "
                  style="text-transform: uppercase;"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="dato.matricula"
              label="Matricula "
              hint="Ingresar Matricula "

            />
            <q-input
              filled
              v-model="dato.celular"
              label="Celular (Whatsapp)"
              hint="Numero de celular"

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
        title="Doctor"
        :columns="columns"
        :rows="rows"
      >
        <template v-slot:top-right>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
      <template v-slot:body-cell-activo="props">
        <q-tr :props="props">

          <q-td key="activo" :props="props">
              <q-badge color="green" v-if="props.row.activo" @click="cambiar(props)">
              ACTIVO
              </q-badge>
              <q-badge color="red" v-else @click="cambiar(props)">
              INACTIVO
              </q-badge>
          </q-td>
        </q-tr>
      </template>

                      <template v-slot:body-cell-opcion="props">

          <q-td key="opcion" :props="props">
              <q-btn  dense round flat color="yellow" @click="editRow(props)" icon="edit"></q-btn>
              <q-btn  dense round flat color="red" @click="deleteRow(props)" icon="delete"></q-btn>
          </q-td>
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

            />
            <q-input
              filled
              v-model="dato2.nombre"
              label="Nombre "
              hint="Ingresar Nombre "
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
              v-model="dato2.especialidad"
              label="Especialidad "
              hint="Ingresar Especialdad"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="dato2.matricula"
              label="Matricula "
              hint="Ingresar Matricula"

            />
            <q-input
              filled
              v-model="dato2.celular"
              label="Celular (Whatsapp)"
              hint="Numero de celular"

            />
            <div>
              <q-btn label="Modificar" type="submit" color="positive" icon="add_circle"/>
                <q-btn  label="Cancelar" icon="delete" color="negative" v-close-popup />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    </q-card>
  </q-page>
</template>

<script>
export default {
  data(){
    return{
      filter:'',
      alert:false,
      dialog_mod:false,
      dato:{},
      rows:[],

      columns:[
        { name: 'ci', label: 'ci', field: 'ci'},
        { name: 'nombre', label: 'nombre', field: 'nombre'},
        { name: 'paterno', label: 'paterno', field: 'paterno'},
        { name: 'materno', label: 'materno', field: 'materno'},
        { name: 'especialidad', label: 'especialidad', field: 'especialidad'},
        { name: 'matricula', label: 'matricula', field: 'matricula'},
        { name: 'celular', label: 'celular', field: 'celular'},
        { name: 'activo', label: 'activo', field: 'activo'},
        { name: 'opcion', label: 'opcion', field: 'opcion'},
      ]
    }
  },
  created(){
    // console.log('aa')
    // setTimeout(() => {
    //   console.log("Refresh")
    // }, 1000)
    // console.log('ias')
    // window.location.reload(true)
    // this.$q.loading.show()
    this.listado();

  },
  methods: {
    cambiar(props){

     this.$axios.post(process.env.API+'/doctoractivo',props.row).then(res=>{
       this.listado();
     })
    },
    listado(){
     this.$q.loading.show()
     this.$axios.get(process.env.API+'/doctor').then(res=>{
       console.log(res.data)
       this.rows=res.data
       this.$q.loading.hide()
     })
    },
    onSubmit(){
     this.$axios.post(process.env.API+'/doctor',this.dato).then(res=>{
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'info',
          message: 'Registrado correctamente!'
        });
        this.alert=false;
        this.dato={};
        this.listado();
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
        title: 'Eliminar Doctor',
        message: 'Esta Seguro de Eliminar ?',
        cancel: true,
      }).onOk(() => {
          this.$axios.delete(process.env.API+'/doctor/'+this.dato2.id).then(res=>{
         this.$q.notify({
          message: 'Se elimino correctamente',
          color: 'green'
        })
        this.listado();
          })
      })
    },
        onMod(){
      console.log(this.dato2)
      this.$axios.put(process.env.API+'/doctor/'+this.dato2.id,this.dato2).then(res=>{
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
