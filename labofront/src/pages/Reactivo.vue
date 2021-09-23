<template>
  <q-page class="q-pa-xs">
    <q-card>
                <q-btn  label="Registrar Reactivo" color="positive" @click="alert = true" icon="add_circle" class="q-mb-xs" />
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
              v-model="dato.codigo"
              label="Codigo"
              hint="codigo"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="dato.nombre"
              label="Nombre Reactivo"
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
        title="Reactivo"
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
              <q-btn  dense round flat color="yellow" @click="invRow(props)" icon="inventory_2"></q-btn>
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
              v-model="dato2.codigo"
              label="codigo"
              hint="codigo"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
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

    <q-dialog v-model="dialog_reg">
      <q-card>
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Registrar Lote</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <q-form
            @submit="onRegistro"
            class="q-gutter-md"
          >
            <q-input
              filled
              v-model="dato2.nombre"
              label="codigo"
              hint="codigo"
              readonly
            />
            <q-input
              filled
              v-model="invent.marca"
              label="Marca "
              hint="Ingresar marca "
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="invent.lote"
              label="Lote "
              hint="Ingresar lote "
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="invent.ingreso"
              label="Ingreso "
              hint="Ingresar cantidad "
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="invent.observacion"
              label="Observacion "
              hint="Ingresar dato "
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              type="date"
              v-model="invent.fechavencimiento"
              label="Fecha Vencimiento  "
              hint="Ingresar dato "
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />

            <div>
              <q-btn label="Registrar" type="submit" color="positive" icon="add_circle"/>
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
      rows:[],
      dato:{},
      dato2:{},
      invent:{},
      alert:false,
      dialog_mod:false,
      dialog_reg:false,
      columns:[
        { name: 'codigo', label: 'codigo', field: 'codigo'},
        { name: 'nombre', label: 'nombre', field: 'nombre'},
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
    // this.$axios.get(process.env.API+'/contribuyente').then(res=>{
    //   console.log(res.data)
    //   this.contribuyentes=res.data
    //   this.$q.loading.hide()
    // })
    this.listado();
  },
  methods: {
    cambiar(props){

     this.$axios.post(process.env.API+'/reactivoestado',props.row).then(res=>{
       this.listado();
     })
    },
    listado(){
     this.$q.loading.show()
     this.$axios.get(process.env.API+'/reactivo').then(res=>{
       console.log(res.data)
       this.rows=res.data
       this.$q.loading.hide()
     })
    },
    onSubmit(){
     this.$axios.post(process.env.API+'/reactivo',this.dato).then(res=>{
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
    invRow(props){
      this.dato2=props.row;
      this.dialog_reg=true;
    },
    deleteRow(props){
      this.dato2=props.row;
      console.log(this.dato2);

      this.$q.dialog({
        title: 'Eliminar Doctor',
        message: 'Esta Seguro de Eliminar ?',
        cancel: true,
      }).onOk(() => {
          this.$axios.delete(process.env.API+'/reactivo/'+this.dato2.id).then(res=>{
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
      this.$axios.put(process.env.API+'/reactivo/'+this.dato2.id,this.dato2).then(res=>{
         this.$q.notify({
          message: 'Se modifico correctamente',
          color: 'green'
        })
      })
      this.dialog_mod=false;
      this.listado();
    },
        onRegistro(){
        this.invent.reactivo_id=this.dato2.id;
        this.$axios.post(process.env.API+'/inventario',this.invent).then(res=>{
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'info',
          message: 'Registrado correctamente!'
        });
        this.dialog_reg=false;
        this.invent={};
        this.listado();
     })
    },
  },

}
</script>
