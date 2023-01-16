<template>
  <q-page class="q-pa-xs">
    <q-card>
            <q-btn  label="Registrar Seguro" color="positive" @click="alert = true" icon="add_circle" class="q-mb-xs" />
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
              v-model="dato.nombre"
              label="Nombre "
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
        title="SEGUROS"
        :columns="columns"
        :rows="rows"
        row-key="name"
        :filter="filter"
      >
        <template v-slot:top-right>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>

      <template v-slot:body-cell-paciente="props">

          <q-td key="opcion" :props="props">
          <ul>
            <li v-for="p in props.row.paciente" :key="p"><q-btn flat round icon="list" dense color="accent" @click="labo(p)"/>{{p.nombre}} {{p.paterno}} {{p.materno}}</li>
          </ul>
          </q-td>
      </template>

      <template v-slot:body-cell-opcion="props">

          <q-td key="opcion" :props="props">
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

                <q-dialog v-model="dialog_lab">
      <q-card>
        <q-card-section class="bg-green-8 text-white">
          <div class="text-h6">Paciente: {{paciente.nombre}} {{paciente.paterno}} {{paciente.materno}} <br> LABORATORIOS</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">

          <q-table title="FORMULARIOS" :rows="laboratorios" :columns="colab"  row-key="name" :filter="filterlab">
                  <template v-slot:top-right>
              <q-input borderless dense debounce="300" v-model="filterlab" placeholder="Search">
                <template v-slot:append>
                  <q-icon name="search" />
                </template>
              </q-input>
            </template>
                  <template v-slot:body-cell-opcion="props">

          <q-td key="opcion" :props="props">
              <q-btn  dense round flat color="info" @click="imprimir(props.row)" icon="print"></q-btn>
          </q-td>
      </template>
          </q-table>

        </q-card-section>
      </q-card>
    </q-dialog>

    </q-card>
  </q-page>
</template>

