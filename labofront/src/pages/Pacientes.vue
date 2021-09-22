<template>
  <q-page class="q-pa-xs">
    <q-card>
        <q-btn  label="Registrar Paciente" color="positive" @click="alert = true" icon="add_circle" class="q-mb-xs" />
    <q-dialog v-model="alert">
      <q-card>
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Registro</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <q-form
            @submit="onSubmit"
            class="q-gutter-md"
          >
            <q-input
              filled
              v-model="dato.ci"
              label="Cedula Identidad"
              hint="Numero de carnet"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="dato.nombre"
              label="Nombre "
              hint="Ingresar Nombre "
                  style="text-transform: uppercase;"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="dato.paterno"
              label="Ap Paterno "
              hint="Ingresar Paterno "
                  style="text-transform: uppercase;"
            />
            <q-input
              filled
              v-model="dato.materno"
              label="Ap Materno "
              hint="Ingresar Materno "
                  style="text-transform: uppercase;"
            />
            <q-input
              filled
              type="date"
              v-model="dato.fechanac"
              label="Fecha Nac"
              lazy-rules
              :rules="[ val => val && val.length > 0 && val<=actual || 'Por favor ingresa datos']"
            />
            <q-radio v-model="dato.sexo" val="Masculino" label="Masculino" />
            <q-radio v-model="dato.sexo" val="Femenino" label="Femenino" />
            <q-input
              filled
              v-model="dato.celular"
              label="Celular (Whatsapp)"
              hint="Numero de celular"
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
        title="Pacientes"
        :columns="columns"
        :rows="pacientes"
      >
        <template v-slot:top-right>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>

              <template v-slot:body-cell-opcion="props">
        <q-tr :props="props">
          <q-td key="opcion" :props="props">
              <q-btn  dense round flat color="accent" @click="labRow(props)" icon="biotech"></q-btn>
              <q-btn  dense round flat color="yellow" @click="editRow(props)" icon="edit"></q-btn>
              <q-btn  dense round flat color="red" @click="deleteRow(props)" icon="delete"></q-btn>
          </q-td>
        </q-tr>
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
              v-model="dato2.ci"
              label="Cedula Identidad"
              hint="Numero de carnet"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="dato2.nombre"
              label="Nombre "
              hint="Ingresar Nombre Completo"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-input
              filled
              v-model="dato2.paterno"
              label="Ap paterno"
              hint="Ingresar Paterno"
            />
            <q-input
              filled
              v-model="dato2.materno"
              label="Ap Materno"
              hint="Ingresar Materno"
            />
            <q-input
              filled
              type="date"
              v-model="dato2.fechanac"
              label="Fecha Nac"
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
            />
            <q-radio v-model="dato2.sexo" val="Masculino" label="Masculino" />
            <q-radio v-model="dato2.sexo" val="Femenino" label="Femenino" />
            <q-input
              filled
              v-model="dato2.celular"
              label="Celular (Whatsapp)"
              hint="Numero de celular"
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

     <q-dialog v-model="dialog_lab" maximized>
      <q-card >
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Formularios</div>
        </q-card-section>
        <q-card-section >
         <q-select v-model="tab" :options="formulario"  label="FORMULARIOS" />

        <q-separator />
              <q-tab-panels v-model="tab.value" animated class="shadow-2 rounded-borders">
        <q-tab-panel name="hemograma">
            <q-form @submit="onHemograma" >
        <table style="width: 100%;color: black">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="../assets/natividad.png" alt="Logo Clinica" srcset="" style="height: 3cm; width:8cm;"></td>
                <td style="color: blue; text-align:center; height:0.5cm;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Telf: 5254721 Fax: 52-83667 </td>
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Emergencia las 24 horas del dia. </td>
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Bolivar Nº 753 entre Arica e Iquique </td>
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center"><h3>HEMOGRAMA COMPLETO</h3></td>
                <td>Form. 001</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.age}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td>
                    <q-select borderless  :options="doctors" v-model="requerido" style="width:100%"/>
                </td>

                <td style="color: darkblue">SEXO </td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td><q-input borderless  type="text" style="width: 100%" placeholder="Tipo muestra" v-model="hemograma.tipomuestra"/></td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">METODO</td>
                <td colspan="3">
                    Contador Hematologico MINDRAY BC 5130
                    Hematocrito (Metodo Manual) Hemoglobina (Clanmetahemoglobina reactivo drabking)
                </td>
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3"></td>
                <td>REFERENCIA</td>
                <td colspan="2"></td>
                <td>REFERENCIA</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Globulos Rojos</td>
                <td><input borderless type="text" placeholder="00" v-model="hemograma.d1"   style="width: 100%"/></td>
                <td>x10 <sup>12 </sup>/L</td>
                <td>Varon 5.1-5.7x10 <sup>12</sup>/L <br> Mujer 4.8-5.4x10 <sup>12</sup>/L</td>
                <td class="bg-negative text-white">Tiempo de cuagulacion</td>
                <td><input borderless type="text" placeholder="00"  style="width: 100%" v-model="hemograma.d2"/></td>
                <td>5-10 min</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Hemoglobina</td>
                <td><input borderless type="text" placeholder="00" name="d3"   style="width: 100%" v-model="hemograma.d3"/></td>
                <td>g/L</td>
                <td>Varon 151-175 g/L <br> Mujer Mujer 141-165 g/L</td>
                <td class="bg-negative text-white">Tiempo de sangria</td>
                <td><input type="text" placeholder="00" name="d4"   style="width: 100%" v-model="hemograma.d4"/></td>
                <td>1-3 min</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Hematocrito</td>
                <td><input type="text" placeholder="00" name="d5"   style="width: 100%" v-model="hemograma.d5"/></td>
                <td>L/L</td>
                <td>Varon 0.51-0.57 L/L <br> Mujer 0.46-0.53 L/L</td>
                <td class="bg-negative text-white">Tiempo de Protrombina</td>
                <td><input type="text" placeholder="00" name="d6"   style="width: 100%" v-model="hemograma.d6"/></td>
                <td>12-13 seg</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">V.E.S.</td>
                <td><input type="text" placeholder="00" name="d7"   style="width: 100%" v-model="hemograma.d7"/></td>
                <td>mm.</td>
                <td>Varon 15 mm/hora <br> Mujer 20 mm/hora</td>
                <td class="bg-negative text-white">% Actividad</td>
                <td><input type="text" placeholder="00" name="d8"   style="width: 100%" v-model="hemograma.d8"/></td>
                <td>95-100%</td>
            </tr>

            <tr>
                <td class="bg-negative text-white">V.C.M.</td>
                <td><input type="text" placeholder="00" name="d9"   style="width: 100%" v-model="hemograma.d9"/></td>
                <td>ft.</td>
                <td>Varon 83.0-97.0 ft</td>
                <td class="bg-negative text-white">INR</td>
                <td><input type="text" placeholder="00" name="d10"   style="width: 100%" v-model="hemograma.d10"/></td>
                <td>0.97-1.04</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Hb.C.M.</td>
                <td><input type="text" placeholder="00" name="d11"   style="width: 100%" v-model="hemograma.d11"/></td>
                <td>pg.</td>
                <td>27.0-31.0 pg.</td>
                <td class="bg-negative text-white">Grupofactor</td>
                <td colspan="2"><input type="text" placeholder="00" name="d12"   style="width: 100%" v-model="hemograma.d12"/></td>
            </tr>
            <tr>
                <td class="bg-negative text-white">C. Hb.C.M.</td>
                <td><input type="text" placeholder="00" name="d13"   style="width: 100%" v-model="hemograma.d13"/></td>
                <td>%</td>
                <td>32-36%</td>
                <td class="bg-negative text-white">Reticulocitos</td>
                <td><input type="text" placeholder="00" name="d14"   style="width: 100%" v-model="hemograma.d14"/></td>
                <td>0.5-2%</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Globulos Blancos</td>
                <td><input type="text" placeholder="00" name="d15"   style="width: 100%" v-model="hemograma.d15"/></td>
                <td>10 <sup>9</sup>/L</td>
                <td> 4.5-10.5x10 <sup>9</sup>/L</td>
                <td class="bg-negative text-white">IPR</td>
                <td><input type="text" placeholder="00" name="d16"   style="width: 100%" v-model="hemograma.d16"/></td>
                <td></td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Plaquetas</td>
                <td><input hide-bottom-space type="text" placeholder="00" style="width: 100%" v-model="hemograma.d17"/></td>
                <td>x10 <sup>9 </sup>/L</td>
                <td>105-400x10 <sup>9</sup> /L</td>
                <td colspan="3"></td>
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="5"></td>
                <td colspan="2">VALOR REFERENCIAL</td>
            </tr>
            <tr>
                <td class="bg-negative text-white"></td>
                <td colspan="2">RELATIVA</td>
                <td colspan="2">ABSOLUTA</td>
                <td>RELATIVA</td>
                <td>ABSOLUTA</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Cayados</td>
                <td><input type="text" placeholder="00" name="d18"   style="width: 100%" v-model="hemograma.d18"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d19"   style="width: 100%" v-model="hemograma.d19"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>0-3%</td>
                <td>0.00-0.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Neutrofilos</td>
                <td><input type="text" placeholder="00" name="d20"   style="width: 100%" v-model="hemograma.d20"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d21"   style="width: 100%" v-model="hemograma.d21"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>50-70%</td>
                <td>2.50-7.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Eosinofilos</td>
                <td><input type="text" placeholder="00" name="d22"   style="width: 100%" v-model="hemograma.d22"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d23"   style="width: 100%" v-model="hemograma.d23"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>0-3%</td>
                <td>0.00-0.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Basofilos</td>
                <td><input type="text" placeholder="00" name="d24"   style="width: 100%" v-model="hemograma.d24"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d25"   style="width: 100%" v-model="hemograma.d25"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>0-1%</td>
                <td>0.00-0.15x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Linfocitos</td>
                <td><input type="text" placeholder="00" name="d26"   style="width: 100%" v-model="hemograma.d26"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d27"   style="width: 100%" v-model="hemograma.d27"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>25-40%</td>
                <td>1.25-4.200x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Monocitos</td>
                <td><input type="text" placeholder="00" name="d28"   style="width: 100%" v-model="hemograma.d28"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d29"   style="width: 100%" v-model="hemograma.d29"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>4-8%</td>
                <td>2.00-8.40x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">BLASTOS</td>
                <td><input type="text" placeholder="00" name="d30"   style="width: 100%" v-model="hemograma.d30"></td>
                <td>%</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="2">MORFOLOGIA DE FRONTIS DE SANGRE PERIFERICA</td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Serie Rojas:</td>
                <td><input type="text" placeholder="00" name="d31"   style="width: 100%" v-model="hemograma.d31"></td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Serie Blancas:</td>
                <td><input type="text" placeholder="00" name="d32"   style="width: 100%" v-model="hemograma.d32"></td>
            </tr>
            <tr>
                <td class="bg-negative text-white">Serie Plaquetarias:</td>
                <td><input type="text" placeholder="00" name="d33"   style="width: 100%" v-model="hemograma.d33"></td>
            </tr>
            <tr>
                <td class="bg-negative text-white">FECHA DE TOMA DE MUESTRA:</td>
                <td><input type="date" name="fechatoma"   style="width: 100%" v-model="hemograma.fechatoma"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button class="btn btn-success btn-block" type="submit"><i class="fa fa-save"></i> REGISTRAR</button>
                </td>
            </tr>
        </table>
    </q-form>
        </q-tab-panel>


        <q-tab-panel name="sanguinia">
          <div class="text-h6">QUIMICA sanguinia</div>
            <q-form @submit="onsanguinia" >

        <table style="width: 100%;color: black">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="../assets/natividad.png" alt="Logo Clinica" srcset="" style="height: 4cm; width:8cm;"></td>
                <td style="color: blue; text-align:center; height:0.5cm;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Telf: 5254721 Fax: 52-83667 </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Emergencia las 24 horas del dia. </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Bolivar Nº 753 entre Arica e Iquique </td>                
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center"><h3>QUIMICA sanguinia</h3></td>
                <td>Form. 002</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}}</td>
                <td style="color: darkblue">EDAD</td>
                <td></td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td><input type="text" style="width: 100%" v-model="sanguinia.tipomuestra" placeholder="Tipo muestra" ></td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr class="bg-purpal text-white">
                <th>PRUEBA</th>
                <th>VALOR</th>
                <th>REFERENCIA</th>
                <th>METODO</th>
                <th>PRUEBA</th>
                <th>VALOR</th>
                <th>VALOR</th>
                <th>METODO</th>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Glicemia</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d1"   style="width: 100%"></td>
                <td>70-105mg/dl</td>
                <td>Glucosa Oxidasa</td>
                <td class="bg-red text-center text-white">Fosfatasa alcalina</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d2"   style="width: 100%"></td>
                <td>adultos hasta 100UI/L</td>
                <td>Cinetico</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Creatinina</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d3"   style="width: 100%"></td>
                <td>0.7-1.5mg/dl</td>
                <td>Picrato Alcalino</td>
                <td class="bg-red text-center text-white">Fosfatasa alcalina</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d4"   style="width: 100%"></td>
                <td>niños 100-400UI/L</td>
                <td>Cinetico</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Urea</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d5"   style="width: 100%"></td>
                <td>15-45mg/dl</td>
                <td>Enzimatico UV</td>
                <td class="bg-red text-center text-white">Transamisas GOT</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d6"   style="width: 100%"></td>
                <td>hasta 40UI/L</td>
                <td>Cinetico</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">NUS-BUN</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d7"   style="width: 100%"></td>
                <td>7-18mg/dl</td>
                <td>Cinetico UV</td>
                <td class="bg-red text-center text-white">Transamisas GPT</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d8"   style="width: 100%"></td>
                <td>hasta 41UI/L</td>
                <td>Cinetico</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Acido Urico</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d9"   style="width: 100%"></td>
                <td>2.6-7.2mg/dl</td>
                <td>Uricasa/Peroxidasa</td>
                <td class="bg-purpal text-center text-white">LIPIDOGRAMA</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Proteinas Totales</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d10"   style="width: 100%"></td>
                <td>6.2-8.5g/dl</td>
                <td>Biuret</td>
                <td class="bg-red text-center text-white">Trigliceridos</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d11"   style="width: 100%"></td>
                <td>40-160mg/dl</td>
                <td>GPO-PAP</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Albumina</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d12"   style="width: 100%"></td>
                <td>3.5-5.3g/dl</td>
                <td>Verde Bromocresol</td>
                <td class="bg-red text-center text-white">Colesterol Total</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d13"   style="width: 100%"></td>
                <td>menor 200mg/dl</td>
                <td>CHOD-PAP</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Globulina</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d14"   style="width: 100%"></td>
                <td>2.8-3.5g/dl</td>
                <td></td>
                <td class="bg-red text-center text-white">HDL-Col.</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d15"   style="width: 100%"></td>
                <td>35-65mg/dl</td>
                <td>CHOD-PAP</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Amilasa</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d16"   style="width: 100%"></td>
                <td>menor a 120 UI/L</td>
                <td>Enzimatico a Amilasa</td>
                <td class="bg-red text-center text-white">LDL-Col.</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d17"   style="width: 100%"></td>
                <td>Hasta 150mg/dl</td>
                <td>CHOD-PAP</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Lipasa</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d18"   style="width: 100%"></td>
                <td>10-150UI/L</td>
                <td>Enzimatica Colorimetrica</td>
                <td class="bg-purpal text-center text-white">ELECTROLITOS</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Bilirrubina Total</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d19"   style="width: 100%"></td>
                <td>hasta 1.2 mg/dl</td>
                <td rowspan="3">Acido Sulfanilico con Diaazo</td>
                <td class="bg-red text-center text-white">Sodio</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d20"   style="width: 100%"></td>
                <td>135-155 mEq/L</td>
                <td rowspan="4">Automatizado CORNLEY AFT-500</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Bilirrubina Directa</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d21"   style="width: 100%"></td>
                <td>hasta 0.3 mg/dl</td>
                <td class="bg-red text-center text-white">Cloro</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d22"   style="width: 100%"></td>
                <td>98-106 mEq/L</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Bilirrubina Inderecta</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d23"   style="width: 100%"></td>
                <td>hasta 0.9 mg/dl</td>
                <td class="bg-red text-center text-white">Potasio</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d24"   style="width: 100%"></td>
                <td>3.4-5.3 mEq/L</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">CK-MB</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d25"   style="width: 100%"></td>
                <td>0-24 UI/L</td>
                <td>Enzimatico </td>
                <td class="bg-red text-center text-white">Calcio</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d26"   style="width: 100%"></td>
                <td>8.5-10.5mg/dl</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">LDH</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d27"   style="width: 100%"></td>
                <td>200-480UI/L</td>
                <td>Piruvato Lactato</td>
                <td class="bg-red text-center text-white">Magnesio</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d28"   style="width: 100%"></td>
                <td>1.7-2.4mg/dl</td>
                <td>Colorimetrico calmagita</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Hierro</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d29"   style="width: 100%"></td>
                <td>50-170ug/dl</td>
                <td>Goodwing Modificado</td>
                <td class="bg-red text-center text-white">Fosforo</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d30"   style="width: 100%"></td>
                <td>2.5-4.5mg/dl</td>
                <td>Fosfomolibdato UV</td>
            </tr>
            <tr>
                <td colspan="2" class="bg-blue text-center text-white">OBSERVACIONES</td>
                <td colspan="6"><input placeholder="00" type="text" v-model="sanguinia.d31"   style="width: 100%" ></td>
            </tr>
            <tr>
                <td colspan="2" rowspan="2" class="bg-blue text-center text-white">RESPONSABLE</td>
                <td colspan="2" rowspan="2">
                    sanguinia.
                </td>
                <td colspan="3" class="bg-blue text-center text-white">FECHA TOMA DE MUESTRA</td>
                <td><input placeholder="00" type="date" v-model="sanguinia.fechatoma"   style="width: 100%" ></td>
            </tr>
            <tr>
                <td colspan="3" class="bg-blue text-center text-white">FECHA DE ENTREGA DE RESULTADO</td>
                <td><input placeholder="00" type="date" v-model="sanguinia.fechares"   style="width: 100%" ></td>
            </tr>
            <tr>
                <td colspan="8">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus-circle"></i> GUARDAR</button>
                </td>
            </tr>
        </table>
    </q-form>
        </q-tab-panel>


        <q-tab-panel name="orina">
          <div class="text-h6">EXAMEN GENERAL DE ORINA</div>
            <q-form @submit="onOrina" >
        <table style="width: 100%;color: black">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="../assets/natividad.png" alt="Logo Clinica" srcset="" style="height: 4cm; width:8cm;"></td>
                <td style="color: blue; text-align:center; height:0.5cm;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Telf: 5254721 Fax: 52-83667 </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Emergencia las 24 horas del dia. </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Bolivar Nº 753 entre Arica e Iquique </td>                
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center"><h3>EXAMEN GENERAL DE ORINA</h3></td>
                <td>Form. 003</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}}</td>
                <td style="color: darkblue">EDAD</td>
                <td></td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td><input type="text" style="width: 100%" v-model="orina.tipomuestra" placeholder="Tipo muestra"  ></td>

                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td class="bg-red text-center text-white">EX. FISICO</td>
                <td class="bg-red text-center text-white">VALOR</td>
                <td>REFERENCIA</td>
                <td>EX. QUIMICO</td>
                <td class="bg-red text-center text-white">VALOR</td>
                <td>REFERENCIA</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Color</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d1"></td>
                <td>Amarillo</td>
                <td class="bg-red text-center text-white">Proteinas</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d2"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Olor</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d3"></td>
                <td>Sui-generis</td>
                <td class="bg-red text-center text-white">Glucosa</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d4"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Aspecto</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d5"></td>
                <td>Limpido o lig opal</td>
                <td class="bg-red text-center text-white">C. cetonicos</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d6"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Espuma</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d7"></td>
                <td>Blanco fugaz</td>
                <td class="bg-red text-center text-white">Bilirrubina</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d8"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Deposito</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d9"></td>
                <td>Nulo o escacaso</td>
                <td class="bg-red text-center text-white">Hemoglobina</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d10"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Densidad</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d11"></td>
                <td>1.010-1.030</td>
                <td class="bg-red text-center text-white">Urobilina</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d12"></td>
                <td>Normal</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Reaccion</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d13"></td>
                <td>Lig. acida</td>
                <td class="bg-red text-center text-white">Nitrinos</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d14"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: center">SEDIMENTO: EXAMEN MICROSCOPICO</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">CELULAS</td>
                <td class="bg-red text-center text-white">VALOR</td>
                <td>REFERENCIA</td>
                <td>CILINDROS</td>
                <td class="bg-red text-center text-white">VALOR</td>
                <td>REFERENCIA</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Celulas epiteliales</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d15"></td>
                <td>Hasta 2/c.</td>
                <td class="bg-red text-center text-white">Hialino</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d16"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Celulas de transicion</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d17"></td>
                <td>Negativo</td>
                <td class="bg-red text-center text-white">Granuloso</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d18"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Celulas clave</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d19"></td>
                <td>Negativo</td>
                <td class="bg-red text-center text-white">Epiteliales</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d20"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Leucocitos</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d21"></td>
                <td>Hasta 5/c</td>
                <td class="bg-red text-center text-white">Eritrocitario</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d22"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Eritrocitos</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d23"></td>
                <td>Hasta 3/c</td>
                <td class="bg-red text-center text-white">Leucositario</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d24"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Bacterias</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d25"></td>
                <td>Escaso</td>
                <td class="bg-red text-center text-white">Cereos</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d26"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">CRISTALES</td>
                <td colspan="2"></td>
                <td class="bg-red text-center text-white">Mixtos</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d27"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Uratos amorfos</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d28"></td>
                <td>Escasos</td>
                <td colspan="3">OTROS</td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Fosfato amorfo</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d29"></td>
                <td>Escasos</td>
                <td class="bg-red text-center text-white">Filamento mucoso</td>
                <td colspan="2"><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d30"></td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Oxalato de calcio</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d31"></td>
                <td>Escasos</td>
                <td class="bg-red text-center text-white">Piocitos</td>
                <td colspan="2"><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d32"></td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Fosfato de calcio</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d33"></td>
                <td>Escasos</td>
                <td class="bg-red text-center text-white">Levaduras</td>
                <td colspan="2"><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d34"></td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">Acido Urico</td>
                <td><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d35"></td>
                <td>Escasos</td>
                <td class="bg-red text-center text-white">Esporas micoticas</td>
                <td colspan="2"><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d36"></td>
            </tr>
            <tr>
                <td class="bg-red text-center text-white">OBSERVACIONES</td>
                <td colspan="5"><input placeholder="00" type="text"  style="width: 100%" v-model="orina.d37"></td>
            </tr>
            <tr>
                <td rowspan="4">RESPONSABLE</td>
                <td colspan="2" rowspan="4"></td>
            </tr>
            <tr>
                <td colspan="2">FECHA DE TOMA DE MUESTRAS</td>
                <td><input type="date"  style="width: 100%" v-model="orina.fechatoma"></td>
            </tr>
            <tr>
                <td colspan="2">FECHA DE ENTREGA DE MUESTRAS</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">HORA TOMA DE MUESTRA</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="6">
                    <button class="btn btn-success btn-block"><i class="fa fa-save"></i> REGISTRAR</button>
                </td>
            </tr>
        </table>
    </q-form>


        </q-tab-panel>

                <q-tab-panel name="uretral">
          <div class="text-h6">ANALISIS DE SECRECION URETRAL</div>
            <q-form @submit="onUretral">
            <table style="width: 100%;color: black">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="../assets/natividad.png" alt="Logo Clinica" srcset="" style="height: 4cm; width:8cm;"></td>
                <td style="color: blue; text-align:center; height:0.5cm;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Telf: 5254721 Fax: 52-83667 </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Emergencia las 24 horas del dia. </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Bolivar Nº 753 entre Arica e Iquique </td>                
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center"><h3>ANALISIS DE SECRECION URETRAL</h3></td>
                <td>Form. 004</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}}</td>
                <td style="color: darkblue">EDAD</td>
                <td></td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td><input type="text" style="width: 100%" v-model="uretral.tipomuestra" placeholder="Tipo muestra" ></td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>
 
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="2" style="text-align: center ">EXAMEN EN FRESCO</td>
            </tr>
            <tr>
                <td class="text-left text-red">CELULAS EPITELIALES</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d1"></td>
            </tr>
            <tr>
                <td class="text-left text-red">LEUCOCITOS</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d2"></td>
            </tr>
            <tr>
                <td class="text-left text-red">HEMATIES</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d3"></td>
            </tr>
            <tr>
                <td class="text-left text-red">CELULAS CLAVE</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d4"></td>
            </tr>
            <tr>
                <td class="text-left text-red">LEVADURAS</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d5"></td>
            </tr>
            <tr>
                <td class="text-left text-red">PARASITOS</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d6"></td>
            </tr>
            <tr>
                <td class="text-left text-red">BACTERIAS</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d7"></td>
            </tr>
            <tr>
                <td class="text-left text-red">KOH</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d8"></td>
            </tr>
            <tr>
                <td class="text-left text-red">PH</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d9"></td>
            </tr>
            <tr>
                <td class="text-left text-red">OBSERVACIONES</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d10"></td>
            </tr>

            <tr colspan="2">
                <td colspan="2" class="text-center">TINCION DE GRAM</td>
            </tr>
            <tr>
                <td class="text-left text-red">BACILOS GRAM POSITIVO</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d11"></td>
            </tr>
            <tr>
                <td class="text-left text-red">BACILOS GRAM NEGATIVO</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d12"></td>
            </tr>
            <tr>
                <td class="text-left text-red">COCOS GRAM POSITIVO</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d13"></td>
            </tr>
            <tr>
                <td class="text-left text-red">COCOBACILOS GRAM POSITIVO</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d14"></td>
            </tr>
            <tr>
                <td class="text-left text-red">COCOBACILOS GRAM NEGATIVO</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d15"></td>
            </tr>
            <tr>
                <td class="text-left text-red">ESPORAS E HIFAS MICOTICAS</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d16"></td>
            </tr>
            <tr>
                <td class="text-left text-red">OBSERVACIONES</td>
                <td ><input type="text"  style="width: 100%" v-model="uretral.d17"></td>
            </tr>


            <tr>
                <td rowspan="2" >RESPONSABLE: </td>
                <td>
                    FECHA DE TOMA DE MUESTRAS
                    <input placeholder="00" type="date"  style="width: 100%" v-model="uretral.fechatoma">
                </td>
            </tr>
            <tr>
                <td>
                    FECHA DE ENTREGA DE MUESTRAS
                    <input placeholder="00" type="date"  style="width: 100%" name="fechaentrega">
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <button class="btn btn-success btn-block"><i class="fa fa-save"></i> REGISTRAR</button>
                </td>
            </tr>
        </table>

    </q-form>          
        </q-tab-panel>


                <q-tab-panel name="vaginal">
          <div class="text-h6">ANALISIS DE SECRECION VAGINAL</div>
              <q-form @submit="onVaginal">
            <table style="width: 100%;color: black">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="../assets/natividad.png" alt="Logo Clinica" srcset="" style="height: 4cm; width:8cm;"></td>
                <td style="color: blue; text-align:center; height:0.5cm;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Telf: 5254721 Fax: 52-83667 </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Emergencia las 24 horas del dia. </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Bolivar Nº 753 entre Arica e Iquique </td>                
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center"><h3>ANALISIS DE SECRECION VAGINAL</h3></td>
                <td>Form. 004</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}}</td>
                <td style="color: darkblue">EDAD</td>
                <td></td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td><input type="text" style="width: 100%" v-model="vaginal.tipomuestra" placeholder="Tipo muestra" ></td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>

        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="2" style="text-align: center ">EXAMEN EN FRESCO</td>
            </tr>
            <tr>
                <td class="text-left text-red">CELULAS EPITELIALES</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d1"></td>
            </tr>
            <tr>
                <td class="text-left text-red">LEUCOCITOS</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d2"></td>
            </tr>
            <tr>
                <td class="text-left text-red">HEMATIES</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d3"></td>
            </tr>
            <tr>
                <td class="text-left text-red">CELULAS CLAVE</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d4"></td>
            </tr>
            <tr>
                <td class="text-left text-red">LEVADURAS</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d5"></td>
            </tr>
            <tr>
                <td class="text-left text-red">PARASITOS</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d6"></td>
            </tr>
            <tr>
                <td class="text-left text-red">BACTERIAS</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d7"></td>
            </tr>
            <tr>
                <td class="text-left text-red">KOH</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d8"></td>
            </tr>
            <tr>
                <td class="text-left text-red">PH</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d9"></td>
            </tr>
            <tr>
                <td class="text-left text-red">OBSERVACIONES</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d10"></td>
            </tr>

            <tr colspan="2">
                <td colspan="2" class="text-center">TINCION DE GRAM</td>
            </tr>
            <tr>
                <td class="text-left text-red">BACILOS GRAM POSITIVO</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d11"></td>
            </tr>
            <tr>
                <td class="text-left text-red">BACILOS GRAM NEGATIVO</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d12"></td>
            </tr>
            <tr>
                <td class="text-left text-red">COCOS GRAM POSITIVO</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d13"></td>
            </tr>
            <tr>
                <td class="text-left text-red">COCOBACILOS GRAM POSITIVO</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d14"></td>
            </tr>
            <tr>
                <td class="text-left text-red">COCOBACILOS GRAM NEGATIVO</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d15"></td>
            </tr>
            <tr>
                <td class="text-left text-red">ESPORAS E HIFAS MICOTICAS</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d16"></td>
            </tr>
            <tr>
                <td class="text-left text-red">OBSERVACIONES</td>
                <td ><input type="text"  style="width: 100%" v-model="vaginal.d17"></td>
            </tr>

            <tr>
                <td rowspan="2" >RESPONSABLE: </td>
                <td>
                    FECHA DE TOMA DE MUESTRAS
                    <input placeholder="00" type="date"  style="width: 100%" v-model="vaginal.fechatoma">
                </td>
            </tr>
            <tr>
                <td>
                    FECHA DE ENTREGA DE MUESTRAS
                    <input placeholder="00" type="date"  style="width: 100%" name="fechaentrega">
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <button class="btn btn-success btn-block"><i class="fa fa-save"></i> REGISTRAR</button>
                </td>
            </tr>
        </table>

    </q-form>          
        </q-tab-panel>


        <q-tab-panel name="hece">
          <div class="text-h6">ANALISIS DE HECES</div>
        <q-form @submit="onHece">
        <table style="width: 100%;color: black">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="../assets/natividad.png" alt="Logo Clinica" srcset="" style="height: 4cm; width:8cm;"></td>
                <td style="color: blue; text-align:center; height:0.5cm;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Telf: 5254721 Fax: 52-83667 </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Emergencia las 24 horas del dia. </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Bolivar Nº 753 entre Arica e Iquique </td>                
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center"><h3>ANALISIS DE HECES</h3></td>
                <td>Form. 010</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}}</td>
                <td style="color: darkblue">EDAD</td>
                <td></td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td><input type="text" style="width: 100%" v-model="hece.tipomuestra" placeholder="Tipo muestra" ></td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>

        </table>
        <table border="1" style="width: 100%;color: black">
            <tr >
                <td colspan="2" style="text-align: center ">EXAMEN EN FRESCO</td>
            </tr>
            <tr>
                <td class="text-left text-red">ASPECTO DE LA MUESTRA</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d1"></td>
            </tr>
            <tr>
                <td class="text-left text-red">COLOR</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d2"></td>
            </tr>
            <tr>
                <td class="text-left text-red">CELULAS EPITELIALES</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d3"></td>
            </tr>
            <tr>
                <td class="text-left text-red">LEUCOCITOS</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d4"></td>
            </tr>
            <tr>
                <td class="text-left text-red">HEMATIES</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d5"></td>
            </tr>
            <tr>
                <td class="text-left text-red">ALMIDON</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d6"></td>
            </tr>
            <tr>
                <td class="text-left text-red">LEVADURAS</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d7"></td>
            </tr>
            <tr>
                <td class="text-left text-red">GRASAS</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d8"></td>
            </tr>
            <tr>
                <td class="text-left text-red">PARASITOS</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d9"></td>
            </tr>

            <tr>
                <td rowspan="2" class="text-left text-red">MOCO FECAL
                 <input type="text"  style="width: 100%"  v-model="hece.d10">               </td>
                <td> Polimorfonucleares<input type="text" style="width: 100%"   v-model="hece.d11"></td>

            </tr>
            <tr>
                <td>Mononucleares<input type="text" style="width: 100%"  v-model="hece.d12"></td>
                
            </tr>
            <tr>
                <td class="text-left text-red">OTROS</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d13"></td>
            </tr>

            <tr colspan="2">
                <td colspan="2" class="text-center">TINCION DE GRAM</td>
            </tr>
            <tr>
                <td class="text-left text-red">BACILOS GRAM POSITIVOS</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d14"></td>
            </tr>
            <tr>
                <td class="text-left text-red">BACILOS GRAM NEGATIVOS</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d15"></td>
            </tr>
            <tr>
                <td class="text-left text-red">COCOS GRAM POSITIVOS</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d16"></td>
            </tr>
            <tr>
                <td class="text-left text-red">COCOS GRAM NEGATIVOS</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d17"></td>
            </tr>
            <tr>
                <td class="text-left text-red">COCOBACILOS GRAM</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d18"></td>
            </tr>
            <tr>
                <td class="text-left text-red">ESPORAS MICOTICAS</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d19"></td>
            </tr>
  
            <tr>
                <td class="text-left text-red">OTROS</td>
                <td ><input type="text"  style="width: 100%" v-model="hece.d20"></td>
            </tr>

            <tr>
                <td rowspan="2" >RESPONSABLE: </td>
                <td>
                    FECHA DE TOMA DE MUESTRAS
                    <input placeholder="00" type="date"  style="width: 100%" v-model="hece.fechatoma">
                </td>
            </tr>
            <tr>
                <td>
                    FECHA DE ENTREGA DE MUESTRAS
                    <input placeholder="00" type="date" style="width: 100%" name="fechaentrega">
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <button class="btn btn-success btn-block"><i class="fa fa-save"></i> REGISTRAR</button>
                </td>
            </tr>
        </table>

    </q-form>          
        </q-tab-panel>


          <q-tab-panel name="simple">
          <div class="text-h6">COPRAPARASITOLOGICO SIMPLE</div>
        <q-form @submit="onSimple">
        <table style="width: 100%;color: black">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="../assets/natividad.png" alt="Logo Clinica" srcset="" style="height: 4cm; width:8cm;"></td>
                <td style="color: blue; text-align:center; height:0.5cm;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Telf: 5254721 Fax: 52-83667 </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Emergencia las 24 horas del dia. </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Bolivar Nº 753 entre Arica e Iquique </td>                
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center"><h3>COPRAPARASITOLOGICO SIMPLE</h3></td>
                <td>Form. 009</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}}</td>
                <td style="color: darkblue">EDAD</td>
                <td></td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td><input type="text" style="width: 100%" v-model="simple.tipomuestra" placeholder="Tipo muestra" ></td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.sexo}}</td>
            </tr>

        </table>
            <br>
        <table border="1" style="width: 100%;color: black">
            <tr >
                <td colspan="2" style="text-align: center ">COPRAPARASITOLOGICO SIMPLE</td>
            </tr>
            <tr>
                <td class="text-left text-red">ASPECTO DE LA MUESTRA</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d1"></td>
            </tr>
            <tr>
                <td class="text-left text-red">COLOR</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d2"></td>
            </tr>
            <tr>
                <td class="text-left text-red">CELULAS EPITELIALES</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d3"></td>
            </tr>
            <tr>
                <td class="text-left text-red">LEUCOCITOS</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d4"></td>
            </tr>
            <tr>
                <td class="text-left text-red">HEMATIES</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d5"></td>
            </tr>
            <tr>
                <td class="text-left text-red">GRASAS</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d6"></td>
            </tr>
            <tr>
                <td class="text-left text-red">LEVADURAS</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d7"></td>
            </tr>
            <tr>
                <td class="text-left text-red">ESPORAS MICOTICAS</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d8"></td>
            </tr>
            <tr>
                <td class="text-left text-red">ALMIDON</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d9"></td>
            </tr>
            <tr>
                <td class="text-left text-red">PARASITOS</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d10"></td>
            </tr>
            <tr>
                <td class="text-left text-red">PIOCITOS</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d11"></td>
            </tr>

            <tr>
                <td rowspan="2" class="text-left text-red">MOCO FECAL
                 <input type="text"  style="width: 100%"  v-model="simple.d12">               </td>
                <td> Polimorfonucleares<input type="text" style="width: 100%"   v-model="simple.d13"></td>

            </tr>
            <tr>
                <td>Mononucleares<input type="text" style="width: 100%"  v-model="simple.d14"></td>
                
            </tr>
            <tr>
                <td class="text-left text-red">OBSERVACIONES</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d15"></td>
            </tr>

            <tr colspan="2">
                <td colspan="2" class="text-center">OTROS</td>
            </tr>
            <tr>
                <td class="text-left text-red">SANGRE OCULTA EN HECES</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d16"></td>
            </tr>
            <tr>
                <td class="text-left text-red">TEST DE BENEDICT</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d17"></td>
            </tr>  
  
            <tr>
                <td class="text-left text-red">OBSERVACIONES</td>
                <td ><input type="text"  style="width: 100%" v-model="simple.d18"></td>
            </tr>

            <tr>
                <td rowspan="2" >RESPONSABLE: </td>
                <td>
                    FECHA DE TOMA DE MUESTRAS
                    <input placeholder="00" type="date"  style="width: 100%" v-model="simple.fechatoma">
                </td>
            </tr>
            <tr>
                <td>
                    FECHA DE ENTREGA DE MUESTRAS
                    <input placeholder="00" type="date"  style="width: 100%" name="fechaentrega">
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <button class="btn btn-success btn-block"><i class="fa fa-save"></i> REGISTRAR</button>
                </td>
            </tr>
        </table>

    </q-form>          
          </q-tab-panel>


          <q-tab-panel name="seriado">
          <div class="text-h6">COPROPARASITOLOGICO SERIADO</div>
             <q-form @submit="onSeriado">
        <table style="width: 100%;color: black">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="../assets/natividad.png" alt="Logo Clinica" srcset="" style="height: 4cm; width:8cm;"></td>
                <td style="color: blue; text-align:center; height:0.5cm;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Telf: 5254721 Fax: 52-83667 </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Emergencia las 24 horas del dia. </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Bolivar Nº 753 entre Arica e Iquique </td>                
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center"><h3>COPROPARASITOLOGICO SERIADO</h3></td>
                <td>Form. 008</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}}</td>
                <td style="color: darkblue">EDAD</td>
                <td></td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td><input type="text" style="width: 100%" v-model="seriado.tipomuestra" placeholder="Tipo muestra" ></td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>

        </table>
        <br>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center ">COPROPARASITOLOGICO SERIADO</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center text-red" style="width: 20%">1 MUESTRA</td>
                <td ><input  type="text"  style="width: 100%" v-model="seriado.muestra1"></td>
            </tr>
            <tr>
                <td class="text-left text-red">Fecha</td>
                <td ><input type="date"  style="width: 100%" v-model="seriado.fecha1"></td>
            </tr>
            <tr>
                <td class="text-left text-red">Hora</td>
                <td ><input type="time"  style="width: 100%" v-model="seriado.hora1"></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center text-red" style="width: 20%">2 MUESTRA</td>
                <td ><input type="text"  style="width: 100%" v-model="seriado.muestra2"></td>
            </tr>
            <tr>
                <td class="text-left text-red">Fecha</td>
                <td ><input type="date"  style="width: 100%" v-model="seriado.fecha2"></td>
            </tr>
            <tr>
                <td class="text-left text-red">Hora</td>
                <td ><input type="time"  style="width: 100%" v-model="seriado.hora2"></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center text-red" style="width: 20%">3 MUESTRA</td>
                <td ><input type="text"  style="width: 100%" v-model="seriado.muestra3"></td>
            </tr>
            <tr>
                <td class="text-left text-red">Fecha</td>
                <td ><input type="date"  style="width: 100%" v-model="seriado.fecha3"></td>
            </tr>
            <tr>
                <td class="text-left text-red">Hora</td>
                <td ><input type="time"  style="width: 100%" v-model="seriado.hora3"></td>
            </tr>
            <tr>
                <td colspan="2" class="text-left text-red">OBSERVACIONES</td>
                <td ><input type="text"  style="width: 100%" v-model="seriado.observaciones"></td>
            </tr>

            <tr >
                <td colspan="2" >RESPONSABLE: </td>
                <td>
                    FECHA DE ENTREGA RESULTADOS
                    <input type="date"  style="width: 100%">
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <button class="btn btn-success btn-block"><i class="fa fa-save"></i> REGISTRAR</button>
                </td>
            </tr>
        </table>

    </q-form>
          </q-tab-panel>


          <q-tab-panel name="serologia">
          <div class="text-h6">SEROLOGIA</div>
              <q-form @submit="onSerologia">
        <table style="width: 100%;color: black">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="../assets/natividad.png" alt="Logo Clinica" srcset="" style="height: 4cm; width:8cm;"></td>
                <td style="color: blue; text-align:center; height:0.5cm;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Telf: 5254721 Fax: 52-83667 </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Emergencia las 24 horas del dia. </td>                
            </tr>
            <tr>
                <td style="color: blue; text-align:center; height:0.5cm;">Bolivar Nº 753 entre Arica e Iquique </td>                
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center"><h3>SEROLOGIA</h3></td>
                <td>Form. 005</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}}</td>
                <td style="color: darkblue">EDAD</td>
                <td></td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td><input type="text" style="width: 100%" v-model="serologia.tipomuestra" placeholder="Tipo muestra" ></td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>

        </table>
        <br>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="4" style="text-align: center ">PRUEBA ANTICUERPOS CUANTITATIVOS ANTI SARS COV-2 lg M / lg G</td>
            </tr>
            <tr>
                <td class="text-center text-red" style="width: 20%">lgM</td>
                <td ><input  type="number" step="0.1" style="width: 100%" v-model="serologia.lgm" id="lgm" min="0.0"></td>
                <td ><input  type="text"  style="width: 100%" v-model="serologia.d1" id="d1"></td>
                <td> menor a 0.9 NEGATIVO PARA lgG/lgM</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>0.9 menor igual  & menor 1.1 INDETERMINADO </td>
            </tr>
            <tr>
                <td class="text-center text-red" style="width: 20%">lgG</td>
                <td ><input  type="number" step="0.1"  style="width: 100%" v-model="serologia.lgg" id="lgg" min="0.0"></td>
                <td ><input  type="text"  style="width: 100%" v-model="serologia.d2" id="d2"></td>
                <td style="width: 20%"> >= 1.1 POSITIVO lgG/lgM</td>
            </tr>
            <tr>
                <td colspan="4" style="color:red">INTERPRETACION DE RESULTADOS</td>
            </tr>
            <tr style="text-align:center; color:red">
                <td>lg M</td>
                <td>lg G</td>
                <td>INTERPRETACION</td>
                <td>COMENTARION</td>
            </tr>
            <tr style="text-align:center">
                <td><img src="../assets/resta.png" alt="negativo" srcset="" style="height: 1.5cm; width:1.5cm;"></td>
                <td><img src="../assets/resta.png" alt="negativo" srcset="" style="height: 1.5cm; width:1.5cm;"></td>
                <td>Ausencia de la Enfermedad o Falso Negativo</td>
                <td>Paciente en fase de evolucion de la enfermedad mayor de 21 dias</td>
            </tr>
            <tr style="text-align:center">
                <td><img src="../assets/suma.png" alt="positivo" srcset="" style="height: 1.5cm; width:1.5cm;"></td>
                <td><img src="../assets/resta.png" alt="negativo" srcset="" style="height: 1.5cm; width:1.5cm;"></td>
                <td>Inicio Temprano de ka Enfermedad Infeccion Aguda de la Enfermedad</td>
                <td>Se debe repetir dentro de 5 a 7 dias la prueba</td>
            </tr>
            <tr style="text-align:center">
                <td><img src="../assets/suma.png" alt="positivo" srcset="" style="height: 1.5cm; width:1.5cm;"></td>
                <td><img src="../assets/suma.png" alt="positivo" srcset="" style="height: 1.5cm; width:1.5cm;"></td>
                <td>Fase activa de la Enfermedad</td>
                <td>Correlacionar con clinica del paciente y realizar examenes complementarios (Rayos X, Tomografia)</td>
            </tr>
            <tr style="text-align:center">
                <td><img src="../assets/resta.png" alt="negativo" srcset="" style="height: 1.5cm; width:1.5cm;"></td>
                <td><img src="../assets/suma.png" alt="positivo" srcset="" style="height: 1.5cm; width:1.5cm;"></td>
                <td>Inmunidad Fase final de la Infeccion Infeccion pasada y curada</td>
                <td>Paciente en fase de evolucion de la enfermedad mayor a 21 dias</td>
            </tr>
            <tr>
                <td colspan="4">
                    <span style="color:red">NOTA: </span>
                    Las pruebas rapidas para COVID-19 NO SON CONFIRMATORIAS, en caso de salir positivo alguno de los anticuerpo
                    , se recimienda una segunda toma de muestra con la Tecnica de HISOPADO NASOFARINGEO para RT - PCR y asi confirmar su DIAGNOSTICO
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    Todas las personas producimos anticuerpos a diferente velocidad dependiendo del agente patogeno y nuestra genetica que es lo que
                    determina la funcionalidad de nuestro Sistema Inmunologico
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    Los Resultados deben ser interpretados en funcion de la Clinica del paciente y dias de evolucion de la enfermedad
                </td>
            </tr>
            <tr>
                <td class="text-left text-red">OBSERVACION</td>
                <td colspan="3" ><input type="text"  style="width: 100%" v-model="serologia.d3"></td>
            </tr>

            <tr >
                <td colspan="2" rowspan="2" >RESPONSABLE: </td>
                <td colspan="2">
                    FECHA DE TOMA DE MUESTRAS
                    <input placeholder="00" type="date"  style="width: 100%" v-model="serologia.fechatoma">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    FECHA DE ENTREGA DE MUESTRAS
                    <input placeholder="00" type="date" style="width: 100%" >
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <button class="btn btn-success btn-block"><i class="fa fa-save"></i> REGISTRAR</button>
                </td>
            </tr>
        </table>
    </q-form>
          </q-tab-panel>

          
          <q-tab-panel name="labserologia">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
          </q-tab-panel>

          <q-tab-panel name="reserologia">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
          </q-tab-panel>
          <q-tab-panel name="ensayo">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
          </q-tab-panel>

      </q-tab-panels>
        </q-card-section>
                      <q-card-actions align="right" class="text-primary">
          <q-btn flat label="Cancelar" v-close-popup />
        </q-card-actions>
      </q-card>

    </q-dialog>

    </q-card>
  </q-page>
