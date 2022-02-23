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
            @submit.prevent="onSubmit"
            class="q-gutter-md"
          >
            <q-input
              filled
              v-model="dato.ci"
              label="Cedula Identidad"
              hint="Numero de carnet"

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

            />
            <q-select  outlined v-model="seguro" :options="seguros" label="Seguro" />
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
            <q-btn  dense round flat color="teal" @click="listRow(props)" icon="list"></q-btn>
              <q-btn  dense round flat color="yellow" @click="editRow(props)" icon="edit"></q-btn>
              <q-btn  dense round flat color="red" @click="deleteRow(props)" icon="delete"></q-btn>
          </q-td>
        </q-tr>
      </template>
      </q-table>

      <q-dialog v-model="dialog_mod">
      <q-card>
        <q-card-section class="bg-success-14 text-white">
          <div class="text-h6">Modificar Datos</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <q-form
            @submit.prevent="onMod"
            class="q-gutter-md"
          >
            <q-input
              filled
              v-model="dato2.ci"
              label="Cedula Identidad"
              hint="Numero de carnet"

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

            />
            <q-select  outlined v-model="seguro" :options="seguros" label="Seguro" />
            <div>
              <q-btn label="Modificar" type="submit" color="positive" icon="add_circle"/>
                <q-btn  label="Cancelar" icon="delete" color="negative" v-close-popup />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

          <q-dialog v-model="dialog_hist">
      <q-card>
        <q-card-section class="bg-success-14 text-white">
          <div class="text-h6">Historial Formularios</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
              <q-table
                title="Historial Formularios"
                :columns="columns2"
                 :rows="historial"
                >

              <template v-slot:body-cell-opcion="props">
        <q-tr :props="props">
          <q-td key="opcion" :props="props">
              <q-btn  dense round flat color="teal" @click="impRow(props)" icon="print"></q-btn>
              <q-btn  dense round flat color="red" @click="eliminar(props)" icon="delete"></q-btn>
              <q-btn  dense round flat color="green" @click="whppRow(props)" icon="whatsapp" v-if="dato2.celular"></q-btn>
              <q-btn  dense round flat color="blue" @click="whpdRow(props)" icon="whatsapp" v-if="props.row.dcelular"></q-btn>
          </q-td>
        </q-tr>
      </template>
      </q-table>
          </q-card-section>
      </q-card>
    </q-dialog>

     <q-dialog v-model="dialog_lab" maximized>
      <q-card>

        <q-card-section >
         <q-select v-model="tab" :options="formulario"  label="FORMULARIOS" />

              <q-tab-panels v-model="tab.value" animated class="shadow-2 rounded-borders">
        <q-tab-panel name="hemograma">
            <q-form @submit.prevent="onHemograma" >

        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center">HEMOGRAMA COMPLETO</td>
                <td>Form. 001</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}} {{dato2.paterno}} {{dato2.materno}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.edad}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td>
                    <q-select  borderless  dense :options="doctors" v-model="requerido" style="width:100%;"/>
                </td>

                <td style="color: darkblue">SEXO </td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td>
                <q-input dense borderless type="text" style="width: 100%" placeholder="Tipo muestra" v-model="hemograma.tipomuestra" required list="ophemograma"/>
                <datalist id="ophemograma">
                    <option v-for="(film,index) in ophemograma" :key="index">{{film}}</option>
                </datalist>
                </td>
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
                <td class="text-red">Globulos Rojos</td>
                <td><input borderless type="text" placeholder="00" v-model="hemograma.d1"   style="width: 100%"/></td>
                <td>x10 <sup>12 </sup>/L</td>
                <td><small>Varon 5.1-5.7x10 <sup>12</sup>/L <br> Mujer 4.8-5.4x10 <sup>12</sup>/L</small></td>
                <td class="text-red">Tiempo de cuagulacion</td>
                <td><input borderless type="text" placeholder="00"  style="width: 100%" v-model="hemograma.d2"/></td>
                <td>5-10 min</td>
            </tr>
            <tr>
                <td class="text-red">Hemoglobina</td>
                <td><input borderless type="text" placeholder="00" name="d3"   style="width: 100%" v-model="hemograma.d3"/></td>
                <td>g/L</td>
                <td>Varon 151-175 g/L <br> Mujer Mujer 141-165 g/L</td>
                <td class="text-red">Tiempo de sangria</td>
                <td><input type="text" placeholder="00" name="d4"   style="width: 100%" v-model="hemograma.d4"/></td>
                <td>1-3 min</td>
            </tr>
            <tr>
                <td class="text-red">Hematocrito</td>
                <td><input type="text" placeholder="00" name="d5"   style="width: 100%" v-model="hemograma.d5"/></td>
                <td>L/L</td>
                <td>Varon 0.51-0.57 L/L <br> Mujer 0.46-0.53 L/L</td>
                <td class="text-red">Tiempo de Protrombina</td>
                <td><input type="text" placeholder="00" name="d6"   style="width: 100%" v-model="hemograma.d6"/></td>
                <td>12-13 seg</td>
            </tr>
            <tr>
                <td class="text-red">V.E.S.</td>
                <td><input type="text" placeholder="00" name="d7"   style="width: 100%" v-model="hemograma.d7"/></td>
                <td>mm.</td>
                <td>Varon 15 mm/hora <br> Mujer 20 mm/hora</td>
                <td class="text-red">% Actividad</td>
                <td><input type="text" placeholder="00" name="d8"   style="width: 100%" v-model="hemograma.d8"/></td>
                <td>95-100%</td>
            </tr>

            <tr>
                <td class="text-red">V.C.M.</td>
                <td><input type="text" placeholder="00" name="d9"   style="width: 100%" v-model="hemograma.d9"/></td>
                <td>ft.</td>
                <td>Varon 83.0-97.0 ft</td>
                <td class="text-red">INR</td>
                <td><input type="text" placeholder="00" name="d10"   style="width: 100%" v-model="hemograma.d10"/></td>
                <td>0.97-1.04</td>
            </tr>
            <tr>
                <td class="text-red">Hb.C.M.</td>
                <td><input type="text" placeholder="00" name="d11"   style="width: 100%" v-model="hemograma.d11"/></td>
                <td>pg.</td>
                <td>27.0-31.0 pg.</td>
                <td class="text-red">Grupofactor</td>
                <td colspan="2"><input type="text" placeholder="00" name="d12"   style="width: 100%" v-model="hemograma.d12"/></td>
            </tr>
            <tr>
                <td class="text-red">C. Hb.C.M.</td>
                <td><input type="text" placeholder="00" name="d13"   style="width: 100%" v-model="hemograma.d13"/></td>
                <td>%</td>
                <td>32-36%</td>
                <td class="text-red">Reticulocitos</td>
                <td><input type="text" placeholder="00" name="d14"   style="width: 100%" v-model="hemograma.d14"/></td>
                <td>0.5-2%</td>
            </tr>
            <tr>
                <td class="text-red">Globulos Blancos</td>
                <td><input type="text" placeholder="00" name="d15"   style="width: 100%" v-model="hemograma.d15"/></td>
                <td>10 <sup>9</sup>/L</td>
                <td> 4.5-10.5x10 <sup>9</sup>/L</td>
                <td class="text-red">IPR</td>
                <td><input type="text" placeholder="00" name="d16"   style="width: 100%" v-model="hemograma.d16"/></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-red">Plaquetas</td>
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
                <td class="text-red"></td>
                <td colspan="2">RELATIVA</td>
                <td colspan="2">ABSOLUTA</td>
                <td>RELATIVA</td>
                <td>ABSOLUTA</td>
            </tr>
            <tr>
                <td class="text-red">Cayados</td>
                <td><input type="text" placeholder="00" name="d18"   style="width: 100%" v-model="hemograma.d18"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d19"   style="width: 100%" v-model="hemograma.d19"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>0-3%</td>
                <td>0.00-0.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="text-red">Neutrofilos</td>
                <td><input type="text" placeholder="00" name="d20"   style="width: 100%" v-model="hemograma.d20"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d21"   style="width: 100%" v-model="hemograma.d21"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>50-70%</td>
                <td>2.50-7.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="text-red">Eosinofilos</td>
                <td><input type="text" placeholder="00" name="d22"   style="width: 100%" v-model="hemograma.d22"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d23"   style="width: 100%" v-model="hemograma.d23"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>0-3%</td>
                <td>0.00-0.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="text-red">Basofilos</td>
                <td><input type="text" placeholder="00" name="d24"   style="width: 100%" v-model="hemograma.d24"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d25"   style="width: 100%" v-model="hemograma.d25"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>0-1%</td>
                <td>0.00-0.15x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="text-red">Linfocitos</td>
                <td><input type="text" placeholder="00" name="d26"   style="width: 100%" v-model="hemograma.d26"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d27"   style="width: 100%" v-model="hemograma.d27"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>25-40%</td>
                <td>1.25-4.200x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="text-red">Monocitos</td>
                <td><input type="text" placeholder="00" name="d28"   style="width: 100%" v-model="hemograma.d28"></td>
                <td>%</td>
                <td><input type="text" placeholder="00" name="d29"   style="width: 100%" v-model="hemograma.d29"></td>
                <td>x10 <sup>9</sup>/L</td>
                <td>4-8%</td>
                <td>2.00-8.40x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="text-red">BLASTOS</td>
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
                <td class="text-red">Serie Rojas:</td>
                <td><input type="text" placeholder="00" name="d31"   style="width: 100%" v-model="hemograma.d31"></td>
            </tr>
            <tr>
                <td class="text-red">Serie Blancas:</td>
                <td><input type="text" placeholder="00" name="d32"   style="width: 100%" v-model="hemograma.d32"></td>
            </tr>
            <tr>
                <td class="text-red">Serie Plaquetarias:</td>
                <td><input type="text" placeholder="00" name="d33"   style="width: 100%" v-model="hemograma.d33"></td>
            </tr>
            <tr>
                <td class="text-red">FECHA DE TOMA DE MUESTRA:</td>
                <td><input type="date" name="fechatoma"   style="width: 100%" v-model="hemograma.fechatoma"></td>
            </tr>
            <tr>
                <td class="text-red">Responsable:</td>
                <td><q-select dense borderless  :options="usuarios" required v-model="hemograma.responsable" style="width:100%"/></td>
            </tr>
            <tr>
                <td class="text-red">HORA DE TOMA DE MUESTRA:</td>
                <td><input type="time" name="horatoma"   style="width: 100%" v-model="hemograma.horatoma"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <q-btn class="btn btn-success btn-block full-width" color="teal" type="submit"><i class="fa fa-save"></i> REGISTRAR</q-btn>
                </td>
            </tr>
        </table>
    </q-form>
        </q-tab-panel>


        <q-tab-panel name="sanguinia">
            <q-form @submit.prevent="onSanguinia" >

        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center">QUIMICA sanguinia</td>
                <td>Form. 002</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}} {{dato2.paterno}} {{dato2.materno}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.edad}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select dense borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td>
                <q-input borderless type="text" style="width: 100%" placeholder="Tipo muestra" v-model="sanguinia.tipomuestra" required list="opsanguinia"/>
                <datalist id="opsanguinia">
                    <option v-for="(film,index) in opsanguinia" :key="index">{{film}}</option>
                </datalist>
                </td>
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
                <td class="text-center text-red">Glicemia</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d1"   style="width: 100%"></td>
                <td>70-105mg/dl</td>
                <td>Glucosa Oxidasa</td>
                <td class="text-center text-red">Fosfatasa alcalina</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d2"   style="width: 100%"></td>
                <td>adultos hasta 100UI/L</td>
                <td>Cinetico</td>
            </tr>
            <tr>
                <td class="text-center text-red">Creatinina</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d3"   style="width: 100%"></td>
                <td>0.7-1.5mg/dl</td>
                <td>Picrato Alcalino</td>
                <td class="text-center text-red">Fosfatasa alcalina</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d4"   style="width: 100%"></td>
                <td>niños 100-400UI/L</td>
                <td>Cinetico</td>
            </tr>
            <tr>
                <td class="text-center text-red">Urea</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d5"   style="width: 100%"></td>
                <td>15-45mg/dl</td>
                <td>Enzimatico UV</td>
                <td class="text-center text-red">Transamisas GOT</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d6"   style="width: 100%"></td>
                <td>hasta 40UI/L</td>
                <td>Cinetico</td>
            </tr>
            <tr>
                <td class="text-center text-red">NUS-BUN</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d7"   style="width: 100%"></td>
                <td>7-18mg/dl</td>
                <td>Cinetico UV</td>
                <td class="text-center text-red">Transamisas GPT</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d8"   style="width: 100%"></td>
                <td>hasta 41UI/L</td>
                <td>Cinetico</td>
            </tr>
            <tr>
                <td class="text-center text-red">Acido Urico</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d9"   style="width: 100%"></td>
                <td>2.6-7.2mg/dl</td>
                <td>Uricasa/Peroxidasa</td>
                <td class="bg-purpal text-center text-white">LIPIDOGRAMA</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-center text-red">Proteinas Totales</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d10"   style="width: 100%"></td>
                <td>6.2-8.5g/dl</td>
                <td>Biuret</td>
                <td class="text-center text-red">Trigliceridos</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d11"   style="width: 100%"></td>
                <td>40-160mg/dl</td>
                <td>GPO-PAP</td>
            </tr>
            <tr>
                <td class="text-center text-red">Albumina</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d12"   style="width: 100%"></td>
                <td>3.5-5.3g/dl</td>
                <td>Verde Bromocresol</td>
                <td class="text-center text-red">Colesterol Total</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d13"   style="width: 100%"></td>
                <td>menor 200mg/dl</td>
                <td>CHOD-PAP</td>
            </tr>
            <tr>
                <td class="text-center text-red">Globulina</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d14"   style="width: 100%"></td>
                <td>2.8-3.5g/dl</td>
                <td></td>
                <td class="text-center text-red">HDL-Col.</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d15"   style="width: 100%"></td>
                <td>35-65mg/dl</td>
                <td>CHOD-PAP</td>
            </tr>
            <tr>
                <td class="text-center text-red">Amilasa</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d16"   style="width: 100%"></td>
                <td>menor a 120 UI/L</td>
                <td>Enzimatico a Amilasa</td>
                <td class="text-center text-red">LDL-Col.</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d17"   style="width: 100%"></td>
                <td>Hasta 150mg/dl</td>
                <td>CHOD-PAP</td>
            </tr>
            <tr>
                <td class="text-center text-red">Lipasa</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d18"   style="width: 100%"></td>
                <td>10-150UI/L</td>
                <td>Enzimatica Colorimetrica</td>
                <td class="bg-purpal text-center text-white">ELECTROLITOS</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-center text-red">Bilirrubina Total</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d19"   style="width: 100%"></td>
                <td>hasta 1.2 mg/dl</td>
                <td rowspan="3">Acido Sulfanilico con Diaazo</td>
                <td class="text-center text-red">Sodio</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d20"   style="width: 100%"></td>
                <td>135-155 mEq/L</td>
                <td rowspan="4">Automatizado CORNLEY AFT-500</td>
            </tr>
            <tr>
                <td class="text-center text-red">Bilirrubina Directa</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d21"   style="width: 100%"></td>
                <td>hasta 0.3 mg/dl</td>
                <td class="text-center text-red">Cloro</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d22"   style="width: 100%"></td>
                <td>98-106 mEq/L</td>
            </tr>
            <tr>
                <td class="text-center text-red">Bilirrubina Inderecta</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d23"   style="width: 100%"></td>
                <td>hasta 0.9 mg/dl</td>
                <td class="text-center text-red">Potasio</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d24"   style="width: 100%"></td>
                <td>3.4-5.3 mEq/L</td>
            </tr>
            <tr>
                <td class="text-center text-red">CK-MB</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d25"   style="width: 100%"></td>
                <td>0-24 UI/L</td>
                <td>Enzimatico </td>
                <td class="text-center text-red">Calcio</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d26"   style="width: 100%"></td>
                <td>8.5-10.5mg/dl</td>
            </tr>
            <tr>
                <td class="text-center text-red">LDH</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d27"   style="width: 100%"></td>
                <td>200-480UI/L</td>
                <td>Piruvato Lactato</td>
                <td class="text-center text-red">Magnesio</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d28"   style="width: 100%"></td>
                <td>1.7-2.4mg/dl</td>
                <td>Colorimetrico calmagita</td>
            </tr>
            <tr>
                <td class="text-center text-red">Hierro</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d29"   style="width: 100%"></td>
                <td>50-170ug/dl</td>
                <td>Goodwing Modificado</td>
                <td class="text-center text-red">Fosforo</td>
                <td><input placeholder="00" type="text" v-model="sanguinia.d30"   style="width: 100%"></td>
                <td>2.5-4.5mg/dl</td>
                <td>Fosfomolibdato UV</td>
            </tr>
            <tr>
                <td colspan="2" class=" text-center text-blue">OBSERVACIONES</td>
                <td colspan="6"><input  type="text" v-model="sanguinia.d31"   style="width: 100%" ></td>
            </tr>
            <tr>
                <td colspan="2" rowspan="3" class=" text-center text-blue">RESPONSABLE</td>
                <td colspan="2" rowspan="3"><q-select  borderless dense :options="usuarios" required v-model="sanguinia.responsable" style="width:100%"/></td>
                <td colspan="3" class=" text-center text-blue">FECHA TOMA DE MUESTRA</td>
                <td><input placeholder="00" type="date" v-model="sanguinia.fechatoma"   style="width: 100%" ></td>
            </tr>
            <tr>
                <td colspan="3" class=" text-center text-blue">HORA DE TOMA DE MUESTRA</td>
                <td><input placeholder="00" type="time"  style="width: 100%" v-model="sanguinia.horatoma"></td>
            </tr>
            <tr>
                <td colspan="3" class=" text-center text-blue">FECHA DE ENTREGA DE RESULTADO</td>
                <td><input placeholder="00" type="date"  style="width: 100%" ></td>
            </tr>
            <tr>
                <td colspan="8">
                    <q-btn type="submit" class="btn btn-success btn-block full-width" color="teal"><i class="fa fa-plus-circle"></i> GUARDAR</q-btn>
                </td>
            </tr>
        </table>
    </q-form>
        </q-tab-panel>


        <q-tab-panel name="orina">
            <q-form @submit.prevent="onOrina" >

        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center">EXAMEN GENERAL DE ORINA</td>
                <td>Form. 003</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}} {{dato2.paterno}} {{dato2.materno}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.edad}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select dense borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td>
                <q-input borderless type="text" style="width: 100%" placeholder="Tipo muestra" v-model="orina.tipomuestra" required list="oporina"/>
                <datalist id="oporina">
                    <option v-for="(film,index) in oporina" :key="index">{{film}}</option>
                </datalist>
                </td>

                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td class="text-center text-red">EX. FISICO</td>
                <td class="text-center text-red">VALOR</td>
                <td>REFERENCIA</td>
                <td>EX. QUIMICO</td>
                <td class="text-center text-red">VALOR</td>
                <td>REFERENCIA</td>
            </tr>
            <tr>
                <td class="text-center text-red">Color</td>
                <td><input placeholder="00" type="text"  v-model="orina.d1"></td>
                <td>Amarillo</td>
                <td class="text-center text-red">Proteinas</td>
                <td><input placeholder="00" type="text"  v-model="orina.d2"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="text-center text-red">Olor</td>
                <td><input placeholder="00" type="text"  v-model="orina.d3"></td>
                <td>Sui-generis</td>
                <td class="text-center text-red">Glucosa</td>
                <td><input placeholder="00" type="text"  v-model="orina.d4"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="text-center text-red">Aspecto</td>
                <td><input placeholder="00" type="text"  v-model="orina.d5"></td>
                <td>Limpido o lig opal</td>
                <td class="text-center text-red">C. cetonicos</td>
                <td><input placeholder="00" type="text"  v-model="orina.d6"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="text-center text-red">Espuma</td>
                <td><input placeholder="00" type="text"  v-model="orina.d7"></td>
                <td>Blanco fugaz</td>
                <td class="text-center text-red">Bilirrubina</td>
                <td><input placeholder="00" type="text"  v-model="orina.d8"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="text-center text-red">Deposito</td>
                <td><input placeholder="00" type="text"  v-model="orina.d9"></td>
                <td>Nulo o escacaso</td>
                <td class="text-center text-red">Hemoglobina</td>
                <td><input placeholder="00" type="text"  v-model="orina.d10"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="text-center text-red">Densidad</td>
                <td><input placeholder="00" type="text"  v-model="orina.d11"></td>
                <td>1.010-1.030</td>
                <td class="text-center text-red">Urobilina</td>
                <td><input placeholder="00" type="text"  v-model="orina.d12"></td>
                <td>Normal</td>
            </tr>
            <tr>
                <td class="text-center text-red">Reaccion</td>
                <td><input placeholder="00" type="text"  v-model="orina.d13"></td>
                <td>Lig. acida</td>
                <td class="text-center text-red">Nitrinos</td>
                <td><input placeholder="00" type="text"  v-model="orina.d14"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: center">SEDIMENTO: EXAMEN MICROSCOPICO</td>
            </tr>
            <tr>
                <td class="text-center text-red">CELULAS</td>
                <td class="text-center text-red">VALOR</td>
                <td>REFERENCIA</td>
                <td>CILINDROS</td>
                <td class="text-center text-red">VALOR</td>
                <td>REFERENCIA</td>
            </tr>
            <tr>
                <td class="text-center text-red">Celulas epiteliales</td>
                <td><input placeholder="00" type="text"  v-model="orina.d15"></td>
                <td>Hasta 2/c.</td>
                <td class="text-center text-red">Hialino</td>
                <td><input placeholder="00" type="text"  v-model="orina.d16"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="text-center text-red">Celulas de transicion</td>
                <td><input placeholder="00" type="text"  v-model="orina.d17"></td>
                <td>Negativo</td>
                <td class="text-center text-red">Granuloso</td>
                <td><input placeholder="00" type="text"  v-model="orina.d18"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="text-center text-red">Celulas clave</td>
                <td><input placeholder="00" type="text"  v-model="orina.d19"></td>
                <td>Negativo</td>
                <td class="text-center text-red">Epiteliales</td>
                <td><input placeholder="00" type="text"  v-model="orina.d20"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="text-center text-red">Leucocitos</td>
                <td><input placeholder="00" type="text"  v-model="orina.d21"></td>
                <td>Hasta 5/c</td>
                <td class="text-center text-red">Eritrocitario</td>
                <td><input placeholder="00" type="text"  v-model="orina.d22"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="text-center text-red">Eritrocitos</td>
                <td><input placeholder="00" type="text"  v-model="orina.d23"></td>
                <td>Hasta 3/c</td>
                <td class="text-center text-red">Leucositario</td>
                <td><input placeholder="00" type="text"  v-model="orina.d24"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="text-center text-red">Bacterias</td>
                <td><input placeholder="00" type="text"  v-model="orina.d25"></td>
                <td>Escaso</td>
                <td class="text-center text-red">Cereos</td>
                <td><input placeholder="00" type="text"  v-model="orina.d26"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="text-center text-red">CRISTALES</td>
                <td colspan="2"></td>
                <td class="text-center text-red">Mixtos</td>
                <td><input placeholder="00" type="text"  v-model="orina.d27"></td>
                <td>Negativo</td>
            </tr>
            <tr>
                <td class="text-center text-red">Uratos amorfos</td>
                <td><input placeholder="00" type="text"  v-model="orina.d28"></td>
                <td>Escasos</td>
                <td colspan="3">OTROS</td>
            </tr>
            <tr>
                <td class="text-center text-red">Fosfato amorfo</td>
                <td><input placeholder="00" type="text"  v-model="orina.d29"></td>
                <td>Escasos</td>
                <td class="text-center text-red">Filamento mucoso</td>
                <td colspan="2"><input placeholder="00" type="text"  v-model="orina.d30"></td>
            </tr>
            <tr>
                <td class="text-center text-red">Oxalato de calcio</td>
                <td><input placeholder="00" type="text"  v-model="orina.d31"></td>
                <td>Escasos</td>
                <td class="text-center text-red">Piocitos</td>
                <td colspan="2"><input placeholder="00" type="text"  v-model="orina.d32"></td>
            </tr>
            <tr>
                <td class="text-center text-red">Fosfato de calcio</td>
                <td><input placeholder="00" type="text"  v-model="orina.d33"></td>
                <td>Escasos</td>
                <td class="text-center text-red">Levaduras</td>
                <td colspan="2"><input placeholder="00" type="text"  v-model="orina.d34"></td>
            </tr>
            <tr>
                <td class="text-center text-red">Acido Urico</td>
                <td><input placeholder="00" type="text"  v-model="orina.d35"></td>
                <td>Escasos</td>
                <td class="text-center text-red">Esporas micoticas</td>
                <td colspan="2"><input placeholder="00" type="text"  v-model="orina.d36"></td>
            </tr>
            <tr>
                <td class="text-center text-red">OBSERVACIONES</td>
                <td colspan="5"><input placeholder="00" type="text"  v-model="orina.d37"></td>
            </tr>
            <tr>
                <td rowspan="4">RESPONSABLE</td>
                <td colspan="2" rowspan="4"><q-select borderless dense :options="usuarios" required v-model="orina.responsable" style="width:100%"/></td>
            </tr>
            <tr>
                <td colspan="2">FECHA DE TOMA DE MUESTRAS</td>
                <td><input type="date"  v-model="orina.fechatoma"></td>
            </tr>
            <tr>
                <td colspan="2">FECHA DE ENTREGA DE MUESTRAS</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"  >HORA TOMA DE MUESTRA</td>
                <td ><input type="time" name="" id="" v-model="orina.horatoma"></td>
            </tr>
            <tr>
                <td colspan="6">
                    <q-btn type="submit" class="btn btn-success btn-block full-width" color="teal"><i class="fa fa-save"></i> REGISTRAR</q-btn>
                </td>
            </tr>
        </table>
    </q-form>


        </q-tab-panel>

                <q-tab-panel name="uretral">
            <q-form @submit.prevent="onUretral">

        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center">ANALISIS DE SECRECION URETRAL</td>
                <td>Form. 004</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}} {{dato2.paterno}} {{dato2.materno}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.edad}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select dense  borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td>
                <q-input borderless type="text" style="width: 100%" placeholder="Tipo muestra" v-model="uretral.tipomuestra" required list="opuretral"/>
                <datalist id="opuretral">
                    <option v-for="(film,index) in opuretral" :key="index">{{film}}</option>
                </datalist>
                </td>
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
                <td rowspan="2" >RESPONSABLE: <q-select borderless dense :options="usuarios" required v-model="uretral.responsable" style="width:100%"/></td>
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
                    <q-btn type="submit" class="btn btn-success btn-block full-width" color="teal"><i class="fa fa-save"></i> REGISTRAR</q-btn>
                </td>
            </tr>
        </table>

    </q-form>
        </q-tab-panel>


                <q-tab-panel name="vaginal">
              <q-form @submit.prevent="onVaginal">

        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center">ANALISIS DE SECRECION VAGINAL</td>
                <td>Form. 004</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}} {{dato2.paterno}} {{dato2.materno}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.edad}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select dense borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td>
                <q-input borderless type="text" style="width: 100%" placeholder="Tipo muestra" v-model="vaginal.tipomuestra" required list="opvaginal"/>
                <datalist id="opvaginal">
                    <option v-for="(film,index) in opvaginal" :key="index">{{film}}</option>
                </datalist>
                </td>
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
                <td rowspan="2" >RESPONSABLE: <q-select borderless dense :options="usuarios" required v-model="vaginal.responsable" style="width:100%"/></td>
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
                    <q-btn type="submit" class="btn btn-success btn-block full-width" color="teal"><i class="fa fa-save"></i> REGISTRAR</q-btn>
                </td>
            </tr>
        </table>

    </q-form>
        </q-tab-panel>


        <q-tab-panel name="hece">
        <q-form @submit.prevent="onHece">

        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center">ANALISIS DE HECES</td>
                <td>Form. 010</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}} {{dato2.paterno}} {{dato2.materno}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.edad}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select dense borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td>
                <q-input borderless type="text" style="width: 100%" placeholder="Tipo muestra" v-model="hece.tipomuestra" required list="ophece"/>
                <datalist id="ophece">
                    <option v-for="(film,index) in ophece" :key="index">{{film}}</option>
                </datalist>
                </td>
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
                <td rowspan="2" >RESPONSABLE: <q-select borderless dense :options="usuarios" required v-model="hece.responsable" style="width:100%"/></td>
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
                    <q-btn type="submit" class="btn btn-success btn-block full-width" color="teal"><i class="fa fa-save"></i> REGISTRAR</q-btn>
                </td>
            </tr>
        </table>

    </q-form>
        </q-tab-panel>


          <q-tab-panel name="simple">
        <q-form @submit.prevent="onSimple">

        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center">COPRAPARASITOLOGICO SIMPLE</td>
                <td>Form. 009</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}} {{dato2.paterno}} {{dato2.materno}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.edad}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select  dense borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td>
                <q-input borderless type="text" style="width: 100%" placeholder="Tipo muestra" v-model="simple.tipomuestra" required list="opsimple"/>
                <datalist id="opsimple">
                    <option v-for="(film,index) in opsimple" :key="index">{{film}}</option>
                </datalist>
                </td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
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
                <td rowspan="3" >RESPONSABLE: <q-select borderless dense :options="usuarios" required v-model="simple.responsable" style="width:100%"/> </td>
                <td>
                    FECHA DE TOMA DE MUESTRAS
                    <input placeholder="00" type="date"  style="width: 100%" v-model="simple.fechatoma">
                </td>
            </tr>
            <tr>
                <td>
                    HORA DE TOMA DE MUESTRAS
                    <input placeholder="00" type="time"  style="width: 100%" v-model="simple.horatoma">
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
                    <q-btn type="submit" class="btn btn-success btn-block full-width" color="teal"><i class="fa fa-save"></i> REGISTRAR</q-btn>
                </td>
            </tr>
        </table>

    </q-form>
          </q-tab-panel>


          <q-tab-panel name="seriado">
             <q-form @submit.prevent="onSeriado">

        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center">COPROPARASITOLOGICO SERIADO</td>
                <td>Form. 008</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}} {{dato2.paterno}} {{dato2.materno}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.edad}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select dense borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td>
                <q-input borderless type="text" style="width: 100%" placeholder="Tipo muestra" v-model="seriado.tipomuestra" required list="opseriado"/>
                <datalist id="opseriado">
                    <option v-for="(film,index) in opseriado" :key="index">{{film}}</option>
                </datalist>
                </td>
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
                <td rowspan=3><input  type="text"  style="width: 100%" v-model="seriado.muestra1"></td>
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
                <td colspan="2" >RESPONSABLE: <q-select borderless dense :options="usuarios" required v-model="seriado.responsable" style="width:100%"/></td>
                <td>
                    FECHA DE ENTREGA RESULTADOS
                    <input type="date"  style="width: 100%">
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <q-btn type="submit" class="btn btn-success btn-block full-width" color="teal"><i class="fa fa-save"></i> REGISTRAR</q-btn>
                </td>
            </tr>
        </table>

    </q-form>
          </q-tab-panel>


          <q-tab-panel name="serologia">
              <q-form @submit.prevent="onSerologia">

        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center">SEROLOGIA</td>
                <td>Form. 005</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}} {{dato2.paterno}} {{dato2.materno}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.edad}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select  dense borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td>
                <q-input borderless type="text" style="width: 100%" placeholder="Tipo muestra" v-model="serologia.tipomuestra" required list="opserologia"/>
                <datalist id="opserologia">
                    <option v-for="(film,index) in opserologia" :key="index">{{film}}</option>
                </datalist>
                </td>
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
                <td colspan="2" rowspan="2" >RESPONSABLE: <q-select borderless dense :options="usuarios" required v-model="serologia.responsable" style="width:100%"/></td>
                <td >
                    FECHA DE TOMA DE MUESTRAS
                </td>
                <td >
                    <input placeholder="00" type="date"  style="width: 100%" v-model="serologia.fechatoma">
                </td>
            </tr>
            <tr>
                <td >
                    FECHA DE ENTREGA DE MUESTRAS
                </td>
                <td >
                    <input placeholder="00" type="date" style="width: 100%" >
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <q-btn type="submit" class="btn btn-success btn-block full-width" color="teal"><i class="fa fa-save"></i> REGISTRAR</q-btn>
                </td>
            </tr>
        </table>
    </q-form>
          </q-tab-panel>


          <q-tab-panel name="labserologia">
              <q-form @submit.prevent="onLabserologia">

        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center">SEROLOGIA</td>
                <td>Form. 001</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}} {{dato2.paterno}} {{dato2.materno}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.edad}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select dense borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td>
                <q-input borderless type="text" style="width: 100%" placeholder="Tipo muestra" v-model="labserologia.tipomuestra" required list="oplabserologia"/>
                <datalist id="oplabserologia">
                    <option v-for="(film,index) in oplabserologia" :key="index">{{film}}</option>
                </datalist>
                </td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>

        </table>
        <br>
        <table border="1" style="width: 100%;color: black">

            <tr>
                <td colspan="2" class="text-center text-red" ></td>
                <td class="text-center text-red" > Valor referencial</td>
                <td class="text-center text-red" >Metodo de Prueba</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">Factor Reumatoide(Latex)</td>
                <td ><input  type="text"  v-model="labserologia.d1" ></td>
                <td class="text-center text-blue">Hasta 8 UI/ml</td>
                <td class="text-center text-blue">Aglutinacion directa</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">Antiestreptolisina</td>
                <td ><input  type="text"  v-model="labserologia.d2" ></td>
                <td class="text-center text-blue">Hasta 200 UI/l</td>
                <td class="text-center text-blue">Aglutinacion directa</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">Proteina C Reactiva</td>
                <td ><input  type="text"  v-model="labserologia.d3" ></td>
                <td class="text-center text-blue">Hasta 0.8 mg/dl</td>
                <td class="text-center text-blue">Aglutinacion directa</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">RPR</td>
                <td ><input  type="text"  v-model="labserologia.d4" ></td>
                <td class="text-center text-blue">No Reactivo</td>
                <td class="text-center text-blue">Floculacion directa </td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">Prueba Rapida Sifilis</td>
                <td ><input  type="text"  v-model="labserologia.d5" ></td>
                <td class="text-center text-blue">No Reactivo</td>
                <td class="text-center text-blue">Prueba Rapida Inmunocromatografica</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">Prueba Rapida VIH</td>
                <td ><input  type="text"  v-model="labserologia.d6" ></td>
                <td class="text-center text-blue">No Reactivo</td>
                <td class="text-center text-blue">Prueba Rapida Inmunocromatografica</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">Hepatitis A</td>
                <td ><input  type="text"  v-model="labserologia.d7" ></td>
                <td class="text-center text-blue">NEGATIVO / POSITIVO</td>
                <td class="text-center text-blue">Prueba Rapida Inmunocromatografica</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">Hepatitis B</td>
                <td ><input  type="text"  v-model="labserologia.d8" ></td>
                <td class="text-center text-blue">NEGATIVO / POSITIVO</td>
                <td class="text-center text-blue">Prueba Rapida Inmunocromatografica</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">Hepatitis C</td>
                <td ><input  type="text"  v-model="labserologia.d9" ></td>
                <td class="text-center text-blue">NEGATIVO / POSITIVO</td>
                <td class="text-center text-blue">Prueba Rapida Inmunocromatografica</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">Helicobacter Pylori en Sangre</td>
                <td ><input  type="text"  v-model="labserologia.d10" ></td>
                <td class="text-center text-blue">NEGATIVO / POSITIVO</td>
                <td class="text-center text-blue">Prueba Rapida Inmunocromatografica</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">Helicobacter Pylori en Heces</td>
                <td ><input  type="text"  v-model="labserologia.d11" ></td>
                <td class="text-center text-blue">NEGATIVO / POSITIVO</td>
                <td class="text-center text-blue">Prueba Rapida Inmunocromatografica</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">Troponina I</td>
                <td ><input  type="text"  v-model="labserologia.d12" ></td>
                <td class="text-center text-blue">NEGATIVO / POSITIVO</td>
                <td class="text-center text-blue">Prueba Rapida Inmunocromatografica</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">PSA</td>
                <td ><input  type="text"  v-model="labserologia.d13" ></td>
                <td class="text-center text-blue">Hasta 4 ng/ml</td>
                <td class="text-center text-blue">Prueba Rapida Semicuantitativo</td>
            </tr>



            <tr>
                <td class="text-left text-red">OBSERVACIONES</td>
                <td colspan="3" ><input type="text"  style="width: 100%" v-model="labserologia.d14"></td>
            </tr>

            <tr >
                <td colspan="2" rowspan="3" >RESPONSABLE: <q-select borderless dense :options="usuarios" required v-model="labserologia.responsable" style="width:100%"/> </td>
                <td>
                    FECHA DE TOMA DE MUESTRAS
                </td>
                <td>
                    <input placeholder="00" type="date"  style="width: 100%" v-model="labserologia.fechatoma">
                </td>
            </tr>
                        <tr>
                <td >
                    HORA DE TOMA DE MUESTRAS
                </td>
                <td >
                    <input placeholder="00" type="time"  style="width: 100%" name="fechaentrega" v-model="labserologia.horatoma">
                </td>
            </tr>
            <tr>
                <td>
                    FECHA DE ENTREGA DE MUESTRAS
                </td>
                <td>
                    <input placeholder="00" type="date"  style="width: 100%" name="fechaentrega">
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <q-btn type="submit" class="btn btn-success btn-block full-width" color="teal"><i class="fa fa-save"></i> REGISTRAR</q-btn>
                </td>
            </tr>
        </table>

    </q-form>

          </q-tab-panel>


          <q-tab-panel name="reserologia">
              <q-form @submit.prevent="onReserologia">

        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="border:0; text-align: center; color:darkblue;"><b> SEROLOGIA</b></td>
                <td>Form. 005</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}} {{dato2.paterno}} {{dato2.materno}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.edad}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select dense  borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td>
                <q-input borderless type="text" style="width: 100%" placeholder="Tipo muestra" v-model="reserologia.tipomuestra" required list="opreserologia"/>
                <datalist id="opreserologia">
                    <option v-for="(film,index) in opreserologia" :key="index">{{film}}</option>
                </datalist>
                </td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>

        </table>
        <br>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="4" style="text-align: center; color:red;">PRUEBA RAPIDA ANTIGENOS SARS COV 2</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center "></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center ">METODO: INMUNOGRAMATOGRAFIA CUALITATIVA</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center "></td>
            </tr>
            <tr>
                <td class="text-center text-red" style="width: 20%"></td>
                <td class="text-center text-red" style="width: 20%">ANTIGENO SARS COV2</td>
                <td ><input  type="text"  style="width: 50%" v-model="reserologia.d1"></td>
                <td class="text-center text-red" style="width: 20%"></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:center; color:red">FUNDAMENTO E INTERPRETACION DE LOS RESULTADOS</td>
            </tr>
            <tr>
                <td colspan="4">La prueba rapida de Antigeno Nasal puede revelar una Infeccion activa de SARS-COV-2.<br>
                El test proporciona un resultado de "<span style="color:red">POSITIVO</span>" o "<span style="color:red">NEGATIVO</span>"<br>
                Los antigenos son parte de la estructura del virus, como las proteinas de espiga.<br><br>
                La muestra de Hisopado nasofaringeo pasa por una linea que detecta antigenos y cambia de color, durante la prueba el anticuerpo monocianal de raton anti-SARS-CoV-2 en la muestra
                interactua con el Anticuerpo monocianal lgG anti-COVID-19 conjugando con particulas de color que forman un complejo de particulas de antigeno-anticuerpo. Este complejo migra en la
                menbrana por accion capilar hasta la linea de prueba, donde aera capturado por el anticuerpo por el anticuerpo monocianal anti-SARS-CoV-2 de raton. Una linea de prueba coloreada
                seria visible en la ventana de resultados si los antigenos del SARS-CoV-2 estan presentes en la muestra. <br><br>
                Funciona mejor en la etapa inicial con una <span style="color:red">CARGA VIRAL ALTA</span> y sintomalogia hasta los 10 dias.<br>
                Los resultados <span style="color:red">Negativos</span> indica ausencia de Antigenos detectables de SARS-CoV-2 y cuando la carga viral o la cantidad de antigeno presente se encuentra debajo del limite de deteccion.<br><br>
                Los resultados <span style="color:red">Psitivos</span> no diferencian entre SARS-COV y SARS-COV-2

                </td>
            </tr>
            <tr>
                <td colspan="4">
                <span style="color:red">NOTA:</span> Las pruebas rapidas para COVID-19 NO SON CONFIRMATORIAS<br>
                Los Resultados deben ser interpretados en funcion de la Clinica del paciente y dias de evolucion de la enfermedad
                </td>

            </tr>
            <tr>
                <td class="text-left text-red">OBSERVACIONES</td>
                <td colspan="3" ><input type="text"  style="width: 100%" v-model="reserologia.d2"></td>
            </tr>

            <tr >
                <td colspan="2" rowspan="3" >RESPONSABLE: <q-select borderless dense :options="usuarios" required v-model="reserologia.responsable" style="width:100%"/></td>
                <td >
                    FECHA DE TOMA DE MUESTRAS
                </td>
                <td >
                    <input placeholder="00" type="date"  style="width: 100%" v-model="reserologia.fechatoma">
                </td>
            </tr>
                        <tr>
                <td>
                    Hora DE TOMA DE MUESTRAS
                </td>
                <td>
                    <input placeholder="00" type="time"  style="width: 100%" name="fechaentrega" v-model="reserologia.horatoma">
                </td>
            </tr>
            <tr>
                <td>
                    FECHA DE ENTREGA DE MUESTRAS
                </td>
                <td>
                    <input placeholder="00" type="date"  style="width: 100%" name="fechaentrega">
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <q-btn type="submit" class="btn btn-success btn-block full-width" color="teal"><i class="fa fa-save"></i> REGISTRAR</q-btn>
                </td>
            </tr>
        </table>

    </q-form>
          </q-tab-panel>


          <q-tab-panel name="ensayo">
             <q-form @submit.prevent="onEnsayo">

        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="3" style="text-align: center; border:0">Inmunoensayo de Fluorescencia</td>
                <td>Form. 010</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td>{{dato2.nombre}} {{dato2.paterno}} {{dato2.materno}}</td>
                <td style="color: darkblue">EDAD</td>
                <td>{{dato2.edad}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td> <q-select dense borderless  :options="doctors" v-model="requerido" style="width:100%"/></td>
                <td style="color: darkblue">SEXO</td>
                <td>{{dato2.sexo}}</td>
            </tr>
            <tr>
                <td style="color: darkblue">METODO</td>
                <td><input type="text" style="width: 100%" v-model="ensayo.tipomuestra" placeholder="metodo" ></td>
                <td style="color: darkblue">N PACIENTE</td>
                <td>{{dato2.id}}</td>
            </tr>

        </table>
        <br>
        <table border="1" style="width: 100%;color: black">
            <tr><td colspan="5" class="text-center text-red">
                METODO: INMUNOENSAYO DE FLUORESCENCIA (FIA)
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center text-red" ></td>
                <td class="text-center text-blue" > UNIDAD</td>
                <td class="text-center text-blue" >Tipo de Muestra</td>
                <td class="text-center text-blue" >Valor Referencial</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">DIMEROS D</td>
                <td ><input  type="text"  v-model="ensayo.d1" ></td>
                <td class="text-center text-blue">ng/ml</td>
                <td class="text-center text-blue">Plasma Citratado</td>
                <td class="text-center text-blue">Hasta 500 ng/ml</td>
            </tr>

            <tr>
                <td  class="text-left text-red" style="width: 20%">FERRITINA</td>
                <td ><input  type="text"  v-model="ensayo.d2" ></td>
                <td  class="text-center text-blue">ng/ml</td>
                <td  class="text-center text-blue">Suero</td>
                <td class="text-center text-blue">30-350 ng/ml Varon <br>20-250 ng/ml Mujer </td>
            </tr>

            <tr>
                <td class="text-left text-red" style="width: 20%">IL-6</td>
                <td ><input  type="text"  v-model="ensayo.d3" ></td>
                <td class="text-center text-blue">pg/ml</td>
                <td class="text-center text-blue">Suero/plasma</td>
                <td class="text-center text-blue">7 pg/ml</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">PSA CUANTITATIVO</td>
                <td ><input  type="text"  v-model="ensayo.d4" ></td>
                <td class="text-center text-blue">ng/ml</td>
                <td class="text-center text-blue">Suero</td>
                <td class="text-center text-blue">Menor a 4 ng/ml</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">PCR CUANTITATIVO</td>
                <td ><input  type="text"  v-model="ensayo.d5" ></td>
                <td class="text-center text-blue">mg/L</td>
                <td class="text-center text-blue">Sangre Entera</td>
                <td class="text-center text-blue"> menor 10 mg/L</td>
            </tr>
            <tr>
                <td class="text-left text-red" style="width: 20%">TROPONINA I</td>
                <td ><input  type="text"  v-model="ensayo.d6" ></td>
                <td class="text-center text-blue">ng/ml</td>
                <td class="text-center text-blue">Suero</td>
                <td class="text-center text-blue">0.0 - 0.11 ng/ml</td>
            </tr>
            <tr>
                <td  class="text-left text-red" style="width: 20%">B - HCG</td>
                <td ><input  type="text"  v-model="ensayo.d7" ></td>
                <td  class="text-center text-blue">mlU/ml</td>
                <td  class="text-center text-blue">Suero</td>
                <td class="text-center text-blue">Mujer No Embarazada menor  10 mlU/ml <br>Mujer en postmenopausia menor 10 mlU/ml</td>
            </tr>

            <tr>
                <td  class="text-left text-red" style="width: 20%">PROCALCITONINA</td>
                <td  ><input  type="text"  v-model="ensayo.d8" ></td>
                <td  class="text-center text-blue">ng/ml</td>
                <td   class="text-center text-blue">Suero</td>
                <td class="text-center text-blue">PCT menor 0.5 Es posible infeccion Bacteriana Local <br>PCT 0.5 - 2 Posible Infeccion <br>PCT 2 - 10 Es muy Probable Infeccion (sepsis) a menos que se conozcan otras causas <br>PCT > 10 Sepsis Bacteriana severa o shock septico </td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>

            <tr >
                <td colspan="2" rowspan="3" >RESPONSABLE: <q-select borderless dense :options="usuarios" required v-model="ensayo.responsable" style="width:100%"/></td>
                <td colspan="2">
                    FECHA DE TOMA DE MUESTRAS
                </td>
                <td>
                    <input placeholder="00" type="date"  style="width: 100%" v-model="ensayo.fechatoma">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    HORA DE TOMA DE MUESTRAS
                </td>
                <td>
                    <input placeholder="00" type="time"  style="width: 100%" name="fechaentrega" v-model="ensayo.horatoma">
                </td>
            </tr>            <tr>
                <td colspan="2">

                    FECHA DE ENTREGA DE MUESTRAS
                                               </td>
                <td>
                    <input placeholder="00" type="date"  style="width: 100%" name="fechaentrega">
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <q-btn type="submit" class="btn btn-success btn-block full-width" color="teal"><i class="fa fa-save"></i> REGISTRAR</q-btn>
                </td>
            </tr>
        </table>

    </q-form>
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
import { saveAs } from 'file-saver';
export default {
  data(){
    return{
      filter:'',
      filter2:'',
      $q : useQuasar(),
      pacientes:[],
      rows:[],
      dato:{},
      doctors:[],
      alert:false,
      dialog_mod:false,
      dialog_lab:false,
      dialog_hist:false,
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
      hemograma:{fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')},
      orina:{fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')},
      sanguinia:{fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')},
      uretral:{fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')},
      vaginal:{fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')},
      hece:{fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')},
      simple:{fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')},
      seriado:{fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')},
      serologia:{fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')},
      labserologia:{fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')},
      reserologia:{fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')},
      ensayo:{fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')},
      ophemograma:[],
      oporina:[],
      opsanguinia:[],
      opuretral:[],
      opvaginal:[],
      ophece:[],
      opsimple:[],
      opseriado:[],
      opserologia:[],
      oplabserologia:[],
      opreserologia:[],
      opensayo:[],
      requerido:{},
      usuarios:[],
      actual:date.formatDate(Date.now(),'YYYY-MM-DD'),
      tab:{},
      historial:[],
      seguros:[],
      seguro:{},
      columns:[
        { name: 'ci', label: 'ci', field: 'ci',align: 'center',},
        { name: 'nombre', label: 'nombre', field: 'nombre',align: 'center',},
        { name: 'paterno', label: 'paterno', field: 'paterno',align: 'center',},
        { name: 'materno', label: 'materno', field: 'materno',align: 'center',},
        { name: 'fechanac', label: 'fechanac', field: 'fechanac', align: 'center',},
        { name: 'sexo', label: 'sexo', field: 'sexo',align: 'center',},
        { name: 'celular', label: 'celular', field: 'celular',align: 'center',},
        { name: 'seguro', label: 'seguro', field: row=>row.seguro.nombre,align: 'center',},
        { name: 'opcion', label: 'opcion', field: 'opcion',align: 'center',},
      ],
    columns2:[
        { name: 'formulario', label: 'formulario', field: 'formulario',align: 'center'},
        { name: 'fechatoma', label: 'fechatoma', field: 'fechatoma',align: 'center'},
        { name: 'tipomuestra', label: 'tipomuestra', field: 'tipomuestra',align: 'center'},
        { name: 'opcion', label: 'opcion', field: 'opcion',align: 'center',}
      ],
    }
  },
  created(){
    this.listado();
    this.listdoctor();
    this.listusers();
    this.tab={value:"hemograma",label:"Hemograma completo" };
    this.doctini();
    this.listseguro();
  },
  methods: {
      listseguro(){
          this.seguros=[]
          this.seguro={label:''}
          this.$axios.get(process.env.API+'/seguro').then(res=>{
              res.data.forEach(r => {
                  this.seguros.push({label:r.nombre,r});
              });
              
          })

      },
      listusers(){
          this.usuarios=[]
          this.$axios.post(process.env.API+'/listuser').then(res=>{
             res.data.forEach(element => {
                 this.usuarios.push(element.name);
             });
          //console.log(this.usuarios)
          })

      },
      listmuestra(){
        this.ophemograma=[];
        this.oporina=[];
        this.opsanguinia=[];
        this.opuretral=[];
        this.opvaginal=[];
        this.ophece=[];
        this.opsimple=[];
        this.opseriado=[];
        this.opserologia=[];
        this.oplabserologia=[];
        this.opreserologia=[];
        this.opensayo=[];
          this.$axios.get(process.env.API+'/muestra').then(res=>{
              res.data.forEach(element => {
                  switch (element.formulario) {
                      case 'hemograma':
                            this.ophemograma.push(element.tipomuestra);
                          break;
                      case 'orina':
                            this.oporina.push(element.tipomuestra);
                          break;
                      case 'sanguinia':
                            this.opsanguinia.push(element.tipomuestra);
                          break;
                      case 'uretral':
                            this.opuretral.push(element.tipomuestra);
                          break;
                      case 'vaginal':
                            this.opvaginal.push(element.tipomuestra);
                          break;
                      case 'hece':
                            this.ophece.push(element.tipomuestra);
                          break;
                      case 'simple':
                            this.opsimple.push(element.tipomuestra);
                          break;
                      case 'seriado':
                            this.opseriado.push(element.tipomuestra);
                          break;
                      case 'serologia':
                            this.opserologia.push(element.tipomuestra);
                          break;
                      case 'labserologia':
                            this.oplabserologia.push(element.tipomuestra);
                          break;
                    case 'reserologia':
                            this.opreserologia.push(element.tipomuestra);
                          break;
                      case 'ensayo':
                            this.opensayo.push(element.tipomuestra);
                          break;

                      default:
                          break;
                  }
              });
          })

      },
    listdoctor(){
        this.doctors=[];
          this.$axios.get(process.env.API+'/doctor').then(res=>{
            // console.log(res.data);
            res.data.forEach(e => {
                this.doctors.push({label:e.nombre+' '+e.paterno+' '+e.materno,value:e.id})
             });
                this.requerido=this.doctors[0];
                this.doctini();
          })
    },
    doctini(){
        this.hemograma.responsable=this.usuarios[0];
        this.orina.responsable=this.usuarios[0];
        this.sanguinia.responsable=this.usuarios[0];
        this.uretral.responsable=this.usuarios[0];
        this.vaginal.responsable=this.usuarios[0];
        this.hece.responsable=this.usuarios[0];
        this.simple.responsable=this.usuarios[0];
        this.seriado.responsable=this.usuarios[0];
        this.serologia.responsable=this.usuarios[0];
        this.labserologia.responsable=this.usuarios[0];
        this.reserologia.responsable=this.usuarios[0];
        this.ensayo.responsable=this.usuarios[0];
    },

    listado(){
          this.$axios.get(process.env.API+'/paciente').then(res=>{
       console.log(res.data)
       this.pacientes=res.data;
     })
    },
    editRow(props){
      this.dato2=props.row;
      this.seguro={label:this.dato2.seguro.nombre,r:this.dato2.seguro}
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
          color: 'success'
        })
        this.listado();
          })
      })
    },
    onSubmit(){
        this.dato.seguro=this.seguro.r.id
      this.$q.loading.show()
      this.$axios.post(process.env.API+'/paciente',this.dato).then(res=>{
        this.dato={}
         this.$q.notify({
          message: 'Se registro correctamente',
          color: 'success'
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
      console.log(this.hemograma)
        this.$axios.post(process.env.API+'/hemograma',{paciente:this.dato2,doctor:this.requerido.value,hemograma:this.hemograma}).then(res=>{
          // console.log(res.data)
         this.dialog_lab=false;
           this.$q.notify({
              message: 'Registro Realizado',
              icon: 'done',
             color:'green'
            })
            this.hemograma={fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')};
                this.doctini();

        })
    },
    onSanguinia(){
        this.$axios.post(process.env.API+'/sanguinia',{paciente:this.dato2,doctor:this.requerido.value,sanguinia:this.sanguinia}).then(res=>{
          console.log(res.data)
        this.dialog_lab=false;
           this.$q.notify({
              message: 'Registro Realizado',
              icon: 'done',
             color:'green'
            })
            this.sanguinia={fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')};
                this.doctini();
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
        this.orina={fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mmm')};
                this.doctini();
        })
    },
          onUretral(){
        this.$axios.post(process.env.API+'/uretral',{paciente:this.dato2,doctor:this.requerido.value,uretral:this.uretral}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
            this.$q.notify({
              message: 'Registro Realizado',
              icon: 'done',
             color:'green'
            })
            this.uretral={fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')};
                this.doctini();
        })
    },
    onVaginal(){
        this.$axios.post(process.env.API+'/vaginal',{paciente:this.dato2,doctor:this.requerido.value,vaginal:this.vaginal}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
            this.$q.notify({
              message: 'Registro Realizado',
              icon: 'done',
             color:'green'
            })
            this.vaginal={fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')};
                this.doctini();
        })
    },
    onHece(){
        this.$axios.post(process.env.API+'/hece',{paciente:this.dato2,doctor:this.requerido.value,hece:this.hece}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
            this.$q.notify({
              message: 'Registro Realizado',
              icon: 'done',
             color:'green'
            })
            this.hece={fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')};
                this.doctini();
        })
    },
    onSimple(){
        this.$axios.post(process.env.API+'/simple',{paciente:this.dato2,doctor:this.requerido.value,simple:this.simple}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
            this.$q.notify({
              message: 'Registro Realizado',
              icon: 'done',
             color:'green'
            })
            this.simple={fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')};
                this.doctini();

        })
    },
        onSeriado(){
        this.$axios.post(process.env.API+'/seriado',{paciente:this.dato2,doctor:this.requerido.value,seriado:this.seriado}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
            this.$q.notify({
              message: 'Registro Realizado',
              icon: 'done',
             color:'green'
            })
            this.seriado={fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')};
                this.doctini();

        })
    },
    onSerologia(){
        this.$axios.post(process.env.API+'/serologia',{paciente:this.dato2,doctor:this.requerido.value,serologia:this.serologia}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
            this.$q.notify({
              message: 'Registro Realizado',
              icon: 'done',
             color:'green'
            })
            this.serologia={fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')};
                this.doctini();

        })
    },
    onLabserologia(){
        this.$axios.post(process.env.API+'/labserologia',{paciente:this.dato2,doctor:this.requerido.value,labserologia:this.labserologia}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
            this.$q.notify({
              message: 'Registro Realizado',
              icon: 'done',
             color:'green'
            })
            this.labserologia={fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')};
                this.doctini();

        })
    },
        onReserologia(){
        this.$axios.post(process.env.API+'/reserologia',{paciente:this.dato2,doctor:this.requerido.value,reserologia:this.reserologia}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
            this.$q.notify({
              message: 'Registro Realizado',
              icon: 'done',
             color:'green'
            })
            this.reserologia={fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')};
                this.doctini();

        })
    },
            onEnsayo(){
        this.$axios.post(process.env.API+'/ensayo',{paciente:this.dato2,doctor:this.requerido.value,ensayo:this.ensayo}).then(res=>{
          console.log(res.data)
          this.dialog_lab=false;
            this.$q.notify({
              message: 'Registro Realizado',
              icon: 'done',
             color:'green'
            })
            this.ensayo={fechatoma:date.formatDate(Date.now(),'YYYY-MM-DD'),horatoma:date.formatDate(Date.now(),'HH:mm')};
                this.doctini();

        })
    },
    labRow(props){
      this.dato2=props.row;
      this.dato2.edad=this.getAge(this.dato2.fechanac);
      this.listmuestra();
      this.dialog_lab=true;
    },
    listRow(props){
        this.dato2=props.row;
        this.historial=[];
        this.$axios.get(process.env.API+'/historialform/'+this.dato2.id).then(res=>{
            console.log(res.data);
            this.historial=res.data;
            this.dialog_hist=true;
        })

    },
    impRow(props){
                let nomb=this.dato2.ci+'.pdf';
        let url=(process.env.API+'/'+props.row.formulario+'/'+props.row.id);
      // let route = this.$router.resolve('/link/to/page'); // This also works.
             //window.open(url, 'prueba5');
        var oReq = new XMLHttpRequest();
            // El punto final de su servidor
            var URLToPDF = url;

            // Configurar XMLHttpRequest
            oReq.open("GET", URLToPDF, true);

            // Importante para usar el tipo de respuesta de blob
            oReq.responseType = "blob";

            // Cuando finaliza la solicitud del archivo
            // Depende de usted, la configuración de eventos de error, etc.
            oReq.onload = function() {
                // Una vez descargado el archivo, abre una nueva ventana con el PDF
                // Recuerde permitir los POP-UPS en su navegador
                var file = new Blob([oReq.response], {
                    type: 'application/pdf'
                });

                // ¡Genere la descarga de archivos directamente en el navegador!
                saveAs(file, nomb);
            };

            oReq.send();

    },
    whppRow(props){
        let url=(process.env.API+'/'+props.row.formulario+'/'+props.row.id);

            //oReq.send();
           //window.open(url, '_blank');
            window.open("https://api.whatsapp.com/send?phone=591"+this.dato2.celular+"&text="+url, '_blank');
    },
    whpdRow(props){
        let url=(process.env.API+'/'+props.row.formulario+'/'+props.row.id);
        window.open("https://api.whatsapp.com/send?phone=591"+props.row.dcelular+"&text="+url, '_blank');
    },
    onMod(){
      this.dato2.seguro=this.seguro.r.id
      console.log(this.dato2)
      this.$axios.put(process.env.API+'/paciente/'+this.dato2.id,this.dato2).then(res=>{
         this.$q.notify({
          message: 'Se modifico correctamente',
          color: 'success'
        })
      })
      this.dialog_mod=false;
      this.listado();
    },
    getAge(dateString)
    {
    var today = new Date();
    var birthDate = new Date(dateString);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate()))
    {
        age--;
    }
    return age;
    },

    eliminar (props) {
      this.$q.dialog({
        title: 'Eliminar Formulario',
        message: 'Esta seguro de eliminar?',
        cancel: true,
      }).onOk(() => {
        // console.log('>>>> OK')
            console.log(props.row)
        this.$axios.post(process.env.API+'/eliminar',props.row).then(res=>{
            this.dialog_lab=false;
         this.$q.notify({
          message: 'Elimino el registro',
          icon:'delete',
          color: 'success'
            })
        })

      }).onOk(() => {
        // console.log('>>>> second OK catcher')
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })
    }
  },
}
</script>
<style>
table, td, th {
  border-collapse: collapse;
}
</style>
