<template>
  <q-page class="q-pa-md">
    <div class="row">
      <div class="col-12 col-md-2"></div>
      <div class="col-12 col-md-8">
        <q-card>
          <q-tabs
            v-model="tab"
            dense
            class="text-grey"
            active-color="primary"
            indicator-color="primary"
            align="justify"
            narrow-indicator
          >
            <q-tab name="login" label="Ingresar" />
<!--            <q-tab name="registro" label="Registrarse" />-->
            <!--            <q-tab name="movies" label="Movies" />-->
          </q-tabs>
          <q-separator />
          <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="login">
              <div class="text-h6">Ingresar al sistemas</div>
              Por favor colocar Tu email y contraseña
              <q-form @submit.prevent="ingresar">
                <div class="row">
                  <div class="col-12">
                    <!--                    color="purple-12"-->
                    <q-input outlined v-model="email" label="Celular*" hint="Porfavor ingresar email">
                      <template v-slot:prepend>
                        <q-icon name="email" />
                      </template>
                    </q-input>
                  </div>
                  <div class="col-12">
                    <!--                    color="purple-12"-->
                    <q-input outlined v-model="password" label="Password*" :type="isPwd ? 'password' : 'text'" hint="Porfavor ingresar contraseña">
                      <template v-slot:prepend>
                        <q-icon name="lock" />
                      </template>
                      <template v-slot:append>
                        <q-icon
                          :name="isPwd ? 'visibility_off' : 'visibility'"
                          class="cursor-pointer"
                          @click="isPwd = !isPwd"
                        />
                      </template>
                    </q-input>
                  </div>
                  <div class="col-12 q-py-md">
                    <q-btn label="INGRESAR" color="primary" icon="login" class="full-width" type="submit"/>
<!--                    <q-btn label="Registrate" color="secondary" icon="how_to_reg" @click="tab='registro'" class="full-width q-mt-xs" />-->
<!--                    <div class="text-caption q-py-xs">-->
<!--                      <a href="#">Olvidate tu contraseña?</a>-->
<!--                    </div>-->

                  </div>
                </div>
              </q-form>
            </q-tab-panel>

<!--            <q-tab-panel name="registro">-->
<!--              <div class="text-h6">Registrate </div>-->
<!--              podras realizar tu pedidos-->
<!--              <q-form>-->
<!--                <div class="row">-->
<!--                  <div class="col-12">-->
<!--                    <q-input outlined v-model="user.email" label="Email*" hint="Porfavor ingresar email">-->
<!--                      <template v-slot:prepend>-->
<!--                        <q-icon name="email" />-->
<!--                      </template>-->
<!--                    </q-input>-->
<!--                  </div>-->
<!--                  <div class="col-12">-->
<!--                    <q-input outlined v-model="user.name" label="Nombre Completo*" hint="Porfavor ingresar Nombre Completo">-->
<!--                      <template v-slot:prepend>-->
<!--                        <q-icon name="people" />-->
<!--                      </template>-->
<!--                    </q-input>-->
<!--                  </div>-->
<!--                  <div class="col-12">-->
<!--                    <q-input outlined v-model="user.email" label="Celular*" hint="Porfavor ingresar email">-->
<!--                      <template v-slot:prepend>-->
<!--                        <q-icon name="phone" />-->
<!--                      </template>-->
<!--                    </q-input>-->
<!--                  </div>-->
<!--                  <div class="col-12">-->
<!--                    <q-input outlined v-model="user.cinit" label="Carnet *" hint="Porfavor ingresar carnet de identidad ">-->
<!--                      <template v-slot:prepend>-->
<!--                        <q-icon name="credit_card" />-->
<!--                      </template>-->
<!--                    </q-input>-->
<!--                  </div>-->
<!--                  <div class="col-12">-->
<!--                    <q-input outlined v-model="user.password" label="Password*" :type="isPwd ? 'password' : 'text'" hint="Porfavor ingresar contraseña">-->
<!--                      <template v-slot:prepend>-->
<!--                        <q-icon name="lock" />-->
<!--                      </template>-->
<!--                      <template v-slot:append>-->
<!--                        <q-icon-->
<!--                          :name="isPwd ? 'visibility_off' : 'visibility'"-->
<!--                          class="cursor-pointer"-->
<!--                          @click="isPwd = !isPwd"-->
<!--                        />-->
<!--                      </template>-->
<!--                    </q-input>-->
<!--                  </div>-->
<!--                  <div class="col-12 q-py-md">-->
<!--                    <q-btn label="Registrate" color="secondary" icon="how_to_reg" class="full-width"/>-->
<!--                    <q-btn label="Ingresa" color="primary" icon="login" @click="tab='login'" class="full-width q-mt-xs" />-->
<!--                    <div class="text-caption q-py-xs">-->
<!--                      <a href="#">Olvidate tu contraseña?</a>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </q-form>-->
<!--            </q-tab-panel>-->
          </q-tab-panels>
        </q-card>
      </div>
      <div class="col-12 col-md-2">
      </div>
    </div>
  </q-page>
</template>

<script>

export default {
  data(){
    return{
      email:'admin@test.com',
      password:'admin',
      tab:'login',
      user:{},
      isPwd:true,
    }
  },
  methods: {
    ingresar: function () {
      // return false
      this.$q.loading.show()
      let email = this.email
      let password = this.password
      this.$store.dispatch('login/login', { email, password })
        .then(() =>{
          this.$q.loading.hide()
          this.$router.push('/')
        })
        .catch(err => {
          this.$q.loading.hide();
          // console.log(err.response.data)
          this.$q.notify({
            message:err.response.data.res,
            color:'red',
            icon:'error'
          })
        })
    },
  },
  // setup () {
  //   const $store = useStore()
  //   const $q = useQuasar()
  //   const $router = useRouter()
  //   const email = ref("69603027")
  //   const password = ref("admin")
  //   return {
  //     tab: ref('registro'),
  //     isPwd: ref(true),
  //     email,
  //     password,
  //     login () {
  //       $q.loading.show()
  //       // let email = this.email
  //       // let password = this.password
  //       // console.log(email.value)
  //       $store.dispatch('showcase/login', { email:email.value, password:password.value })
  //         .then(() =>{
  //           $q.loading.hide()
  //           $router.push('/')
  //         })
  //         .catch(err => {
  //           $q.loading.hide();
  //           // console.log(err)
  //           $q.notify({
  //             message:err,
  //             color:'red',
  //             icon:'error'
  //           })
  //         })
  //     },
  //     onReset(){
  //       this.email=null;
  //       this.password=null;
  //     }
  //   }
  // }
}
// export default {
//   data(){
//     return {
//       email : "admin@test.com",
//       password : "admin"
//     }
//   },
//   methods: {
//     login: function () {
//       this.$q.loading.show()
//       let email = this.email
//       let password = this.password
//       this.$store.dispatch('login', { email, password })
//         .then(() =>{
//           this.$q.loading.hide()
//           this.$router.push('/')
//         })
//         .catch(err => {
//           this.$q.loading.hide();
//           // console.log(err.response.data)
//           this.$q.notify({
//             message:err.response.data.res,
//             color:'red',
//             icon:'error'
//           })
//         })
//     },
//     onReset(){
//       this.email=null;
//       this.password=null;
//     }
//   },
// }
</script>

<style scoped>
</style>