</template>

<script>
import { useQuasar } from 'quasar'
import {date} from "quasar";
export default {
  data(){
    return{
      filter:'',
      $q : useQuasar(),
      pacientes:[],
      rows:[],
      dato:{},
      doctors:[],
      alert:false,
      dialog_mod:false,
      dialog_lab:false,
      formulario:[
         {value:"hemograma" ,label:"Hemograma completo" },
         {value:"sanguinia"        ,label:"Quimica sanguinia" },
         {value:"orina"        ,label:"Examen general de orina" },
         {value:"uretral"        ,label:"Analisis de secrecion uretral" },
         {value:"vaginal"        ,label:"Analisis de secrecion Vaginal" },
         {value:"hece"        ,label:"Analisis de Heces" },
         {value:"simple"        ,label:"Copraparasitologico Simple" },
         {value:"seriado"        ,label:"Copraparasitologico Seriado" },
         {value:"serologia"        ,label:"Serologia" },
         {value:"labserologia"        ,label:"Laboratorio Serologia" },
         {value:"reserologia"        ,label:"Resultado Serologia" },
         {value:"ensayo"        ,label:"Inmunoensayo de Fluorescencia" }
      ],
      hemograma:{},
      orina:{},
      sanguinia:{},
      uretral:{},
      vaginal:{},
      hece:{},
      simple:{},
      seriado:{},
      serologia:{},
      labserologia:{},
      reserologoia:{},
      requerido:{},
       actual:date.formatDate(Date.now(),'YYYY-MM-DD'),
      ensayo:{},
      tab:{},
      columns:[
        { name: 'ci', label: 'ci', field: 'ci',align: 'center',},
        { name: 'nombre', label: 'nombre', field: 'nombre',align: 'center',},
        { name: 'paterno', label: 'paterno', field: 'paterno',align: 'center',},
        { name: 'materno', label: 'materno', field: 'materno',align: 'center',},
        { name: 'fechanac', label: 'fechanac', field: 'fechanac', align: 'center',},
        { name: 'sexo', label: 'sexo', field: 'sexo',align: 'center',},
        { name: 'celular', label: 'celular', field: 'celular',align: 'center',},
        { name: 'opcion', label: 'opcion', field: 'opcion',align: 'center',},
      ]
    }
  },
  created(){
    this.listado();
    this.listdoctor();
    this.tab={value:"hemograma",label:"Hemograma completo" };
  },
  methods: {
    listdoctor(){
        this.doctors=[];
          this.$axios.get(process.env.API+'/doctor').then(res=>{

       console.log(res.data)

         res.data.forEach(element => {
            this.doctors.push({label:element.nombre,value:element.id});

         });
         this.requerido=this.doctors[0];
          })
    },
    listado(){
          this.$axios.get(process.env.API+'/paciente').then(res=>{
       console.log(res.data)
       this.pacientes=res.data
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
        title: 'Eliminar Paciente',
        message: 'Esta Seguro de Eliminar ?',
        cancel: true,
      }).onOk(() => {
          this.$axios.delete(process.env.API+'/paciente/'+this.dato2.id).then(res=>{
         this.$q.notify({
          message: 'Se elimino correctamente',
          color: 'green'
        })
        this.listado();
          })
      })
    },
    onSubmit(){
      this.$q.loading.show()
      this.$axios.post(process.env.API+'/paciente',this.dato).then(res=>{
        this.dato={}
         this.$q.notify({
          message: 'Se registro correctamente',
          color: 'green'
        })
        this.$q.loading.hide()
        this.alert=false;
        this.listado();
      }).catch(err=>{
        this.$q.loading.hide()
        this.$q.notify({
          message:err.response.data.message,
          color:'red',
          icon:'error'
        })
      })
    },
    onHemograma(){
        this.$axios.post(process.env.API+'/hemograma',{paciente:this.dato2,doctor:this.requerido.value,hemograma:this.hemograma}).then(res=>{
          console.log(res.data)
         this.dialog_lab=false;
                 $q.notify({
          message: 'Registro Realizado',
          icon: 'beaker'
        })

        })
    },
    onSanguinia(){
        this.$axios.post(process.env.API+'/sanguinia',{paciente:this.dato2,doctor:this.requerido.value,sanguinia:this.sanguinia}).then(res=>{
          console.log(res.data)
        this.dialog_lab=false;
         this.$q.notify({
          message: 'Registro Realizado',
          icon: 'beaker'
        })
        })               
    },
      onOrina(){
        this.$axios.post(process.env.API+'/orina',{paciente:this.dato2,doctor:this.requerido.value,orina:this.orina}).then(res=>{
          console.log(res.data)
        this.dialog_lab=false;
        this.$q.notify({
          message: 'Registro Realizado',
          icon: 'beaker'
        })
        })                
    },
          onUretral(){
        this.$axios.post(process.env.API+'/uretral',{paciente:this.dato2,doctor:this.requerido.value,uretral:this.uretral}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
          this.$q.notify({
          message: 'Registro Realizado',
          icon: 'beaker'
        })
        })                
    },
    onVaginal(){
        this.$axios.post(process.env.API+'/vaginal',{paciente:this.dato2,doctor:this.requerido.value,vaginal:this.vaginal}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
          this.$q.notify({
          message: 'Registro Realizado',
          icon: 'beaker'
        })
        })                
    },
    onHece(){
        this.$axios.post(process.env.API+'/hece',{paciente:this.dato2,doctor:this.requerido.value,hece:this.hece}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
          this.$q.notify({
          message: 'Registro Realizado',
          icon: 'beaker'
        })
        })                
    },
    onSimple(){
        this.$axios.post(process.env.API+'/simple',{paciente:this.dato2,doctor:this.requerido.value,simple:this.simple}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
          this.$q.notify({
          message: 'Registro Realizado',
          icon: 'beaker'
        })
        })                
    },
        onSeriado(){
        this.$axios.post(process.env.API+'/seriado',{paciente:this.dato2,doctor:this.requerido.value,seriado:this.seriado}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
          this.$q.notify({
          message: 'Registro Realizado',
          icon: 'beaker'
        })
        })                
    },
    onSerologia(){
        this.$axios.post(process.env.API+'/serologia',{paciente:this.dato2,doctor:this.requerido.value,serologia:this.serologia}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
          this.$q.notify({
          message: 'Registro Realizado',
          icon: 'beaker'
        })
        })                
    },
    labRow(props){
      this.dato2=props.row;
      this.dialog_lab=true;
    },
    onMod(){
      console.log(this.dato2)
      this.$axios.put(process.env.API+'/paciente/'+this.dato2.id,this.dato2).then(res=>{
         thisthis.$q.notify({
          message: 'Se modifico correctamente',
          color: 'green'
        })
      })
      this.dialog_mod=false;
      this.listado();
    }
  },
}
</script>
<style>
table, td, th {
  border-collapse: collapse;
}
</style>
