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

                        <q-input
              filled
              v-model="dato.unidad"
              label="Unidad Reactivo"
              hint="Ingresar Unidad "
                  style="text-transform: uppercase;"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
                                    <q-input
              filled
              v-model="dato.minimo"
              label="Cantidad MIN Stock"
              hint="Ingresar el min "
              type="number"
              lazy-rules
              :rules="[ val => val && val >= 0 || 'Por favor ingresa datos']"
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
        :filter="filter"
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

          <q-td key="activo" :props="props" style="text-align:center">
              <q-badge color="green" v-if="props.row.activo" @click="cambiar(props)">
              ACTIVO
              </q-badge>
              <q-badge color="red" v-else @click="cambiar(props)">
              INACTIVO
              </q-badge>
          </q-td>
        </q-tr>
      </template>

                      <template v-slot:body-cell-opcion="props" >

          <q-td key="opcion" :props="props" v-if="props.row.activo">
              <q-btn  dense round flat color="green" @click="invRow(props)" icon="inventory_2"></q-btn>
              <q-btn  dense round flat color="accent" @click="retRow(props)" icon="receipt"></q-btn>
              <q-btn  dense round flat color="purple-14" @click="retirarRow(props)" icon="save_alt"></q-btn>
              <q-btn  dense round flat color="yellow" @click="editRow(props)" icon="edit"></q-btn>
              <q-btn  dense round flat color="red" @click="deleteRow(props)" icon="delete"></q-btn>
              <q-btn  dense round flat color="info" @click="printRow(props)" icon="print"></q-btn>
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
                        <q-input
              filled
              v-model="dato2.unidad"
              label="Unidad Reactivo"
              hint="Ingresar Unidad "
                  style="text-transform: uppercase;"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
                                    <q-input
              filled
              v-model="dato2.minimo"
              label="Cantidad MIN Stock"
              hint="Ingresar el min "
              type="number"
              lazy-rules
              :rules="[ val => val && val >= 0 || 'Por favor ingresa datos']"
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
            @submit.prevent="onRegistro"
            class="q-gutter-md"
          >
            <q-input filled v-model="dato2.nombre" label="codigo" hint="codigo" readonly />
          <div class="row">
          <div class="col-md-6 col-sm-12">
          <q-input filled v-model="invent.marca" label="Marca " hint="Ingresar marca " lazy-rules :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"/>
          </div>
          <div class="col-md-6 col-sm-12">

            <q-input filled v-model="invent.lote" label="Lote " hint="Ingresar lote " lazy-rules :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']" />
          </div>
          </div>

            <q-input filled v-model="invent.ingreso" label="Ingreso " hint="Ingresar cantidad " type="number" lazy-rules :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']" />
            <q-input filled v-model="invent.observacion" label="Observacion " hint="Ingresar dato "  />
                      <div class="row">
          <div class="col-md-6 col-sm-12">
            <q-input filled type="date" v-model="invent.fechavencimiento" label="Fecha Vencimiento  " hint="Ingresar dato " lazy-rules :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']" />
          </div>
          <div class="col-md-6 col-sm-12">
            <q-input filled type="date" v-model="invent.fecha" label="Fecha Reg" hint="Ingresar dato " lazy-rules :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']" />
          </div>
          </div>

            <div>
              <q-btn label="Registrar" type="submit" color="positive" icon="add_circle" :loading="loading"/>
                <q-btn  label="Cancelar" icon="delete" color="negative" v-close-popup />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>


    <q-dialog v-model="dialog_inv">
      <q-card>
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Inventario</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
        <q-table
        :columns="columns2"
        :rows="inventarios">
              <template v-slot:body-cell-estado="props">

          <q-td key="estado" :props="props" style="text-align:center">
              <q-badge :color="props.row.estado=='ACTIVO'?'green':'red'" :value="props.row.estado">
              {{props.row.estado}}
              </q-badge>
          </q-td>
      </template>
      <template v-slot:body-cell-opcion="props">
              <q-td key="opcion" :props="props" style="text-align:center" >
              <!--<q-btn  dense round flat color="teal" @click="retirarRow(props)" icon="science"></q-btn>-->
              <q-btn  dense round flat color="red" @click="delingreso(props)" icon="delete" v-if="props.row.saldo>0" />

      </q-td>
      </template>

        </q-table>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="dialog_retiro">
      <q-card>
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Registrar Retiro {{reactivo.nombre}}</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <q-form
            @submit.prevent="onRetiro"
            class="q-gutter-md"
          >
            <q-input
              filled
              v-model="retiro.fecha"
              label="Fecha Reg"
              type="date"
              lazy-rules
              :rules="[ val =>  val.length > 0 || 'Por favor fecha ']"
            />
            <q-input
              filled
              v-model="retiro.egreso"
              label="Cantidad"
              hint="Cantidad"
              type="number"
              lazy-rules
              :rules="[ val => val>0 && val <= reactivo.stock || 'Por favor cantidad ']"
            />

            <q-input
              filled
              v-model="retiro.observacion"
              label="Observacion "
              hint="Ingresar dato "

            />

            <div>
              <q-btn label="Registrar" type="submit" color="positive" icon="add_circle" :loading="loading"/>
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
import {date} from 'quasar'
import {jsPDF} from "jspdf";
import $ from 'jquery'
export default {
  data(){
    return{
      filter:'',
      rows:[],
      dato:{},
      dato2:{},
      loading:false,
      invent:{fechavencimiento:date.formatDate(Date.now(),'YYYY-MM-DD'),fecha:date.formatDate(Date.now(),'YYYY-MM-DD')},
      alert:false,
      dialog_mod:false,
      dialog_reg:false,
      dialog_inv:false,
      dialog_retiro:false,
      inventarios:[],
      inventario:{},
      retiro:{fecha:date.formatDate(Date.now(),'YYYY-MM-DD')},
      reactivo:{},
      columns:[
        { name: 'codigo', label: 'CODIGO', field: 'codigo'},
        { name: 'nombre', label: 'NOMBRE', field: 'nombre'},
        { name: 'unidad', label: 'UNIDAD', field: 'unidad'},
        { name: 'stock', label: 'STOCK', field: 'stock'},
        { name: 'minimo', label: 'MINIMO', field: 'minimo'},
        { name: 'activo', label: 'ESTADO', field: 'activo',align:"center"},
        { name: 'opcion', label: 'OPCION', field: 'opcion'},
      ],

      columns2:[
        { name: 'opcion', label: 'OPCION', field: 'opcion'},
        { name: 'marca', label: 'MARCA', field: 'marca'},
        { name: 'lote', label: 'LOTE', field: 'lote'},
        { name: 'ingreso', label: 'INGRESO', field: 'ingreso'},
        { name: 'fechavencimiento', label: 'FEC VENC', field: 'fechavencimiento'},
        { name: 'observacion', label: 'OBSERVACION', field: 'observacion'},
        { name: 'estado', label: 'ESTADO', field: 'estado'},
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
    this.fecaduca();
    this.rminimo();
    this.baja();

  },
  methods: {
    labRow(props){
      this.reactivo=props.row;
      this.dialog_retiro=true;

    },
    baja(){
      this.$axios.post(process.env.API+'/fvencido').then(res=>{})

    },
    fecaduca(){
      this.$axios.post(process.env.API+'/caduca').then(res=>{
        console.log(res.data)
        res.data.forEach(r => {
        this.$q.notify({
          position:'center',
          color: 'red-8',
          textColor: 'white',
          icon: 'report',
          message: 'MENOS DE 7 DIAS PARA CADUCAR '+r.nombre
        });
        });
      })
    },
        rminimo(){
      this.$axios.post(process.env.API+'/minimo').then(res=>{
        console.log(res.data)
        res.data.forEach(r => {
        this.$q.notify({
          position:'center',
          color: 'yellow-8',
          textColor: 'black',
          icon: 'science',
          message: 'ESTA POR TERMINAR '+r.nombre
        });
        });
      })
    },
    onRetiro(){
      this.loading=true
      this.retiro.reactivo_id=this.reactivo.id;
      this.$axios.post(process.env.API+'/retiro',this.retiro).then(res=>{
                 this.$q.notify({
          message: 'Se Retiro correctamente',
          color: 'green'
        })
        this.dialog_retiro=false;
        this.listado();
        this.loading=false
        this.retiro={fecha:date.formatDate(Date.now(),'YYYY-MM-DD')}
      })

    },
    retirarRow(props){
      //this.inventario=props.row;
      //console.log(this.inventario)
      this.reactivo=props.row
      this.dialog_retiro=true;
    },
    retRow(props){
      this.dato2=props.row;
      this.$axios.post(process.env.API+'/listinventario',this.dato2).then(res=>{
        this.inventarios=res.data;
        this.dialog_inv=true;
      })
    },
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
    printRow(props){
      this.reactivo=props.row;
      this.$q.dialog({
        title: 'GENERER IMPRESION',
        message: 'A PARTIR DE QUE FECHA?',
        prompt: {
          model: '',
          type: 'date' // optional
        },
        cancel: true,
        persistent: false
      }).onOk(data => {
        if(data) {
          this.$axios.post(process.env.API+'/impresion',{reactivo:this.reactivo,fecha:data}).then(res=>{
            console.log(res.data)
            //return false
            /*let cad=" <html><style> table, th, td {border: 1px solid;} table { width:100%; border-collapse: collapse;} </style><body>"
                cad+='<table><tr><td colspan=4 rowspan=3><img src="img/natividad.jpeg" style="height: 1.5cm;"></td><th colspan=4>KARDEX DE LABORATORIO</th></tr>';
                cad+="<tr><th>REACTIVO</th><td colspan=3>"+this.reactivo.nombre+"</td></tr>";
                cad+="<tr><th>CODIGO</th><td colspan=3>"+this.reactivo.codigo+"</td></tr>";
                cad+=res.data;
            console.log(cad)
        let myWindow = window.open("", "Imprimir", "width=1000,height=500");
        myWindow.document.write(cad);
        myWindow.document.close();
        myWindow.focus();
        setTimeout(function(){
          myWindow.print();
          myWindow.close();
        },500);
          }
          */

            var doc = new jsPDF('landscape','mm','legal')
        doc.setFont("arial");
        doc.setFontSize(10);
        var img = new Image()
        img.src = 'img/natividad.jpeg'
        let x=0
        let y=0
        //inicio datos paciete
        function header(nombre,codigo,stock){
        doc.setFontSize(10);
        doc.addImage(img, 'jpg', 195, 3, 70, 20)
        doc.rect(x+195, y+25, 155, 1)
        doc.setFont(undefined, 'bold')
        doc.setTextColor(57,73,171)
        doc.text('KARDEX DE LABORATORIO',x+320, y+10,'center')
        doc.text(['REACTIVO','CODIGO','STOCK'],x+280, y+15,'left')
        doc.setTextColor(0,0,0)
        doc.text([nombre,codigo,stock+''],x+300, y+15,'left')
        doc.setTextColor(0,0,255)
        doc.text('FECHA  ',x+195, y+30,'left')
        doc.text('VENC ',x+215, y+30,'left')
        doc.text('MARCA',x+235, y+30,'left')
        doc.text('LOTE ',x+255, y+30,'left')
        doc.text('INGRESO',x+275, y+30,'left')
        doc.text('EGRESO ',x+295, y+30,'left')
        doc.text('SALDO ',x+315, y+30,'left')
        doc.text('OBS',x+330, y+30,'left')
        }


        header(this.reactivo.nombre,this.reactivo.codigo,this.reactivo.stock);
        doc.setTextColor(0,0,0)
                  let total=0
        doc.setFontSize(8);
          //y=30
        doc.setFont(undefined, 'normal')
        res.data.forEach(r => {
          y+=5
          total = parseFloat(r.anterior) + parseFloat(r.ingreso) - parseFloat(r.egreso)
          doc.text(r.fecha+'',x+195, y+30,'left')
          doc.text(r.fechavencimiento==null?'':r.fechavencimiento,x+215, y+30,'left')
          doc.text(r.marca==null?'':r.marca,x+235, y+30,'left')
          doc.text(r.lote==null?'':r.lote,x+255, y+30,'left')
          doc.text(r.ingreso+'',x+275, y+30,'left')
          doc.text(r.egreso+'',x+295, y+30,'left')
          doc.text( total+'',x+315, y+30,'left')
          doc.text(r.observacion==null?'':r.observacion,x+330, y+30,'left')
        if(y+35>210){
          doc.addPage()
          y=0
          header(this.reactivo.nombre,this.reactivo.codigo,this.reactivo.stock);
          doc.setFontSize(8);
          doc.setFont(undefined, 'normal')
           doc.setTextColor(0,0,0)
        }

        });
        doc.output('save','Reactivo-'+this.reactivo.nombre+'.pdf');

         })}

      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })
    },
    deleteRow(props){
      this.dato2=props.row;
      console.log(this.dato2);

      this.$q.dialog({
        title: 'Eliminar Reactivo',
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

        delingreso(props){
      this.inventario=props.row;

      this.$q.dialog({
        title: 'Eliminar Reactivo',
        message: 'Esta Seguro de Eliminar ?',
        cancel: true,
      }).onOk(() => {
          this.$axios.delete(process.env.API+'/inventario/'+this.inventario.id).then(res=>{
         this.$q.notify({
          message: 'Se elimino correctamente',
          color: 'green'
        })
        this.dialog_inv=false
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
        this.loading=true
        this.$axios.post(process.env.API+'/inventario',this.invent).then(res=>{
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'info',
          message: 'Registrado correctamente!'
        });
        this.dialog_reg=false;
        this.invent={fechavencimiento:date.formatDate(Date.now(),'YYYY-MM-DD'),fecha:date.formatDate(Date.now(),'YYYY-MM-DD')};

        this.listado();
        this.loading=false
     })
    },
  },

}
</script>
