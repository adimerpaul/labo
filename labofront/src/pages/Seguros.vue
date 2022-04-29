<template>
  <q-page class="q-pa-xs">
    <q-card>
            <q-btn  label="Registrar Seguro" color="positive" @click="alert = true" icon="add_circle" class="q-mb-xs" />
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
              label="Nombre "
              hint="Ingresar Nombre "
                  style="text-transform: uppercase;"
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
        title="SEGUROS"
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

      <template v-slot:body-cell-paciente="props">

          <q-td key="opcion" :props="props">
          <ul>
            <li v-for="p in props.row.paciente" :key="p"><q-btn flat round icon="list" dense color="accent" @click="labo(p)"/>{{p.nombre}} {{p.paterno}} {{p.materno}}</li>
          </ul>
          </q-td>
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
              v-model="dato2.nombre"
              label="Nombre "
              hint="Ingresar Nombre "
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

                <q-dialog v-model="dialog_lab">
      <q-card>
        <q-card-section class="bg-green-8 text-white">
          <div class="text-h6">Paciente: {{paciente.nombre}} {{paciente.paterno}} {{paciente.materno}} > LABORATORIOS</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <ul>
            <li v-for="l in laboratorios" :key="l">{{l.fechatoma}} {{l.tipo.nombre}}</li>
          </ul>
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
      paciente:{},
      dialog_lab:false,
      rows:[],

      columns:[
        { name: 'nombre', label: 'SEGURO', field: 'nombre'},
        { name: 'paciente', label: 'PACIENTES', field: 'paciente'},
        { name: 'opcion', label: 'OPCION', field: 'opcion'},
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
    labo(paciente){
      console.log(paciente.laboratorios)
      this.paciente=paciente
      this.laboratorios=paciente.laboratorios
      this.dialog_lab=true
    },
    listado(){
     this.$q.loading.show()
     this.$axios.get(process.env.API+'/seguro').then(res=>{
       console.log(res.data)
       this.rows=res.data
       this.$q.loading.hide()
     })
    },
    onSubmit(){
     this.$axios.post(process.env.API+'/seguro',this.dato).then(res=>{
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
        title: 'Eliminar Seguro',
        message: 'Esta Seguro de Eliminar ?',
        cancel: true,
      }).onOk(() => {
          this.$axios.delete(process.env.API+'/seguro/'+this.dato2.id).then(res=>{
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
      this.$axios.put(process.env.API+'/seguro/'+this.dato2.id,this.dato2).then(res=>{
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
