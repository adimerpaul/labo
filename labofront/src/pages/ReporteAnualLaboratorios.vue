<template>
  <q-page class="q-pa-sm">
    <q-card flat bordered class="q-pa-sm">
      <q-card-section class="q-pa-sm">
        <div class="text-h6">Reporte anual de laboratorios</div>
      </q-card-section>

      <q-card-section class="q-pa-sm">
        <q-form @submit.prevent="buscar" class="row q-col-gutter-xs items-end">
          <div class="col-12 col-sm-2">
            <q-input
              v-model="filtro.ini"
              type="date"
              label="fecha inicio"
              outlined
              dense
            />
          </div>
          <div class="col-12 col-sm-2">
            <q-input
              v-model="filtro.fin"
              type="date"
              label="fecha fin"
              outlined
              dense
            />
          </div>
          <div class="col-12 col-sm-3">
            <q-input v-model="filtro.paciente" label="paciente (opcional)" outlined dense />
          </div>
          <div class="col-12 col-sm-auto">
            <q-btn color="primary" icon="filter_alt" label="filtrar" no-caps type="submit" :loading="loading" />
          </div>
          <div class="col-12 col-sm-auto">
            <q-btn-dropdown color="secondary" icon="description" label="reportes" no-caps style="min-width: 220px;">
              <q-list dense>
                <q-item clickable v-close-popup @click="imprimirTodo">
                  <q-item-section avatar><q-icon name="print" /></q-item-section>
                  <q-item-section>imprimir todo</q-item-section>
                </q-item>
                <q-item clickable v-close-popup @click="imprimirPaciente">
                  <q-item-section avatar><q-icon name="person" /></q-item-section>
                  <q-item-section>imprimir por paciente</q-item-section>
                </q-item>
                <q-item clickable v-close-popup @click="exportarExcel">
                  <q-item-section avatar><q-icon name="table_view" /></q-item-section>
                  <q-item-section>exportar a excel</q-item-section>
                </q-item>
              </q-list>
            </q-btn-dropdown>
          </div>
        </q-form>
      </q-card-section>

      <q-separator />

      <q-card-section class="q-pa-sm">
        <div class="row q-col-gutter-sm q-mb-sm">
          <div class="col-12 col-sm-6 col-md-2">
            <q-card flat bordered class="q-pa-sm">
              <div class="text-caption">Total registros</div>
              <div class="text-h6">{{ resumen.total_registros }}</div>
            </q-card>
          </div>
          <div class="col-12 col-sm-6 col-md-2">
            <q-card flat bordered class="q-pa-sm">
              <div class="text-caption">Pacientes únicos</div>
              <div class="text-h6">{{ resumen.total_pacientes }}</div>
            </q-card>
          </div>
          <div class="col-12 col-sm-6 col-md-2">
            <q-card flat bordered class="q-pa-sm">
              <div class="text-caption">Laboratorios</div>
              <div class="text-h6">{{ resumen.total_laboratorios }}</div>
            </q-card>
          </div>
          <div class="col-12 col-sm-6 col-md-2">
            <q-card flat bordered class="q-pa-sm">
              <div class="text-caption">Cultivos</div>
              <div class="text-h6">{{ resumen.total_cultivos }}</div>
            </q-card>
          </div>
          <div class="col-12 col-sm-6 col-md-2">
            <q-card flat bordered class="q-pa-sm">
              <div class="text-caption">Inmunologías</div>
              <div class="text-h6">{{ resumen.total_inmunologias }}</div>
            </q-card>
          </div>
          <div class="col-12 col-sm-6 col-md-2">
            <q-card flat bordered class="q-pa-sm">
              <div class="text-caption">Sanguíneas</div>
              <div class="text-h6">{{ resumen.total_sanguineas }}</div>
            </q-card>
          </div>
        </div>

        <div class="row items-center justify-between q-mb-xs">
          <div class="text-caption text-grey-8">Total global (todas las páginas): {{ total }}</div>
          <div class="text-caption text-grey-8">Mostrando {{ rows.length }} en página {{ current }}</div>
        </div>

        <q-markup-table flat bordered separator="cell" class="full-width">
          <thead>
            <tr>
              <th>#</th>
              <th>Fecha</th>
              <th>Origen</th>
              <th>Tipo</th>
              <th>Solicitud</th>
              <th>Paciente</th>
              <th>CI</th>
              <th>Usuario</th>
              <th>Doctor</th>
              <th>Muestra</th>
              <th>Responsable</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, idx) in rows" :key="row.row_id">
              <td>{{ ((current - 1) * perPage) + idx + 1 }}</td>
              <td>{{ row.fechatoma }}</td>
              <td>{{ row.origen }}</td>
              <td>{{ row.tipo || '-' }}</td>
              <td>{{ row.solicitud || '-' }}</td>
              <td>{{ row.paciente || '-' }}</td>
              <td>{{ row.paciente_ci || '-' }}</td>
              <td>{{ row.usuario || '-' }}</td>
              <td>{{ row.doctor || '-' }}</td>
              <td>{{ row.tipomuestra || '-' }}</td>
              <td>{{ row.responsable_mostrar || row.responsable || '-' }}</td>
            </tr>
            <tr v-if="!loading && rows.length === 0">
              <td colspan="11" class="text-center">Sin registros para el rango seleccionado</td>
            </tr>
          </tbody>
        </q-markup-table>

        <div class="q-mt-xs row justify-end">
          <q-pagination
            v-model="current"
            :max="lastPage"
            :max-pages="8"
            boundary-numbers
            @update:model-value="cambiarPagina"
          />
        </div>
      </q-card-section>

    </q-card>
  </q-page>
