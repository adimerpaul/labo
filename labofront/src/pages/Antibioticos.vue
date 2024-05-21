<template>
  <q-page class="bg-grey-3">
    <div class="row">
      <div class="col-12">
        <q-table dense :rows="antibioticos" :filter="search" :rows-per-page-options="[0]" :columns="columns" :loading="loading">
          <template v-slot:top-right>
            <q-btn @click="antibioticoCreate" label="Crear" color="primary" icon="add_circle_outline" :loading="loading" />
            <q-input v-model="search" label="Buscar" dense outlined debounce="300">
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </template>
          <template v-slot:body-cell-actions="props">
            <q-td key="actions" :props="props">
              <q-btn @click="antibioticoEdit(props.row)" dense flat round icon="edit" />
              <q-btn @click="antibioticoDelete(props.row.id)" dense flat round icon="delete" color="red" />
            </q-td>
          </template>
          <template v-slot:body-cell-tipo="props">
            <q-td key="tipo" :props="props">
              <q-chip :label="props.row.tipo" :color="props.row.tipo === 'CULTIVO' ? 'primary' : 'secondary'" text-color="white" />
            </q-td>
          </template>
          <template v-slot:body-cell-descripcion="props">
            <q-td key="tipo" :props="props">
              <div class="text-ellipsis" style="max-width: 200px" v-html="props.row.descripcion"></div>
            </q-td>
          </template>
        </q-table>
      </div>
    </div>
    <q-dialog v-model="antibioticoDialog" persistent>
      <q-card style="width: 400px;min-width: 400px" class="q-pa-none" >
        <q-card-section class="row items-center q-pb-none">
            <span class="text-bold" v-if="antibioticoOption === 'Crear'">Crear Antibiotico</span>
            <span class="text-bold" v-else>Editar Antibiotico</span>
            <q-space />
            <q-btn flat round dense icon="close" @click="antibioticoDialog = false" />
        </q-card-section>
        <q-card-section>
          <q-form @submit="antibioticoSubmit">
            <q-input v-model="antibiotico.nombre" label="Nombre" outlined dense :rules="[val => !!val || 'Campo requerido']" />
            <q-input v-model="antibiotico.unidad" label="Unidad" outlined dense />
            <q-input v-model="antibiotico.rangoMin" label="Rango Min" outlined dense type="number" step="0.01"/>
            <q-input v-model="antibiotico.rangoMax" label="Rango Max" outlined dense type="number" step="0.01"/>
            <q-select v-model="antibiotico.categoria" label="Categoria" outlined dense :options="categorias" />
<!--            <q-input type="textarea" v-model="antibiotico.descripcion" label="descripcion" outlined dense />-->
            <q-editor v-model="antibiotico.descripcion" min-height="5rem" label="Descripcion" outlined dense />
            <q-select v-model="antibiotico.tipo" label="Tipo" outlined dense :options="['CULTIVO', 'INMUNOLOGIA']" />
            <q-card-actions align="right">
              <q-btn label="Cancelar" color="red" @click="antibioticoDialog = false" :loading="loading" />
              <q-btn type="submit" :label="antibioticoOption === 'Crear' ? 'Crear' : 'Editar'" color="primary" :loading="loading" />
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
      loading: false,
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
        { name: 'descripcion', label: 'Descripcion', align: 'left', field: row => row.descripcion },
        { name: 'tipo', label: 'Tipo', align: 'left', field: row => row.tipo },
      ],
      categorias: ['MARCADORES TUMORALES', 'PERFIL TIROIDEO', 'HORMONAS DE FERTILIDAD', 'HORMONAS SUPRERRENALES', 'INSULINA', 'PEPTIDO -C', 'PRUEBAS  AUTOINMUNE', 'COMPLEMENTOS', 'ANCA - P', 'ANCA - C', 'CHAGAS', 'PANEL T.O.R.C.H.', 'INFECCIOSAS', 'HELICOBACTER PYLORI EN SANGRE', 'HELICOBACTER PYLORI EN HECES ANTIGENO', 'DETECCION DE GIARDIA LAMBLIA', 'VITAMINA "B12"', 'ACIDO FOLICO', 'VITAMINA "D"', 'HORMONA DEL CRECIMIENTO (hGH)', 'FACTOR DE CRECIMIENTO SIMILAR A LA INSULINA-1 (IGF-1)', 'MIOGLOBINA']

    }
  },
  mounted() {
    this.antibioticosGet();
  },
  methods: {
    antibioticoSubmit() {
      console.log(this.antibioticoOption);
      if (this.antibioticoOption === 'Crear') {
        this.antibioticoPost();
      } else {
        this.antibioticoPut();
      }
    },
    antibioticoDelete(id) {
      this.$q.dialog({
        title: 'Eliminar',
        message: '¿Está seguro de eliminar este registro?',
        ok: 'Si',
        cancel: 'No'
      }).onOk(() => {
        this.loading = true;
        this.$axios.delete(`antibioticos/${id}`)
        .then(() => {
          this.antibioticos = this.antibioticos.filter(antibiotico => antibiotico.id !== id);
        })
        .catch(error => {
          console.log(error);
        }).finally(() => {
          this.loading = false;
        });
      });
    },
    antibioticoEdit(antibiotico) {
      this.antibioticoDialog = true;
      this.antibioticoOption = 'Editar';
      this.antibiotico = Object.assign({}, antibiotico);
    },
    antibioticoPut() {
      this.loading = true;
      this.$axios.put(`antibioticos/${this.antibiotico.id}`, this.antibiotico)
      .then(response => {
        this.antibioticos = this.antibioticos.map(antibiotico => {
          return antibiotico.id === response.data.id ? response.data : antibiotico;
        });
        this.antibioticoDialog = false;
      })
      .catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    antibioticoCreate() {
      this.antibioticoDialog = true;
      this.antibioticoOption = 'Crear';
      this.antibiotico = {
        nombre: '',
        unidad: '',
        rangoMin: '0',
        rangoMax: '0',
        referencia: '',
        tipo: 'CULTIVO',
        descripcion: '',
      };
    },
    antibioticoPost() {
      console.log(this.antibiotico);
      this.loading = true;
      this.$axios.post('antibioticos', this.antibiotico)
      .then(response => {
        this.antibioticos.unshift(response.data);
        this.antibioticoDialog = false;
      })
      .catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    antibioticosGet() {
      this.loading = true;
      this.$axios.get('antibioticos')
      .then(response => {
        this.antibioticos = response.data;
      })
      .catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    }
  }
}
</script>
