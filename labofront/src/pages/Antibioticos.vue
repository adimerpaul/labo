<template>
  <q-page class="bg-grey-3">
    <div class="row">
      <div class="col-12">
        <q-table :rows="antibioticos" :filter="search" :rows-per-page-options="[5, 10, 20]" :columns="columns">
          <template v-slot:top-right>
            <q-btn @click="antibioticoCreate" label="Crear" color="primary" icon="add_circle_outline" />
            <q-input v-model="search" label="Buscar" dense outlined debounce="300">
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </template>
        </q-table>
      </div>
    </div>
    <q-dialog v-model="antibioticoDialog" persistent>
      <q-card>
        <q-card-section class="row items-center q-pb-none">
            <span v-if="antibioticoOption === 'Crear'">Crear Antibiotico</span>
            <span v-else>Editar Antibiotico</span>
            <q-space />
            <q-btn flat round dense icon="close" @click="antibioticoDialog = false" />
        </q-card-section>
        <q-card-section>
          <q-form @submit="antibioticoOption === 'Crear' ? antibioticoPost : antibioticoPut">
            <q-input v-model="antibiotico.nombre" label="Nombre" outlined dense />
            <q-input v-model="antibiotico.unidad" label="Unidad" outlined dense />
            <q-input v-model="antibiotico.rangoMin" label="Rango Min" outlined dense type="number" />
            <q-input v-model="antibiotico.rangoMax" label="Rango Max" outlined dense type="number"/>
            <q-input type="textarea" v-model="antibiotico.referencia" label="Referencia" outlined dense />
            <q-input v-model="antibiotico.tipo" label="Tipo" outlined dense />
            <q-card-actions align="right">
              <q-btn label="Cancelar" color="red" @click="antibioticoDialog = false" />
              <q-btn type="submit" label="Guardar" color="primary" />
            </q-card-actions>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>
<script>
export default {
  name: 'Antibioticos',
  data () {
    return {
      antibioticos:[],
      antibiotico:{},
      antibioticoDialog: false,
      antibioticoOption: '',
      search: '',
      columns: [
        { name: 'actions', label: 'Acciones', align: 'center' },
        { name: 'nombre', label: 'Nombre', align: 'left', field: row => row.nombre },
        { name: 'unidad', label: 'Unidad', align: 'left', field: row => row.unidad },
        { name: 'rangoMin', label: 'Rango Min', align: 'left', field: row => row.rangoMin },
        { name: 'rangoMax', label: 'Rango Max', align: 'left', field: row => row.rangoMax },
        { name: 'referencia', label: 'Referencia', align: 'left', field: row => row.referencia },
        { name: 'tipo', label: 'Tipo', align: 'left', field: row => row.tipo },
      ]
    }
  },
  mounted() {
    this.antibioticosGet();
  },
  methods: {
    antibioticoCreate() {
      this.antibioticoDialog = true;
      this.antibioticoOption = 'Crear';
      this.antibiotico = {
        nombre: '',
        unidad: '',
        rangoMin: '',
        rangoMax: '',
        referencia: '',
        tipo: ''
      };
    },
    antibioticosGet() {
      this.$axios.get('antibioticos')
      .then(response => {
        this.antibioticos = response.data;
      })
      .catch(error => {
        console.log(error);
      });
    }
  }
}
</script>