</template>

<script>
export default {
  name: 'ReporteAnualLaboratorios',
  data () {
    const year = new Date().getFullYear() - 1
    return {
      loading: false,
      rows: [],
      current: 1,
      lastPage: 1,
      total: 0,
      resumen: {
        total_registros: 0,
        total_pacientes: 0,
        total_laboratorios: 0,
        total_cultivos: 0,
        total_inmunologias: 0,
        total_sanguineas: 0
      },
      perPage: 10,
      filtro: {
        ini: `${year}-01-01`,
        fin: `${year}-12-31`,
        paciente: ''
      }
    }
  },
  created () {
    this.buscar()
  },
  methods: {
    buscar () {
      this.current = 1
      this.cargarDatos()
    },
    cambiarPagina (page) {
      this.current = page
      this.cargarDatos()
    },
    cargarDatos () {
      this.loading = true
      this.$axios.post(process.env.API + '/reporteanual/laboratorios', {
        ini: this.filtro.ini,
        fin: this.filtro.fin,
        paciente: this.filtro.paciente,
        page: this.current,
        per_page: this.perPage
      }).then(res => {
        this.rows = res.data.data.map(item => ({
          ...item,
          row_id: `${item.origen}-${item.id}`
        }))
        this.lastPage = res.data.last_page || 1
        this.total = res.data.total || 0
        this.resumen = res.data.resumen || this.resumen
      }).finally(() => {
        this.loading = false
      })
    },
    async imprimirTodo () {
      await this.descargarArchivo('/reporteanual/laboratorios/print-all', 'reporte_laboratorios.pdf', 'application/pdf')
    },
    async imprimirPaciente () {
      if (!this.filtro.paciente) {
        this.$q.notify({ type: 'warning', message: 'Ingrese paciente para este reporte' })
        return
      }
      await this.descargarArchivo('/reporteanual/laboratorios/print-paciente', 'reporte_laboratorios_paciente.pdf', 'application/pdf')
    },
    async exportarExcel () {
      await this.descargarArchivo('/reporteanual/laboratorios/export-excel', 'reporte_laboratorios.xls', 'application/vnd.ms-excel')
    },
    async descargarArchivo (url, filename, mime) {
      this.loading = true
      try {
        const res = await this.$axios.post(process.env.API + url, {
          ini: this.filtro.ini,
          fin: this.filtro.fin,
          full_year: url.includes('print-all'),
          paciente: this.filtro.paciente
        }, { responseType: 'blob', timeout: 300000 })
        const blob = new Blob([res.data], { type: mime })
        const link = document.createElement('a')
        link.href = window.URL.createObjectURL(blob)
        link.download = filename
        link.click()
        window.URL.revokeObjectURL(link.href)
      } catch (e) {
        if (e.response && e.response.data) {
          const text = await e.response.data.text()
          try {
            const json = JSON.parse(text)
            this.$q.notify({ type: 'warning', message: json.message || 'No se pudo generar el archivo.' })
          } catch (_) {
            this.$q.notify({ type: 'negative', message: 'No se pudo generar el archivo. Pruebe exportar a Excel.' })
          }
        } else {
          this.$q.notify({ type: 'negative', message: 'No se pudo generar el archivo. Pruebe exportar a Excel.' })
        }
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
