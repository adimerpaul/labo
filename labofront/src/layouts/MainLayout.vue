<template>
  <q-layout view="lHh Lpr lFf">
    <q-header >
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="leftDrawerOpen = !leftDrawerOpen"
        />

        <q-toolbar-title>
          {{$store.state.login.user.name}}
        </q-toolbar-title>
        <div>v{{ $q.version }}</div>
      </q-toolbar>
      <div class="">
        <div class="text-h3">Laboratorio v11.4.5</div>
        <div class="text-subtitle1">{{now}} </div>
      </div>
      <q-img
        src="~assets/mountains.jpg"
        class="header-image absolute-top"
      />
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      :width="200"
      :breakpoint="600"
    >
      <q-scroll-area style="height: calc(100% - 192px); margin-top: 192px; border-right: 1px solid #ddd">
        <q-list bordered>
          <q-item to="/" exact clickable v-ripple>
            <q-item-section avatar>
              <q-icon name="home" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Principal</q-item-label>
              <q-item-label caption>Menu </q-item-label>
            </q-item-section>
          </q-item>
          <q-item to="/login" exact clickable v-ripple v-if="!$store.getters['login/isLoggedIn']">
            <q-item-section avatar>
              <q-icon name="login" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Ingresar</q-item-label>
              <q-item-label caption>Ingresar al sistema</q-item-label>
            </q-item-section>
          </q-item>
          <q-item to="user"  exact clickable v-ripple v-if="$store.state.login.booluser">
            <q-item-section avatar>
              <q-icon name="people" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Usuarios</q-item-label>
<!--              <q-item-label caption>Controlar usuarios</q-item-label>-->
            </q-item-section>
          </q-item>

          <q-item to="doctor"  exact clickable v-ripple v-if="$store.state.login.booldoctor">
            <q-item-section avatar>
              <q-icon name="home" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Doctor</q-item-label>
<!--              <q-item-label caption>Controlar doctores</q-item-label>-->
            </q-item-section>
          </q-item>
         <!--  <q-item to="pacientes"  exact clickable v-ripple v-if="$store.state.login.boolpacientes">
            <q-item-section avatar>
              <q-icon name="people" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Pacientes</q-item-label>
             <q-item-label caption>Controlar pacientes</q-item-label>
            </q-item-section>
          </q-item> -->
          <q-item to="pacientes2"  exact clickable v-ripple v-if="$store.state.login.boolpacientes">
            <q-item-section avatar>
              <q-icon name="people" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Pacientes</q-item-label>
              <!--              <q-item-label caption>Controlar pacientes</q-item-label>-->
            </q-item-section>
          </q-item>

          <q-item to="historial"  exact clickable v-ripple v-if="$store.state.login.boolhistorial">
            <q-item-section avatar>
              <q-icon name="code" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Historial</q-item-label>
<!--              <q-item-label caption>Controlar usuarios</q-item-label>-->
            </q-item-section>
          </q-item>

          <q-item to="reporte"  exact clickable v-ripple v-if="$store.state.login.boolhistorial">
            <q-item-section avatar>
              <q-icon name="code" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Reporte se</q-item-label>
<!--              <q-item-label caption>Controlar usuarios</q-item-label>-->
            </q-item-section>
          </q-item>

          <q-item to="reactivo"  exact clickable v-ripple v-if="$store.state.login.boolreactivo">
            <q-item-section avatar>
              <q-icon name="shop" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Reactivos</q-item-label>
              <!--              <q-item-label caption>Controlar usuarios</q-item-label>-->
            </q-item-section>
          </q-item>

                    <q-item to="seguros"  exact clickable v-ripple v-if="$store.state.login.boolseguro">
            <q-item-section avatar>
              <q-icon name="list" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Seguros</q-item-label>
<!--              <q-item-label caption>Controlar usuarios</q-item-label>-->
            </q-item-section>
          </q-item>
          <q-item to="antibioticos"  exact clickable v-ripple v-if="$store.state.login.boolseguro">
            <q-item-section avatar>
              <q-icon name="o_verified" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Antibiotico</q-item-label>
            </q-item-section>
          </q-item>

          <!--
          <q-item to="porcaducar"  exact clickable v-ripple v-if="$store.state.login.boolporcaducar">
            <q-item-section avatar>
              <q-icon name="list" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Por caducar</q-item-label>
                          <q-item-label caption>Controlar usuarios</q-item-label>
            </q-item-section>
          </q-item>-->
          <q-item @click="logout"  exact clickable v-ripple v-if="$store.getters['login/isLoggedIn']">
            <q-item-section avatar>
              <q-icon name="logout" />
            </q-item-section>
            <q-item-section>
              <q-item-label> Salir</q-item-label>
              <q-item-label caption>Salir</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>

      <q-img class="absolute-top" src="~assets/mountains.jpg" style="height: 192px">
        <div class="absolute-bottom bg-transparent">
          <q-avatar size="56px" class="q-mb-sm">
            <img src="~assets/logo.png">
          </q-avatar>
          <div class="text-weight-bold">Laboratorio</div>
          <div>Clinica natividad</div>
        </div>
      </q-img>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import {date} from 'quasar';
export default {
  name: 'MainLayout',
  data () {
    return {
      leftDrawerOpen: false,
      // essentialLinks: linksData
    }
  },
  computed:{
    now(){
      let timeStamp = Date.now()
      // let formattedString = date.formatDate(timeStamp, 'dddd D MMMM');
      let formattedString = date.formatDate(timeStamp, 'dddd D MMMM', {
        days: ['Domingo', 'Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo' /* and all the rest of days - remember starting with Sunday */],
        daysShort: ['Dum', 'Lun', /* and all the rest of days - remember starting with Sunday */],
        months: ['Enero', 'Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre', /* and all the rest of months */],
        monthsShort: ['Ian', 'Feb', /* and all the rest of months */]
      })
      return formattedString
    }
  },
  methods:{
    logout(){
      this.$q.loading.show()
      this.$store.dispatch('login/logout')
        .then(() => {
          this.$q.loading.hide()
          this.$router.push('/login')
        })
    }
  }
}
</script>
<style lang="scss">
.header-image{
  height: 100%;
  z-index: -1;
  opacity: 0.2;
  filter: grayscale(100%);
}
</style>