<script>
import {date} from "quasar";
import {jsPDF} from "jspdf";
import $ from 'jquery'
import moment from 'moment'
export default {
  data(){
    return{
      filter:'',
      filterlab:'',
      alert:false,
      dialog_mod:false,
      dato:{},
      paciente:{},
      dialog_lab:false,
      rows:[],

      columns:[
        { name: 'nombre', label: 'SEGURO', field: 'nombre'},
        { name: 'paciente', label: 'PACIENTES', field: 'paciente',align:'left'},
        { name: 'opcion', label: 'OPCION', field: 'opcion'},
      ],

        colab:[
        { name: 'opcion', label: 'OPCION', field: 'opcion'},
        { name: 'fecha', label: 'FECHA', field: 'fechatoma'},
        { name: 'laboratorio', label: 'LABORATORIO', field: row=>row.tipo.nombre,align:'left'},
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
    this.listado();

  },
  methods: {

    imprimir(labo){
      //console.log(labo)
      //console.log(this.paciente)
          var nacimiento=moment(this.paciente.fechanac)
          var hoy=moment()
          var anios=hoy.diff(nacimiento,"years")
          this.paciente.tiempo=anios
          this.paciente.paciente=this.paciente.nombre+' '+this.paciente.paterno+' '+this.paciente.materno
      let p=this.paciente
      //console.log(this.paciente)
      //return false
      let l=labo
            if(l.tipo_id==1)
        this.hemograma(p,l)
      if(l.tipo_id==2)
        this.sanguinea(p,l)
      if(l.tipo_id==3)
        this.orina(p,l)
      if(l.tipo_id==4)
        this.uretral(p,l)
      if(l.tipo_id==5)
        this.vaginal(p,l)
      if(l.tipo_id==6)
        this.heces(p,l)
      if(l.tipo_id==7)
        this.simple(p,l)
      if(l.tipo_id==8)
        this.seriado(p,l)
      if(l.tipo_id==9)
        this.lgmserologia(p,l)
      if(l.tipo_id==10)
        this.labserologia(p,l)
      if(l.tipo_id==11)
        this.reserologia(p,l)
      if(l.tipo_id==12)
        this.ensayo(p,l)
      if(l.tipo_id==13)
        this.embarazo(p,l)
      if(l.tipo_id==14)
        this.alcoholemia(p,l)
      if(l.tipo_id==15)
        this.widal(p,l)
      if(l.tipo_id==16)
        this.multidrogas(p,l)
      if(l.tipo_id==17)
        this.glicosilada(p,l)
      if(l.tipo_id==18)
        this.gasometria(p,l)
      if(l.tipo_id==19)
        this.hepatitis(p,l)       
      if(l.tipo_id==20)
        this.toxoplasmosis(p,l)  
      if(l.tipo_id==21)
        this.hemodialisis(p,l)  
      if(l.tipo_id==22)
        this.hemodialisistri(p,l)   
              if(l.tipo_id==23)
        this.miscelaneo(p,l)   
              if(l.tipo_id==24)
        this.hierro(p,l)   

    },

   seriado(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 008',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('COPROPARASITOLOGICO SERIADO',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+48, 205, 85)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('                             COPROPARASITOLOGICO SERIADO',x+50,y+52)
    doc.setFont(undefined, 'bold')
    doc.text('     1º MUESTRA',x+8,y+58)
    doc.setFont(undefined, 'normal')
    doc.text(['Fecha','Hora'],x+8,y+65)
    doc.text([l.d1,l.d2],x+20,y+65)
    // doc.text(l.d3,x+50,y+58)

        doc.setFont(undefined, 'bold')
    doc.text('     2º MUESTRA',x+8,y+80)
    doc.setFont(undefined, 'normal')
    doc.text(['Fecha','Hora'],x+8,y+85)
    doc.text([l.d4,l.d5],x+20,y+85)
    // doc.text(l.d6,x+50,y+80)

        doc.setFont(undefined, 'bold')
    doc.text('     3º MUESTRA',x+8,y+100)
    doc.setFont(undefined, 'normal')
    doc.text(['Fecha','Hora'],x+8,y+105)
    doc.text([l.d7+'',l.d8+''],x+20,y+105)
    // doc.text(l.d9+'',x+50,y+100)

    doc.setTextColor(57,73,171)
        doc.setFont(undefined, 'bold')
    doc.text('OBSERVACIONES',x+8,y+120)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text(l.d10,x+8,y+125)
    doc.setTextColor(0,0,0)

    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)

    doc.rect(x+5, y+135, 205, 20)
    doc.rect(x+5, y+54, 205, 22)
    //doc.rect(x+5, y+75, 205, 25)
    doc.rect(x+5, y+96, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+140)
    doc.setFont(undefined, 'NORMAL')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+8,y+145)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(57,73,171)
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+140,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+140,'left')
     // doc.text(l.d3,x+50,y+58)
     doc.html( '<div style="font-size: 3px;width: 500px">'+l.d3+'</div>' , {
       callback: function (doc) {
         doc.html( '<div style="font-size: 3px;width: 500px">'+l.d6+'</div>' , {
           callback: function (doc) {
             doc.html( '<div style="font-size: 3px;width: 500px">'+l.d9+'</div>' , {
               callback: function (doc) {
                window.open(doc.output('bloburl'), '_blank');
                 //doc.output('save','COPROPARASITOLOGICO SERIADO-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
               },
               x: x+50,
               y: y+95,
             })
           },
           x: x+50,
           y: y+75,
         })
       },
       x: x+50,
       y: y+55,
     })
     // doc.save("Pago"+date.formatDate(Date.now(),'DD-MM-YYYY')+".pdf");
     // window.open(doc.output('bloburl'), '_blank');
    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    //  window.open(doc.output('bloburl'), '_blank');
    },


                  labserologia(p,l){

            var doc = new jsPDF('landscape',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 195, 3, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+195, y+27, 155, 23)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+320, y+10,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+225, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 010',x+323, y+30)
    doc.setTextColor(57,73,171)
    doc.text('SEROLOGIA',x+270, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+196, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+268, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+305, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+305, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+337, y+35,'center')
    doc.setTextColor(211,47,47)
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+195, y+53, 155, 130)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(211,47,47)
    doc.text('',x+233,y+55)
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.text('                   VALOR REF             METODO DE PRUEBA',x+260,y+60)
    doc.setFontSize(12);

    doc.setFont(undefined, 'normal')
    doc.setFontSize(11);
    doc.setFontSize(11);
    //doc.text(['Factor Reumatoide ','    (Latex)','Antiestreptolisina',' ','Proteina C Reactiva','','RPR','','Prueba Rapida Sifilis ','','Prueba Rapida VIH ',' ','Hepatitis A ',' ','Hepatitis B ',' ','Hepatitis C  ',' ','Helicobacter Pylori ','   en Sangre','Helicobacter Pylori ','    en Heces' ,'Troponina I ','','PSA','','OBSERVACIONES'],x+200,y+65,'left')
    doc.text(['Factor Reumatoide ','    (Latex)','Antiestreptolisina',' ','Proteina C Reactiva','','RPR','','Prueba Rapida Sifilis ','','Prueba Rapida VIH ',' ','Hepatitis A ',' ','Hepatitis B ',' ','Hepatitis C  ',' ','Helicobacter Pylori ','   en Sangre',' ','    ' ,'','','','','OBSERVACIONES'],x+200,y+65,'left')
    doc.setTextColor(0,0,0)
    let regex = /^[0-9]+([.][0-9]+)?$/;
    
    if( l.d1!='' && regex.test(l.d1))
      doc.text(l.d1+' UI/ml',x+240,y+65,'left')
    else
      doc.text(l.d1,x+240,y+65,'left')
    if( l.d2!='' && regex.test(l.d2))
      doc.text(l.d2+' UI/l',x+240,y+73,'left')
    else
    doc.text(l.d2,x+240,y+73,'left')
    if( l.d3!='' && regex.test(l.d3))
      doc.text(l.d3+ ' mg/dl',x+240,y+82,'left')
    else
      doc.text(l.d3,x+240,y+82,'left')
    doc.text(l.d4,x+240,y+91,'left')
    doc.text(l.d5,x+240,y+101,'left')
    doc.text(l.d6,x+240,y+110,'left')
    doc.text(l.d7,x+240,y+119,'left')
    doc.text(l.d8,x+240,y+128,'left')
    doc.text(l.d9,x+240,y+136,'left')
    doc.text(l.d10,x+240,y+146,'left')
    //doc.text(l.d11 ,x+240,y+156,'left')
    //doc.text(l.d12,x+240,y+164,'left')
   // if(l.d13!='' && regex.test(l.d13))
    //  doc.text(l.d13+' ng/ml',x+240,y+172,'left')
   // else
  //    doc.text(l.d13,x+240,y+172,'left')
    doc.text(l.d14,x+240,y+181,'left')

        /*if(($row->d1<0 || $row->d1>500)&& $row->d1!='')
        doc.setTextColor(195,47,47)
        else
        doc.setTextColor(0,0,0)

        if($row->paciente->sexo=='Masculino' && ($row->d2<30 || $row->d2>350)&& $row->d2!='')$cd2='color:#ff726f;';
        if($row->paciente->sexo=='Femenino' && ($row->d2<20 || $row->d2>250)&& $row->d2!='')$cd2='color:#ff726f;';
        if(($row->d3<0 || $row->d3>7)&& $row->d3!='')$cd3='color:#ff726f;';
        if(($row->d4<0 || $row->d4>4)&& $row->d4!='')$cd4='color:#ff726f;';
        if(($row->d5<0 || $row->d5>10)&& $row->d5!='')$cd5='color:#ff726f;';
        if(($row->d6<0 || $row->d6>0.11)&& $row->d6!='')$cd6='color:#ff726f;';
        if(($row->d7<0 || $row->d7>10)&& $row->d7!='')$cd7='color:#ff726f;';*/
    //doc.text(['100',' ','100',' ','100',' ','100',' ','100',' ','100',' ',' ','100',' ',' ',' ','100'],x+250,y+65,'center')
    doc.setFontSize(8);
    doc.text('Hasta 8 UI/ml ',x+290,y+65,'center')
    doc.text('Hasta 200 UI/l',x+290,y+74,'center')
    doc.text('Hasta 0.8 mg/dl ',x+290,y+83,'center')
    doc.text('No Reactivo',x+290,y+92,'center')
    doc.text('No Reactivo',x+290,y+101,'center')
    doc.text('No Reactivo',x+290,y+110,'center')
    doc.text('NEGATIVO / POSITIVO ',x+290,y+119,'center')
    doc.text('NEGATIVO / POSITIVO ',x+290,y+128,'center')
    doc.text('NEGATIVO / POSITIVO ',x+290,y+137,'center')
    doc.text('NEGATIVO / POSITIVO ',x+290,y+146,'center')
    //doc.text('NEGATIVO / POSITIVO ',x+290,y+155,'center')
    //doc.text('NEGATIVO / POSITIVO ',x+290,y+164,'center')
    //doc.text('Hasta 4 ng/ml',x+290,y+172,'center')
    doc.setFontSize(11);
    doc.setTextColor(57,73,171)
    //doc.text(['Aglutinacion directa','','Aglutinacion directa','','Aglutinacion directa','','Floculacion directa','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida ','Semicuantitativo'],x+312,y+65,'left')
    doc.text(['Aglutinacion directa','','Aglutinacion directa','','Aglutinacion directa','','Floculacion directa','','Prueba Rapida ','Inmunocromatografica','Prueba Rapida ','Inmunocromatografica','Prueba Rapida ','Inmunocromatografica','Prueba Rapida ','Inmunocromatografica','Prueba Rapida ','Inmunocromatografica','Prueba Rapida ','Inmunocromatografica','Prueba Rapida ','Inmunocromatografica',' ','',' ',''],x+312,y+65,'left')
       doc.setFontSize(11);

    doc.rect(x+195, y+185, 155, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+205,y+190,'left')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+205,y+195,'left')
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.setLineHeightFactor(1.5)
    doc.text(['Fecha toma de Muestra','Hora toma Muestra','Fecha Entrega de Resultado'],x+280,y+190,'left')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+330,y+190,'left')

      //doc.output('save','LABORATORIO SEROLOGIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
         window.open(doc.output('bloburl'), '_blank');
      },

   lgmserologia(p,l){
          var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    var imgmas = new Image()
    var imgmenos = new Image()
    img.src = 'img/natividad.jpeg'
    imgmas.src = 'img/suma.JPEG'
    imgmenos.src = 'img/resta.JPEG'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 009',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('PRUEBA DE COVID-19',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+48, 205, 25)
    doc.rect(x+5, y+75, 205, 72)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('               PRUEBA ANTICUERPOS CUANTITATIVOS ANTI SARS COV-2 lg M / lg G',x+50,y+52)
    doc.text('      METODO: INMUNOENSAYO DE FLUORECENCIA (FIA)                                      Valor de Referencia',x+8,y+58)
    doc.setFont(undefined, 'normal')
    let vallggm1='',vallggm2='',obs1='',obs2=''
     if( parseFloat(l.d1) < 0.9 || parseFloat(l.d1)>=1.1)
     {vallggm1=l.d1
      obs1=l.d2}
            if(parseFloat(l.d1) >= 0.9 && parseFloat(l.d1)<1.1)
            {vallggm2=l.d1
            obs2=l.d2
            }
    doc.text(['lgM','','lgG'],x+30,y+63)
    doc.setTextColor(0,0,0)
    doc.text([vallggm1,vallggm2],x+70,y+63)
    doc.text(l.d3,x+70,y+71)
    if(l.d2=='POSITIVO')
    doc.setTextColor(255,0,0)

    doc.text([obs1,obs2],x+100,y+63)
    doc.setTextColor(0,0,0)
            if(l.d4=='POSITIVO')
    doc.setTextColor(255,0,0)
    doc.text(l.d4,x+100,y+71)
    doc.setTextColor(0,0,0)

    doc.setTextColor(57,73,171)
    doc.text(['menor a 0.9 NEGATIVO PARA lgG/lgM','0.9 menor igual & mayor 1.1 INDETERMINADO','mayor igual 1.1 POSITIVO lgG/lgM'],x+170,y+63,'center')
    doc.setFont(undefined, 'bold')
    doc.text('                        INTERPRETACION DE RESULTADOS',x+50,y+80)



    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text('              lgM                lgG                         INTERPRETACION                          COMENTARIO',x+5,y+85)
    doc.setFont(undefined, 'normal')
    doc.addImage(imgmenos, 'jpeg', 20, 90, 5, 5)
    doc.addImage(imgmenos, 'jpeg', 40, 90, 5, 5)
    doc.addImage(imgmas, 'jpeg', 20, 95, 5, 5)
    doc.addImage(imgmenos, 'jpeg', 40, 95, 5, 5)
    doc.addImage(imgmas, 'jpeg', 20, 100, 5, 5)
    doc.addImage(imgmas, 'jpeg', 40, 100, 5, 5)
    doc.addImage(imgmenos, 'jpeg', 20, 105, 5, 5)
    doc.addImage(imgmas, 'jpeg',  40, 105, 5, 5)
    doc.setFont(undefined, 'normal')
    doc.setFontSize(9);

    doc.text(['>Ausencia de la Enfermedad o Falso Negativo','>Inicio Temprano de ka Enfermedad Infeccion','   Aguda de la Enfermedad','>Fase activa de la Enfermedad','>Inmunidad Fase final de la Infeccion','  pasada y curada'],x+50,y+90,'left')
    doc.text(['Paciente en fase de evolucion de la enfermedad mayor de 21 dias','Se debe repetir dentro de 5 a 7 dias la prueba','Correlacionar con clinica del paciente y realizar examenes ','  complementarios (Rayos X, Tomografia)','Paciente en fase de evolucion de la enfermedad ','  mayor a 21 dias'],x+120,y+90,'left')
    doc.setTextColor(57,73,171)
    doc.setFontSize(9);
    doc.text(['NOTA: Las pruebas rapidas para COVID-19 NO SON CONFIRMATORIAS, en caso de salir positivo alguno de los anticuerpo , se recomienda',' una segunda toma de muestra con la Tecnica de HISOPADO NASOFARINGEO para RT - PCR y asi confirmar su DIAGNOSTICO','','Todas las personas producimos anticuerpos a diferente velocidad dependiendo del agente patogeno y nuestra genetica que es lo que determina la ','funcionalidad de nuestro Sistema Inmunologico',
    '','Los Resultados deben ser interpretados en funcion de la Clinica del paciente y dias de evolucion de la enfermedad'],x+8,y+115,'left')

    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('OBSERVACIONES',x+8,y+142)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.text(l.d5,x+8,y+145)

    doc.rect(x+5, y+147, 205, 15)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+150)
    doc.setFont(undefined, 'NORMAL')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+8,y+155)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(57,73,171)
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+150,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+150,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    //  doc.output('save','PRUEBA DE COVID-19-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    window.open(doc.output('bloburl'), '_blank');

    },
    simple(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 007',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('COPROPARASITOLOGICO SIMPLE',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+48, 205, 86)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('                             COPROPARASITOLOGICO SIMPLE',x+50,y+52)
    doc.setFont(undefined, 'normal')
    doc.text(['ASPECTO DE LA MUESTRA','COLOR','CELULAS EPITELIALES','LEUCOCITOS','HEMATIES','GRASAS','LEVADURAS','ESPORAS MICOTICAS','ALMIDON','PARASITOS','PIOCITOS'],x+8,y+58)
    doc.setTextColor(0,0,0)
    doc.text([l.d1,l.d2,l.d3,l.d4,l.d5,l.d6,l.d7,l.d8,l.d9,l.d10,l.d11],x+70,y+58)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('MOCO FECAL       ',x+8,y+102)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text(l.d12,x+50,y+102)

    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['Polimorfonuclueares: ','Mononuclueras: '],x+100,y+102)
        doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([l.d13,l.d14],x+145,y+102)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('OBSERVACIONES',x+8,y+110)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.text(l.d15,x+70,y+110)

    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('                                        OTROS',x+50,y+115)
    doc.setFont(undefined, 'normal')
    doc.text(['SANGRE OCULTA EN HECES','TEST DE HELICOBACTER EN HECES','TEST DE BENEDICT','OBSERVACIONES'],x+8,y+120)
    doc.setTextColor(0,0,0)
    doc.text([l.d16,l.d19,l.d17,l.d18],x+70,y+120)

    doc.rect(x+5, y+135, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+140)
    doc.setFont(undefined, 'NORMAL')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+8,y+145)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(57,73,171)
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+140,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+140,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
     // doc.output('save','COPROPARASITOLOGICO SIMPLE-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    window.open(doc.output('bloburl'), '_blank');
    },

    heces(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 006',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('ANALISIS DE HECES',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+48, 205, 93)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('                                          EXAMEN EN FRESCO',x+50,y+52)
    doc.setFont(undefined, 'normal')
    doc.text(['ASPECTO DE LA MUESTRA','COLOR','CELULAS EPITELIALES','LEUCOCITOS','HEMATIES','ALMIDON','LEVADURAS','GRASAS','PARASITOS'],x+8,y+58)
    doc.setTextColor(0,0,0)
    doc.text([l.d1,l.d2,l.d3,l.d4,l.d5,l.d6,l.d7,l.d8,l.d9],x+70,y+58)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('MOCO FECAL       ',x+8,y+95)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text(l.d10,x+50,y+95)
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['Polimorfonuclueares: ','Mononuclueras: '],x+100,y+95)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([l.d11,l.d12],x+145,y+95)

    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('OTROS',x+8,y+105)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.text(l.d13,x+70,y+105)

    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('                                         TINCION DE GRAM',x+50,y+110)
    doc.setFont(undefined, 'normal')
    doc.text(['BACILOS GRAM POSITIVOS','BACILOS GRAM NEGATIVOS','COCOS GRAM POSITIVOS','COCOS GRAM NEGATIVOS','COCOBACILOS GRAM','ESPORAS MICOTICAS','OTROS'],x+8,y+115)
    doc.setTextColor(0,0,0)
    doc.text([l.d14,l.d15,l.d16,l.d17,l.d18,l.d19,l.d20],x+70,y+115)

    doc.rect(x+5, y+142, 205, 22)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+145)
    doc.setFont(undefined, 'NORMAL')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+8,y+150)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(57,73,171)
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+145,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+145,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    //  doc.output('save','ANALISIS DE HECES-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    window.open(doc.output('bloburl'), '_blank');
    },

            orina(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 17)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 003',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('EXAMEN GENERAL DE ORINA',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+44, 205, 94)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('      EX FISICO                  VALOR                         REFERENCIA                  EX QUIMICO                   VALOR              REFERENCIA',x+6,y+48)
    doc.setFont(undefined, 'normal')
    doc.text(['Color','Olor','Aspecto','Espuma','Deposito','Densidad','Reaccion'],x+8,y+54)
    doc.text(['Amarillo','Sui-generis','Limpido o lig opal','Blanco fugaz','Nulo o escaso','1.010-1.030','Lig acida'],x+90,y+54,'center')
    doc.text(['Proteinas','Glucosa','C cetonicos','Bilirrubina','Hemoglobina','Urobilina','Nitritos'],x+110,y+54,'left')
    doc.text(['Negativo','Negativo','Negativo','Negativo','Negativo','Normal','Negativo'],x+185,y+54,'left')
    doc.setTextColor(0,0,0)
    doc.text([l.d1,l.d3,l.d5,l.d7,l.d9,l.d11,l.d13],x+40,y+54)
    doc.setTextColor(0,0,0)
    if(l.d2!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d2,x+160,y+54)
        doc.setTextColor(0,0,0)
    if(l.d4!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d4,x+160,y+58)
        doc.setTextColor(0,0,0)
    if(l.d6!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d6,x+160,y+62)
        doc.setTextColor(0,0,0)
    if(l.d8!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d8,x+160,y+66)
        doc.setTextColor(0,0,0)
    if(l.d10!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d10,x+160,y+70)
        doc.setTextColor(0,0,0)
    if(l.d12!='NORMAL')
      doc.setTextColor(255,0,0)
    doc.text(l.d12,x+160,y+74)
        doc.setTextColor(0,0,0)
    if(l.d14!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d14,x+160,y+78)
    doc.setTextColor(57,73,171)
    doc.text('SEDIMENTO: EXAMEN MICROSCOPICO',x+75,y+83,'center')

    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('          CELULAS                      VALOR                   REFERENCIA                   CILINDROS                     VALOR              REFERENCIA',x+6,y+86)
    doc.setFont(undefined, 'normal')
    doc.text(['Celulas epiteliales','Celulas de transicion','Celulas Clave','Leucocitos','Eritrocitos','Bacterias','CRISTALES'],x+8,y+91)
    doc.text(['Hasta 2/c','Negativo','Negativo','Hasta 5/c','Hasta 3/c','Escaso',''],x+90,y+91,'center')
    doc.text(['Hialino','Granuloso','Epiteliales','Eritrocitario','Leucocitario','Cereos','Mixtos'],x+110,y+91,'left')
    doc.text(['Negativo','Negativo','Negativo','Negativo','Negativo','Negativo','Negativo'],x+185,y+91,'left')
    doc.setTextColor(0,0,0)
    doc.text([l.d15,l.d17,l.d19,l.d21,l.d23,l.d25],x+50,y+91)
    doc.setTextColor(0,0,0)
    if(l.d16!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d16,x+160,y+91)
        doc.setTextColor(0,0,0)
    if(l.d18!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d18,x+160,y+95)
        doc.setTextColor(0,0,0)
    if(l.d20!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d20,x+160,y+99)
        doc.setTextColor(0,0,0)
    if(l.d22!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d22,x+160,y+103)
        doc.setTextColor(0,0,0)
    if(l.d24!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d24,x+160,y+107)
        doc.setTextColor(0,0,0)
    if(l.d26!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d26,x+160,y+111)
        doc.setTextColor(0,0,0)
    if(l.d27!='NEGATIVO')
      doc.setTextColor(255,0,0)
    doc.text(l.d27,x+160,y+115)
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('OTROS',x+160,y+119)

    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'normal')
    doc.text(['Uratos amorfos','Fosfato amorfo','Oxalato de calcio','Fosfato de calcio','Acido Urico'],x+8,y+121)
    doc.text(['Escaso','Escaso','Escaso','Escaso','Escaso'],x+90,y+121,'center')
    doc.text(['Filamento mucoso','Piocitos','Levaduras','Esporas micoticas'],x+110,y+124,'left')
    doc.setTextColor(0,0,0)
    doc.text([l.d28,l.d29,l.d31,l.d33,l.d35],x+50,y+121)
    doc.text([l.d30,l.d32,l.d34,l.d36],x+160,y+124)

    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('OBSERVACIONES:',x+6,y+141)
    doc.setFont(undefined, 'normal')
   // doc.text(l.d37,x+6,y+148,'left')

    doc.rect(x+5, y+138, 205, 25)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+153)
    doc.setFont(undefined, 'NORMAL')
    doc.text(l.responsable,x+8,y+157)
    doc.setFont(undefined, 'normal')
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+153,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+153,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    //window.open(doc.output('bloburl'), '_blank');
    doc.html( '<div style="font-size: 3px;width: 300px">'+l.d37+'</div>' , {
       callback: function (doc) {
                //doc.output('save','EXAMEN GENERAL DE ORINA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
                window.open(doc.output('bloburl'), '_blank');
       },
       x: x+6,
       y: y+141,
     })
    },


    alcoholemia(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 17)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 014',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('ALCOHOLEMIA',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+44, 205, 94)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.rect(x+75, y+55, 50, 7 )
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d1) > 50 )
    doc.setTextColor(255,0,0)
    doc.text(l.d1+' mg/dl',x+90,y+60)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)

    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('VALOR REFERENCIAL:',x+30,y+85,'center')

    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.rect(x+30, y+90, 150, 30)
    doc.rect(x+30, y+110, 150, 10)
    doc.text('10 - 50 mg/dl',x+50,y+100,'center')
    doc.text('Mayor a 50 mg/dl',x+50,y+115,'center')
    doc.setFont(undefined, 'bold')
    doc.text('ALCOHOLEMIA NEGATIVO',x+120,y+95,'center')
    doc.rect(x+80, y+90, 100, 20)
    //doc.setDrawColor(0);
    //doc.setFillColor(255,255,50);
    doc.rect(x+80, y+110, 100, 10)
    doc.text('ALCOHOLEMIA POSITIVO',x+120,y+115,'center')
    //doc.setFillColor(0, 0, 0);
    doc.setFont(undefined, 'normal')
    doc.text(['Alcoholes en sangre por la degradacion de alimentos', 'ingerido ricos en Hidratos de Carbono'],x+120,y+100,'center')

    doc.rect(x+5, y+138, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+145)
    doc.setFont(undefined, 'NORMAL')
    doc.text(l.responsable,x+8,y+149)
    doc.setFont(undefined, 'normal')
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+145,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+145,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    //window.open(doc.output('bloburl'), '_blank');
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','ALCOHOLEMIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

    },

    widal(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 17)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 015',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('REACCION DE WIDAL',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.setFontSize(12);
    doc.rect(x+5, y+44, 205, 94)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('METODO AGLUTINACION DIRECTA',x+20,y+50)
    doc.text('REACCION DE WIDAL',x+20,y+60)
    doc.setFontSize(13);

    doc.setFont(undefined, 'bold')
    doc.text(' A: ',x+80,y+60)
    doc.text(' B: ',x+80,y+75)
    doc.text(' H: ',x+80,y+90)
    doc.text(' O: ',x+80,y+105)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text(l.d1,x+90,y+60)
    doc.text(l.d2,x+90,y+75)
    doc.text(l.d3,x+90,y+90)
    doc.text(l.d4,x+90,y+105)
    doc.setTextColor(0,0,0)
    doc.setFontSize(11);

    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('OBSERVACIONES:',x+10,y+120,'left')

    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.text(l.d5,x+10,y+125,'left')
    doc.setFontSize(10);

    doc.rect(x+5, y+138, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+145)
    doc.setFont(undefined, 'NORMAL')
    doc.text(l.responsable,x+8,y+149)
    doc.setFont(undefined, 'normal')
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+145,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+145,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    //window.open(doc.output('bloburl'), '_blank');
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','REACCION DE WIDAL-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

    },

        multidrogas(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 17)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 016',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('MULTIDROGAS',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+44, 205, 94)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.setFontSize(12);
    doc.text('METODO: PRUEBA RAPIDA INMUNOCROMATOGRAFICA DOA MULTI 6',x+8,y+55)
    doc.setTextColor(0,0,0)
    doc.setFontSize(12);
    doc.text('MULTIDROGAS EN ORINA',x+30,y+65)
    doc.setFont(undefined, 'bold')
    doc.text('PRUEBAS',x+100,y+70)

    doc.setFont(undefined, 'normal')
    doc.text(' Marihuana THC ',x+8,y+80)
    doc.text(' Cocaina COC ',x+8,y+90)
    doc.text(' Amfetamina AMP ',x+8,y+100)
    doc.text(' Metanfetamina MET ',x+8,y+110)
    doc.text(' Morfina MOP ',x+8,y+120)
    doc.text(' Metil-N-dioximetanfetamina ',x+100,y+80)
    doc.text(' Benzodiazepinas BZO ',x+100,y+90)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    if(l.d1!='NEGATIVO')
    doc.setTextColor(255,0,0)

    doc.text(l.d1,x+60,y+80)
        doc.setTextColor(0,0,0)
    if(l.d3!='NEGATIVO')
    doc.setTextColor(255,0,0)
    doc.text(l.d3,x+60,y+90)
        doc.setTextColor(0,0,0)
    if(l.d5!='NEGATIVO')
    doc.setTextColor(255,0,0)
    doc.text(l.d5,x+60,y+100)
        doc.setTextColor(0,0,0)
    if(l.d6!='NEGATIVO')
    doc.setTextColor(255,0,0)
    doc.text(l.d6,x+60,y+110)
        doc.setTextColor(0,0,0)
    if(l.d7!='NEGATIVO')
    doc.setTextColor(255,0,0)
    doc.text(l.d7,x+60,y+120)

    doc.setTextColor(0,0,0)
    if(l.d2!='NEGATIVO')
    doc.setTextColor(255,0,0)
    doc.text(l.d2,x+160,y+80)
        doc.setTextColor(0,0,0)
    if(l.d4!='NEGATIVO')
    doc.setTextColor(255,0,0)
    doc.text(l.d4,x+160,y+90)
    doc.setTextColor(0,0,0)
    doc.setFontSize(11);

    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('OBSERVACIONES:',x+10,y+130,'left')

    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.text(l.d8,x+10,y+135,'left')
    doc.setFontSize(10);

    doc.rect(x+5, y+138, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+145)
    doc.setFont(undefined, 'NORMAL')
    doc.text(l.responsable,x+8,y+149)
    doc.setFont(undefined, 'normal')
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+145,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+145,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','MULTIDROGAS-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

    },


        glicosilada(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 17)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 017',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('HEMOGLOBINA GLICOSILADA',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+44, 205, 94)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.setFontSize(12);
    doc.text('HbA1c METODO INMUNOENSAYO DE FLUORESCENCIA (FIA)',x+50,y+60)
    doc.setTextColor(0,0,0)
    //doc.setFontSize(12);

    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('Valor de Referencia: ',x+60,y+100)
    doc.setFontSize(12);
    doc.setTextColor(0,0,0)

    if(parseFloat(l.d1)<4.5 || parseFloat(l.d1)>6.5)
          doc.setTextColor(255,0,0)
    doc.text(l.d1+' %',x+100,y+75)
    doc.setTextColor(255,0,0)
    doc.rect(x+75, y+105, 60, 12)
    doc.rect(x+75, y+105, 60, 6)
    doc.rect(x+75, y+105, 24, 12)
    doc.rect(x+75, y+70, 60, 6)

    doc.text('NGSP ',x+80,y+110)
    doc.text('IFCC ',x+80,y+115)
    doc.setTextColor(0,0,0)
    doc.text('4.5 - 6.5% ',x+105,y+110)
    doc.text('26 - 48 mmol/mol',x+100,y+115)

    doc.setFontSize(10);

    doc.rect(x+5, y+138, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+145)
    doc.setFont(undefined, 'NORMAL')
    doc.text(l.responsable,x+8,y+149)
    doc.setFont(undefined, 'normal')
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+145,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+145,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','HEMOGLOBINA GLICOSILADA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

    },
    
        gasometria (p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 17)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 018',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('GASOMETRIA',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+44, 205, 94)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.setFontSize(12);
    doc.text('GASOMETRIA ARTERIAL : ',x+10,y+60)
    doc.setTextColor(0,0,0)
    doc.text(l.d1,x+70,y+60)
    //doc.setFontSize(12);

    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.rect(x+5, y+138, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+145)
    doc.setFont(undefined, 'NORMAL')
    doc.text(l.responsable,x+8,y+149)
    doc.setFont(undefined, 'normal')
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+145,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+145,'left')

    if(l.imagen!=null && l.imagen!=''){

        this.$axios.post(process.env.API+'/base64',{imagen:l.imagen}).then(res=>{
            //doc.addPage();
            var imgData=''
          //console.log(res.data)
           imgData =res.data
                     doc.addImage(imgData, "jpeg", 90, 50, 90, 80)
          console.log(imgData)
                doc.output('save','GASOMETRIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf)
                return false
      })      
    }
    else
    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','GASOMETRIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

    },
  hepatitis(p,l){
        var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 17)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 019',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('HEPATITIS B',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+44, 205, 94)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.setFontSize(12);
    doc.text('Anti-HBS METODO INMUNOENSAYO DE FLUORESCENCIA (FIA)',x+50,y+60)
    doc.setTextColor(0,0,0)
    //doc.setFontSize(12);

    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('Valor de Referencia: ',x+60,y+100)
    doc.setFontSize(12);
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d1)>15 )
          doc.setTextColor(255,0,0)
    doc.text(l.d1+' mlU/ml',x+100,y+75)
    doc.setTextColor(0,0,0)

    if(l.d2 =='POSITIVO' )
          doc.setTextColor(255,0,0)
    doc.text(l.d2,x+100,y+85)
    doc.setTextColor(0,0,0)

    doc.rect(x+75, y+105, 70, 12)
    doc.rect(x+75, y+105, 70, 6)
    doc.rect(x+75, y+105, 30, 12)
    doc.rect(x+75, y+117, 70, 6)
    doc.rect(x+75, y+117, 30, 6)
    doc.rect(x+75, y+70, 70, 8)
    doc.rect(x+75, y+80, 70, 8)
        doc.setTextColor(57,73,171)


    doc.text('< 5 mlU/ml ',x+80,y+110)
    doc.text('5 - 15 mlU/ml ',x+80,y+115)
    doc.text('> 15 mlU/ml ',x+80,y+120)
    doc.setTextColor(0,0,0)
    doc.text('NEGATIVO',x+112,y+110)
    doc.text('INDETERMINADO',x+107,y+115)
    doc.text('POSITIVO',x+112,y+120)

    doc.setFontSize(10);

    doc.rect(x+5, y+138, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+145)
    doc.setFont(undefined, 'NORMAL')
    doc.text(l.responsable,x+8,y+149)
    doc.setFont(undefined, 'normal')
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+145,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+145,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','HEPATITIS B-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

  },
  toxoplasmosis(p,l){
        var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 17)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 020',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('TOXOPLASMOSIS lg G / lg M',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+44, 205, 94)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.setFontSize(12);
    doc.text(' METODO INMUNOENSAYO DE FLUORESCENCIA (FIA)',x+50,y+55)
    doc.setTextColor(0,0,0)
    //doc.setFontSize(12);

    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('Valor de Referencia: ',x+55,y+85)
    doc.setFontSize(12);
    doc.setTextColor(0,0,0)
    doc.text('lg M',x+60,y+65)
    doc.text('lg G',x+60,y+75)
    if(parseFloat(l.d1)>8 )
          doc.setTextColor(255,0,0)
    doc.text(l.d1+'',x+90,y+65)
    doc.setTextColor(0,0,0)

    if(parseFloat(l.d3)>1.1 )
          doc.setTextColor(255,0,0)
    doc.text(l.d3+'',x+90,y+75)
    doc.setTextColor(0,0,0)

    if(l.d2 =='POSITIVO' )
          doc.setTextColor(255,0,0)
    doc.text(l.d2,x+110,y+65)
    doc.setTextColor(0,0,0)

    if(l.d4 =='POSITIVO' )
          doc.setTextColor(255,0,0)
    doc.text(l.d4,x+110,y+75)
    doc.setTextColor(0,0,0)
    doc.rect(x+55, y+60, 25, 8)
    doc.rect(x+80, y+60, 25, 8)
    doc.rect(x+105, y+60, 50, 8)

    doc.rect(x+55, y+70, 25, 8)
    doc.rect(x+80, y+70, 25, 8)
    doc.rect(x+105, y+70, 50, 8)

    doc.rect(x+55, y+90, 25, 16)
    doc.rect(x+55, y+106, 25, 16)    
    doc.rect(x+80, y+90, 90, 16)
    doc.rect(x+80, y+106, 90, 16)  
    //doc.rect(x+75, y+105, 30, 12)
    //doc.rect(x+75, y+117, 70, 6)
    //doc.rect(x+75, y+117, 30, 6)
    doc.setTextColor(0,0,0)


    doc.text('lg G ',x+65,y+100)
    doc.text('lg M',x+65,y+115)
        doc.setTextColor(57,73,171)

    doc.text('< 4 IU/ml',x+85,y+95)
    doc.text('4 - 8 UI/ml',x+85,y+100)
    doc.text('> 8 IU/ml',x+85,y+105)

    doc.text('< 0.9 IU/ml',x+85,y+110)
    doc.text('0.9 - 1.1 UI/ml',x+85,y+115)
    doc.text('> 1.1 IU/ml',x+85,y+120)
    doc.setTextColor(0,0,0)

    doc.text('NEGATIVO',x+120,y+95)
    doc.text('INDETERMINADO',x+120,y+100)
    doc.text('POSITIVO',x+120,y+105)
    doc.text('NEGATIVO',x+120,y+110)
    doc.text('INDETERMINADO',x+120,y+115)
    doc.text('POSITIVO',x+120,y+120)

    doc.setFontSize(10);

    doc.rect(x+5, y+138, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+145)
    doc.setFont(undefined, 'NORMAL')
    doc.text(l.responsable,x+8,y+149)
    doc.setFont(undefined, 'normal')
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+145,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+145,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','TOXOPLASMOSIS '+p.nombre+' '+p.paterno+' '+p.materno.pdf);
  },
  hemodialisis(p,l){
            var doc = new jsPDF('landscape',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 195, 3, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+195, y+27, 155, 23)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+320, y+10,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+225, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 021',x+323, y+30)
    doc.setTextColor(57,73,171)
    doc.text('HEMODIALISIS M Y B',x+270, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+196, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+268, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+305, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+305, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+337, y+35,'center')
    doc.setTextColor(211,47,47)
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+195, y+53, 155, 130)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(211,47,47)
    doc.text('',x+233,y+55)
    doc.setTextColor(57,73,171)
    doc.setFontSize(12);
    doc.setLineHeightFactor(1.7);
    doc.text('CONTROL MENSUAL',x+260,y+60)
    doc.text('PRUEBA                           VALOR                                     REFERENCIA',x+200,y+65)
    doc.setFontSize(12);

    doc.setFont(undefined, 'normal')
    doc.text(['Glicemia','Creatinina','Urea','NUS-BUN ','Transaminasas GOT','Transaminasas GPT','Sodio','Cloro','Potasio'],x+200,y+70,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d1)<70 || parseFloat(l.d1)>105)
      doc.setTextColor(255,0,0)
    doc.text(l.d1,x+250,y+70,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d2)<0.7 || parseFloat(l.d2)>1.5)
      doc.setTextColor(255,0,0)
    doc.text(l.d2,x+250,y+77,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d3)<15 || parseFloat(l.d3)>45)
      doc.setTextColor(255,0,0)
    doc.text(l.d3,x+250,y+84,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d4)<7 || parseFloat(l.d4)>18)
      doc.setTextColor(255,0,0)
    doc.text(l.d4,x+250,y+91,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d5)>40)
      doc.setTextColor(255,0,0)
    doc.text(l.d5,x+250,y+99,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d6)>41)
      doc.setTextColor(255,0,0)    
    doc.text(l.d6,x+250,y+106,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d7)<135 || parseFloat(l.d7)>155)
      doc.setTextColor(255,0,0)    
    doc.text(l.d7,x+250,y+114,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d8)<98 || parseFloat(l.d8)>106)
      doc.setTextColor(255,0,0)  
    doc.text(l.d8,x+250,y+121,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d9)<3.4 || parseFloat(l.d9)>5.6)
      doc.setTextColor(255,0,0) 
    doc.text(l.d9,x+250,y+128,'left')

  doc.setTextColor(57,73,171)

    doc.text(['70 - 105 mg/dl','0.7 - 1.5 mg/dl','15 - 45 mg/dl','7 - 18 mg/dl ','hasta 40 UI/L','hasta 41 UI/L','135 - 155 mEq/L','98 - 106 mEq/L','3.4 - 5.6 mEq/L'],x+300,y+70,'left')
    doc.setFont(undefined, 'bold')
    doc.text('CONTROL BIMESTRAL',x+260,y+140)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(57,73,171)
    doc.setLineHeightFactor(1.7);
    doc.text(['Acido Urico','Fosfatasa Alcalina','calcio','Fosforo','OBSERVACIONES'],x+200,y+145,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d10)<2.6 || parseFloat(l.d10)>7.2)
      doc.setTextColor(255,0,0) 
    doc.text(l.d10,x+250,y+145,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d11)>135 )
      doc.setTextColor(255,0,0) 
    doc.text(l.d11,x+250,y+152,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d12)<8.5 || parseFloat(l.d12)>10.5)
      doc.setTextColor(255,0,0) 
    doc.text(l.d12,x+250,y+160,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d13)<2.5 || parseFloat(l.d13)>4.5)
      doc.setTextColor(255,0,0)
    doc.text(l.d13,x+250,y+167,'left')
    doc.setTextColor(0,0,0)
    doc.text(l.d14,x+200,y+179,'left')
    doc.setTextColor(57,73,171)
    doc.text(['2.6 - 7.2 mg/dl','adultos Hasta 135 UI/L','8.5 - 10.5 mg/dl','2.5 - 4.5 mg/dl'],x+300,y+145,'left')
   
    doc.setLineHeightFactor(1.5);
    doc.rect(x+195, y+185, 155, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+205,y+190,'left')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+205,y+195,'left')
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.setLineHeightFactor(1.5)
    doc.text(['Fecha toma de Muestra','Hora toma Muestra','Fecha Entrega de Resultado'],x+280,y+190,'left')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+330,y+190,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    //  doc.output('save','HEMODIALISIS M Y B-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
       window.open(doc.output('bloburl'), '_blank');

  },
    hemodialisistri(p,l){
            var doc = new jsPDF('landscape',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 195, 3, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+195, y+27, 155, 23)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+320, y+10,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+225, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 022',x+323, y+30)
    doc.setTextColor(57,73,171)
    doc.text('HEMODIALISIS TRIMENSUAL',x+270, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+196, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+268, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+305, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+305, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+337, y+35,'center')
    doc.setTextColor(211,47,47)
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+195, y+53, 155, 130)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(211,47,47)
    doc.text('',x+233,y+55)
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.text('CONTROL TRIMENSUAL',x+260,y+60)
    doc.text('PRUEBA                         VALOR                         UNIDAD                   REFERENCIA',x+200,y+65)

    doc.setFont(undefined, 'normal')
    doc.text(['Proteinas Totales','Albumina','Globulina','Relacion A/G','Trigliceridos','Colesterol Total','HDL - Col','LDL - Col','VLDL - Col','Hierro','Prueba Rapida VIH'],x+200,y+70,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d1)<6.0 || parseFloat(l.d1)>8.5)
      doc.setTextColor(255,0,0)
    doc.text(l.d1,x+245,y+70,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d2)<3.5 || parseFloat(l.d2)>5.3)
      doc.setTextColor(255,0,0)
    doc.text(l.d2,x+245,y+74,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d3)<2.8 || parseFloat(l.d3)>3.5)
      doc.setTextColor(255,0,0)
    doc.text(l.d3,x+245,y+78,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d4)<1.2 || parseFloat(l.d4)>2.2)
      doc.setTextColor(255,0,0)    
    doc.text(l.d4,x+245,y+82,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d5)<10 || parseFloat(l.d5)>160)
      doc.setTextColor(255,0,0)  
    doc.text(l.d5,x+245,y+86,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d6)>=200)
      doc.setTextColor(255,0,0)    
    doc.text(l.d6,x+245,y+90,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d7)<35 || parseFloat(l.d7)>65)
      doc.setTextColor(255,0,0)     
    doc.text(l.d7,x+245,y+94,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d8)>150)
      doc.setTextColor(255,0,0)    
    doc.text(l.d8,x+245,y+98,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d9)<2 || parseFloat(l.d9)>30)
      doc.setTextColor(255,0,0)     
    doc.text(l.d9,x+245,y+102,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d10)<50 || parseFloat(l.d10)>170)
      doc.setTextColor(255,0,0)     
    doc.text(l.d10,x+245,y+106,'left')
    doc.setTextColor(0,0,0)
    if(l.d11!='NO REACTIVO')
      doc.setTextColor(255,0,0)  
    doc.text(l.d11,x+245,y+110,'left')
    doc.setTextColor(0,0,0)
    doc.text(['g/dl','g/dl','g/dl','g/dl','mg/dl','mg/dl','mg/dl','mg/dl','mg/dl','ug/dl',''],x+280,y+70,'left')
     doc.setTextColor(57,73,171)
    doc.text(['6.0 - 8.5 g/dl','3.5 - 5.3 g/dl','2.8 - 3.5 g/dl','1.2 - 2.2 g/dl','10 - 160 mmg/dl','menor 200 mg/dl','35 - 65 mg/dl','hasta 150 mg/dl','2 - 30 mg/dl','50 - 170 ug/dl','NO REACTIVO'],x+310,y+70,'left')

     doc.setTextColor(57,73,171)

    doc.setFont(undefined, 'bold')
    doc.text('METODO: CAPACIDAD DE FIJACION DE HIERRO',x+220,y+120)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(57,73,171)
    doc.text(['Capacidad Latente de','  Fijacion de Hierro','Capacidad Total de ','  Fijacion de Hierro','Transferrina'],x+200,y+125,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d12)<140 || parseFloat(l.d12)>280)
      doc.setTextColor(255,0,0)
    doc.text(l.d12,x+245,y+125,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d13)<250 || parseFloat(l.d13)>450)
      doc.setTextColor(255,0,0)    
    doc.text(l.d13,x+245,y+133,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d14)<200 || parseFloat(l.d14)>300)
      doc.setTextColor(255,0,0)     
    doc.text(l.d14,x+245,y+141,'left')
    doc.setTextColor(0,0,0)
    doc.text(['ug/dl','','ug/dl','','mg/dl'],x+280,y+125,'left')
    doc.setTextColor(57,73,171)
    doc.text(['140 - 280 ug/dl','','250 - 450 ug/dl','','200 - 300 mg/dl'],x+310,y+125,'left')
  
    doc.setFont(undefined, 'bold')
    doc.text('METODO: INMUNOENSAYO DE FLUORESCENCIA (FIA)',x+220,y+150)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(57,73,171)
    doc.text(['FERRITNA','','','HEPATITIS B','','OBSERVACIONES'],x+200,y+155,'left')
    doc.setTextColor(0,0,0)
    if((parseFloat(l.d15)<30 || parseFloat(l.d15)>350) && p.sexo=='Masculino')
      doc.setTextColor(255,0,0)
    if((parseFloat(l.d15)<20 || parseFloat(l.d15)>250) && p.sexo=='Femenino')
      doc.setTextColor(255,0,0)      
    doc.text(l.d15,x+240,y+155,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d16)>15)
      doc.setTextColor(255,0,0) 
    doc.text(l.d16,x+240,y+167,'left')
    doc.setTextColor(0,0,0)
    if(l.d17 =='POSITIVO')
      doc.setTextColor(255,0,0) 
    doc.text(l.d17,x+240,y+171,'left')
    doc.setTextColor(0,0,0)
    doc.text(l.d18,x+200,y+180,'left')
    doc.text(['ng/ml','','','mlU/dl',''],x+280,y+155,'left')
    doc.setTextColor(57,73,171)
    doc.text(['30-350 ng/ml Varon','20-250 ng/ml Mujer','< 5 mlU/ml NEGATIVO','5-15 mlU/ml Indeterminado','> 15 mlU/ml POSITIVO'],x+300,y+155,'left')
    
    doc.rect(x+195, y+185, 155, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+205,y+190,'left')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+205,y+195,'left')
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.setLineHeightFactor(1.5)
    doc.text(['Fecha toma de Muestra','Hora toma Muestra','Fecha Entrega de Resultado'],x+280,y+190,'left')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+330,y+190,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
     // doc.output('save','HEMODIALISIS TRI-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
        window.open(doc.output('bloburl'), '_blank');

  },
        miscelaneo(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 023',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('MISCELANEOS - ORINA',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+48, 205, 93)
    doc.setFont(undefined, 'bold')
    doc.setFontSize(12);
    doc.setTextColor(57,73,171)
    doc.setLineHeightFactor(2)
    doc.text('                       RESULTADO               VALOR DE REFERENCIA',x+50,y+52)
    doc.setFont(undefined, 'normal')
    doc.text(['ORINA EN 24 HORAS','CREATININA EN SUERO','CREATININA EN ORINA 24', '    HORAS','CLEARENCE DE CREATININA','','PROTEINA EN ORINA AL AZAR','PROTEINURIA DE 24 HORAS'],x+8,y+60)
    doc.setTextColor(0,0,0)
    doc.text(l.d1,x+80,y+60)
    if(parseFloat(l.d2)<0.6 || parseFloat(l.d2)>1.4)
      doc.setTextColor(255,0,0)
    doc.text(l.d2,x+80,y+70)
    doc.setTextColor(0,0,0)
    if((parseFloat(l.d3)<1500 || parseFloat(l.d3)>2500) && p.sexo=='Masculino')
      doc.setTextColor(255,0,0)
    if((parseFloat(l.d3)<800 || parseFloat(l.d3)>1500) && p.sexo=='Femenino')
      doc.setTextColor(255,0,0)
    doc.text(l.d3,x+80,y+80)
    doc.setTextColor(0,0,0)
        if((parseFloat(l.d4)<97 || parseFloat(l.d4)>137) && p.sexo=='Masculino')
      doc.setTextColor(255,0,0)
    if((parseFloat(l.d4)<88 || parseFloat(l.d4)>128) && p.sexo=='Femenino')
      doc.setTextColor(255,0,0)
    doc.text(l.d4,x+80,y+95)
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d5)<1 || parseFloat(l.d5)>15)
      doc.setTextColor(255,0,0)
    doc.text(l.d5,x+80,y+110)
    doc.setTextColor(0,0,0)
        if(parseFloat(l.d6)<28 || parseFloat(l.d6)>141)
      doc.setTextColor(255,0,0)
    doc.text(l.d6,x+80,y+120)
    doc.setTextColor(0,0,0)
    doc.text(['VOLUMEN','0.6 - 1.4 mg/dl','Varones 1500 - 2500 mg/24hrs','Mujeres 800 - 1500 mg/24hrs ','Varones 97 - 137 ml/min','Mujeres 88 - 128 ml/min ','1 - 15 mg/dl','28 - 141 mg/24hrs'],x+130,y+60)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('OBSERVACION',x+8,y+125)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.text(l.d7,x+8,y+130)
    doc.setFontSize(10);
    doc.setLineHeightFactor(1.5)

    doc.rect(x+5, y+142, 205, 22)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+145)
    doc.setFont(undefined, 'NORMAL')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+8,y+150)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(57,73,171)
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+145,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+145,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
     // doc.output('save','MICSCELANEOS ORINA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    window.open(doc.output('bloburl'), '_blank');
    },

                     hierro(p,l){

            var doc = new jsPDF('landscape',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 195, 3, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+195, y+27, 155, 23)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+320, y+10,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+225, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 024',x+323, y+30)
    doc.setTextColor(57,73,171)
    doc.text('FIJACION DE HIERRO',x+270, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+196, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+268, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+305, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+305, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+337, y+35,'center')
    doc.setTextColor(211,47,47)
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+195, y+53, 155, 130)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(211,47,47)
    doc.text('',x+233,y+55)
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.setFontSize(12);
      doc.setTextColor(255,0,0)
    doc.text('                   METODO: CAPACIDAD DE FIJACION DE HIERRO',x+200,y+60)
    doc.setTextColor(57,73,171)
    doc.text('  PRUEBA                     VALOR               UNIDAD                REFERENCIA',x+200,y+65)

    doc.setFont(undefined, 'normal')
    doc.text(['Capacidad Latente de ',' Fijacion de Hierro','Capacitacion Total de ',' Fijacion de Hierro','Transferrina'],x+200,y+70,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d1)<140 || parseFloat(l.d1)>280)
      doc.setTextColor(255,0,0)
    doc.text(l.d1,x+250,y+70,'left')
    doc.setTextColor(0,0,0)
        if(parseFloat(l.d2)<250 || parseFloat(l.d2)>450)
      doc.setTextColor(255,0,0)
    doc.text(l.d2,x+250,y+80,'left')
    doc.setTextColor(0,0,0)
            if(parseFloat(l.d3)<200 || parseFloat(l.d3)>300)
      doc.setTextColor(255,0,0)
    doc.text(l.d3,x+250,y+90,'left')
    doc.setTextColor(0,0,0)
    doc.text(['ug/dl',' ','ug/dl',' ','mg/dl'],x+280,y+70,'left')
    doc.text(['140 - 280 ug/dl',' ','250 - 450 ug/dl',' ','200 - 300 mg/dl'],x+310,y+70,'left')

    doc.setFontSize(11);
    doc.setTextColor(57,73,171)
    doc.text('OBSERVACION',x+200,y+175,'left')
    doc.setTextColor(0,0,0)
    doc.text(l.d4,x+200,y+180,'left')
    doc.setFontSize(11);


    doc.rect(x+195, y+185, 155, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+205,y+190,'left')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+205,y+195,'left')
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.setLineHeightFactor(1.5)
    doc.text(['Fecha toma de Muestra','Hora toma Muestra','Fecha Entrega de Resultado'],x+280,y+190,'left')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+330,y+190,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      //doc.output('save','FIJACION HIERRO-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
       window.open(doc.output('bloburl'), '_blank');
      },
            uretral(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 004',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('ANALISIS DE SECRECION URETRAL',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+48, 205, 85)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('                                          EXAMEN EN FRESCO',x+50,y+52)
    doc.setFont(undefined, 'normal')
    doc.text(['CELULAS EPITELIALES','LEUCOCITOS','HEMATIES','CELULAS CLAVE','LEVADURAS','PARASITOS','BACTERIAS','KOH','PH','OBSERVACIONES'],x+8,y+58)
    doc.setTextColor(0,0,0)
    doc.text([l.d1,l.d2,l.d3,l.d4,l.d5,l.d6,l.d7,l.d8,l.d9,l.d10],x+70,y+58)

    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('                                         TINCION DE GRAM',x+50,y+100)
    doc.setFont(undefined, 'normal')
    doc.text(['BACILOS GRAM POSITIVO','BACILOS GRAM NEGATIVO','COCOS GRAM POSITIVO','COCOBACILOS GRAM POSITIVO','COCOBACILOS GRAM NEGATIVO','ESPORAS E HIFAS MICOTICAS','OBSERVACIONES'],x+8,y+105)
    doc.setTextColor(0,0,0)
    doc.text([l.d11,l.d12,l.d13,l.d14,l.d15,l.d16,l.d17],x+70,y+105)

    doc.rect(x+5, y+135, 205, 22)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+140)
    doc.setFont(undefined, 'NORMAL')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+8,y+145)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(57,73,171)
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+145,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+145,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','ANALISIS DE SECRECION URETRAL-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },

            vaginal(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 005',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('ANALISIS DE SECRECION VAGINAL',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+48, 205, 85)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('                                          EXAMEN EN FRESCO',x+50,y+52)
    doc.setFont(undefined, 'normal')
    doc.text(['CELULAS EPITELIALES','LEUCOCITOS','HEMATIES','CELULAS CLAVE','LEVADURAS','PARASITOS','BACTERIAS','KOH','PH','OBSERVACIONES'],x+8,y+58)
    doc.setTextColor(0,0,0)
    doc.text([l.d1,l.d2,l.d3,l.d4,l.d5,l.d6,l.d7,l.d8,l.d9,l.d10],x+70,y+58)

    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('                                         TINCION DE GRAM',x+50,y+100)
    doc.setFont(undefined, 'normal')
    doc.text(['BACILOS GRAM POSITIVO','BACILOS GRAM NEGATIVO','COCOS GRAM POSITIVO','COCOBACILOS GRAM POSITIVO','COCOBACILOS GRAM NEGATIVO','ESPORAS E HIFAS MICOTICAS','OBSERVACIONES'],x+8,y+105)
    doc.setTextColor(0,0,0)
    doc.text([l.d11,l.d12,l.d13,l.d14,l.d15,l.d16,l.d17],x+70,y+105)

    doc.rect(x+5, y+135, 205, 22)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+140)
    doc.setFont(undefined, 'NORMAL')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+8,y+145)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(57,73,171)
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+145,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+145,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','ANALISIS DE SECRECION VAGINAL-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },

