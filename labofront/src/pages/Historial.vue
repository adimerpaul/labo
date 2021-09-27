<template>
  <q-page class="q-pa-xs">
    <q-card>

    <q-form @submit.prevent="onBuscar" class="q-gutter-md" >
    <div class="row">


    
      <div class="col-3">
      <q-input type="date" label="Fecha" v-model="dato.fecha" lazy-rules 
        :rules="[ val => val && val.length > 0 || 'Ingrese fecha']"/>
    </div>
      <div class="col-3">
        <q-select label="Reactivo" :options="reactivos" v-model="dato.reactivo"
        :rules="[ val => val && val!={} || 'seleccione'] "/>
      </div>
          <q-btn label="Buscar" type="submit" color="primary" />
    </div>
    </q-form>
      <q-table
        title="Historial"
        :columns="columns"
        :rows="historial"
      >
        <template v-slot:top-right>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
      </q-table>
    </q-card>
  </q-page>
</template>

<script>
import {date} from "quasar";
export default {
  data(){
    return{
      filter:'',
      rows:[],
      reactivos:[],
      historial:[],
      dato:{fecha:date.formatDate(Date.now(),'YYYY-MM-DD')},
      columns:[
        { name: 'fecha', label: 'fecha', field: 'fecha'},
        { name: 'fechavencimiento', label: 'fechavencimiento', field: 'fechavencimiento'},
        { name: 'marca', label: 'marca', field: 'marca'},
        { name: 'lote', label: 'lote', field: 'lote'},
        { name: 'ingreso', label: 'ingreso', field: 'ingreso'},
        { name: 'egreso', label: 'egreso', field: 'egreso'},
        { name: 'saldo', label: 'saldo', field: 'saldo'},
        { name: 'observacion', label: 'observacion', field: 'observacion'},
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
    this.listreactivo();
  },
  methods: {
    listreactivo(){
      this.reactivos=[];
     this.$axios.get(process.env.API+'/reactivo').then(res=>{
       console.log(res.data)
       res.data.forEach(element => {
          this.reactivos.push({label:element.nombre,value:element.id});
       });
     })

    },
    onBuscar(){
      this.dato.id=this.dato.reactivo.value;
      console.log(this.dato)
      this.historial=[];
     this.$axios.post(process.env.API+'/listinventario',this.dato).then(res=>{
       console.log(res.data)
         res.data.forEach(element => {
           this.historial.push(element);
           console.log(element)
           this.$axios.post(process.env.API+'/listretiro',{id:element.id}).then(res2=>{
             res2.data.forEach(element2 => {
                this.historial.push(element2);
             });
           })
         
       });

    })
    console.log(this.historial)
  },
  }
}
</script>
