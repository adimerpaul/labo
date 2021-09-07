<template>
  <q-page class="q-pa-xs">
    <q-card>
      <q-table
        title="Historial"
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
      </q-table>
    </q-card>
  </q-page>
</template>

<script>
export default {
  data(){
    return{
      filter:'',
      rows:[],
      columns:[
        { name: 'padron', label: 'padron', field: 'padron'},
        { name: 'paterno', label: 'paterno', field: 'paterno'},
        { name: 'materno', label: 'materno', field: 'materno'},
        { name: 'cedula', label: 'cedula', field: 'cedula'},
        { name: 'expedido', label: 'expedido', field: 'expedido'},
        { name: 'celular', label: 'celular', field: 'celular'},
        { name: 'direccion', label: 'direccion', field: 'direccion'},
        { name: 'telefono', label: 'telefono', field: 'telefono'},
        { name: 'tipo', label: 'tipo', field: 'tipo'},
        { name: 'action', label: 'action', field: 'action'},
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
  }
}
</script>
