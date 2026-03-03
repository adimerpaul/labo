<template>
  <q-page class="q-pa-sm">
    <q-card flat bordered class="q-pa-sm q-mb-sm">
      <div class="row items-center justify-between">
        <div>
          <div class="text-h6">Resumen anual</div>
          <div class="text-caption text-grey-7">Consulta entre fechas</div>
        </div>
        <div class="row q-col-gutter-xs items-end">
          <div class="col-auto">
            <q-btn dense color="primary" flat no-caps label="día" @click="setRangoDia" />
          </div>
          <div class="col-auto">
            <q-btn dense color="primary" flat no-caps label="mes" @click="setRangoMes" />
          </div>
          <div class="col-auto">
            <q-btn dense color="primary" flat no-caps label="año" @click="setRangoAnio" />
          </div>
          <div class="col-auto">
            <q-input dense outlined type="date" v-model="filtro.ini" label="desde" />
          </div>
          <div class="col-auto">
            <q-input dense outlined type="date" v-model="filtro.fin" label="hasta" />
          </div>
          <div class="col-auto">
            <q-btn icon="search" color="primary" no-caps label="filtrar" @click="cargarResumen" :loading="loading" />
          </div>
        </div>
      </div>
    </q-card>

    <div class="row q-col-gutter-sm q-mb-sm">
      <div class="col-12 col-sm-6">
        <q-card flat bordered class="q-pa-sm">
          <div class="text-caption text-grey-7">Total realizados</div>
          <div class="text-h4">{{ cards.total_periodo }}</div>
        </q-card>
      </div>
      <div class="col-12 col-sm-6">
        <q-card flat bordered class="q-pa-sm">
          <div class="text-caption text-grey-7">Pacientes únicos</div>
          <div class="text-h4">{{ cards.pacientes_periodo }}</div>
        </q-card>
      </div>
    </div>

    <q-card flat bordered class="q-pa-sm">
      <div class="text-subtitle2 q-mb-sm">Laboratorios más usados (top 10)</div>
      <div class="chart-box">
        <canvas id="chart-top-tipos"></canvas>
      </div>
    </q-card>

    <q-card flat bordered class="q-pa-sm q-mt-sm">
      <div class="text-subtitle2 q-mb-sm">Distribución (torta)</div>
      <div class="chart-box">
        <canvas id="chart-top-tipos-pie"></canvas>
      </div>
    </q-card>
  </q-page>
</template>

<script>
import Chart from 'chart.js/auto'

export default {
  name: 'PageIndex',
  data () {
    const now = new Date()
    const y = now.getFullYear() - 1
    return {
      loading: false,
      filtro: {
        ini: `${y}-01-01`,
        fin: `${y}-12-31`
      },
      cards: {
        total_periodo: 0,
        pacientes_periodo: 0
      },
      chartTopTipos: null,
      chartTopTiposPie: null
    }
  },
  mounted () {
    this.cargarResumen()
  },
  beforeUnmount () {
    if (this.chartTopTipos) this.chartTopTipos.destroy()
    if (this.chartTopTiposPie) this.chartTopTiposPie.destroy()
  },
  methods: {
    setRangoDia () {
      const d = new Date().toISOString().slice(0, 10)
      this.filtro.ini = d
      this.filtro.fin = d
      this.cargarResumen()
    },
    setRangoMes () {
      const n = new Date()
      const y = n.getFullYear()
      const m = n.getMonth()
      const ini = new Date(y, m, 1).toISOString().slice(0, 10)
      const fin = new Date(y, m + 1, 0).toISOString().slice(0, 10)
      this.filtro.ini = ini
      this.filtro.fin = fin
      this.cargarResumen()
    },
    setRangoAnio () {
      const y = new Date().getFullYear()
      this.filtro.ini = `${y}-01-01`
      this.filtro.fin = `${y}-12-31`
      this.cargarResumen()
    },
    async cargarResumen () {
      this.loading = true
      try {
        const res = await this.$axios.post(process.env.API + '/dashboard/resumen', {
          ini: this.filtro.ini,
          fin: this.filtro.fin
        })
        this.cards = res.data.cards || this.cards
        this.renderTopTipos(res.data.charts?.top_tipos || [])
      } finally {
        this.loading = false
      }
    },
    renderTopTipos (rows) {
      if (this.chartTopTipos) this.chartTopTipos.destroy()
      if (this.chartTopTiposPie) this.chartTopTiposPie.destroy()
      const canvas = document.getElementById('chart-top-tipos')
      const pieCanvas = document.getElementById('chart-top-tipos-pie')
      this.chartTopTipos = new Chart(canvas, {
        type: 'bar',
        data: {
          labels: rows.map(r => r.tipo || 'Sin tipo'),
          datasets: [{
            label: 'Cantidad',
            data: rows.map(r => Number(r.total)),
            backgroundColor: '#1976d2'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } }
        }
      })

      this.chartTopTiposPie = new Chart(pieCanvas, {
        type: 'pie',
        data: {
          labels: rows.map(r => r.tipo || 'Sin tipo'),
          datasets: [{
            data: rows.map(r => Number(r.total)),
            backgroundColor: ['#1976d2', '#43a047', '#fb8c00', '#8e24aa', '#039be5', '#6d4c41', '#00897b', '#c62828', '#5e35b1', '#f9a825']
          }]
        },
        options: { responsive: true, maintainAspectRatio: false }
      })
    }
  }
}
</script>

<style scoped>
.chart-box {
  position: relative;
  height: 360px;
}
</style>