sanguinea(p,l){
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 002',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('QUIMICA SANGUINEA',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+48, 205, 73)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('          PRUEBA                      VALOR                   REFERENCIA                  PRUEBA                     VALOR              REFERENCIA',x+6,y+52)
    doc.setFont(undefined, 'normal')
    doc.text(['Glicemia','Creatinina','Urea','NUS-BUN','Acido Urico','Proteinas Totales','Albumina','Globulina','Amilasa','Lipasa','Bilirrubina Total','Bilirrubina Directa','Bilirrubina Indirecta','CK-MB','LDH','Hierro'],x+8,y+58)
    doc.text(['70-105mg/dl','0.7-1.5mg/dl','15-45mg/dl','7-18mg/dl','2.6-7.2mg/dl','6.2-8.5g/dl','3.5-5.3g/dl','2.8-3.5g/dl','menor a 120UI/L','10-150UI/L','hasta 1.2 mg/dl','hasta 0.3 mg/dl','hasta 0.9 mg/dl','0-24 UI/L','200-480 UI/L','50-170ug/dl'],x+80,y+58,'left')
    doc.text(['Fosfatasa alcalina','Fosfatasa alcalina','Transamisas GOT','Transamisas GPT','LIPIDOGRAMA','Trigliceridos','Colesterol Total','HDL-Col','LDL-Col','ELECTROLITOS','Sodio','Cloro','Potasio','Calcio','Magnesio','Fosforo'],x+110,y+58,'left')
    doc.text(['adultos hasta 100UI/L','niños 100-400UI/L','hasta 40UI/L','hasta 41UI/L','','10-160mg/dl','menor 200mg/dl','35-65mg/dl','hasta 150mg/dl','','135-155mEq/L','98-106 mEq/L','3.4-5.3 mEq/L','8.5-10.5mg/dl','1.7-2.4mg/dl','2.5-4.5mg/dl'],x+175,y+58,'left')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d1)<70 || parseFloat(l.d1)>105)
      doc.setTextColor(255,0,0)
      if(l.d1!='')
      doc.text(l.d1 +' mg/dl',x+55,y+58)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d3)<0.7 || parseFloat(l.d3)>1.5)
      doc.setTextColor(255,0,0)
      if(l.d3!='')
    doc.text(l.d3+' mg/dl',x+55,y+62)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d5)<15 || parseFloat(l.d5)>45)
      doc.setTextColor(255,0,0)
      if(l.d5!='')
    doc.text(l.d5+ ' mg/dl',x+55,y+66)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d7)<7 || parseFloat(l.d7)>18)
      doc.setTextColor(255,0,0)
      if(l.d7!='')
    doc.text(l.d7+' mg/dl',x+55,y+70)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d9)<2.6 || parseFloat(l.d9)>7.2)
      doc.setTextColor(255,0,0)
      if(l.d9!='')
    doc.text(l.d9+ ' mg/dl',x+55,y+74)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d10)<6.2 || parseFloat(l.d10)>8.5)
      doc.setTextColor(255,0,0)
      if(l.d10!='')
    doc.text(l.d10+' g/dl',x+55,y+78)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d12)<3.5 || parseFloat(l.d12)>5.3)
      doc.setTextColor(255,0,0)
      if(l.d12!='')
    doc.text(l.d12+' g/dl',x+55,y+82)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d14)<2.8 || parseFloat(l.d14)>3.5)
      doc.setTextColor(255,0,0)
      if(l.d14!='')
    doc.text(l.d14+' g/dl',x+55,y+86)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d16)<0 || parseFloat(l.d16)>120)
      doc.setTextColor(255,0,0)
      if(l.d16!='')
    doc.text(l.d16+' UI/L',x+55,y+90)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d18)<10 || parseFloat(l.d18)>150)
      doc.setTextColor(255,0,0)
      if(l.d18!='')
    doc.text(l.d18+' UI/L',x+55,y+94)


      doc.setTextColor(0,0,0)
    if(parseFloat(l.d19)<0 || parseFloat(l.d19)>1.2)
      doc.setTextColor(255,0,0)
      if(l.d19!='')
    doc.text(l.d19+' mg/dl',x+55,y+98)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d21)<0 || parseFloat(l.d21)>0.3)
      doc.setTextColor(255,0,0)
      if(l.d21!='')
    doc.text(l.d21+' mg/dl',x+55,y+102)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d23)<0 || parseFloat(l.d23)>0.9)
      doc.setTextColor(255,0,0)
      if(l.d23!='')
    doc.text(l.d23+' mg/dl',x+55,y+106)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d25)<0 || parseFloat(l.d25)>24)
      doc.setTextColor(255,0,0)
      if(l.d25!='')
    doc.text(l.d25+' UI/L',x+55,y+110)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d27)<200 || parseFloat(l.d27)>480)
      doc.setTextColor(255,0,0)
      if(l.d27!='')
    doc.text(l.d27+' UI/L',x+55,y+114)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d29)<50 || parseFloat(l.d29)>170)
      doc.setTextColor(255,0,0)
      if(l.d29!='')
    doc.text(l.d29+' ug/dl',x+55,y+118)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d2)<0 || parseFloat(l.d2)>100)
      doc.setTextColor(255,0,0)
      if(l.d2!='')
    doc.text(l.d2+' UI/L',x+155,y+58)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d4)<100 || parseFloat(l.d4)>400)
      doc.setTextColor(255,0,0)
      if(l.d4!='')
    doc.text(l.d4+' UI/L',x+155,y+62)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d6)<0 || parseFloat(l.d6)>40)
      doc.setTextColor(255,0,0)
      if(l.d6!='')
    doc.text(l.d6+' UI/L',x+155,y+66)

          doc.setTextColor(0,0,0)
    if(parseFloat(l.d8)<0 || parseFloat(l.d8)>41)
      doc.setTextColor(255,0,0)
      if(l.d8!='')
    doc.text(l.d8+' UI/L',x+155,y+70)

          doc.setTextColor(0,0,0)
    if(parseFloat(l.d11)<10 || parseFloat(l.d11)>160)
      doc.setTextColor(255,0,0)
      if(l.d11!='')
    doc.text(l.d11+' mg/dl',x+155,y+78)

          doc.setTextColor(0,0,0)
    if(parseFloat(l.d13)<0 || parseFloat(l.d13)>200)
      doc.setTextColor(255,0,0)
      if(l.d13!='')
    doc.text(l.d13+' mg/dl',x+155,y+82)

          doc.setTextColor(0,0,0)
    if(parseFloat(l.d15)<35 || parseFloat(l.d15)>65)
      doc.setTextColor(255,0,0)
      if(l.d15!='')
    doc.text(l.d15+' mg/dl',x+155,y+86)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d17)<0 || parseFloat(l.d17)>150)
      doc.setTextColor(255,0,0)
      if(l.d17!='')
    doc.text(l.d17+' mg/dl',x+155,y+90)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d20)<135 || parseFloat(l.d20)>155)
      doc.setTextColor(255,0,0)
      if(l.d20!='')
    doc.text(l.d20+' mEq/L',x+155,y+98)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d22)<98 || parseFloat(l.d22)>106)
      doc.setTextColor(255,0,0)
      if(l.d22!='')
    doc.text(l.d22+' mEq/L',x+155,y+102)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d24)<3.4 || parseFloat(l.d24)>5.3)
      doc.setTextColor(255,0,0)
      if(l.d24!='')
    doc.text(l.d24+' mEq/L',x+155,y+106)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d26)<8.5 || parseFloat(l.d26)>10.5)
      doc.setTextColor(255,0,0)
      if(l.d26!='')
    doc.text(l.d26+' mg/dl',x+155,y+110)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d28)<1.7 || parseFloat(l.d28)>2.4)
      doc.setTextColor(255,0,0)
      if(l.d28!='')
    doc.text(l.d28+' mg/dl',x+155,y+114)

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d30)<2.5 || parseFloat(l.d30)>4.5)
      doc.setTextColor(255,0,0)
      if(l.d30!='')
    doc.text(l.d30+' mg/dl',x+155,y+118)


    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('OBSERVACIONES:',x+6,y+125)
    doc.setFont(undefined, 'normal')
      doc.setTextColor(0,0,0)
    doc.text(l.d31,x+6,y+128,'left')

    doc.rect(x+5, y+122, 205, 25)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+135)
    doc.setFont(undefined, 'NORMAL')
    doc.text(l.responsable,x+8,y+140)
    doc.setFont(undefined, 'normal')
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+135,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+135,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','QUIMICA SANGUINIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },

          serologia(){
            var doc = new jsPDF('landscape',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 195, 3, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+195, y+27, 155, 23)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+320, y+10,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+225, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 005',x+323, y+30)
    doc.setTextColor(57,73,171)
    doc.text('INMUNOENSAYO DE FLUORESCENCIA',x+270, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA','METODO'],x+196, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text(['ADIEMR PAUL CHAMBI AJATA','ADIMER PAUL CHAMBI AJATA A','COMPLRETA'],x+268, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+305, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+305, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.text(['9999 ','MACULJONO','11'],x+337, y+35,'center')
    doc.setTextColor(211,47,47)
    doc.text('CONTADOR Hematologico MINDRAY BC 5130',x+260, y+47,'center')
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+195, y+53, 155, 125)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(211,47,47)
    doc.text('',x+233,y+55)
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.text('       UNIDAD         TIPO MUESTRA      VALOR REFE',x+250,y+60)
    doc.setFontSize(12);

    doc.setFont(undefined, 'normal')
    doc.setFontSize(11);
    doc.text(['DIMEROS D',
      '',
      'FERRITINA',
      '',
      'IL-6',
      '',
      'PSA CUANTITATIVO',
      '',
      'PCR CUANTITATIVO',
      '',
      'TROPONINA I ',
      ' ',
      ' ',
      'B - HCG ',
      ' ',
      ' ',
      'PROCALCITONINA',
    ],x+197,y+65,'left')
    doc.setTextColor(0,0,0)
    doc.setFontSize(11);
    doc.setFont(undefined, 'normal')
    doc.setFontSize(11);
    doc.text([
      'DAS',
      '',
      'DAS',
      '',
      'DAS',
      '',
      'DAS',
      '',
      'DAS',
      '',
      'DAS',
      '',
      ' ',
      'DAS',
      ' ',
      ' ',
      'DAS',
      '',
    ],x+241,y+65,'center')
    doc.setTextColor(57,73,171)
    doc.text([
      'ng/ml ',
      '',
      'ng/ml ',
      '',
      'ng/ml',
      '',
      'pg/ml',
      '',
      'ng/ml',
      '',
      'mg/L',
      '',
      '',
      'mlU/ml ',
      '',
      ' ',
      'ng/ml',
      '',
    ],x+254,y+65,'center')
    doc.text([
      'Plasma Citratado',
      '',
      'Suero',
      '',
      'Suero/plasma',
      '',
      'Suero',
      '',
      'Sangre Entera ',
      '',
      'Suero',
      '',
      '',
      'Suero',
      '',
      ' ',
      'Suero',
      '',
    ],x+275,y+65,'center')
    doc.setFontSize(11);
    doc.text([
      'Hasta 500 ng/ml',
      '',
      '30-350 ng/ml Varon',
      '20-250 ng/ml Mujer',
      '7 pg/ml',
      '',
      'Menor a 4 ng/ml',
      '',
      'Menor a 10 mg/L',
      '',
      '0.0 - 0.11 ng/ml',
      '',
      'Mujer no Embarazada',
      'menor a 10 mlU/ml',
      'Mujer en postmenopausia',
      'menor a 10 mlU/ml',
      'Suero',
      '',
    ],x+315,y+65,'center')
    doc.setFontSize(11);


    doc.rect(x+195, y+180, 155, 30)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+205,y+185,'left')
    doc.setFontSize(10);
    doc.setLineHeightFactor(1.5)
    doc.text(['Fecha toma de Muestra','Fecha Entrega de Resultado','Hora toma Muestra'],x+280,y+185,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','QUIMICA SANGUINIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      },
      hemograma(p,l){
            var doc = new jsPDF('landscape',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 195, 3, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+195, y+27, 155, 23)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+320, y+10,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+225, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 001',x+323, y+30)
    doc.setTextColor(57,73,171)
    doc.text('HEMOGRAMA COMPLETO',x+270, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA','METODO'],x+196, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+268, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+305, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+305, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+337, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.text('CONTADOR Hematologico MINDRAY BC 5130',x+300, y+47,'center')
    //fin datos paciete
    //inicio datos
    doc.rect(x+195, y+51, 155, 77)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('REFERENCIA',x+253,y+55)
    doc.setFont(undefined, 'normal')
    doc.text(['Globulos rojos',' ','Hemoglobina',' ','Hematocrito',' ','V.E.S.',' ','V.C.M.',' ','Hb.C.M.',' ','C.Hb.C.M.',' ','Globulos Blancos',' ','Plaquetas'],x+209,y+60,'center')
      doc.setTextColor(0,0,0)
    if((parseFloat(l.d1)<5.1 || parseFloat(l.d1)>5.7) && p.sexo=='Masculino')
      doc.setTextColor(255,0,0)
    if((parseFloat(l.d1)<4.8 || parseFloat(l.d1)>5.4) && p.sexo=='Femenino')
      doc.setTextColor(255,0,0)
    doc.text(l.d1,x+226,y+60,'center')

      doc.setTextColor(0,0,0)
    if((parseFloat(l.d3)<151 || parseFloat(l.d3)>175) && p.sexo=='Masculino')
      doc.setTextColor(255,0,0)
    if((parseFloat(l.d3)<141 || parseFloat(l.d3)>165) && p.sexo=='Femenino')
      doc.setTextColor(255,0,0)
    doc.text(l.d3,x+226,y+68,'center')
      doc.setTextColor(0,0,0)

    if((parseFloat(l.d5)<0.51 || parseFloat(l.d5)>0.57) && p.sexo=='Masculino')
      doc.setTextColor(255,0,0)
    if((parseFloat(l.d5)<0.46 || parseFloat(l.d5)>0.53) && p.sexo=='Femenino')
      doc.setTextColor(255,0,0)
    doc.text(l.d5,x+226,y+76,'center')
          doc.setTextColor(0,0,0)

    if((parseFloat(l.d7)<0 || parseFloat(l.d7)>15) && p.sexo=='Masculino')
      doc.setTextColor(255,0,0)
    if((parseFloat(l.d7)<0 || parseFloat(l.d7)>20) && p.sexo=='Femenino')
      doc.setTextColor(255,0,0)
    doc.text(l.d7,x+226,y+84,'center')
          doc.setTextColor(0,0,0)

    if(parseFloat(l.d9)<83.0 || parseFloat(l.d9)>97.0)
      doc.setTextColor(255,0,0)
    doc.text(l.d9,x+226,y+92,'center')

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d11)<27.0 || parseFloat(l.d11)>31.0)
      doc.setTextColor(255,0,0)
      doc.text(l.d11,x+226,y+100,'center')

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d13)<32.0 || parseFloat(l.d13)>36.0)
      doc.setTextColor(255,0,0)

      doc.text(l.d13,x+226,y+108,'center')

      doc.setTextColor(0,0,0)
    if(parseFloat(l.d15)<4.5 || parseFloat(l.d15)>10.5)
      doc.setTextColor(255,0,0)

    doc.text(l.d15,x+226,y+116,'center')
      doc.setTextColor(0,0,0)
    if(parseFloat(l.d17)<150 || parseFloat(l.d17)>400)
      doc.setTextColor(255,0,0)
    doc.text(l.d17,x+226,y+125,'center')

    doc.setTextColor(57,73,171)
    doc.text(['x10^(12)/L',' ','g/L',' ','L/L',' ','mm.',' ','ft.',' ','pg.',' ','%',' ','x10^(9)L',' ','x10^(9)L'],x+240,y+60,'center')
    doc.setFontSize(6);
    doc.text(['Varon 5.1-5.7x10^12/L','Mujer 4.8-5.4x10^12/L'],x+265,y+59,'center')
    doc.text(['Varon 151-175 g/L','Mujer 141-165 g/L'],x+265,y+67,'center')
    doc.text(['Varon 0.51-0.57 L/L','Mujer 0.46-0.53 L/L'],x+265,y+75,'center')
    doc.text(['Varon 15 mm/hora','Mujer 20mm/hora'],x+265,y+83,'center')
    doc.text(['83.0-97.0 ft'],x+265,y+92,'center')
    doc.text(['27.0-31.0 pg.'],x+265,y+100,'center')
    doc.text(['32-36%'],x+265,y+108,'center')
    doc.text(['4.5-10.5x10^(9)/L'],x+265,y+116,'center')
    doc.text(['150-400x10^(9)L'],x+265,y+124,'center')
    doc.setFontSize(10);

    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('REFERENCIA',x+325,y+55)
    doc.setFont(undefined, 'normal')
    doc.text(['Tiempo','cuagulacion','Tiempo','sangria','Tiempo','Protrombina','% Actividad',' ','INR',' ','Grupo factor',' ','Reticulocitos',' ','IPR'],x+296,y+60,'center')
    doc.setTextColor(0,0,0)
    doc.setFontSize(8)
    doc.text(l.d2,x+320,y+60,'center')
    doc.text(l.d4,x+320,y+68,'center')
    doc.text(l.d6,x+320,y+76,'center')
    if(l.d8!='')
    doc.text(l.d8+' %',x+320,y+84,'center')
    doc.text(l.d10,x+320,y+92,'center')
    doc.text(l.d12,x+320,y+100,'center')
    doc.text(l.d14,x+320,y+108,'center')
    doc.text(l.d16,x+320,y+116,'center')

    //doc.text([l.d2,' ',' ',l.d4,' ',l.d6,' ',l.d8,' ',l.d10,' ',l.d12,' ',l.d14,' ',l.d16,' '],x+315,y+60,'center')
    doc.setTextColor(57,73,171)
    doc.setFontSize(10)

    doc.text(['5-10min',' ','1-3min',' ','12-13seg',' ','95-100%',' ','0.97-1.04.',' ','',' ','0.5-2%'],x+337,y+60,'center')

    doc.rect(x+195, y+129, 155, 40)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('VALOR REFERENCIAL',x+300,y+133)
    doc.text('RELATIVA               ABSOLUTA               RELATIVA               ABSOLUTA',x+220,y+138)
    doc.setFont(undefined, 'nomral')
    doc.text(['Cayados','Neutrofilos','Eosinofilos','Basofilos','Linfocitos','Monocitos','BLASTOS'],x+209,y+143,'center')
    doc.setTextColor(0,0,0)
    doc.text([l.d18,l.d20,l.d22,l.d24,l.d26,l.d28,l.d30],x+225,y+143,'center')
    doc.setTextColor(57,73,171)
    doc.text(['%','%','%','%','%','%','%'],x+233,y+143,'center')
    doc.setTextColor(0,0,0)
    doc.text([l.d19,l.d21,l.d23,l.d25,l.d27,l.d29],x+256,y+143,'center')
    doc.setTextColor(57,73,171)
    doc.text(['x10^(9)L','x10^(9)L','x10^(9)L','x10^(9)L','x10^(9)L','x10^(9)L'],x+268,y+143,'center')
    doc.text(['0-3%','50-70%','0-3%','0-1%','25-40%','4-8%'],x+295,y+143,'center')
    doc.text(['0.00-0.35x10^(9)L','2.50-7.35x10^(9)L','0.00-0.35x10^(9)L','0.00-0.15x10^(9)L','1.25-4.20x10^(9)L','2.00-8.40x10^(9)L'],x+327,y+143,'center')
    doc.rect(x+195, y+170, 155, 15)

    doc.text('MORFOLOGIA DE FROTIS DE SANGRE PERIFERICA',x+265,y+173,'center')
    doc.text(['Serie Rojas','Serie Blancas','Serie Plaquetarias'],x+223,y+176,'right')
    doc.setTextColor(0,0,0)
    doc.text([l.d31,l.d32,l.d33],x+228,y+176,'left')
    doc.setTextColor(57,73,171)
    doc.rect(x+195, y+186, 155, 15)

    doc.text('RESPONSABLE',x+205,y+190,'left')
    doc.text(l.responsable,x+215,y+195,'center')
    doc.text(['Fecha de Toma de Muestra','Hora de Toma de Muestra:','Fecha de Entrega Resultado'],x+265,y+190,'left')
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+310,y+190,'left')
   // doc.text(l.responsable,x+235,y+178,'right')

    // doc.html('<div style="border:1px solid black;width: 50px;font-size: 3px"><b><i>Adimer paul chambi ajata</i></b></div>', {
    //   callback: function (doc) {
    //     window.open(doc.output('bloburl'), '_blank');
    //   },
    //   x: 10,
    //   y: 10,
    // });
    // doc.save("Pago"+date.formatDate(Date.now(),'DD-MM-YYYY')+".pdf");
    // window.open(doc.output('bloburl'), '_blank');

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','HEMOGRAMA COMPLETO-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      },
      ensayo(p,l){
            var doc = new jsPDF('landscape',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 195, 3, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+195, y+27, 155, 23)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+320, y+10,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+225, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 012',x+323, y+30)
    doc.setTextColor(57,73,171)
    doc.text('',x+270, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+196, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+268, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+305, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+305, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+337, y+35,'center')
    doc.setTextColor(211,47,47)
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+195, y+51, 155, 130)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(211,47,47)
    doc.text('METODO: INMUNOENSAYO DE FLUORESCENCIA (FIA)',x+233,y+55)
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.text('UNIDAD   TIPO MUESTRA   VALOR REF',x+260,y+60)
    doc.setFontSize(12);

    doc.setFont(undefined, 'normal')
    doc.setFontSize(10);
    doc.text('DIMEROS D',x+198,y+65,'left')
    doc.text('FERRITINA',x+198,y+73,'left')
    doc.text('IL-6',x+198,y+81,'left')
    doc.text('PSA CUANTITATIVO',x+198,y+89,'left')
    doc.text('PCR CUANTITATIVO',x+198,y+97,'left')
    doc.text('TROPONINA I',x+198,y+106,'left')
    doc.text('B - HCG',x+198,y+114,'left')
    doc.text('PROCALCITONINA',x+198,y+130,'left')
    doc.text('Anti-HBS',x+198,y+152,'left')
    doc.text('lg M',x+198,y+164,'left')
    doc.text('lg G',x+198,y+174,'left')
    doc.setFontSize(12);
    doc.setTextColor(0,0,0)

       if(parseFloat(l.d1)>500.0)
      doc.setTextColor(255,0,0)
    doc.text(l.d1,x+250,y+65,'center')
    doc.setTextColor(0,0,0)
    if((parseFloat(l.d2)<30 || parseFloat(l.d2)>350) && p.sexo=='Masculino')
      doc.setTextColor(255,0,0)
    if((parseFloat(l.d2)<20 || parseFloat(l.d2)>250) && p.sexo=='Femenino')
      doc.setTextColor(255,0,0)
    doc.text(l.d2,x+250,y+73,'center')
    doc.setTextColor(0,0,0)
    doc.text(l.d3,x+250,y+81,'center')
    if(parseFloat(l.d4)>4)
      doc.setTextColor(255,0,0)
    doc.text(l.d4,x+250,y+89,'center')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d5)>10)
      doc.setTextColor(255,0,0)
    doc.text(l.d5,x+250,y+97,'center')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d6)>0.11 || parseFloat(l.d6)<0.0)
      doc.setTextColor(255,0,0)
    doc.text(l.d6,x+250,y+106,'center')
    doc.setTextColor(0,0,0)
    
    doc.text(l.d7,x+250,y+118,'center')
    doc.text(l.d8,x+250,y+134,'center')

    if(parseFloat(l.d9)>15 )
      doc.setTextColor(255,0,0)
    doc.text(l.d9,x+250,y+152,'center')
    doc.setTextColor(0,0,0)
    if(parseFloat(l.d10)>1.1 )
      doc.setTextColor(255,0,0)
      doc.text(l.d10,x+250,y+164,'center')
      doc.setTextColor(0,0,0)
    if(parseFloat(l.d11)>8 )
      doc.setTextColor(255,0,0)
    doc.text(l.d11,x+250,y+174,'center')
    doc.setTextColor(0,0,0)

    doc.text('ng/ml',x+270,y+65,'center')
    doc.text('ng/ml',x+270,y+73,'center')
    doc.text('pg/ml',x+270,y+81,'center')
    doc.text('ng/ml',x+270,y+89,'center')
    doc.text('mg/l',x+270,y+97,'center')
    doc.text('ng/ml',x+270,y+106,'center')
    doc.text('mlU/ml',x+270,y+118,'center')
    doc.text('ng/ml',x+270,y+134,'center')
    doc.text('mlU/ml',x+270,y+152,'center')
    doc.text('IU/ml',x+270,y+164,'center')
    doc.text('IU/ml',x+270,y+174,'center')

    doc.setFontSize(10);
    doc.text('Plasma Citratado',x+290,y+65,'center')
    doc.text('Suero',x+290,y+73,'center')
    doc.text('Suero/plasma',x+290,y+81,'center')
    doc.text('Suero',x+290,y+89,'center')
    doc.text('Sangre Entera',x+290,y+97,'center')
    doc.text('Suero',x+290,y+106,'center')
    doc.text('Suero',x+290,y+118,'center')
    doc.text('Suero',x+290,y+134,'center')
    doc.text('FIA',x+290,y+152,'center')
    doc.text('FIA',x+290,y+164,'center')
    doc.text('FIA',x+290,y+174,'center')
    doc.setTextColor(57,73,171)
    doc.setFontSize(8);
    doc.setLineHeightFactor(1)
    doc.text(['Hasta 500 ng/ml','','30-350 ng/ml Varon','20-250 ng/ml Mujer','',' ','7 pg/ml','',' ','Menor a 4 ng/ml','',' ','<10 mg/L','',' ','0.0 - 0.11 ng/ml','',' ','Mujer No Embarazada < 10 mlU/ml','Mujer en postmenopausia <10 mlU/ml','',
    'PCT < 0.5 Es posible infeccion' ,'  bacteriana local','PCT 0.5 - 2 Posible infeccion','PCT 2 -10 Es muy probable ',' infeccion(sepsia) a menores que se ','conozcan otras causas',
    'PCT > 10 Sepsia Bacteriana severa',' o shock septico','',
    '< 5 mlU/ml NEGATIVO','5 - 15 mlU/ml INDETERMINADO','> 15 mlU/ml POSITIVO','','< 0.9 IU/ml NEGATIVO','0.9 - 1.1 UI/ml INTERMEDIO','> 1.1 IU/ml POSITIVO','',
    '< 4 IU/ml NEGATIVO','4 - 8 UI/ml INTERMEDIO','> 8 IU/ml POSITIVO',''
    ],x+305,y+65,'left')
    doc.setLineHeightFactor(1.15)
    doc.setFontSize(10);


    doc.rect(x+195, y+184, 155, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+205,y+189,'left')
    doc.text(l.responsable,x+205,y+193,'left')
    doc.setFontSize(10);
    doc.text(['Fecha toma de Muestra','Hora toma Muestra','Fecha Entrega de Resultado'],x+280,y+189,'left')
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+330,y+189,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','INMUNOENSAYO DE FLUORESCENCIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      },
      reserologia(p,l){
                    var doc = new jsPDF('landscape',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 195, 3, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+195, y+27, 155, 23)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+320, y+10,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+225, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 011',x+323, y+30)
    doc.setTextColor(57,73,171)
    doc.text('',x+270, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+196, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+268, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+305, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+305, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+337, y+35,'center')
    doc.setTextColor(211,47,47)
    doc.setTextColor(57,73,171)
    //fin datos paciete
    //inicio datos
    doc.rect(x+195, y+51, 155, 25)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(211,47,47)
    doc.text('PRUEBA RAPIDA ANTIGENOS SARS COV 2',x+233,y+55)
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.text('METODO:  INMUNOGRAMATOGRAFIA CUALITATIVA',x+220,y+60)
    doc.setFontSize(12);

    doc.setFont(undefined, 'normal')
    doc.setFontSize(10);
    doc.setTextColor(211,47,47)
    doc.text(['ANTIGENO','SARS COV 2'],x+250,y+70,'left')
    doc.setTextColor(57,73,171)
    doc.text(l.d1,x+285,y+73,'left')
    doc.setTextColor(0,0,0)
    doc.rect(x+195, y+75, 155, 72)

    doc.text('FUNDAMENTO E INTERPRETACION DE LOS RESULTADO',x+220,y+80)
    doc.text(['La prueba rapida de Antigeno Nasal puede revelar una Infeccion activa de SARS-COV-2.','El test proporciona un resultado de "POSITIVO" o "NEGATIVO"','Los antigenos son parte de la estructura del virus, como las proteinas de espiga.'],x+205,y+85)
    doc.text(['La muestra de Hisopado nasofaringeo pasa por una linea que detecta antigenos y cambia de color, ','durante la prueba el anticuerpo monocianal de raton anti-SARS-CoV-2 en la muestra interactua con ','el Anticuerpo monocianal lgG anti-COVID-19 conjugando con particulas de color que forman un ','complejo de particulas de antigeno-anticuerpo. Este complejo migra en la menbrana por accion ','capilar hasta la linea de prueba, donde aera capturado por el anticuerpo por el anticuerpo monocianal ','anti-SARS-CoV-2 de raton. Una linea de prueba coloreada seria visible en la ventana de resultados ','si los antigenos del SARS-CoV-2 estan presentes en la muestra.'],x+205,y+100)
    doc.text(['Funciona mejor en la etapa inicial con una CARGA VIRAL ALTA y sintomalogia hasta los 10 dias.','Los resultados Negativos indica ausencia de Antigenos detectables de SARS-CoV-2 y cuando la ','carga viral o la cantidad de antigeno presente se encuentra debajo del limite de deteccion.'],x+205,y+130)

    doc.text('Los resultados Psitivos no diferencian entre SARS-COV y SARS-COV-2',x+205,y+145)
    doc.rect(x+195, y+148, 155, 10)
    doc.text(['NOTA: Las pruebas rapidas para COVID-19 NO SON CONFIRMATORIAS Los Resultados deben ','ser interpretados en funcion de la Clinica del paciente y dias de evolucion de la enfermedad'],x+205,y+152)

   //doc.text('El test proporciona un resultado de "POSITIVO" o "NEGATIVO"',x+205,y+85)
    doc.setFontSize(10);


    doc.rect(x+195, y+160, 155, 10)
    doc.setTextColor(57,73,171)
    doc.text('OBSERVACION',x+200,y+164)
    doc.setTextColor(0,0,0)
    doc.text(l.d2,x+200,y+168)
    doc.rect(x+195, y+170, 155, 30)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+205,y+175,'left')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+205,y+180,'left')
    doc.setTextColor(57,73,171)
    doc.setFontSize(10);
    doc.text(['Fecha toma de Muestra','Hora toma Muestra','Fecha Entrega de Resultado'],x+280,y+175,'left')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+325,y+175,'left')

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','RESULTADO SEROLOGIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      },
      embarazo(p,l) {
    var doc = new jsPDF('P',undefined,'legal')
    doc.setFont("arial");
    doc.setFontSize(10);
    var img = new Image()
    img.src = 'img/natividad.jpeg'
    doc.addImage(img, 'jpg', 5, 2, 70, 20)
    let x=0
    let y=0
    //inicio datos paciete
    doc.setDrawColor(120);
    doc.rect(x+5, y+27, 205, 23)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    doc.setTextColor(195,47,47)
    doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    doc.setTextColor(211,47,47)
    doc.text('Form. 013',x+190, y+30)
    doc.setTextColor(57,73,171)
    doc.text('PRUEBA RAPIDA INMUNOCROMATOGRAFICA',x+100, y+30,'center')
    doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA','METODO'],x+6, y+35)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text(['EDAD','SEXO'],x+130, y+35)
    doc.setTextColor(211,47,47)
    doc.text('N PACIENTE',x+130, y+43)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
        let anio=''
    if(p.edad==null||p.edad==undefined||p.edad=='')
    anio=p.tiempo
    else anio=p.edad
    doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    doc.setTextColor(57,73,171)
    doc.text('PRUEBA RAPIDA INMUNOCROMATOGRAFICA',x+100, y+47,'center')
    //fin datos paciete
    //inicio datos
    doc.rect(x+5, y+51, 205, 50)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('TEST EMBARAZO EN SANGRE',x+25,y+60)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(0,0,0)
    doc.text(l.d1,x+100,y+65,'center')
    doc.setTextColor(57,73,171)
    doc.text('F.U.M.',x+45,y+75,'center')
    doc.setTextColor(0,0,0)
    doc.text(l.d2,x+100,y+80,'center')

    doc.setTextColor(57,73,171)
    doc.setFont(undefined, 'bold')
    doc.text('OBSERVACIONES:',x+20,y+90)
    doc.setTextColor(0,0,0)
    doc.setFont(undefined, 'normal')
    doc.text(l.d3,x+30,y+95,'left')

    doc.rect(x+5, y+102, 205, 20)
    doc.setFont(undefined, 'bold')
    doc.setTextColor(57,73,171)
    doc.text('RESPONSABLE',x+6,y+110)
    doc.setFont(undefined, 'NORMAL')
    doc.setTextColor(0,0,0)
    doc.text(l.responsable,x+8,y+115)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(57,73,171)
    doc.text(['FECHA DE TOMA DE MUESTRA','HORA TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+110,'center')
    doc.setTextColor(0,0,0)
    doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,date.formatDate(new Date(),'DD-MM-YYYY')],x+170,y+110,'left')
    doc.setTextColor(0,0,0)

    //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    window.open(doc.output('bloburl'), '_blank');
    //            doc.output('save','TEST EMBARAZO-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
  },

    labo(paciente){
      console.log(paciente.laboratorios)
      this.paciente=paciente
      this.laboratorios=paciente.laboratorios
      this.dialog_lab=true
    },
    listado(){
     this.$q.loading.show()
     this.$axios.get(process.env.API+'/seguro').then(res=>{
       console.log(res.data)
       this.rows=res.data
       this.$q.loading.hide()
     })
    },
    onSubmit(){
     this.$axios.post(process.env.API+'/seguro',this.dato).then(res=>{
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
    deleteRow(props){
      this.dato2=props.row;
      console.log(this.dato2);

      this.$q.dialog({
        title: 'Eliminar Seguro',
        message: 'Esta Seguro de Eliminar ?',
        cancel: true,
      }).onOk(() => {
          this.$axios.delete(process.env.API+'/seguro/'+this.dato2.id).then(res=>{
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
      this.$axios.put(process.env.API+'/seguro/'+this.dato2.id,this.dato2).then(res=>{
         this.$q.notify({
          message: 'Se modifico correctamente',
          color: 'green'
        })
      })
      this.dialog_mod=false;
      this.listado();
    }
  },
              calcular3(fechanac){
      if(fechanac==null || fechanac=='' || fechanac==undefined)
        return ''
      else{
        	var a = moment();
	        var b = moment(fechanac);

          var years = a.diff(b, 'year');
          b.add(years, 'years');

          var months = a.diff(b, 'months');
          b.add(months, 'months');

          var days = a.diff(b, 'days');

          if(years==0){
            if(months<=1){
              if(days<=1){
                return months + ' MES ' + days + ' DIA'
                }else{
                return  months + ' MES ' + days + ' DIAS'
                }
            }else{
              if(days<=1){
                return months + ' MESES ' + days + ' DIA'
              }else{
                return months + ' MESES ' + days + ' DIAS'
              }  
	            }
     
            }else{
              if(years==1){
                return  years + ' AÑO'
                }else{
                return years + ' AÑOS'
                }	
	          }
      }
    },

}
</script>
