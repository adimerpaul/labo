<template>
  <q-page class="q-pa-xs">
    <div class="row">
      <div class="col-12">
        <!--      <iframe id="docpdf" src="" frameborder="0" style="width: 100%;height: 100vh"></iframe>-->
      </div>
      <div class="col-12">
        <q-table title1="Listado de pacientes" :loading="loading" :rows="pacientes" :columns="columspaciente"
                 :rows-per-page-options="[10,100,200,0]">
          <template v-slot:body-cell-seguro="props">

            <q-td :props="props">{{ props.row.seguro.nombre }}</q-td>
          </template>

          <template v-slot:body-cell-laboratorios="props">
            <q-td :props="props" auto-width>
              <q-btn color="accent" @click="laboratorioClick(props.row)" label="Laboratorios"/>

            </q-td>
          </template>
          <template v-slot:body-cell-opciones="props">
            <q-td :props="props" auto-width>
              <!--            <q-btn @click="frmlaboratorio(props.row)" size="xs"  icon="science" color="primary" label="agregar" />-->
              <q-btn-dropdown color="primary" label="Opciones" icon="science">
                <q-list>

                  <q-item clickable v-close-popup @click="modificar(props.row)">
                    <q-item-section>
                      <q-item-label>Modificar Paciente</q-item-label>
                    </q-item-section>
                  </q-item>
                  <q-item clickable v-close-popup @click="frmlaboratorio(props.row)">
                    <q-item-section>
                      <q-item-label>Agregar laboratorio</q-item-label>
                    </q-item-section>
                  </q-item>
                  <q-item clickable v-close-popup @click="deleteRow(props.row)">
                    <q-item-section>
                      <q-item-label>Eliminar Paciente</q-item-label>
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-btn-dropdown>
            </q-td>
          </template>
          <template v-slot:body-cell-paciente="props">
            <q-td :props="props" auto-width>
              <q-btn @click="Whatsapp(props.row.celular)" size="xs" flat round color="green" icon="whatsapp"
                     v-if="props.row.celular!='' && props.row.celular!=null"/>
              {{ props.row.paciente }}
            </q-td>
          </template>
          <template v-slot:body-cell-edad="props">
            <q-td :props="props" auto-width>
              <template v-if="props.row.edad==undefined||props.row.edad==null||props.row.edad==''">
                {{ props.row.tiempo }}
              </template>
              <template v-else>
                {{ props.row.edad }}
              </template>
            </q-td>
          </template>
          <template v-slot:top-right>
            <q-btn color="primary" icon="add_circle" label="crear paciente" @click="alert = true"/>
            <q-btn color="grey" icon="refresh" flat round @click="mispacientes"/>
            <q-input outlined dense debounce="300" v-model="filter" placeholder="Search"
                     @update:modelValue="mispacientes"
                     :loading="loading" clearable>
              <template v-slot:append>
                <q-icon name="search"/>
              </template>
            </q-input>
          </template>
          <template v-slot:bottom>
            <div class="full-width flex flex-center">
              <q-pagination
                v-model="current"
                :max="last_page"
                :max-pages="7"
                boundary-numbers
                @update:modelValue="mispacientes"
              />
            </div>
          </template>
        </q-table>
        <q-dialog v-model="dialoglistlabo">
          <q-card style="min-width: 350px">
            <q-card-section>
              <div class="text-h6">LISTA DE LABORATORIOS</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="row">
                <div class="col-6">
                  <q-input dense v-model="fechalab" autofocus type="date"/>
                </div>
                <div class="col-6 flex flex-center">
                  <q-btn color="info" icon="search" label="Consultar" @click="consultarLab" :loading="loading"/>
                </div>
              </div>
            </q-card-section>
            <q-card-section class="q-pt-none">
              <ul style="border: 0px;margin: 0px;padding: 0px;list-style: none">
                <li style="border: 0px;margin: 0px;padding: 0px" v-for=" l in laboratorios" :key="l.id">
                  <q-btn @click="eliminar(l)" size="xs" flat round color="red" icon="delete"/>
                  <q-btn @click="imprimirlaboratorio(paciente2,l)" size="xs" flat round color="info" icon="print"/>
                  <q-btn @click="datformulario(paciente2,l)" size="xs" flat round color="yellow" icon="edit"/>
                  <q-btn @click="sobre(paciente2,l)" size="xs" flat round color="teal" icon="mail_outline"/>
                  <q-btn @click="upimagen(l)" size="xs" flat round color="purple-5" icon="add_photo_alternate"
                         v-if="l.tipo_id==18"/>
                  <q-btn @click="descargar(l)" size="xs" flat round color="deep-orange-5" icon="image"
                         v-if="l.imagen!=null && l.imagen!=''"/>

                  <q-btn @click="Whatsapp(l.doctor.celular)" size="xs" flat round color="purple" icon="whatsapp"
                         v-if="l.doctor.celular!='' && l.doctor.celular!=null"/>
                  {{ l.fechatoma }}
                  {{ l.tipo.nombre }} - {{ l.solicitud }}
                </li>
              </ul>
              <!--                <pre>{{laboratorios}}</pre>-->
            </q-card-section>
            <q-card-actions align="right" class="text-primary">
              <q-btn flat label="Cancel" v-close-popup/>
            </q-card-actions>
          </q-card>
        </q-dialog>
      </div>
    </div>
    <q-dialog v-model="alert" full-width>
      <q-card>
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Registro de paciente</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <q-form
            @submit.prevent="onSubmit"
            class="q-gutter-md"
          >
            <div class="row">
              <div class="col-6">
                <q-input outlined v-model="dato.nro" label="Nro Paciente" hint="Numero "/>
              </div>
              <div class="col-6">
                <q-input outlined v-model="dato.ci" label="Cedula Identidad" hint="Numero de carnet"/>
              </div>
              <div class="col-6">
                <q-input outlined v-model="dato.nombre" label="Nombre " hint="Ingresar Nombre "
                         style="text-transform: uppercase;" lazy-rules
                         :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
                />
              </div>
              <div class="col-6">
                <q-input outlined v-model="dato.paterno" label="Ap Paterno " hint="Ingresar Paterno "
                         style="text-transform: uppercase;"
                />
              </div>
              <div class="col-6">
                <q-input outlined v-model="dato.materno" label="Ap Materno " hint="Ingresar Materno "
                         style="text-transform: uppercase;"
                />
              </div>
              <div class="col-6">
                <q-input outlined type="date" v-model="dato.fechanac" label="Fecha Nac"/>
                <br>
                <span style="color:red" v-if="calcular!=''">{{ calcular }} </span>
              </div>
              <div class="col-6">
                <q-input outlined type="text" v-model="dato.edad" label="Edad"
                />
              </div>
              <div class="col-6">
                <q-radio v-model="dato.sexo" val="Masculino" label="Masculino"/>
                <q-radio v-model="dato.sexo" val="Femenino" label="Femenino"/>
              </div>
              <div class="col-6">
                <q-input outlined v-model="dato.celular" label="Celular (Whatsapp)" hint="Numero de celular"/>
              </div>
              <div class="col-6">
                <q-select outlined v-model="seguro" :options="seguros" label="Seguro"/>
              </div>
            </div>
            <div>
              <q-btn label="Crear" type="submit" color="positive" icon="add_circle" :loading="loading"/>
              <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup/>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="dialogmodimg">
      <q-card>
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Modificar Imagen</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <div class="col-12 col-sm-6">
            <label for="">IMAGEN : </label>
            <input type="file" @change="getImage">
          </div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <q-btn label="Crear" color="positive" icon="add_circle" @click="onmodimagen"/>
          <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup/>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="dialog_mod" full-width>
      <q-card>
        <q-card-section class="bg-green-14 text-white">
          <div class="text-h6">Modificar paciente</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <q-form
            @submit.prevent="modpaciente"
            class="q-gutter-md"
          >
            <div class="row">
              <div class="col-6">
                <q-input outlined v-model="datos2.nro" label="Nro Paciente" hint="Numero "/>
              </div>
              <div class="col-6">
                <q-input outlined v-model="datos2.ci" label="Cedula Identidad" hint="Numero de carnet"/>
              </div>
              <div class="col-6">
                <q-input outlined v-model="datos2.nombre" label="Nombre " hint="Ingresar Nombre "
                         style="text-transform: uppercase;" lazy-rules
                         :rules="[ val => val && val.length > 0 || 'Por favor ingresa datos']"
                />
              </div>
              <div class="col-6">
                <q-input outlined v-model="datos2.paterno" label="Ap Paterno " hint="Ingresar Paterno "
                         style="text-transform: uppercase;"
                />
              </div>
              <div class="col-6">
                <q-input outlined v-model="datos2.materno" label="Ap Materno " hint="Ingresar Materno "
                         style="text-transform: uppercase;"
                />
              </div>
              <div class="col-6">
                <q-input outlined type="date" v-model="datos2.fechanac" label="Fecha Nac"/>
                <span style="color:red" v-if="calcular2!='' ">{{ calcular2 }} </span>
              </div>
              <div class="col-6">
                <q-input outlined type="text" v-model="datos2.edad" label="Edad"
                />
              </div>
              <div class="col-6">
                <q-radio v-model="datos2.sexo" val="Masculino" label="Masculino"/>
                <q-radio v-model="datos2.sexo" val="Femenino" label="Femenino"/>
              </div>
              <div class="col-6">
                <q-input outlined v-model="datos2.celular" label="Celular (Whatsapp)" hint="Numero de celular"/>
              </div>
              <div class="col-6">
                <q-select outlined v-model="seguro" :options="seguros" label="Seguro"/>
              </div>
            </div>
            <div>
              <q-btn label="Crear" type="submit" color="positive" icon="add_circle"/>
              <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup/>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>


    <q-dialog v-model="dialoglaboratorio" full-width full-height>
      <q-card>
        <q-card-section>
          <div class="row">
            <div class="col-5">
              <div class="text-subtitle2">{{ paciente.paciente }}</div>
            </div>
            <div class="col-7">
              <q-select dense outlined :options="tipos" label="Laboratorio" v-model="tipo"
                        @update:model-value="resetlabo" required></q-select>
            </div>
          </div>
        </q-card-section>
        <q-separator/>
        <q-card-section>
          <q-form @submit.prevent="createLaboratorio">
            <div class="row">
              <div class="col-12 col-sm-4">
                <q-select dense outlined :options="doctors" label="Doctor" v-model="doctor" @filter="filterFn" use-input
                          input-debounce="0">
                  <template v-slot:no-option>
                    <q-item>
                      <q-item-section class="text-grey">
                        No results
                      </q-item-section>
                    </q-item>
                  </template>
                </q-select>
              </div>

              <div class="col-12 col-sm-4">
                <q-input dense outlined label="Tipo Muestra" v-model="laboratorio.tipomuestra" list="listmuestra"
                         class="text-uppercase"/>
                <datalist id="listmuestra">
                  <option v-for="(film,index) in listmuestra" :key="index">{{ film }}</option>
                </datalist>
              </div>
              <div class="col-12 col-sm-4">
                <q-input dense outlined label="N Solicitud" v-model="laboratorio.solicitud" type="number"/>
              </div>


              <template v-if="tipo.label=='HEMOGRAMA COMPLETO'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Globulos rojos" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Tiempo de cuagulacion" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Hemoglobina" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Tiempo de sangria" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Hematocrito" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Tiempo de Protrombina" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="V.E.S." v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="% Actividad" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="V.C.M." v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="INR" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Hb.C.M." v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="APTT" v-model="laboratorio.d34"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="C.Hb.C.M." v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Grupo Factor" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Globulos Blancos" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Reticulocitos" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Plaquetas" v-model="laboratorio.d17"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="IPR" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                    </div>
                  </q-card-section>
                  <q-separator/>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Cayados" v-model="laboratorio.d18" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="x10^9/L" v-model="laboratorio.d19"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Neutrofilos" v-model="laboratorio.d20" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="x10^9/L" v-model="laboratorio.d21"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Eosinofilos" v-model="laboratorio.d22" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="x10^9/L" v-model="laboratorio.d23"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Basofilos" v-model="laboratorio.d24" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="x10^9/L" v-model="laboratorio.d25"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Linfocitos" v-model="laboratorio.d26" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="x10^9/L" v-model="laboratorio.d27"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Monocitos" v-model="laboratorio.d28" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="x10^9/L" v-model="laboratorio.d29"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="BLASTOS" v-model="laboratorio.d30" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div v-if="es100" class="col-12 text-red text-bold q-pa-xs">
                        No suman 100%
                      </div>
                    </div>
                  </q-card-section>
                  <q-separator/>
                  <q-card-section class="bg-amber-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Serie Rojas" v-model="laboratorio.d31"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Serie Blancas" v-model="laboratorio.d32"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Serie Plaquetarias" v-model="laboratorio.d33"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>


                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>

              </template>
              <template v-if="tipo.label=='QUIMICA SANGUINIA'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Glicemia" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Fosfatasa alcalina" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Creatinina" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="GGT" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Urea" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Transamisas GOT" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="NUS-BUN" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Transamisas GPT" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Acido Urico" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Proteinas Totales" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Trigliceridos" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Albumina" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Colesterol Total" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Globulina" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="HDL-Col" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Amilasa" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="LDL-Col." v-model="laboratorio.d17"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Lipasa" v-model="laboratorio.d18"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="VLDL" v-model="laboratorio.d32"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Bilirrubina Total" v-model="laboratorio.d19"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Sodio" v-model="laboratorio.d20"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Bilirrubina Directa" v-model="laboratorio.d21"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Cloro" v-model="laboratorio.d22"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Bilirrubina Indirecta" v-model="laboratorio.d23"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Potasio" v-model="laboratorio.d24"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="CPK TOTAL" v-model="laboratorio.d33"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Calcio" v-model="laboratorio.d26"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="CK-MB" v-model="laboratorio.d25"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Magnesio" v-model="laboratorio.d28"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="LDH" v-model="laboratorio.d27"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Fosforo" v-model="laboratorio.d30"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Hierro" v-model="laboratorio.d29"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-amber-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Observaciones" v-model="laboratorio.d31"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='EXAMEN GENERAL DE ORINA'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Color" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Proteinas" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Olor" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Glucosa" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Aspecto" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="C cetonicos" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Espuma" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Bilirrubina" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Deposito" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Hemoglogina" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Densidad" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Urobilina" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Reaccion" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Nitritos" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-12 col-sm-12"></div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Celulas epiteliales" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Hialino" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Celulas de transicion" v-model="laboratorio.d17"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Granuloso" v-model="laboratorio.d18"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Celulas clave" v-model="laboratorio.d19"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Epiteliales" v-model="laboratorio.d20"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Leucocitos" v-model="laboratorio.d21"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Eritrocitario" v-model="laboratorio.d22"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Eritrocis" v-model="laboratorio.d23"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Leucocitario" v-model="laboratorio.d24"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Bacterias" v-model="laboratorio.d25"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Cereos" v-model="laboratorio.d26"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Mixtos" v-model="laboratorio.d27"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-amber-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Uratros amorfos" v-model="laboratorio.d28"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Fosfato amorfo" v-model="laboratorio.d29"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Filamento mucoso" v-model="laboratorio.d30"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Oxalato de calcio" v-model="laboratorio.d31"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Piocitos" v-model="laboratorio.d32"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Fosfato de calcio" v-model="laboratorio.d33"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Levaduras" v-model="laboratorio.d34"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Acido Urico" v-model="laboratorio.d35"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Esporas micoticas" v-model="laboratorio.d36"/>
                      </div>

                      <div class="col-6 col-sm-12">
                        <div>Observaciones</div>
                        <q-editor v-model="laboratorio.d37" min-height="3rem"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='ANALISIS DE SECRECION URETRAL'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">EXAMEN EN FRESCO</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS EPITELIALES" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEUCOCITOS" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HEMATIES" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS CLAVE" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEVADURAS" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PARASITOS" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACTERIAS" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="KOH" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PH" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d10"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">

                      <div class="col-6 col-sm-12">TINCION DE GRAM</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACILOS GRAM POSITIVO" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACILOS GRAM NEGATIVO" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOS GRAM POSITIVO" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOBASILOS GRAM POSITIVO" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOBACILOS GRAM NEGATIVO" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ESPORAS E HIFAS MICOTICAS" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d17"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='ANALISIS DE SECRECION VAGINAL'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">EXAMEN EN FRESCO</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS EPITELIALES" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEUCOCITOS" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HEMATIES" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS CLAVE" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEVADURAS" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PARASITOS" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACTERIAS" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="KOH" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PH" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d10"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">TINCION DE GRAM</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACILOS GRAM POSITIVO" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACILOS GRAM NEGATIVO" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOS GRAM POSITIVO" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOBASILOS GRAM POSITIVO" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOBACILOS GRAM NEGATIVO" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ESPORAS E HIFAS MICOTICAS" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d17"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='ANALISIS DE HECES'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">COPROPARASITOLOGICO</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ASPECTO DE LA MUESTRA" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COLOR" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS EPITELIALES" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEUCOCITOS" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HEMATIES" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ALMIDON" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEVADURAS" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="GRASAS" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PARASITOS" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="MOCO FECAL" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="Polimorfonucleares" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="Mononuclueares" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OTROS" v-model="laboratorio.d13"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">

                      <div class="col-6 col-sm-12">TINCION DE GRAM</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACILOS GRAM POSITIVOS" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACILOS GRAM NEGATIVOS" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOS GRAM POSITIVOS" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOS GRAM NEGATIVOS" v-model="laboratorio.d17"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOBACILOS GRAM" v-model="laboratorio.d18"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ESPORAS MICOTICAS" v-model="laboratorio.d19"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OTROS" v-model="laboratorio.d20"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>


              <template v-if="tipo.label=='COPROPARASITOLOGICO SIMPLE'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">COPROPARASITOLOGICO SIMPLE</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ASPECTO DE LA MUESTRA" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COLOR" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS EPITELIALES" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEUCOCITOS" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HEMATIES" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="GRASAS" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEVADURAS" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ESPORAS MICOTICAS" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ALMIDON" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PARASITOS" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PIOCITOS" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="MOCO FECAL" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="Polimorfonucleares" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="Mononuclueares" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d15"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">OTROS</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="SANGRE OCULTA EN HECES" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TEST DE HELICOBACTER EN HECES" v-model="laboratorio.d19"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TEST DE BENEDICT" v-model="laboratorio.d17"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d18"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='COPROPARASITOLOGICO SERIADO'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">COPROPARASITOLOGICO SERIADO</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ASPECTO DE LA MUESTRA" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COLOR" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS EPITELIALES" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEUCOCITOS" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HEMATIES" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="GRASAS" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEVADURAS" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ESPORAS MICOTICAS" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ALMIDON" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PARASITOS" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PIOCITOS" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="MOCO FECAL" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="Polimorfonucleares" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="Mononuclueares" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d15"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">OTROS</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="SANGRE OCULTA EN HECES" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TEST DE HELICOBACTER EN HECES" v-model="laboratorio.d19"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TEST DE BENEDICT" v-model="laboratorio.d17"/>
                      </div>
                      <div class="col-6 col-sm-10">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d18"/>
                      </div>
                      <div class="col-6 col-sm-2">
                        <q-select v-model="laboratorio.d20" dense outlined :options="['1','2','3']"
                                  label="Numero de muestras"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <!--             <template v-if="tipo.label=='COPROPARASITOLOGICO SERIADO'">-->
              <!--             <q-card class="my-card"  flat bordered style="width:100%">-->
              <!--            <q-card-section  class="bg-green-2"> <div class="row">-->
              <!--            <div class="col-6 col-sm-12">COPROPARASITOLOGICO SERIADO</div>-->
              <!--            <div class="col-6 col-sm-12">MUESTRA1</div>-->
              <!--            <div class="col-6 col-sm-6"><q-input dense outlined label="FECHA" v-model="laboratorio.d1" type="date"/></div>-->
              <!--            <div class="col-6 col-sm-6"><q-input dense outlined label="HORA" v-model="laboratorio.d2" type="time"/></div>-->
              <!--            <div class="col-6 col-sm-12"><q-editor v-model="laboratorio.d3" min-height="5rem" /></div>-->
              <!--            </div></q-card-section>-->
              <!--            <q-card-section  class="bg-blue-2"> <div class="row">-->
              <!--            <div class="col-6 col-sm-12">MUESTRA2</div>-->
              <!--            <div class="col-6 col-sm-6"><q-input dense outlined label="FECHA" v-model="laboratorio.d4" type="date"/></div>-->
              <!--            <div class="col-6 col-sm-6"><q-input dense outlined label="HORA" v-model="laboratorio.d5" type="time"/></div>-->
              <!--            <div class="col-6 col-sm-12"><q-editor v-model="laboratorio.d6" min-height="5rem" /></div>-->
              <!--            </div></q-card-section>-->
              <!--            <q-card-section  class="bg-amber-2"> <div class="row">-->
              <!--                      <div class="col-6 col-sm-12">MUESTRA3</div>-->
              <!--            <div class="col-6 col-sm-6"><q-input dense outlined label="FECHA" v-model="laboratorio.d7" type="date"/></div>-->
              <!--            <div class="col-6 col-sm-6"><q-input dense outlined label="HORA" v-model="laboratorio.d8" type="time"/></div>-->
              <!--            <div class="col-6 col-sm-12"><q-editor v-model="laboratorio.d9" min-height="5rem" /></div>-->
              <!--            </div></q-card-section>-->

              <!--            <q-card-section  class="bg-teal-2"> <div class="row">-->
              <!--            <div class="col-6 col-sm-12"><q-input dense outlined label="OBSERVACION" v-model="laboratorio.d10"   /></div>-->
              <!--            </div></q-card-section>-->
              <!--            <q-card-section  class="bg-red-2"> <div class="row">-->

              <!--            <div class="col-6 col-sm-6"><q-select dense outlined :options="usuarios" label="Responsable" v-model="user" required></q-select></div>-->

              <!--            <div class="col-6 col-sm-3"><q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma" /></div>-->
              <!--            <div class="col-6 col-sm-3"><q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma" /></div>-->
              <!--            </div></q-card-section>-->
              <!--                </q-card>-->
              <!--            </template>-->


              <template v-if="tipo.label=='PRUEBA DE COVID-19'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-12 col-sm-12">PRUEBA ANTICUERPOS CUANTITATIVOS ANTI SARS COV-2 lg M / lg G</div>
                      <div class=" col-sm-6">
                        <q-input dense outlined label="lgM" v-model="laboratorio.d1"/>
                      </div>
                      <div class=" col-sm-6">
                        <q-select :options="['NEGATIVO','INDETERMINADO','POSITIVO']" dense outlined label="referencia"
                                  v-model="laboratorio.d2"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class=" col-sm-6">
                        <q-input dense outlined label="lgG" v-model="laboratorio.d3"/>
                      </div>
                      <div class=" col-sm-6">
                        <q-select :options="['NEGATIVO','INDETERMINADO','POSITIVO']" dense outlined label="referencia"
                                  v-model="laboratorio.d4"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-amber-2">
                    <div class="row">
                      <div class=" col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d5"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class=" col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class=" col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class=" col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='LABORATORIO SEROLOGIA'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">SEROLOGIA</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Factor Reumatoide(Latex)" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Antiestreptolisina" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Proteina C Reactiva" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="RPR" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Prueba Rapida Sifilis" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Prueba Rapida VIH" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Hepatitis A" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Hepatitis B" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Hepatitis C" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Helicobacter Pylori en Sangre" v-model="laboratorio.d10"/>
                      </div><!--
            <div class="col-6 col-sm-12"><q-input dense outlined label="Helicobacter Pylori en Heces" v-model="laboratorio.d11" /></div>
            <div class="col-6 col-sm-12"><q-input dense outlined label="Troponina I" v-model="laboratorio.d12" /></div>
            <div class="col-6 col-sm-12"><q-input dense outlined label="PSA" v-model="laboratorio.d13" /></div>-->
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d14"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='RESULTADO SEROLOGIA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-12 col-sm-12">PRUEBA RAPIDA ANTIGENOS SARS COV 2</div>
                      <div class="col-12 col-sm-3"></div>
                      <div class="col-12 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="ANTIGENO SARS COV2"
                                  v-model="laboratorio.d1"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">

                    <div class="col-12 col-sm-12">
                      <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d2"/>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-12 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='INMUNOENSAYO DE FLUORESCENCIA'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">PRUEBA RAPIDA ANTIGENOS SARS COV 2</div>
                      <div class="col-6 col-sm-3"></div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="DIMEROS D" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="FERRITNA" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="IL-6" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PSA CUANTITATIVO" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PCR CUANTITATIVO" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TROPONINA I" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TROPONINA T" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="B - HCG" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PROCALCITONINA" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="lg M" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="lg G" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Anti-HBS" v-model="laboratorio.d11"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='TEST EMBARAZO'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">PRUEBA RAPIDA INMUNOCROMATOGRAFICA</div>
                      <div class="col-6 col-sm-3"></div>
                      <div class="col-6 col-sm-12">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="TEST EMBARAZO EN SANGRE"
                                  v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="F.U.M." type='date' v-model="laboratorio.d2"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d3"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='ALCOHOLEMIA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-3"></div>
                      <div class="col-6 col-sm-10 ">
                        <q-input dense outlined label="ALCOHOLEMIA" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-1 col-sm-1">mg/dl</div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='REACCION DE WIDAL'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">METODO AGLUTINACION DIRECTA</div>
                      <div class="col-6 col-sm-3">REACCION DE WIDAL</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="A:" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="B:" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="H:" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="O:" v-model="laboratorio.d4"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d5"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='MULTIDROGAS'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">METODO: PRUEBA RAPIDA INMUNOCROMATOTOGRAFICA DOA MULTI 6</div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Marihuana THC"
                                  v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Metil-N-dioximetanfetamina"
                                  v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Cocaina COC"
                                  v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Benzodiazepinas BZO"
                                  v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Amfetamina AMP"
                                  v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Metanfetamina MET"
                                  v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Morfina MOP"
                                  v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d8"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='HEMOGLOBINA GLICOSILADA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">HbA1c METODO INMUNOENSAYO DE FLUORESENCIA (FIA)</div>
                      <div class="col-6 col-sm-3"></div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HbA1c" v-model="laboratorio.d1"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='GASOMETRIA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-3"></div>
                      <div class="col-6 col-sm-3"></div>
                      <div class="col-12 col-sm-6">
                        <label for="">IMAGEN : </label>
                        <input type="file" @change="getImage">
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="GASOMETRIA ARTERIAL" v-model="laboratorio.d1"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='HEPATITIS B'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-3"></div>
                      <div class="col-6 col-sm-10">
                        <q-input dense outlined label="Anti-HBS" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-2">mlU/ml</div>
                      <div class="col-6 col-sm-12">
                        <q-select dense outlined v-model="laboratorio.d2"
                                  :options="['NEGATIVO','INDETERMINADO','POSITIVO']" label="VALOR"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='TOXOPLASMOSIS'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="lg M" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined v-model="laboratorio.d2"
                                  :options="['NEGATIVO','INDETERMINADO','POSITIVO']" label="VALOR"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="lg G" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined v-model="laboratorio.d4"
                                  :options="['NEGATIVO','INDETERMINADO','POSITIVO']" label="VALOR"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='HEMODIALISIS M Y B'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">CONTROL MENSUAL</div>

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Glicemia" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Creatinina" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Urea" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="NUS-BUN" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Transaminasas GOT" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Transaminasas GPT" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Sodio" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Cloro" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Potasio" v-model="laboratorio.d9"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">CONTROL BIMENSUAL</div>

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Acido Urico" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Fosfatasa Alcalina" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Calcio" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Fosforo" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Observaciones" v-model="laboratorio.d14"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>


              <template v-if="tipo.label=='HEMODIALISIS TRI'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">CONTROL TRIMESTRAL</div>

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Proteinas Totales" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Albumina" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Globulina" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Relacion A/G" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Trigliceridos" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Colesterol Total" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HDL-Col" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LDL-Col" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="VLDL-Col" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Hierro" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Prueba Rapida VIH" v-model="laboratorio.d11"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">

                      <div class="col-6 col-sm-12">CAPACIDAD DE FIJACION DE HIERRO</div>

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Capacidad Latente de Fijacion de Hierro"
                                 v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Capacidad Total de Fijacion de Hierro"
                                 v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Transferrina" v-model="laboratorio.d14"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-lime-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">INMUNOENSAYO DE FLUORESCENCIA</div>

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="FERRITINA" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="HEPATITIS B" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined v-model="laboratorio.d17"
                                  :options="['NEGATIVO','INDETERMINADO','POSITIVO']" label="VALOR"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Observaciones" v-model="laboratorio.d18"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='MISCELANEOS ORINA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ORINA EN 24 HORAS" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CREATINA EN SUERO" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CREATINA EN ORINA 24 HORAS" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CLEARENCE DE CREATININA" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PROTEINA EN ORINAL AL AZAR" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PROTEINURIA DE 24 HORAS" v-model="laboratorio.d6"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACION" v-model="laboratorio.d7"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='FIJACION DE HIERRO'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CAPACIDAD LATENTE DE FIJACION DE HIERRO"
                                 v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CAPACIDAD TOTAL DE FIJACION DE HIERRO" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TRANSFERRINA" v-model="laboratorio.d3"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACION" v-model="laboratorio.d4"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='CULTIVO Y ANTIBIOGRAMA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-12 ">EXAMEN DIRECTO</div>
                      <div class="col-12">
                        <q-editor v-model="laboratorio.examendirecto" min-height="5rem"/>
                      </div>


                      <div class="col-12">TINCION DE GRAM</div>

                      <div class="col-12">
                        <q-editor v-model="laboratorio.tinciongram" min-height="5rem"/>
                      </div>

                      <div class="col-12">MICROORGANISMO IDENTIFICADO</div>

                      <div class="col-12">
                        <q-editor v-model="laboratorio.microorganizmo" min-height="5rem"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-select dense square outlined v-model="antibiotico" :options="antibioticos"
                                  label="Antibiotico"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-select dense square outlined v-model="resultado"
                                  :options="['Resistente','Sensible','Intermedio']" label="interpretacion"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-btn color='green' icon="control_point" dense @click="agregarDetalle"/>
                      </div>
                      <div class="col-12">
                        <q-table title="ANTIBIOGRAMA" :rows="detalle" :columns="colAntibiotico" row-key="name" dense>
                          <template v-slot:body-cell-op="props">
                            <q-td key="op" :props="props">
                              <q-btn color="red" icon='delete' dense @click="deleteDetalle(props.row,props.pageIndex)"/>
                            </q-td>
                          </template>
                        </q-table>

                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACION" v-model="laboratorio.observacion"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='INMUNOLOGIA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-4 q-pa-xs">
                        <q-select dense square outlined v-model="antibiotico" :options="antibioticos" label="Ensayo"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-input dense square outlined v-model="resultado" label="Resultado"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-btn color='green' icon="control_point" dense @click="agregarDetalle"/>
                      </div>
                      <div class="col-12">
                        <q-table title="" :rows="detalle" :columns="colAntibiotico" row-key="name" dense>
                          <template v-slot:body-cell-op="props">
                            <q-td key="op" :props="props">
                              <q-btn color="red" icon='delete' dense @click="deleteDetalle(props.row,props.pageIndex)"/>
                            </q-td>
                          </template>
                          <template v-slot:body-cell-descripcion="props">
                            <q-td key="op" :props="props">
                              <div v-html="props.row.descripcion"></div>
                            </q-td>
                          </template>
                        </q-table>

                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACION" v-model="laboratorio.observacion"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='Q SANGUINEA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-4 q-pa-xs">
                        <q-select dense square outlined v-model="antibiotico" :options="antibioticos" label="Prueba"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-input dense square outlined v-model="resultado" label="Resultado"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-btn color='green' icon="control_point" dense @click="agregarDetalle"/>
                      </div>
                      <div class="col-12">
                        <q-table title="" :rows="detalle" :columns="colAntibiotico" row-key="name" dense>
                          <template v-slot:body-cell-op="props">
                            <q-td key="op" :props="props">
                              <q-btn color="red" icon='delete' dense @click="deleteDetalle(props.row,props.pageIndex)"/>
                            </q-td>
                          </template>
                          <template v-slot:body-cell-descripcion="props">
                            <q-td key="op" :props="props">
                              <div v-html="props.row.descripcion"></div>
                            </q-td>
                          </template>
                        </q-table>

                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACION" v-model="laboratorio.observacion"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='PLOMO EN SANGRE'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-sm-12 col-md-12">METODO : Automatizado</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="Indice Biologico de Exposicion" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-sm-2 col-md-4">ng/ml</div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-12 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='INMUNOCROMATOGRAFIA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-sm-12 col-md-12">METODO : INMUNOCROMATOGRAFIA</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-select dense outlined label="INFLUENZA A" v-model="laboratorio.d1"
                                  :options="['NEGATIVO','POSITIVO']"/>
                      </div>
                      <div class="col-sm-2 col-md-4">NEGATIVO</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-select dense outlined label="INFLUENZA B" v-model="laboratorio.d2"
                                  :options="['NEGATIVO','POSITIVO']"/>
                      </div>
                      <div class="col-sm-2 col-md-4">NEGATIVO</div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-12 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='QUIMICAS'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-sm-12 col-md-12">METODO : OXIDASA/PEROXIDASA</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="Glucusa en ayunas" v-model="laboratorio.d1" step="0.01"
                                 type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4">mg/dl</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="Glucusa en a los 30 min" v-model="laboratorio.d2" step="0.01"
                                 type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4">mg/dl</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="Glucusa en a los 60 min" v-model="laboratorio.d3" step="0.01"
                                 type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4">mg/dl</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="Glucusa en a los 90 min" v-model="laboratorio.d4" step="0.01"
                                 type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4">mg/dl</div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-12 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='INMUNOLOGIA ENA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-sm-12 col-md-12">METODO ELISA</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="RNP/Sm" v-model="laboratorio.d1" step="0.01" type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4"></div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="SM" v-model="laboratorio.d2" step="0.01" type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4"></div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="JO-1" v-model="laboratorio.d3" step="0.01" type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4"></div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="SCI-70" v-model="laboratorio.d4" step="0.01" type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="SSA" v-model="laboratorio.d5" step="0.01" type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="BBS" v-model="laboratorio.d6" step="0.01" type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4"></div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-12 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <div class="col-12">
                <q-btn label="Guardar" type="submit" class="full-width" icon="add_circle" color="positive"
                       :loading="loading"/>
              </div>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="dialogmodlab" full-width full-height>
      <q-card>
        <q-card-section>
          <div class="row">
            <div class="col-5">
              <div class="text-subtitle2">{{ paciente.paciente }}</div>
            </div>
            <div class="col-7">
              <q-select dense outlined :options="tipos" label="Laboratorio" v-model="tipo" disable readonly></q-select>
            </div>
          </div>
        </q-card-section>
        <q-separator/>
        <q-card-section>
          <q-form @submit.prevent="modLaboratorio">
            <div class="row">
              <div class="col-12 col-sm-4">
                <q-select dense outlined :options="doctors" label="Doctor" v-model="doctor" @filter="filterFn" use-input
                          input-debounce="0">
                  <template v-slot:no-option>
                    <q-item>
                      <q-item-section class="text-grey">
                        No results
                      </q-item-section>
                    </q-item>
                  </template>
                </q-select>
              </div>

              <div class="col-12 col-sm-4">
                <q-input dense outlined label="Tipo Muestra" v-model="laboratorio.tipomuestra" list="listmuestra"/>
                <datalist id="listmuestra">
                  <option v-for="(film,index) in listmuestra" :key="index">{{ film }}</option>
                </datalist>
              </div>
              <div class="col-12 col-sm-4">
                <q-input dense outlined label="N Solicitud" v-model="laboratorio.solicitud" type="number"/>
              </div>
              <template v-if="tipo.label=='HEMOGRAMA COMPLETO'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Globulos rojos" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Tiempo de cuagulacion" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Hemoglobina" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Tiempo de sangria" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Hematocrito" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Tiempo de Protrombina" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="V.E.S." v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="% Actividad" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="V.C.M." v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="INR" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Hb.C.M." v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="APTT" v-model="laboratorio.d34"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="C.Hb.C.M." v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Grupo Factor" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Globulos Blancos" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Reticulocitos" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Plaquetas" v-model="laboratorio.d17"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="IPR" v-model="laboratorio.d16"/>
                      </div>

                      <div class="col-6 col-sm-6"></div>
                    </div>
                  </q-card-section>
                  <q-separator/>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Cayados" v-model="laboratorio.d18" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="x10^9/L" v-model="laboratorio.d19"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Neutrofilos" v-model="laboratorio.d20" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="x10^9/L" v-model="laboratorio.d21"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Eosinofilos" v-model="laboratorio.d22" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="x10^9/L" v-model="laboratorio.d23"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Basofilos" v-model="laboratorio.d24" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="x10^9/L" v-model="laboratorio.d25"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Linfocitos" v-model="laboratorio.d26" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="x10^9/L" v-model="laboratorio.d27"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Monocitos" v-model="laboratorio.d28" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="x10^9/L" v-model="laboratorio.d29"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="BLASTOS" v-model="laboratorio.d30" @keyup1="valcien"
                                 type="number"/>
                      </div>
                      <div v-if="es100" class="col-12 text-red text-bold q-pa-xs">
                        No suman 100%
                      </div>
                    </div>
                  </q-card-section>
                  <q-separator/>
                  <q-card-section class="bg-amber-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Serie Rojas" v-model="laboratorio.d31"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Serie Blancas" v-model="laboratorio.d32"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Serie Plaquetarias" v-model="laboratorio.d33"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>


                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>

              </template>
              <template v-if="tipo.label=='QUIMICA SANGUINIA'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Glicemia" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Fosfatasa alcalina" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Creatinina" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="GGT" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Urea" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Transamisas GOT" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="NUS-BUN" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Transamisas GPT" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Acido Urico" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Proteinas Totales" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Trigliceridos" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Albumina" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Colesterol Total" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Globulina" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="HDL-Col" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Amilasa" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="LDL-Col." v-model="laboratorio.d17"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Lipasa" v-model="laboratorio.d18"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="VLDL" v-model="laboratorio.d32"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Bilirrubina Total" v-model="laboratorio.d19"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Sodio" v-model="laboratorio.d20"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Bilirrubina Directa" v-model="laboratorio.d21"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Cloro" v-model="laboratorio.d22"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Bilirrubina Indirecta" v-model="laboratorio.d23"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Potasio" v-model="laboratorio.d24"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="CPK TOTAL" v-model="laboratorio.d33"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Calcio" v-model="laboratorio.d26"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="CK-MB" v-model="laboratorio.d25"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Magnesio" v-model="laboratorio.d28"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="LDH" v-model="laboratorio.d27"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Fosforo" v-model="laboratorio.d30"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Hierro" v-model="laboratorio.d29"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-amber-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Observaciones" v-model="laboratorio.d31"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>

                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='EXAMEN GENERAL DE ORINA'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Color" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Proteinas" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Olor" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Glucosa" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Aspecto" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="C cetonicos" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Espuma" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Bilirrubina" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Deposito" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Hemoglogina" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Densidad" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Urobilina" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Reaccion" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Nitritos" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-12 col-sm-12"></div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Celulas epiteleales" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Hialino" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Celulas de transicion" v-model="laboratorio.d17"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Granuloso" v-model="laboratorio.d18"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Celulas clave" v-model="laboratorio.d19"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Epiteliales" v-model="laboratorio.d20"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Leucocitos" v-model="laboratorio.d21"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Eritrocitario" v-model="laboratorio.d22"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Eritrocis" v-model="laboratorio.d23"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Leucocitario" v-model="laboratorio.d24"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Bacterias" v-model="laboratorio.d25"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Cereos" v-model="laboratorio.d26"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Mixtos" v-model="laboratorio.d27"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-amber-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Uratros amorfos" v-model="laboratorio.d28"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Fosfato amorfo" v-model="laboratorio.d29"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Filamento mucoso" v-model="laboratorio.d30"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Oxalato de calcio" v-model="laboratorio.d31"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Piocitos" v-model="laboratorio.d32"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Fosfato de calcio" v-model="laboratorio.d33"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Levaduras" v-model="laboratorio.d34"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Acido Urico" v-model="laboratorio.d35"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="Esporas micoticas" v-model="laboratorio.d36"/>
                      </div>

                      <div class="col-6 col-sm-12">
                        <div>Observaciones</div>
                        <q-editor v-model="laboratorio.d37" min-height="3rem"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='ANALISIS DE SECRECION URETRAL'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">EXAMEN EN FRESCO</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS EPITELIALES" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEUCOCITOS" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HEMATIES" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS CLAVE" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEVADURAS" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PARASITOS" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACTERIAS" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="KOH" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PH" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d10"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">

                      <div class="col-6 col-sm-12">TINCION DE GRAM</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACILOS GRAM POSITIVO" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACILOS GRAM NEGATIVO" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOS GRAM POSITIVO" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOBASILOS GRAM POSITIVO" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOBACILOS GRAM NEGATIVO" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ESPORAS E HIFAS MICOTICAS" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d17"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='ANALISIS DE SECRECION VAGINAL'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">EXAMEN EN FRESCO</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS EPITELIALES" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEUCOCITOS" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HEMATIES" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS CLAVE" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEVADURAS" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PARASITOS" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACTERIAS" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="KOH" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PH" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d10"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">TINCION DE GRAM</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACILOS GRAM POSITIVO" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACILOS GRAM NEGATIVO" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOS GRAM POSITIVO" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOBASILOS GRAM POSITIVO" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOBACILOS GRAM NEGATIVO" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ESPORAS E HIFAS MICOTICAS" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d17"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='ANALISIS DE HECES'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">EXAMEN EN FRESCO</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ASPECTO DE LA MUESTRA" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COLOR" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS EPITELIALES" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEUCOCITOS" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HEMATIES" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ALMIDON" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEVADURAS" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="GRASAS" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PARASITOS" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="MOCO FECAL" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="Polimorfonucleares" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="Mononuclueares" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OTROS" v-model="laboratorio.d13"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">

                      <div class="col-6 col-sm-12">TINCION DE GRAM</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACILOS GRAM POSITIVOS" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="BACILOS GRAM NEGATIVOS" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOS GRAM POSITIVOS" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOS GRAM NEGATIVOS" v-model="laboratorio.d17"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COCOBACILOS GRAM" v-model="laboratorio.d18"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ESPORAS MICOTICAS" v-model="laboratorio.d19"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OTROS" v-model="laboratorio.d20"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>


              <template v-if="tipo.label=='COPROPARASITOLOGICO SIMPLE'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">COPROPARASITOLOGICO SIMPLE</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ASPECTO DE LA MUESTRA" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COLOR" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS EPITELIALES" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEUCOCITOS" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HEMATIES" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="GRASAS" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEVADURAS" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ESPORAS MICOTICAS" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ALMIDON" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PARASITOS" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PIOCITOS" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="MOCO FECAL" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="Polimorfonucleares" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="Mononuclueares" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d15"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">OTROS</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="SANGRE OCULTA EN HECES" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TEST DE HELICOBACTER EN HECES" v-model="laboratorio.d19"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TEST DE BENEDICT" v-model="laboratorio.d17"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d18"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='COPROPARASITOLOGICO SERIADO'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">COPROPARASITOLOGICO SERIADO</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ASPECTO DE LA MUESTRA" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="COLOR" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CELULAS EPITELIALES" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEUCOCITOS" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HEMATIES" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="GRASAS" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LEVADURAS" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ESPORAS MICOTICAS" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ALMIDON" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PARASITOS" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PIOCITOS" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="MOCO FECAL" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="Polimorfonucleares" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-4">
                        <q-input dense outlined label="Mononuclueares" v-model="laboratorio.d14"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d15"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">OTROS</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="SANGRE OCULTA EN HECES" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TEST DE HELICOBACTER EN HECES" v-model="laboratorio.d19"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TEST DE BENEDICT" v-model="laboratorio.d17"/>
                      </div>
                      <div class="col-6 col-sm-10">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d18"/>
                      </div>
                      <div class="col-6 col-sm-2">
                        <q-select label="Numero Muestra" v-model="laboratorio.d20" dense outlined
                                  :options="['1','2','3']"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <!--             <template v-if="tipo.label=='COPROPARASITOLOGICO SERIADO'">-->
              <!--             <q-card class="my-card"  flat bordered style="width:100%">-->
              <!--            <q-card-section  class="bg-green-2"> <div class="row">-->
              <!--            <div class="col-6 col-sm-12">COPROPARASITOLOGICO SERIADO</div>-->
              <!--            <div class="col-6 col-sm-12">MUESTRA1</div>-->
              <!--            <div class="col-6 col-sm-6"><q-input dense outlined label="FECHA" v-model="laboratorio.d1" type="date"/></div>-->
              <!--            <div class="col-6 col-sm-6"><q-input dense outlined label="HORA" v-model="laboratorio.d2" type="time"/></div>-->
              <!--            <div class="col-6 col-sm-12"><q-editor v-model="laboratorio.d3" min-height="5rem" /></div>-->
              <!--            </div></q-card-section>-->
              <!--            <q-card-section  class="bg-blue-2"> <div class="row">-->
              <!--            <div class="col-6 col-sm-12">MUESTRA2</div>-->
              <!--            <div class="col-6 col-sm-6"><q-input dense outlined label="FECHA" v-model="laboratorio.d4" type="date"/></div>-->
              <!--            <div class="col-6 col-sm-6"><q-input dense outlined label="HORA" v-model="laboratorio.d5" type="time"/></div>-->
              <!--            <div class="col-6 col-sm-12"><q-editor v-model="laboratorio.d6" min-height="5rem" /></div>-->
              <!--            </div></q-card-section>-->
              <!--            <q-card-section  class="bg-amber-2"> <div class="row">-->
              <!--                      <div class="col-6 col-sm-12">MUESTRA3</div>-->
              <!--            <div class="col-6 col-sm-6"><q-input dense outlined label="FECHA" v-model="laboratorio.d7" type="date"/></div>-->
              <!--            <div class="col-6 col-sm-6"><q-input dense outlined label="HORA" v-model="laboratorio.d8" type="time"/></div>-->
              <!--            <div class="col-6 col-sm-12"><q-editor v-model="laboratorio.d9" min-height="5rem" /></div>-->
              <!--            </div></q-card-section>-->

              <!--            <q-card-section  class="bg-teal-2"> <div class="row">-->
              <!--            <div class="col-6 col-sm-12"><q-input dense outlined label="OBSERVACION" v-model="laboratorio.d10"   /></div>-->
              <!--            </div></q-card-section>-->
              <!--            <q-card-section  class="bg-red-2"> <div class="row">-->

              <!--            <div class="col-6 col-sm-6"><q-select dense outlined :options="usuarios" label="Responsable" v-model="user" required></q-select></div>-->

              <!--            <div class="col-6 col-sm-3"><q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma" /></div>-->
              <!--            <div class="col-6 col-sm-3"><q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma" /></div>-->
              <!--            <div class="col-6 col-sm-3"><q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp" /></div>-->
              <!--            </div></q-card-section>-->
              <!--                </q-card>-->
              <!--            </template>-->


              <template v-if="tipo.label=='PRUEBA DE COVID-19'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-12 col-sm-12">PRUEBA ANTICUERPOS CUANTITATIVOS ANTI SARS COV-2 lg M / lg G</div>
                      <div class=" col-sm-6">
                        <q-input dense outlined label="lgM" v-model="laboratorio.d1"/>
                      </div>
                      <div class=" col-sm-6">
                        <q-select :options="['NEGATIVO','INDETERMINADO','POSITIVO']" dense outlined label="referencia"
                                  v-model="laboratorio.d2"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class=" col-sm-6">
                        <q-input dense outlined label="lgG" v-model="laboratorio.d3"/>
                      </div>
                      <div class=" col-sm-6">
                        <q-select :options="['NEGATIVO','INDETERMINADO','POSITIVO']" dense outlined label="referencia"
                                  v-model="laboratorio.d4"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-amber-2">
                    <div class="row">
                      <div class=" col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d5"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class=" col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class=" col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class=" col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='LABORATORIO SEROLOGIA'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">SEROLOGIA</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Factor Reumatoide(Latex)" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Antiestreptolisina" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Proteina C Reactiva" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="RPR" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Prueba Rapida Sifilis" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Prueba Rapida VIH" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Hepatitis A" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Hepatitis B" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Hepatitis C" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Helicobacter Pylori en Sangre" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Helicobacter Pylori en Heces" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Troponina I" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PSA" v-model="laboratorio.d13"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d14"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='RESULTADO SEROLOGIA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-12 col-sm-12">PRUEBA RAPIDA ANTIGENOS SARS COV 2</div>
                      <div class="col-12 col-sm-3"></div>
                      <div class="col-12 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="ANTIGENO SARS COV2"
                                  v-model="laboratorio.d1"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">

                    <div class="col-12 col-sm-12">
                      <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d2"/>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-12 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='INMUNOENSAYO DE FLUORESCENCIA'">
                <q-card class="my-card" flat bordered>
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">PRUEBA RAPIDA ANTIGENOS SARS COV 2</div>
                      <div class="col-6 col-sm-3"></div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="DIMEROS D" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="FERRITNA" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="IL-6" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PSA CUANTITATIVO" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PCR CUANTITATIVO" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TROPONINA I" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TROPONINA T" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="B - HCG" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PROCALCITONINA" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="lg M" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="lg G" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Anti-HBS" v-model="laboratorio.d11"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='TEST EMBARAZO'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">PRUEBA RAPIDA INMUNOCROMATOGRAFICA</div>
                      <div class="col-6 col-sm-3"></div>
                      <div class="col-6 col-sm-12">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="TEST EMBARAZO EN SANGRE"
                                  v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="F.U.M." type='date' v-model="laboratorio.d2"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d3"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='ALCOHOLEMIA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-3"></div>
                      <div class="col-6 col-sm-10 ">
                        <q-input dense outlined label="ALCOHOLEMIA" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-1 col-sm-1">mg/dl</div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='REACCION DE WIDAL'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">METODO AGLUTINACION DIRECTA</div>
                      <div class="col-6 col-sm-3">REACCION DE WIDAL</div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="A:" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="B:" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="H:" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="O:" v-model="laboratorio.d4"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d5"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='MULTIDROGAS'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">METODO: PRUEBA RAPIDA INMUNOCROMATOTOGRAFICA DOA MULTI 6</div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Marihuana THC"
                                  v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Metil-N-dioximetanfetamina"
                                  v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Cocaina COC"
                                  v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Benzodiazepinas BZO"
                                  v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Amfetamina AMP"
                                  v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Metanfetamina MET"
                                  v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                      <div class="col-6 col-sm-6">
                        <q-select :options="['POSITIVO','NEGATIVO']" dense outlined label="Morfina MOP"
                                  v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-6"></div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACIONES" v-model="laboratorio.d8"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='HEMOGLOBINA GLICOSILADA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">HbA1c METODO INMUNOENSAYP DE FLUORESENCIA (FIA)</div>
                      <div class="col-6 col-sm-3"></div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HbA1c" v-model="laboratorio.d1"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='GASOMETRIA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="GASOMETRIA ARTERIAL" v-model="laboratorio.d1"/>
                      </div>

                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='HEPATITIS B'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-3"></div>
                      <div class="col-6 col-sm-10">
                        <q-input dense outlined label="Anti-HBS" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-2">mlU/ml</div>
                      <div class="col-6 col-sm-12">
                        <q-select dense outlined v-model="laboratorio.d2"
                                  :options="['NEGATIVO','INDETERMINADO','POSITIVO']" label="VALOR"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='TOXOPLASMOSIS'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="lg M" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined v-model="laboratorio.d2"
                                  :options="['NEGATIVO','INDETERMINADO','POSITIVO']" label="VALOR"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="lg G" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined v-model="laboratorio.d4"
                                  :options="['NEGATIVO','INDETERMINADO','POSITIVO']" label="VALOR"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='HEMODIALISIS M Y B'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">CONTROL MENSUAL</div>

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Glicemia" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Creatinina" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Urea" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="NUS-BUN" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Transaminasas GOT" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Transaminasas GPT" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Sodio" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Cloro" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Potasio" v-model="laboratorio.d9"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">CONTROL BIMENSUAL</div>

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Acido Urico" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Fosfatasa Alcalina" v-model="laboratorio.d11"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Calcio" v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Fosforo" v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Observaciones" v-model="laboratorio.d14"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='HEMODIALISIS TRI'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">CONTROL TRIMESTRAL</div>

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Proteinas Totales" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Albumina" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Globulina" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Relacion A/G" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Trigliceridos" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Colesterol Total" v-model="laboratorio.d6"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="HDL-Col" v-model="laboratorio.d7"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="LDL-Col" v-model="laboratorio.d8"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="VLDL-Col" v-model="laboratorio.d9"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Hierro" v-model="laboratorio.d10"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Prueba Rapida VIH" v-model="laboratorio.d11"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">CAPACIDAD DE FIJACION DE HIERRO</div>

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Capacidad Latente de Fijacion de Hierro"
                                 v-model="laboratorio.d12"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Capacidad Total de Fijacion de Hierro"
                                 v-model="laboratorio.d13"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Transferrina" v-model="laboratorio.d14"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-lime-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">INMUNOENSAYO DE FLUORESCENCIA</div>

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="FERRITINA" v-model="laboratorio.d15"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-input dense outlined label="HEPATITIS B" v-model="laboratorio.d16"/>
                      </div>
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined v-model="laboratorio.d17"
                                  :options="['NEGATIVO','INDETERMINADO','POSITIVO']" label="VALOR"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="Observaciones" v-model="laboratorio.d18"/>
                      </div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>


              <template v-if="tipo.label=='MISCELANEOS ORINA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="ORINA EN 24 HORAS" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CREATINA EN SUERO" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CREATINA EN ORINA 24 HORAS" v-model="laboratorio.d3"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CLEARENCE DE CREATININA" v-model="laboratorio.d4"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PROTEINA EN ORINAL AL AZAR" v-model="laboratorio.d5"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="PROTEINURIA DE 24 HORAS" v-model="laboratorio.d6"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">

                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACION" v-model="laboratorio.d7"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='FIJACION DE HIERRO'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CAPACIDAD LATENTE DE FIJACION DE HIERRO"
                                 v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="CAPACIDAD TOTAL DE FIJACION DE HIERRO" v-model="laboratorio.d2"/>
                      </div>
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="TRANSFERRINA" v-model="laboratorio.d3"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACION" v-model="laboratorio.d4"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <template v-if="tipo.label=='CULTIVO Y ANTIBIOGRAMA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-12 ">EXAMEN DIRECTO</div>
                      <div class="col-12">
                        <q-editor v-model="laboratorio.examenDirecto" min-height="5rem"/>
                      </div>


                      <div class="col-12">TINCION DE GRAM</div>

                      <div class="col-12">
                        <q-editor v-model="laboratorio.tincionGram" min-height="5rem"/>
                      </div>

                      <div class="col-12">MICROORGANISMO IDENTIFICADO</div>

                      <div class="col-12">
                        <q-editor v-model="laboratorio.microorganizmo" min-height="5rem"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-select dense square outlined v-model="antibiotico" :options="antibioticos"
                                  label="Antibiotico"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-select dense square outlined v-model="resultado"
                                  :options="['Resistente','Sensible','Intermedio']" label="interpretacion"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-btn color='green' icon="control_point" dense @click="agregarDetalle"/>
                      </div>
                      <div class="col-12">
                        <q-table title="ANTIBIOGRAMA" :rows="detalle" :columns="colAntibiotico" row-key="name" dense>
                          <template v-slot:body-cell-op="props">
                            <q-td key="op" :props="props">
                              <q-btn color="red" icon='delete' dense @click="deleteDetalle(props.row,props.pageIndex)"/>
                            </q-td>
                          </template>
                        </q-table>

                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACION" v-model="laboratorio.observacion"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='INMUNOLOGIA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-4 q-pa-xs">
                        <q-select dense square outlined v-model="antibiotico" :options="antibioticos" label="Ensayo"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-input dense square outlined v-model="resultado" label="Resultado"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-btn color='green' icon="control_point" dense @click="agregarDetalle"/>
                      </div>
                      <div class="col-12">
                        <q-table title="" :rows="detalle" :columns="colAntibiotico" row-key="name" dense>
                          <template v-slot:body-cell-op="props">
                            <q-td key="op" :props="props">
                              <q-btn color="red" icon='delete' dense @click="deleteDetalle(props.row,props.pageIndex)"/>
                            </q-td>
                          </template>
                          <template v-slot:body-cell-descripcion="props">
                            <q-td key="op" :props="props">
                              <div v-html="props.row.descripcion"></div>
                            </q-td>
                          </template>
                        </q-table>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACION" v-model="laboratorio.observacion"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='Q SANGUINEA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-4 q-pa-xs">
                        <q-select dense square outlined v-model="antibiotico" :options="antibioticos" label="Prueba"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-input dense square outlined v-model="resultado" label="Resultado"/>
                      </div>
                      <div class="col-4 q-pa-xs">
                        <q-btn color='green' icon="control_point" dense @click="agregarDetalle"/>
                      </div>
                      <div class="col-12">
                        <q-table title="" :rows="detalle" :columns="colAntibiotico" row-key="name" dense>
                          <template v-slot:body-cell-op="props">
                            <q-td key="op" :props="props">
                              <q-btn color="red" icon='delete' dense @click="deleteDetalle(props.row,props.pageIndex)"/>
                            </q-td>
                          </template>
                          <template v-slot:body-cell-descripcion="props">
                            <q-td key="op" :props="props">
                              <div v-html="props.row.descripcion"></div>
                            </q-td>
                          </template>
                        </q-table>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-blue-2">
                    <div class="row">
                      <div class="col-6 col-sm-12">
                        <q-input dense outlined label="OBSERVACION" v-model="laboratorio.observacion"/>
                      </div>
                    </div>
                  </q-card-section>
                  <q-card-section class="bg-red-2">
                    <div class="row">
                      <div class="col-6 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>


              <template v-if="tipo.label=='PLOMO EN SANGRE'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-sm-12 col-md-12">METODO : Automatizado</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="Indice Biologico de Exposicion" v-model="laboratorio.d1"/>
                      </div>
                      <div class="col-sm-2 col-md-4">ng/ml</div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-12 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='INMUNOCROMATOGRAFIA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-sm-12 col-md-12">METODO : INMUNOCROMATOGRAFIA</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-select dense outlined label="INFLUENZA A" v-model="laboratorio.d1"
                                  :options="['NEGATIVO','POSITIVO']"/>
                      </div>
                      <div class="col-sm-2 col-md-4">NEGATIVO</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="INFLUENZA B" v-model="laboratorio.d2"
                                 :options="['NEGATIVO','POSITIVO']"/>
                      </div>
                      <div class="col-sm-2 col-md-4">NEGATIVO</div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-12 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='QUIMICAS'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-sm-12 col-md-12">METODO : OXIDASA/PEROXIDASA</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="Glucusa en ayunas" v-model="laboratorio.d1" step="0.01"
                                 type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4">mg/dl</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="Glucusa en a los 30 min" v-model="laboratorio.d2" step="0.01"
                                 type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4">mg/dl</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="Glucusa en a los 60 min" v-model="laboratorio.d3" step="0.01"
                                 type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4">mg/dl</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="Glucusa en a los 90 min" v-model="laboratorio.d4" step="0.01"
                                 type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4">mg/dl</div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-12 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>
              <template v-if="tipo.label=='INMUNOLOGIA ENA'">
                <q-card class="my-card" flat bordered style="width:100%">
                  <q-card-section class="bg-green-2">
                    <div class="row">
                      <div class="col-sm-12 col-md-12">METODO ELISA</div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="RNP/Sm" v-model="laboratorio.d1" step="0.01" type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4"></div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="SM" v-model="laboratorio.d2" step="0.01" type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4"></div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="JO-1" v-model="laboratorio.d3" step="0.01" type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4"></div>
                      <div class="col-sm-12 col-md-12"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="SCI-70" v-model="laboratorio.d4" step="0.01" type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="SSA" v-model="laboratorio.d5" step="0.01" type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4"></div>
                      <div class="col-sm-10 col-md-8">
                        <q-input dense outlined label="BBS" v-model="laboratorio.d6" step="0.01" type="number"/>
                      </div>
                      <div class="col-sm-2 col-md-4"></div>
                    </div>
                  </q-card-section>

                  <q-card-section class="bg-red-2">
                    <div class="row">

                      <div class="col-12 col-sm-6">
                        <q-select dense outlined :options="usuarios" label="Responsable" v-model="user"
                                  required></q-select>
                      </div>

                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Fecha toma" type="date" v-model="laboratorio.fechatoma"/>
                      </div>
                      <div class="col-12 col-sm-3">
                        <q-input dense outlined label="Hora Toma" type="time" v-model="laboratorio.horatoma"/>
                      </div>
                      <div class="col-6 col-sm-3">
                        <q-input dense outlined label="Fecha Entrega" type="date" v-model="laboratorio.fechaimp"/>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </template>

              <div class="col-12">
                <q-btn label="Modificar" type="submit" class="full-width" icon="add_circle" color="positive"
                       :loading='loading'/>
              </div>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <div id="myelement" class="hidden"></div>
    <!--    <canvas id="glucoseChart" width="400" height="400" class=""></canvas>-->
  </q-page>
</template>
<script>
import {date} from "quasar";
import {jsPDF} from "jspdf";
import $ from 'jquery'
import moment from 'moment'
import {Printd} from 'printd'
import Chart from "chart.js/auto";

export default {
  data() {
    return {
      colAntibiotico: [
        {name: 'op', label: 'OP', field: 'op'},
        {name: 'antibiotico', label: 'Ensayo', field: 'nombre'},
        {name: 'interpretacion', label: 'INTERPRETACION', field: 'resultado'},
        {name: 'rangoMin', label: 'RANGO MIN', field: 'rangoMin'},
        {name: 'rangoMax', label: 'RANGO MAX', field: 'rangoMax'},
        {name: 'descripcion', label: 'DESCRIPCION', field: 'descripcion'},
      ],
      current: 1,
      last_page: 0,
      dialoglaboratorio: false,
      dialoglistlabo: false,
      dialogmodlab: false,
      dialogmodimg: false,
      dialog_mod: false,
      filter: '',
      alert: false,
      dato: {fechanac: date.formatDate(new Date(), 'YYYY-MM-DD')},
      fechalab: (moment().subtract(90, 'days').format("YYYY-MM-DD")),
      datos2: {},
      pacientes: [],
      paciente: {},
      paciente2: {},
      tipos: [],
      tipo: {},
      seguros: [],
      seguro: {},
      doctors: [],
      doctor: {},
      filterdoc: [],
      user: {},
      caledad: '',
      resultado: '',
      usuarios: [],
      laboratorios: [],
      laboratorios2: [],
      laboratorio: {
        tipomuestra: 'COMPLETA',
        fechatoma: date.formatDate(new Date(), 'YYYY-MM-DD'),
        horatoma: date.formatDate(new Date(), 'HH:mm'),
        d1: '',
        d2: '',
        d3: '',
        d4: '',
        d5: '',
        d6: '',
        d7: '',
        d8: '',
        d9: '',
        d10: '',
        d11: '',
        d12: '',
        d13: '',
        d14: '',
        d15: '',
        d16: '',
        d17: '',
        d18: '',
        d19: '',
        d20: '',
        d21: '',
        d22: '',
        d23: '',
        d24: '',
        d25: '',
        d26: '',
        d27: '',
        d28: '',
        d29: '',
        d30: '',
        d31: '',
        d32: '',
        d33: '',
        d34: '',
        d35: '',
        d36: '',
        d37: '',
        d38: '',
        d39: '',
        d40: '',
        d41: '',
        d42: '',
        d43: '',
        d44: '',
        d45: '',
        responsable: '',
        tipo_id: '',
        paciente_id: '',
        user_id: this.$store.state.login.user.id,
        doctor_id: '',
      },
      cultivo: {},
      fechacalculo: '',
      url: process.env.API,
      listmuestra: [],
      labmod: {},
      loading: false,
      antibioticos: [],
      antibiotico: {label: ''},
      detalle: [],
      detalle2: [],
      imagen: null,
      columspaciente: [
        {name: 'opciones', field: 'opciones', label: 'opciones', align: 'center'},
        {name: 'paciente', field: 'paciente', label: 'paciente', align: 'left'},
        {name: 'laboratorios', field: 'laboratorios', label: 'laboratorios', align: 'left'},
        {name: 'celular', field: 'celular', label: 'celular'},
        {name: 'seguro', field: 'seguro', label: 'seguro'},
        {name: 'edad', field: 'edad', label: 'edad'},
      ]
    }
  },
  mounted() {
    //this.sanguinea();
    this.listusers();
    this.muestras();
  },

  created() {
    this.listseguro();
    this.embarazo;
    this.mispacientes()
    this.$axios.get(process.env.API + '/tipo').then(res => {
      this.tipos = []
      res.data.forEach(r => {
        let d = r
        d.label = r.nombre
        this.tipos.push(d)
      })
      this.tipo = this.tipos[0]
    })
    this.$axios.get(process.env.API + '/listdoctor').then(res => {
      this.doctors = []
      res.data.forEach(r => {
        let d = r
        d.label = r.nombre + ' ' + r.paterno + ' ' + r.materno + ' - ' + r.especialidad
        this.doctors.push(d)
      })
      this.filterdoc = this.doctors
      this.doctor = {label: ''}
    })
  },
  computed: {

    calcular() {
      if (this.dato.fechanac == null || this.dato.fechanac == '' || this.dato.fechanac == undefined)
        return ''
      else {
        var a = moment();
        var b = moment(this.dato.fechanac);

        var years = a.diff(b, 'year');
        b.add(years, 'years');

        var months = a.diff(b, 'months');
        b.add(months, 'months');

        var days = a.diff(b, 'days');

        if (years == 0) {
          if (months <= 1) {
            if (days <= 1) {
              return months + ' MES ' + days + ' DIA'
            } else {
              return months + ' MES ' + days + ' DIAS'
            }
          } else {
            if (days <= 1) {
              return months + ' MESES ' + days + ' DIA'
            } else {
              return months + ' MESES ' + days + ' DIAS'
            }
          }

        } else {
          if (years == 1) {
            return years + ' AÑO'
          } else {
            return years + ' AÑOS'
          }
        }
      }
    },
    calcular2() {
      if (this.datos2.fechanac == null || this.datos2.fechanac == '' || this.datos2.fechanac == undefined)
        return ''
      else {
        var a = moment();
        var b = moment(this.datos2.fechanac);

        var years = a.diff(b, 'year');
        b.add(years, 'years');

        var months = a.diff(b, 'months');
        b.add(months, 'months');

        var days = a.diff(b, 'days');

        if (years == 0) {
          if (months <= 1) {
            if (days <= 1) {
              return months + ' MES ' + days + ' DIA'
            } else {
              return months + ' MES ' + days + ' DIAS'
            }
          } else {
            if (days <= 1) {
              return months + ' MESES ' + days + ' DIA'
            } else {
              return months + ' MESES ' + days + ' DIAS'
            }
          }

        } else {
          if (years == 1) {
            return years + ' AÑO'
          } else {
            return years + ' AÑOS'
          }
        }
      }

    },

    es100() {
      if (
        (
          parseInt(this.laboratorio.d18 == '' ? 0 : this.laboratorio.d18) +
          parseInt(this.laboratorio.d20 == '' ? 0 : this.laboratorio.d20) +
          parseInt(this.laboratorio.d22 == '' ? 0 : this.laboratorio.d22) +
          parseInt(this.laboratorio.d24 == '' ? 0 : this.laboratorio.d24) +
          parseInt(this.laboratorio.d26 == '' ? 0 : this.laboratorio.d26) +
          parseInt(this.laboratorio.d28 == '' ? 0 : this.laboratorio.d28) +
          parseInt(this.laboratorio.d30 == '' ? 0 : this.laboratorio.d30)
        ) == 100
      ) {
        return false;
      } else {
        return true;
      }
    }
  },
  methods: {
    laboratorioClick(p) {
      this.dialoglistlabo = true;
      this.paciente2 = p;
      this.laboratorios = []
      this.consultarLab()
    },
    deleteDetalle(det, pageIndex) {
      this.detalle.splice(pageIndex, 1)
    },
    deleteDetalle2(det, pageIndex) {
      this.detalle2.splice(pageIndex, 1)
    },
    cargarAntibiotico(tipo) {
      this.antibioticos = []
      this.$axios.get(process.env.API + '/listAntib/' + tipo).then(res => {
        console.log(res.data)
        res.data.forEach(r => {
          r.label = r.nombre
          this.antibioticos.push(r)
        })
      })
    },
    agregarDetalle() {
      if (this.antibiotico.id == undefined || this.resultado == '')
        return false
      this.antibiotico.resultado = this.resultado
      this.detalle.push(this.antibiotico)
      this.antibiotico = {label: ''}
      this.resultado = ''
      console.log(this.detalle)
    },
    agregarDetalle2() {
      if (this.antibiotico.id == undefined || this.resultado == '')
        return false
      this.antibiotico.resultado = this.resultado
      this.detalle2.push(this.antibiotico)
      this.antibiotico = {label: ''}
      this.resultado = ''
      console.log(this.detalle)
    },
    consultarLab() {
      // if(this.fechalab==null || this.fechalab==undefined){
      //   return false
      // }
      this.loading = true
      this.laboratorios = []
      this.$axios.post(process.env.API + '/listLabo', {fecha: this.fechalab, id: this.paciente2.id}).then(res => {
        // console.log(res.data)
        this.laboratorios = res.data
        this.$axios.post(process.env.API + '/listCultivo', {fecha: this.fechalab, id: this.paciente2.id}).then(res => {
          this.laboratorios = this.laboratorios.concat(res.data)
          this.$axios.post(process.env.API + '/listImmunologia', {
            fecha: this.fechalab,
            id: this.paciente2.id
          }).then(res => {
            res.data.forEach(r => {
              r.tipoLabo = 'INMUNOLOGIA'
            });
            this.laboratorios = this.laboratorios.concat(res.data)
            this.$axios.post(process.env.API + '/listSanguinea', {
              fecha: this.fechalab,
              id: this.paciente2.id
            }).then(res => {
              res.data.forEach(r => {
                r.tipoLabo = 'Q SANGUINEA'
              });
              this.laboratorios = this.laboratorios.concat(res.data)
              this.loading = false
            })
          })
        })
      })
    },
    consultarCult() {
      if (this.fechalab == null || this.fechalab == undefined) {
        return false
      }
      this.laboratorios = []
      this.$axios.post(process.env.API + '/listCultivo', {fecha: this.fechalab, id: this.paciente2.id}).then(res => {
        // console.log(res.data)
        this.laboratorios2 = res.data
      })
    },
    muestras() {
      this.listmuestra = []
      this.$axios.post(process.env.API + '/listmuestra').then(res => {
        console.log(res.data)
        res.data.forEach(r => {
          this.listmuestra.push(r.tipomuestra)

        });

      })

    },
    calcular3(fechanac) {
      if (fechanac == null || fechanac == '' || fechanac == undefined)
        return ''
      else {
        var a = moment();
        var b = moment(fechanac);

        var years = a.diff(b, 'year');
        b.add(years, 'years');

        var months = a.diff(b, 'months');
        b.add(months, 'months');

        var days = a.diff(b, 'days');

        if (years == 0) {
          if (months <= 1) {
            if (days <= 1) {
              return months + ' MES ' + days + ' DIA'
            } else {
              return months + ' MES ' + days + ' DIAS'
            }
          } else {
            if (days <= 1) {
              return months + ' MESES ' + days + ' DIA'
            } else {
              return months + ' MESES ' + days + ' DIAS'
            }
          }

        } else {
          if (years == 1) {
            return years + ' AÑO'
          } else {
            return years + ' AÑOS'
          }
        }
      }
    },
    Whatsapp(celular) {
      window.open('https://wa.me/+591' + celular, '_blank');

    },
    sobre(p, l) {

      var doc = new jsPDF('p', 'cm', 'letter')
      doc.setFont("arial");
      doc.setFontSize(14);
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setTextColor(0, 0, 0)
      doc.text(p.paciente, x + 10.5, y + 5.4, 'left')
      doc.text(l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, x + 10.5, y + 6.3, 'left')
      doc.text(l.doctor.especialidad, x + 10.5, y + 7.2, 'left')
      doc.text(l.solicitud, x + 10.5, y + 8.1, 'left')
      window.open(doc.output('bloburl'), '_blank');


    },
    descargar(l) {
      var fileName = this.url + '/../imagenes/' + l.imagen;
      window.open(fileName, 'Download');
    },
    filterFn(val, update) {
      if (val === '') {
        update(() => {
          this.doctors = this.filterdoc

          // here you have access to "ref" which
          // is the Vue reference of the QSelect
        })
        return
      }

      update(() => {
        const needle = val.toLowerCase()
        this.doctors = this.filterdoc.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
      })
    },
    upimagen(l) {
      this.imagen = ''
      this.labmod = l
      this.dialogmodimg = true

    },

    datformulario(cl, labo) {
      // console.log(cl)
      // console.log(labo)
      this.tipo = labo.tipo
      this.paciente = cl
      this.doctor = labo.doctor
      this.doctor.label = this.doctor.nombre + ' ' + this.doctor.paterno + ' ' + this.doctor.materno + ' - ' + this.doctor.especialidad
      this.tipo.label = this.tipo.nombre
      this.laboratorio = labo
      this.user = this.laboratorio.responsable
      if (this.tipo.id == 25 || this.tipo.id == 26) {
        this.cargarAntibiotico('INMUNOLOGIA');
        this.detalle = this.laboratorio.antibioticos
        this.detalle.forEach(r => {
          r.resultado = r.pivot.resultado
        });
      }
      this.dialogmodlab = true
    },
    modLaboratorio() {
      this.laboratorio.tipo_id = this.tipo.id
      //this.laboratorio.paciente_id=this.paciente.id
      this.laboratorio.doctor_id = this.doctor.id
      this.laboratorio.user_id = this.$store.state.login.user.id
      this.laboratorio.responsable = this.user
      if (this.tipo.label == 'CULTIVO Y ANTIBIOGRAMA') {
        this.laboratorio.antibiograma = this.detalle
        this.loading = true
        this.$axios.put(process.env.API + '/cultivo/' + this.laboratorio.id, this.laboratorio).then(res => {
          console.log(res.data)
          this.consultarLab()
          this.mispacientes()
          this.dialogmodlab = false
          this.resetlabo()
          this.muestras()
          this.loading = false
        })
        return false
      }
      if (this.tipo.label == 'INMUNOLOGIA') {
        this.laboratorio.antibiograma = this.detalle
        this.loading = true
        this.$axios.put(process.env.API + '/inmunologia/' + this.laboratorio.id, this.laboratorio).then(res => {
          this.dialogmodlab = false
          this.consultarLab()
          //console.log(res.data)
          //return false
          this.mispacientes()
          this.resetlabo()
          this.muestras()
          this.loading = false
        })
        return false
      }
      if (this.tipo.label == 'Q SANGUINEA') {
        this.laboratorio.antibiograma = this.detalle
        this.loading = true
        this.$axios.put(process.env.API + '/sanguinea/' + this.laboratorio.id, this.laboratorio).then(res => {
          this.dialogmodlab = false
          this.consultarLab()
          //console.log(res.data)
          //return false
          this.mispacientes()
          this.resetlabo()
          this.muestras()
          this.loading = false
        })
        return false
      }
      if (this.tipo.label == 'HEMOGRAMA COMPLETO') {
        if (this.laboratorio.d18 != '' ||
          this.laboratorio.d20 != '' ||
          this.laboratorio.d22 != '' ||
          this.laboratorio.d24 != '' ||
          this.laboratorio.d26 != '' ||
          this.laboratorio.d28 != '' ||
          this.laboratorio.d30 != '') {
          let total = 0
          if (this.es100) {
            this.$q.notify({
              message: 'No suma el 100 % ',
              icon: 'info',
              color: 'red'
            })
            return false;
          }
        }
      }
      this.loading = true
      this.$axios.put(process.env.API + '/laboratorio/' + this.laboratorio.id, this.laboratorio).then(res => {
        // console.log(res.data)
        this.mispacientes()
        this.consultarLab()
        this.dialogmodlab = false
        this.resetlabo()
        this.loading = false
      })
    },
    resetlabo() {
      console.log('tipo ' + this.tipo.label);

      this.doctor = {label: ''}
      this.user = ''
      this.imagen = null
      switch (this.tipo.label) {
        case 'CULTIVO Y ANTIBIOGRAMA':
          this.detalle = []
          this.interpretacion = ''
          this.laboratorio = {
            tipomuestra: '',
            fechatoma: date.formatDate(new Date(), 'YYYY-MM-DD'),
            horatoma: date.formatDate(new Date(), 'HH:mm'),
            examendirecto: '',
            tinciongram: '',
            microorganizmo: '',
            solicitud: '',
            responsable: '',
            tipo_id: '',
            paciente_id: '',
            user_id: this.$store.state.login.user.id,
            doctor_id: '',
          }
          this.cargarAntibiotico('CULTIVO');
          break;
        case 'INMUNOLOGIA':
          this.detalle = []
          this.interpretacion = ''
          this.laboratorio = {
            tipomuestra: '',
            fechatoma: date.formatDate(new Date(), 'YYYY-MM-DD'),
            horatoma: date.formatDate(new Date(), 'HH:mm'),
            // examendirecto:'',
            // tinciongram:'',
            // microorganizmo:'',
            solicitud: '',
            responsable: '',
            tipo_id: '',
            paciente_id: '',
            user_id: this.$store.state.login.user.id,
            doctor_id: '',
          }
          this.cargarAntibiotico('INMUNOLOGIA');
          break;
        case 'Q SANGUINEA':
          this.detalle = []
          this.interpretacion = ''
          this.laboratorio = {
            tipomuestra: '',
            fechatoma: date.formatDate(new Date(), 'YYYY-MM-DD'),
            horatoma: date.formatDate(new Date(), 'HH:mm'),
            // examendirecto:'',
            // tinciongram:'',
            // microorganizmo:'',
            solicitud: '',
            responsable: '',
            tipo_id: '',
            paciente_id: '',
            user_id: this.$store.state.login.user.id,
            doctor_id: '',
          }
          this.cargarAntibiotico('SANGUINEO');
          break;
        case 'EXAMEN GENERAL DE ORINA':
          this.laboratorio = {
            tipomuestra: '',
            fechatoma: date.formatDate(new Date(), 'YYYY-MM-DD'),
            horatoma: date.formatDate(new Date(), 'HH:mm'),
            d1: 'AMARILLO',
            d2: 'NEGATIVO',
            d3: 'SUIGENERIS',
            d4: 'NEGATIVO',
            d5: 'LIMPIDO',
            d6: 'NEGATIVO',
            d7: 'BLANCO FUGAZ',
            d8: 'NEGATIVO',
            d9: 'NULO',
            d10: 'NEGATIVO',
            d11: '1.025',
            d12: 'NORMAL',
            d13: 'pH 5.0',
            d14: 'NEGATIVO',
            d15: '',
            d16: 'NEGATIVO',
            d17: '',
            d18: 'NEGATIVO',
            d19: '',
            d20: 'NEGATIVO',
            d21: '',
            d22: 'NEGATIVO',
            d23: '',
            d24: 'NEGATIVO',
            d25: '',
            d26: 'NEGATIVO',
            d27: 'NEGATIVO',
            d28: '',
            d29: '',
            d30: '',
            d31: '',
            d32: '',
            d33: '',
            d34: '',
            d35: '',
            d36: '',
            d37: '',
            d38: '',
            d39: '',
            d40: '',
            d41: '',
            d42: '',
            d43: '',
            d44: '',
            d45: '',
            solicitud: '',
            responsable: '',
            tipo_id: '',
            paciente_id: '',
            user_id: this.$store.state.login.user.id,
            doctor_id: '',
          }
          break;
        case "ANALISIS DE HECES":
          this.laboratorio = {
            tipomuestra: '',
            fechatoma: date.formatDate(new Date(), 'YYYY-MM-DD'),
            horatoma: date.formatDate(new Date(), 'HH:mm'),
            d1: 'Heces Pastosa',
            d2: 'Marron',
            d3: 'No se Observa',
            d4: 'No se Observa',
            d5: 'No se Observa',
            d6: 'No se Observa',
            d7: 'No se Observa',
            d8: 'No se Observa',
            d9: 'No se Observa Formas Parasitarias',
            d10: 'NEGATIVO',
            d11: '',
            d12: '',
            d13: '',
            d14: 'Moderada Cantidad',
            d15: 'Moderada Cantidad',
            d16: 'Escasa Cantidad',
            d17: 'Esporadica Cantidad',
            d18: 'No se Observa',
            d19: 'No se Observa',
            d20: '',
            d21: '',
            d22: '',
            d23: '',
            d24: '',
            d25: '',
            d26: '',
            d27: '',
            d28: '',
            d29: '',
            d30: '',
            d31: '',
            d32: '',
            d33: '',
            d34: '',
            d35: '',
            d36: '',
            d37: '',
            d38: '',
            d39: '',
            d40: '',
            d41: '',
            d42: '',
            d43: '',
            d44: '',
            d45: '',
            responsable: '',
            tipo_id: '',
            paciente_id: '',
            user_id: this.$store.state.login.user.id,
            doctor_id: '',
          }
          break;
        case "COPROPARASITOLOGICO SIMPLE":
          this.laboratorio = {
            tipomuestra: '',
            fechatoma: date.formatDate(new Date(), 'YYYY-MM-DD'),
            horatoma: date.formatDate(new Date(), 'HH:mm'),
            d1: 'Heces Pastosa',
            d2: 'Cafe',
            d3: 'No se Observa',
            d4: 'No se Observa',
            d5: 'No se Observa',
            d6: 'No se Observa',
            d7: 'No se Observa',
            d8: 'No se Observa',
            d9: 'No se Observa',
            d10: 'No se Observa Formas Parasitarias Intestinales',
            d11: 'No se Observa',
            d12: '',
            d13: '',
            d14: '',
            d15: '',
            d16: '',
            d17: '',
            d18: '',
            d19: '',
            d20: '',
            d21: '',
            d22: '',
            d23: '',
            d24: '',
            d25: '',
            d26: '',
            d27: '',
            d28: '',
            d29: '',
            d30: '',
            d31: '',
            d32: '',
            d33: '',
            d34: '',
            d35: '',
            d36: '',
            d37: '',
            d38: '',
            d39: '',
            d40: '',
            d41: '',
            d42: '',
            d43: '',
            d44: '',
            d45: '',
            responsable: '',
            tipo_id: '',
            paciente_id: '',
            user_id: this.$store.state.login.user.id,
            doctor_id: '',
          }
          break;
        case "COPROPARASITOLOGICO SERIADO":
          this.laboratorio = {
            tipomuestra: '',
            fechatoma: date.formatDate(new Date(), 'YYYY-MM-DD'),
            horatoma: date.formatDate(new Date(), 'HH:mm'),
            d1: '',
            d2: '',
            d3: 'NO SE OBSERVA',
            d4: 'NO SE OBSERVA',
            d5: 'NO SE OBSERVA',
            d6: 'NO SE OBSERVA',
            d7: 'NO SE OBSERVA',
            d8: 'NO SE OBSERVA',
            d9: 'NO SE OBSERVA',
            d10: 'NO SE OBSERVA FORMAS PARASITARIAS',
            d11: 'NO SE OBSERVA',
            d12: '',
            d13: '',
            d14: '',
            d15: '',
            d16: '',
            d17: '',
            d18: '',
            d19: '',
            d20: '',
            d21: '',
            d22: '',
            d23: '',
            d24: '',
            d25: '',
            d26: '',
            d27: '',
            d28: '',
            d29: '',
            d30: '',
            d31: '',
            d32: '',
            d33: '',
            d34: '',
            d35: '',
            d36: '',
            d37: '',
            d38: '',
            d39: '',
            d40: '',
            d41: '',
            d42: '',
            d43: '',
            d44: '',
            d45: '',
            responsable: '',
            tipo_id: '',
            paciente_id: '',
            user_id: this.$store.state.login.user.id,
            doctor_id: '',
          }
          break;
        default:

          this.laboratorio = {
            tipomuestra: '',
            fechatoma: date.formatDate(new Date(), 'YYYY-MM-DD'),
            horatoma: date.formatDate(new Date(), 'HH:mm'),
            d1: '',
            d2: '',
            d3: '',
            d4: '',
            d5: '',
            d6: '',
            d7: '',
            d8: '',
            d9: '',
            d10: '',
            d11: '',
            d12: '',
            d13: '',
            d14: '',
            d15: '',
            d16: '',
            d17: '',
            d18: '',
            d19: '',
            d20: '',
            d21: '',
            d22: '',
            d23: '',
            d24: '',
            d25: '',
            d26: '',
            d27: '',
            d28: '',
            d29: '',
            d30: '',
            d31: '',
            d32: '',
            d33: '',
            d34: '',
            d35: '',
            d36: '',
            d37: '',
            d38: '',
            d39: '',
            d40: '',
            d41: '',
            d42: '',
            d43: '',
            d44: '',
            d45: '',
            responsable: '',
            tipo_id: '',
            paciente_id: '',
            user_id: this.$store.state.login.user.id,
            doctor_id: '',

          }
          break;
      }
    },
    // seriado(p,l){
    //  var doc = new jsPDF('P',undefined,'legal')
    //  doc.setFont("arial");
    //  doc.setFontSize(10);
    //  var img = new Image()
    //  img.src = 'img/natividad.jpeg'
    //  doc.addImage(img, 'jpg', 5, 2, 70, 20)
    //  let x=0
    //  let y=0
    //  //inicio datos paciete
    //  doc.setDrawColor(120);
    //  doc.rect(x+5, y+27, 205, 20)
    //  doc.setFont(undefined, 'bold')
    //  doc.setTextColor(57,73,171)
    //  doc.text(['SERVICIO DE LABORATORIO','Bolivar N°753 entre Arica e Iquique','Telf: 5254721 Fax: 52-83667','Emergencia las 24 horas del dia.'],x+175, y+8,'center')
    //  doc.setTextColor(195,47,47)
    //  doc.text('N Registro CODEDLAB 000045',x+150, y+25)
    //  doc.setTextColor(211,47,47)
    //  doc.text('Form. 008',x+190, y+30)
    //  doc.setTextColor(57,73,171)
    //  doc.text('COPROPARASITOLOGICO SERIADO',x+100, y+30,'center')
    //  doc.text(['PACIENTE','REQUERIDO POR','TIPO MUESTRA'],x+6, y+35)
    //  doc.setTextColor(0,0,0)
    //  doc.setFont(undefined, 'normal')
    //  doc.text([p.paciente,l.doctor.nombre+' '+l.doctor.paterno+' ' +l.doctor.materno,l.tipomuestra],x+70, y+35,'center')
    //  doc.setTextColor(57,73,171)
    //  doc.setFont(undefined, 'bold')
    //  doc.text(['EDAD','SEXO'],x+130, y+35)
    //  doc.setTextColor(211,47,47)
    //  doc.text('N PACIENTE',x+130, y+43)
    //  doc.setFont(undefined, 'normal')
    //  doc.setTextColor(0,0,0)
    //  let anio=''
    //  if(p.edad==null||p.edad==undefined||p.edad=='')
    //  anio=p.tiempo
    //  else anio=p.edad
    //  doc.text([anio+'',p.sexo,l.solicitud+''],x+160, y+35,'center')
    //  doc.setTextColor(57,73,171)
    //  //fin datos paciete
    //  //inicio datos
    //  doc.rect(x+5, y+48, 205, 85)
    //  doc.setFont(undefined, 'bold')
    //  doc.setTextColor(57,73,171)
    //  doc.text('                             COPROPARASITOLOGICO SERIADO',x+50,y+52)
    //  doc.setFont(undefined, 'bold')
    //  doc.text('     1º MUESTRA',x+8,y+58)
    //  doc.setFont(undefined, 'normal')
    //  doc.text(['Fecha','Hora'],x+8,y+65)
    //  doc.text([l.d1,l.d2],x+20,y+65)
    //  // doc.text(l.d3,x+50,y+58)
    //
    //      doc.setFont(undefined, 'bold')
    //  doc.text('     2º MUESTRA',x+8,y+80)
    //  doc.setFont(undefined, 'normal')
    //  doc.text(['Fecha','Hora'],x+8,y+85)
    //  doc.text([l.d4,l.d5],x+20,y+85)
    //  // doc.text(l.d6,x+50,y+80)
    //
    //      doc.setFont(undefined, 'bold')
    //  doc.text('     3º MUESTRA',x+8,y+100)
    //  doc.setFont(undefined, 'normal')
    //  doc.text(['Fecha','Hora'],x+8,y+105)
    //  doc.text([l.d7+'',l.d8+''],x+20,y+105)
    //  // doc.text(l.d9+'',x+50,y+100)
    //
    //  doc.setTextColor(57,73,171)
    //      doc.setFont(undefined, 'bold')
    //  doc.text('OBSERVACIONES',x+8,y+120)
    //  doc.setTextColor(0,0,0)
    //  doc.setFont(undefined, 'normal')
    //  doc.text(l.d10,x+8,y+125)
    //  doc.setTextColor(0,0,0)
    //
    //  doc.setFont(undefined, 'bold')
    //  doc.setTextColor(57,73,171)
    //  doc.setFont(undefined, 'normal')
    //  doc.setTextColor(0,0,0)
    //
    //  doc.rect(x+5, y+135, 205, 20)
    //  doc.rect(x+5, y+54, 205, 22)
    //  //doc.rect(x+5, y+75, 205, 25)
    //  doc.rect(x+5, y+96, 205, 20)
    //  doc.setFont(undefined, 'bold')
    //  doc.setTextColor(57,73,171)
    //  doc.text('RESPONSABLE',x+6,y+140)
    //  doc.setFont(undefined, 'NORMAL')
    //  doc.setTextColor(0,0,0)
    //  doc.text(l.responsable,x+8,y+145)
    //  doc.setFont(undefined, 'normal')
    //  doc.setTextColor(57,73,171)
    //  doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+140,'center')
    //  doc.setTextColor(0,0,0)
    //  if(l.fechaimp==null || l.fechaimp == undefined ) l.fechaimp =  moment()
    //  doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,moment(l.fechaimp).format("DD-MM-YYYY")],x+170,y+140,'left')
    //   // doc.text(l.d3,x+50,y+58)
    //   doc.html( '<div style="font-size: 3px;width: 500px">'+l.d3+'</div>' , {
    //     callback: function (doc) {
    //       doc.html( '<div style="font-size: 3px;width: 500px">'+l.d6+'</div>' , {
    //         callback: function (doc) {
    //           doc.html( '<div style="font-size: 3px;width: 500px">'+l.d9+'</div>' , {
    //             callback: function (doc) {
    //              window.open(doc.output('bloburl'), '_blank');
    //              // doc.output('save','COPROPARASITOLOGICO SERIADO-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    //             },
    //             x: x+50,
    //             y: y+95,
    //           })
    //         },
    //         x: x+50,
    //         y: y+75,
    //       })
    //     },
    //     x: x+50,
    //     y: y+55,
    //   })
    //   // doc.save("Pago"+date.formatDate(Date.now(),'DD-MM-YYYY')+".pdf");
    //   // window.open(doc.output('bloburl'), '_blank');
    //  //$( '#docpdf' ).attr('src', doc.output('datauristring'));
    //  //  window.open(doc.output('bloburl'), '_blank');
    //  },
    seriado(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 007', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('COPROPARASITOLOGICO SERIADO', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 48, 205, 86)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('                             COPROPARASITOLOGICO SERIADO', x + 50, y + 52)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text('NUMERO DE MUESTRA #' + l.d20, x + 160, y + 52)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(57, 73, 171)
      doc.text(['ASPECTO DE LA MUESTRA', 'COLOR', 'CELULAS EPITELIALES', 'LEUCOCITOS', 'HEMATIES', 'GRASAS', 'LEVADURAS', 'ESPORAS MICOTICAS', 'ALMIDON', 'PARASITOS', 'PIOCITOS'], x + 8, y + 58)
      doc.setTextColor(0, 0, 0)
      doc.text([l.d1, l.d2, l.d3, l.d4, l.d5, l.d6, l.d7, l.d8, l.d9, l.d10, l.d11], x + 70, y + 58)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('MOCO FECAL       ', x + 8, y + 102)
      if (l.d12 != '' && l.d12 != undefined && l.d12 != null) {
        doc.setTextColor(0, 0, 0)
        doc.setFont(undefined, 'normal')
        doc.text(l.d12, x + 50, y + 102)
      }

      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['Polimorfonuclueares: ', 'Mononuclueras: '], x + 100, y + 102)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([l.d13, l.d14], x + 145, y + 102)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('OBSERVACIONES', x + 8, y + 110)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d15, x + 70, y + 110)

      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('                                        OTROS', x + 50, y + 115)
      doc.setFont(undefined, 'normal')

      doc.text(['SANGRE OCULTA EN HECES', 'TEST DE HELICOBACTER EN HECES', 'TEST DE BENEDICT', 'OBSERVACIONES'], x + 8, y + 120)
      doc.setTextColor(0, 0, 0)
      doc.text([l.d16, l.d19, l.d17, l.d18], x + 70, y + 120)

      doc.rect(x + 5, y + 135, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 140)
      doc.setFont(undefined, 'NORMAL')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 8, y + 145)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(57, 73, 171)
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 140, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 140, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      // doc.output('save','COPROPARASITOLOGICO SIMPLE-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      window.open(doc.output('bloburl'), '_blank');
    },

    labserologia(p, l) {

      var doc = new jsPDF('landscape', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 195, 3, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 195, y + 27, 155, 23)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 320, y + 10, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 225, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 010', x + 323, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('SEROLOGIA', x + 270, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 196, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 268, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 305, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 305, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 337, y + 35, 'center')
      doc.setTextColor(211, 47, 47)
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 195, y + 53, 155, 130)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(211, 47, 47)
      doc.text('', x + 233, y + 55)
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10);
      doc.text('                   VALOR REF             METODO DE PRUEBA', x + 260, y + 60)
      doc.setFontSize(12);

      doc.setFont(undefined, 'normal')
      doc.setFontSize(11);
      //doc.text(['Factor Reumatoide ','    (Latex)','Antiestreptolisina',' ','Proteina C Reactiva','','RPR','','Prueba Rapida Sifilis ','','Prueba Rapida VIH ',' ','Hepatitis A ',' ','Hepatitis B ',' ','Hepatitis C  ',' ','Helicobacter Pylori ','   en Sangre','Helicobacter Pylori ','    en Heces' ,'Troponina I ','','PSA','','OBSERVACIONES'],x+200,y+65,'left')
      doc.text(['Factor Reumatoide ', '    (Latex)', 'Antiestreptolisina', ' ', 'Proteina C Reactiva', '', 'RPR', '', 'Prueba Rapida Sifilis ', '', 'Prueba Rapida VIH ', ' ', 'Hepatitis A ', ' ', 'Hepatitis B ', ' ', 'Hepatitis C  ', ' ', 'Helicobacter Pylori ', '   en Sangre', ' ', '    ', '', '', '', '', 'OBSERVACIONES'], x + 200, y + 65, 'left')
      doc.setTextColor(0, 0, 0)
      let regex = /^[0-9]+([.][0-9]+)?$/;

      if (l.d1 != '' && regex.test(l.d1))
        doc.text(l.d1 + ' UI/ml', x + 240, y + 65, 'left')
      else
        doc.text(l.d1, x + 240, y + 65, 'left')
      if (l.d2 != '' && regex.test(l.d2))
        doc.text(l.d2 + ' UI/l', x + 240, y + 73, 'left')
      else
        doc.text(l.d2, x + 240, y + 73, 'left')
      if (l.d3 != '' && regex.test(l.d3))
        doc.text(l.d3 + ' mg/dl', x + 240, y + 82, 'left')
      else
        doc.text(l.d3, x + 240, y + 82, 'left')
      doc.text(l.d4, x + 240, y + 91, 'left')
      doc.text(l.d5, x + 240, y + 101, 'left')
      doc.text(l.d6, x + 240, y + 110, 'left')
      doc.text(l.d7, x + 240, y + 119, 'left')
      doc.text(l.d8, x + 240, y + 128, 'left')
      doc.text(l.d9, x + 240, y + 136, 'left')
      doc.text(l.d10, x + 240, y + 146, 'left')
      doc.text(l.d11, x + 240, y + 156, 'left')
      //doc.text(l.d12,x+240,y+164,'left')
      // if(l.d13!='' && regex.test(l.d13))
      //  doc.text(l.d13+' ng/ml',x+240,y+172,'left')
      // else
      //    doc.text(l.d13,x+240,y+172,'left')
      doc.text(l.d14, x + 240, y + 181, 'left')

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
      doc.text('Hasta 8 UI/ml ', x + 290, y + 65, 'center')
      doc.text('Hasta 200 UI/l', x + 290, y + 74, 'center')
      doc.text('Hasta 0.6 mg/dl ', x + 290, y + 83, 'center')
      doc.text('No Reactivo', x + 290, y + 92, 'center')
      doc.text('No Reactivo', x + 290, y + 101, 'center')
      doc.text('No Reactivo', x + 290, y + 110, 'center')
      doc.text('NEGATIVO / POSITIVO ', x + 290, y + 119, 'center')
      doc.text('NEGATIVO / POSITIVO ', x + 290, y + 128, 'center')
      doc.text('NEGATIVO / POSITIVO ', x + 290, y + 137, 'center')
      doc.text('NEGATIVO / POSITIVO ', x + 290, y + 140, 'center')
      //doc.text('NEGATIVO / POSITIVO ',x+290,y+155,'center')
      //doc.text('NEGATIVO / POSITIVO ',x+290,y+164,'center')
      //doc.text('Hasta 4 ng/ml',x+290,y+172,'center')
      doc.setFontSize(11);
      doc.setTextColor(57, 73, 171)
      //doc.text(['Aglutinacion directa','','Aglutinacion directa','','Aglutinacion directa','','Floculacion directa','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida I-croma','','Prueba Rapida ','Semicuantitativo'],x+312,y+65,'left')
      doc.text(['Aglutinacion directa', '', 'Aglutinacion directa', '', 'Aglutinacion directa', '', 'Floculacion directa', '', 'Prueba Rapida ', 'Inmunocromatografica', 'Prueba Rapida ', 'Inmunocromatografica', 'Prueba Rapida ', 'Inmunocromatografica', 'Prueba Rapida ', 'Inmunocromatografica', 'Prueba Rapida ', 'Inmunocromatografica', 'Prueba Rapida ', 'Inmunocromatografica', 'Prueba Rapida ', 'Inmunocromatografica', ' ', '', ' ', ''], x + 312, y + 65, 'left')
      doc.setFontSize(11);


      doc.rect(x + 195, y + 185, 155, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 205, y + 190, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 205, y + 195, 'left')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10);
      doc.setLineHeightFactor(1.5)
      doc.text(['Fecha toma de Muestra', 'Hora toma Muestra', 'Fecha Entrega de Resultado'], x + 280, y + 190, 'left')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 330, y + 190, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      // doc.output('save','LABORATORIO SEROLOGIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      window.open(doc.output('bloburl'), '_blank');
    },

    lgmserologia(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      var imgmas = new Image()
      var imgmenos = new Image()
      img.src = 'img/natividad.jpeg'
      imgmas.src = 'img/suma.JPEG'
      imgmenos.src = 'img/resta.JPEG'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 009', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('PRUEBA DE COVID-19', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 48, 205, 25)
      doc.rect(x + 5, y + 75, 205, 72)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('               PRUEBA ANTICUERPOS CUANTITATIVOS ANTI SARS COV-2 lg M / lg G', x + 50, y + 52)
      doc.text('      METODO: INMUNOENSAYO DE FLUORECENCIA (FIA)                                      Valor de Referencia', x + 8, y + 58)
      doc.setFont(undefined, 'normal')
      let vallggm1 = '', vallggm2 = '', obs1 = '', obs2 = ''
      if (parseFloat(l.d1) < 0.9 || parseFloat(l.d1) >= 1.1) {
        vallggm1 = l.d1
        obs1 = l.d2
      }
      if (parseFloat(l.d1) >= 0.9 && parseFloat(l.d1) < 1.1) {
        vallggm2 = l.d1
        obs2 = l.d2
      }
      doc.text(['lgM', '', 'lgG'], x + 30, y + 63)
      doc.setTextColor(0, 0, 0)
      doc.text([vallggm1, vallggm2], x + 70, y + 63)
      doc.text(l.d3, x + 70, y + 71)
      if (l.d2 == 'POSITIVO')
        doc.setTextColor(255, 0, 0)

      doc.text([obs1, obs2], x + 100, y + 63)
      doc.setTextColor(0, 0, 0)
      if (l.d4 == 'POSITIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d4, x + 100, y + 71)
      doc.setTextColor(0, 0, 0)

      doc.setTextColor(57, 73, 171)
      doc.text(['menor a 0.9 NEGATIVO PARA lgG/lgM', '0.9 menor igual & mayor 1.1 INDETERMINADO', 'mayor igual 1.1 POSITIVO lgG/lgM'], x + 170, y + 63, 'center')
      doc.setFont(undefined, 'bold')
      doc.text('                        INTERPRETACION DE RESULTADOS', x + 50, y + 80)


      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text('              lgM                lgG                         INTERPRETACION                          COMENTARIO', x + 5, y + 85)
      doc.setFont(undefined, 'normal')
      doc.addImage(imgmenos, 'jpeg', 20, 90, 5, 5)
      doc.addImage(imgmenos, 'jpeg', 40, 90, 5, 5)
      doc.addImage(imgmas, 'jpeg', 20, 95, 5, 5)
      doc.addImage(imgmenos, 'jpeg', 40, 95, 5, 5)
      doc.addImage(imgmas, 'jpeg', 20, 100, 5, 5)
      doc.addImage(imgmas, 'jpeg', 40, 100, 5, 5)
      doc.addImage(imgmenos, 'jpeg', 20, 105, 5, 5)
      doc.addImage(imgmas, 'jpeg', 40, 105, 5, 5)
      doc.setFont(undefined, 'normal')
      doc.setFontSize(9);

      doc.text(['>Ausencia de la Enfermedad o Falso Negativo', '>Inicio Temprano de ka Enfermedad Infeccion', '   Aguda de la Enfermedad', '>Fase activa de la Enfermedad', '>Inmunidad Fase final de la Infeccion', '  pasada y curada'], x + 50, y + 90, 'left')
      doc.text(['Paciente en fase de evolucion de la enfermedad mayor de 21 dias', 'Se debe repetir dentro de 5 a 7 dias la prueba', 'Correlacionar con clinica del paciente y realizar examenes ', '  complementarios (Rayos X, Tomografia)', 'Paciente en fase de evolucion de la enfermedad ', '  mayor a 21 dias'], x + 120, y + 90, 'left')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(9);
      doc.text(['NOTA: Las pruebas rapidas para COVID-19 NO SON CONFIRMATORIAS, en caso de salir positivo alguno de los anticuerpo , se recomienda', ' una segunda toma de muestra con la Tecnica de HISOPADO NASOFARINGEO para RT - PCR y asi confirmar su DIAGNOSTICO', '', 'Todas las personas producimos anticuerpos a diferente velocidad dependiendo del agente patogeno y nuestra genetica que es lo que determina la ', 'funcionalidad de nuestro Sistema Inmunologico',
        '', 'Los Resultados deben ser interpretados en funcion de la Clinica del paciente y dias de evolucion de la enfermedad'], x + 8, y + 115, 'left')

      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text('OBSERVACIONES', x + 8, y + 142)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d5, x + 8, y + 145)

      doc.rect(x + 5, y + 147, 205, 15)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 150)
      doc.setFont(undefined, 'NORMAL')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 8, y + 155)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(57, 73, 171)
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 150, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 150, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      //  doc.output('save','PRUEBA DE COVID-19-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      window.open(doc.output('bloburl'), '_blank');

    },
    simple(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 007', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('COPROPARASITOLOGICO SIMPLE', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 48, 205, 86)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('                             COPROPARASITOLOGICO SIMPLE', x + 50, y + 52)
      doc.setFont(undefined, 'normal')
      doc.text(['ASPECTO DE LA MUESTRA', 'COLOR', 'CELULAS EPITELIALES', 'LEUCOCITOS', 'HEMATIES', 'GRASAS', 'LEVADURAS', 'ESPORAS MICOTICAS', 'ALMIDON', 'PARASITOS', 'PIOCITOS'], x + 8, y + 58)
      doc.setTextColor(0, 0, 0)
      doc.text([l.d1, l.d2, l.d3, l.d4, l.d5, l.d6, l.d7, l.d8, l.d9, l.d10, l.d11], x + 70, y + 58)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('MOCO FECAL       ', x + 8, y + 102)
      if (l.d12 != '' && l.d12 != undefined && l.d12 != null) {
        doc.setTextColor(0, 0, 0)
        doc.setFont(undefined, 'normal')
        doc.text(l.d12, x + 50, y + 102)
      }

      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['Polimorfonuclueares: ', 'Mononuclueras: '], x + 100, y + 102)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([l.d13, l.d14], x + 145, y + 102)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('OBSERVACIONES', x + 8, y + 110)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d15, x + 70, y + 110)

      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('                                        OTROS', x + 50, y + 115)
      doc.setFont(undefined, 'normal')

      doc.text(['SANGRE OCULTA EN HECES', 'TEST DE HELICOBACTER EN HECES', 'TEST DE BENEDICT', 'OBSERVACIONES'], x + 8, y + 120)
      doc.setTextColor(0, 0, 0)
      doc.text([l.d16, l.d19, l.d17, l.d18], x + 70, y + 120)

      doc.rect(x + 5, y + 135, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 140)
      doc.setFont(undefined, 'NORMAL')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 8, y + 145)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(57, 73, 171)
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 140, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 140, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      // doc.output('save','COPROPARASITOLOGICO SIMPLE-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      window.open(doc.output('bloburl'), '_blank');
    },

    heces(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 006', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('ANALISIS DE HECES', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 48, 205, 93)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('                                          COPROPARASITOLOGICO', x + 50, y + 52)
      doc.setFont(undefined, 'normal')
      doc.text(['ASPECTO DE LA MUESTRA', 'COLOR', 'CELULAS EPITELIALES', 'LEUCOCITOS', 'HEMATIES', 'ALMIDON', 'LEVADURAS', 'GRASAS', 'PARASITOS'], x + 8, y + 58)
      doc.setTextColor(0, 0, 0)
      doc.text([l.d1, l.d2, l.d3, l.d4, l.d5, l.d6, l.d7, l.d8, l.d9], x + 70, y + 58)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('MOCO FECAL       ', x + 8, y + 95)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text(l.d10, x + 50, y + 95)
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['Polimorfonuclueares: ', 'Mononuclueras: '], x + 100, y + 95)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([l.d11, l.d12], x + 145, y + 95)

      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('OTROS', x + 8, y + 105)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d13, x + 70, y + 105)

      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('                                         TINCION DE GRAM', x + 50, y + 110)
      doc.setFont(undefined, 'normal')
      doc.text(['BACILOS GRAM POSITIVOS', 'BACILOS GRAM NEGATIVOS', 'COCOS GRAM POSITIVOS', 'COCOS GRAM NEGATIVOS', 'COCOBACILOS GRAM', 'ESPORAS MICOTICAS', 'OTROS'], x + 8, y + 115)
      doc.setTextColor(0, 0, 0)
      doc.text([l.d14, l.d15, l.d16, l.d17, l.d18, l.d19, l.d20], x + 70, y + 115)

      doc.rect(x + 5, y + 142, 205, 22)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 145)
      doc.setFont(undefined, 'NORMAL')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 8, y + 150)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(57, 73, 171)
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      //doc.output('save','ANALISIS DE HECES-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      window.open(doc.output('bloburl'), '_blank');
    },

    orina(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 17)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 003', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('EXAMEN GENERAL DE ORINA', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 44, 205, 94)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('      EX FISICO                  VALOR                         REFERENCIA                  EX QUIMICO                   VALOR              REFERENCIA', x + 6, y + 48)
      doc.setFont(undefined, 'normal')
      doc.text(['Color', 'Olor', 'Aspecto', 'Espuma', 'Deposito', 'Densidad', 'Reaccion'], x + 8, y + 54)
      doc.text(['Amarillo', 'Sui-generis', 'Limpido o lig opal', 'Blanco fugaz', 'Nulo o escaso', '1.010-1.030', 'Lig acida'], x + 90, y + 54, 'center')
      doc.text(['Proteinas', 'Glucosa', 'C cetonicos', 'Bilirrubina', 'Hemoglobina', 'Urobilina', 'Nitritos'], x + 110, y + 54, 'left')
      doc.text(['Negativo', 'Negativo', 'Negativo', 'Negativo', 'Negativo', 'Normal', 'Negativo'], x + 185, y + 54, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text([l.d1, l.d3, l.d5, l.d7, l.d9, l.d11, l.d13], x + 40, y + 54)
      doc.setTextColor(0, 0, 0)
      if (l.d2 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d2, x + 160, y + 54)
      doc.setTextColor(0, 0, 0)
      if (l.d4 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d4, x + 160, y + 58)
      doc.setTextColor(0, 0, 0)
      if (l.d6 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d6, x + 160, y + 62)
      doc.setTextColor(0, 0, 0)
      if (l.d8 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d8, x + 160, y + 66)
      doc.setTextColor(0, 0, 0)
      if (l.d10 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d10, x + 160, y + 70)
      doc.setTextColor(0, 0, 0)
      if (l.d12 != 'NORMAL')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d12, x + 160, y + 74)
      doc.setTextColor(0, 0, 0)
      if (l.d14 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d14, x + 160, y + 78)
      doc.setTextColor(57, 73, 171)
      doc.text('SEDIMENTO: EXAMEN MICROSCOPICO', x + 75, y + 83, 'center')

      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('          CELULAS                      VALOR                   REFERENCIA                   CILINDROS                     VALOR              REFERENCIA', x + 6, y + 86)
      doc.setFont(undefined, 'normal')
      doc.text(['Celulas epiteliales', 'Celulas de transicion', 'Celulas Clave', 'Leucocitos', 'Eritrocitos', 'Bacterias', 'CRISTALES'], x + 8, y + 91)
      doc.text(['Hasta 2/c', 'Negativo', 'Negativo', 'Hasta 5/c', 'Hasta 3/c', 'Escaso', ''], x + 90, y + 91, 'center')
      doc.text(['Hialino', 'Granuloso', 'Epiteliales', 'Eritrocitario', 'Leucocitario', 'Cereos', 'Mixtos'], x + 110, y + 91, 'left')
      doc.text(['Negativo', 'Negativo', 'Negativo', 'Negativo', 'Negativo', 'Negativo', 'Negativo'], x + 185, y + 91, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text([l.d15, l.d17, l.d19, l.d21, l.d23, l.d25], x + 50, y + 91)
      doc.setTextColor(0, 0, 0)
      if (l.d16 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d16, x + 160, y + 91)
      doc.setTextColor(0, 0, 0)
      if (l.d18 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d18, x + 160, y + 95)
      doc.setTextColor(0, 0, 0)
      if (l.d20 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d20, x + 160, y + 99)
      doc.setTextColor(0, 0, 0)
      if (l.d22 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d22, x + 160, y + 103)
      doc.setTextColor(0, 0, 0)
      if (l.d24 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d24, x + 160, y + 107)
      doc.setTextColor(0, 0, 0)
      if (l.d26 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d26, x + 160, y + 111)
      doc.setTextColor(0, 0, 0)
      if (l.d27 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d27, x + 160, y + 115)
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text('OTROS', x + 160, y + 119)

      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'normal')
      doc.text(['Uratos amorfos', 'Fosfato amorfo', 'Oxalato de calcio', 'Fosfato de calcio', 'Acido Urico'], x + 8, y + 121)
      doc.text(['Escaso', 'Escaso', 'Escaso', 'Escaso', 'Escaso'], x + 90, y + 121, 'center')
      doc.text(['Filamento mucoso', 'Piocitos', 'Levaduras', 'Esporas micoticas'], x + 110, y + 124, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text([l.d28, l.d29, l.d31, l.d33, l.d35], x + 50, y + 121)
      doc.text([l.d30, l.d32, l.d34, l.d36], x + 160, y + 124)

      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text('OBSERVACIONES:', x + 6, y + 141)
      doc.setFont(undefined, 'normal')
      // doc.text(l.d37,x+6,y+148,'left')

      doc.rect(x + 5, y + 138, 205, 25)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 153)
      doc.setFont(undefined, 'NORMAL')
      doc.text(l.responsable, x + 8, y + 157)
      doc.setFont(undefined, 'normal')
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 153, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 153, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      //window.open(doc.output('bloburl'), '_blank');
      doc.html('<div style="font-size: 3px;width: 300px">' + l.d37 + '</div>', {
        callback: function (doc) {
          //doc.output('save','EXAMEN GENERAL DE ORINA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
          window.open(doc.output('bloburl'), '_blank');
        },
        x: x + 6,
        y: y + 141,
      })
    },


    alcoholemia(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 17)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 014', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('ALCOHOLEMIA', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 44, 205, 94)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.rect(x + 75, y + 55, 50, 7)
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d1) > 50)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d1 + ' mg/dl', x + 90, y + 60)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)

      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text('VALOR REFERENCIAL:', x + 30, y + 85, 'center')

      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.rect(x + 30, y + 90, 150, 30)
      doc.rect(x + 30, y + 110, 150, 10)
      doc.text('10 - 50 mg/dl', x + 50, y + 100, 'center')
      doc.text('Mayor a 50 mg/dl', x + 50, y + 115, 'center')
      doc.setFont(undefined, 'bold')
      doc.text('ALCOHOLEMIA NEGATIVO', x + 120, y + 95, 'center')
      doc.rect(x + 80, y + 90, 100, 20)
      //doc.setDrawColor(0);
      //doc.setFillColor(255,255,50);
      doc.rect(x + 80, y + 110, 100, 10)
      doc.text('ALCOHOLEMIA POSITIVO', x + 120, y + 115, 'center')
      //doc.setFillColor(0, 0, 0);
      doc.setFont(undefined, 'normal')
      doc.text(['Alcoholes en sangre por la degradacion de alimentos', 'ingerido ricos en Hidratos de Carbono'], x + 120, y + 100, 'center')

      doc.rect(x + 5, y + 138, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 145)
      doc.setFont(undefined, 'NORMAL')
      doc.text(l.responsable, x + 8, y + 149)
      doc.setFont(undefined, 'normal')
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      //window.open(doc.output('bloburl'), '_blank');
      window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','ALCOHOLEMIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

    },

    widal(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 17)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 015', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('REACCION DE WIDAL', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.setFontSize(12);
      doc.rect(x + 5, y + 44, 205, 94)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('METODO AGLUTINACION DIRECTA', x + 20, y + 50)
      doc.text('REACCION DE WIDAL', x + 20, y + 60)
      doc.setFontSize(13);

      doc.setFont(undefined, 'bold')
      doc.text(' A: ', x + 80, y + 60)
      doc.text(' B: ', x + 80, y + 75)
      doc.text(' H: ', x + 80, y + 90)
      doc.text(' O: ', x + 80, y + 105)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text(l.d1, x + 90, y + 60)
      doc.text(l.d2, x + 90, y + 75)
      doc.text(l.d3, x + 90, y + 90)
      doc.text(l.d4, x + 90, y + 105)
      doc.setTextColor(0, 0, 0)
      doc.setFontSize(11);

      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text('OBSERVACIONES:', x + 10, y + 120, 'left')

      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d5, x + 10, y + 125, 'left')
      doc.setFontSize(10);

      doc.rect(x + 5, y + 138, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 145)
      doc.setFont(undefined, 'NORMAL')
      doc.text(l.responsable, x + 8, y + 149)
      doc.setFont(undefined, 'normal')
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      //window.open(doc.output('bloburl'), '_blank');
      window.open(doc.output('bloburl'), '_blank');
      //         doc.output('save','REACCION DE WIDAL-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

    },

    multidrogas(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 17)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 016', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('MULTIDROGAS', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 44, 205, 94)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(12);
      doc.text('METODO: PRUEBA RAPIDA INMUNOCROMATOGRAFICA DOA MULTI 6', x + 8, y + 55)
      doc.setTextColor(0, 0, 0)
      doc.setFontSize(12);
      doc.text('MULTIDROGAS EN ORINA', x + 30, y + 65)
      doc.setFont(undefined, 'bold')
      doc.text('PRUEBAS', x + 100, y + 70)

      doc.setFont(undefined, 'normal')
      doc.text(' Marihuana THC ', x + 8, y + 80)
      doc.text(' Cocaina COC ', x + 8, y + 90)
      doc.text(' Amfetamina AMP ', x + 8, y + 100)
      doc.text(' Metanfetamina MET ', x + 8, y + 110)
      doc.text(' Morfina MOP ', x + 8, y + 120)
      doc.text(' Metil-N-dioximetanfetamina ', x + 100, y + 80)
      doc.text(' Benzodiazepinas BZO ', x + 100, y + 90)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      if (l.d1 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)

      doc.text(l.d1, x + 60, y + 80)
      doc.setTextColor(0, 0, 0)
      if (l.d3 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d3, x + 60, y + 90)
      doc.setTextColor(0, 0, 0)
      if (l.d5 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d5, x + 60, y + 100)
      doc.setTextColor(0, 0, 0)
      if (l.d6 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d6, x + 60, y + 110)
      doc.setTextColor(0, 0, 0)
      if (l.d7 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d7, x + 60, y + 120)

      doc.setTextColor(0, 0, 0)
      if (l.d2 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d2, x + 160, y + 80)
      doc.setTextColor(0, 0, 0)
      if (l.d4 != 'NEGATIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d4, x + 160, y + 90)
      doc.setTextColor(0, 0, 0)
      doc.setFontSize(11);

      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text('OBSERVACIONES:', x + 10, y + 130, 'left')

      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d8, x + 10, y + 135, 'left')
      doc.setFontSize(10);

      doc.rect(x + 5, y + 138, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 145)
      doc.setFont(undefined, 'NORMAL')
      doc.text(l.responsable, x + 8, y + 149)
      doc.setFont(undefined, 'normal')
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','MULTIDROGAS-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

    },
    glicosilada(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 17)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 017', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('HEMOGLOBINA GLICOSILADA', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 44, 205, 94)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(12);
      doc.text('HbA1c METODO INMUNOENSAYO DE FLUORESCENCIA (FIA)', x + 50, y + 60)
      doc.setTextColor(0, 0, 0)
      //doc.setFontSize(12);

      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('Valor de Referencia: ', x + 60, y + 100)
      doc.setFontSize(12);
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d1) < 4.5 || parseFloat(l.d1) > 6.5)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d1 + ' %', x + 100, y + 75)
      doc.setTextColor(0, 0, 0)

      doc.rect(x + 75, y + 105, 60, 12)
      doc.rect(x + 75, y + 105, 60, 6)
      doc.rect(x + 75, y + 105, 24, 12)
      doc.rect(x + 75, y + 70, 60, 6)
      doc.setTextColor(255, 0, 0)

      doc.text('NGSP ', x + 80, y + 110)
      doc.text('IFCC ', x + 80, y + 115)
      doc.setTextColor(0, 0, 0)
      doc.text('4.5 - 6.5% ', x + 105, y + 110)
      doc.text('26 - 48 mmol/mol', x + 100, y + 115)

      doc.setFontSize(10);

      doc.rect(x + 5, y + 138, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 145)
      doc.setFont(undefined, 'NORMAL')
      doc.text(l.responsable, x + 8, y + 149)
      doc.setFont(undefined, 'normal')
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','HEMOGLOBINA GLICOSILADA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

    },

    gasometria(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 17)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 018', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('GASOMETRIA', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 44, 205, 94)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(12);
      doc.text('GASOMETRIA ARTERIAL : ', x + 10, y + 60)
      doc.setTextColor(0, 0, 0)

      doc.text(l.d1, x + 70, y + 60)
      //doc.setFontSize(12);

      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10);
      doc.rect(x + 5, y + 138, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 145)
      doc.setFont(undefined, 'NORMAL')
      doc.text(l.responsable, x + 8, y + 149)
      doc.setFont(undefined, 'normal')
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      if (l.imagen != null && l.imagen != '') {

        this.$axios.post(process.env.API + '/base64', {imagen: l.imagen}).then(res => {
          //doc.addPage();
          var imgData = ''
          //console.log(res.data)
          imgData = res.data
          doc.addImage(imgData, "jpeg", 90, 50, 90, 80)
          //  console.log(imgData)
          window.open(doc.output('bloburl'), '_blank');
          //    doc.output('save','GASOMETRIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf)
          return false
        })
      } else
        //$( '#docpdf' ).attr('src', doc.output('datauristring'));
        window.open(doc.output('bloburl'), '_blank');
      //          doc.output('save','GASOMETRIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

    },
    hepatitis(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 17)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 019', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('HEPATITIS B', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 44, 205, 94)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(12);
      doc.text('Anti-HBS METODO INMUNOENSAYO DE FLUORESCENCIA (FIA)', x + 50, y + 60)
      doc.setTextColor(0, 0, 0)
      //doc.setFontSize(12);

      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('Valor de Referencia: ', x + 60, y + 100)
      doc.setFontSize(12);
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d1) > 15)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d1 + ' mlU/ml', x + 100, y + 75)
      doc.setTextColor(0, 0, 0)

      if (l.d2 == 'POSITIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d2, x + 100, y + 85)
      doc.setTextColor(0, 0, 0)

      doc.rect(x + 75, y + 105, 70, 12)
      doc.rect(x + 75, y + 105, 70, 6)
      doc.rect(x + 75, y + 105, 30, 12)
      doc.rect(x + 75, y + 117, 70, 6)
      doc.rect(x + 75, y + 117, 30, 6)
      doc.rect(x + 75, y + 70, 70, 8)
      doc.rect(x + 75, y + 80, 70, 8)
      doc.setTextColor(57, 73, 171)


      doc.text('< 5 mlU/ml ', x + 80, y + 110)
      doc.text('5 - 15 mlU/ml ', x + 80, y + 115)
      doc.text('> 15 mlU/ml ', x + 80, y + 120)
      doc.setTextColor(0, 0, 0)
      doc.text('NEGATIVO', x + 112, y + 110)
      doc.text('INDETERMINADO', x + 107, y + 115)
      doc.text('POSITIVO', x + 112, y + 120)

      doc.setFontSize(10);

      doc.rect(x + 5, y + 138, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 145)
      doc.setFont(undefined, 'NORMAL')
      doc.text(l.responsable, x + 8, y + 149)
      doc.setFont(undefined, 'normal')
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      window.open(doc.output('bloburl'), '_blank');
      //      doc.output('save','HEPATITIS B-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

    },
    toxoplasmosis(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 17)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 020', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('TOXOPLASMOSIS lg G / lg M', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 44, 205, 94)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(12);
      doc.text(' METODO INMUNOENSAYO DE FLUORESCENCIA (FIA)', x + 50, y + 55)
      doc.setTextColor(0, 0, 0)
      //doc.setFontSize(12);

      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('Valor de Referencia: ', x + 55, y + 85)
      doc.setFontSize(12);
      doc.setTextColor(0, 0, 0)
      doc.text('lg M', x + 60, y + 65)
      doc.text('lg G', x + 60, y + 75)
      if (parseFloat(l.d1) > 8)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d1 + '', x + 90, y + 65)
      doc.setTextColor(0, 0, 0)

      if (parseFloat(l.d3) > 1.1)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d3 + '', x + 90, y + 75)
      doc.setTextColor(0, 0, 0)

      if (l.d2 == 'POSITIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d2, x + 110, y + 65)
      doc.setTextColor(0, 0, 0)

      if (l.d4 == 'POSITIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d4, x + 110, y + 75)
      doc.setTextColor(0, 0, 0)
      doc.rect(x + 55, y + 60, 25, 8)
      doc.rect(x + 80, y + 60, 25, 8)
      doc.rect(x + 105, y + 60, 50, 8)

      doc.rect(x + 55, y + 70, 25, 8)
      doc.rect(x + 80, y + 70, 25, 8)
      doc.rect(x + 105, y + 70, 50, 8)

      doc.rect(x + 55, y + 90, 25, 16)
      doc.rect(x + 55, y + 106, 25, 16)
      doc.rect(x + 80, y + 90, 90, 16)
      doc.rect(x + 80, y + 106, 90, 16)
      //doc.rect(x+75, y+105, 30, 12)
      //doc.rect(x+75, y+117, 70, 6)
      //doc.rect(x+75, y+117, 30, 6)
      doc.setTextColor(0, 0, 0)


      doc.text('lg G ', x + 65, y + 100)
      doc.text('lg M', x + 65, y + 115)
      doc.setTextColor(57, 73, 171)

      doc.text('< 4 IU/ml', x + 85, y + 95)
      doc.text('4 - 8 UI/ml', x + 85, y + 100)
      doc.text('> 8 IU/ml', x + 85, y + 105)

      doc.text('< 0.9 IU/ml', x + 85, y + 110)
      doc.text('0.9 - 1.1 UI/ml', x + 85, y + 115)
      doc.text('> 1.1 IU/ml', x + 85, y + 120)
      doc.setTextColor(0, 0, 0)

      doc.text('NEGATIVO', x + 120, y + 95)
      doc.text('INDETERMINADO', x + 120, y + 100)
      doc.text('POSITIVO', x + 120, y + 105)
      doc.text('NEGATIVO', x + 120, y + 110)
      doc.text('INDETERMINADO', x + 120, y + 115)
      doc.text('POSITIVO', x + 120, y + 120)

      doc.setFontSize(10);

      doc.rect(x + 5, y + 138, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 145)
      doc.setFont(undefined, 'NORMAL')
      doc.text(l.responsable, x + 8, y + 149)
      doc.setFont(undefined, 'normal')
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','TOXOPLASMOSIS '+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },
    hemodialisis(p, l) {
      var doc = new jsPDF('landscape', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 195, 3, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 195, y + 27, 155, 23)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 320, y + 10, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 225, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 021', x + 323, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('HEMODIALISIS M Y B', x + 270, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 196, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 268, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 305, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 305, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 337, y + 35, 'center')
      doc.setTextColor(211, 47, 47)
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 195, y + 53, 155, 130)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(211, 47, 47)
      doc.text('', x + 233, y + 55)
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(12);
      doc.setLineHeightFactor(1.7);
      doc.text('CONTROL MENSUAL', x + 260, y + 60)
      doc.text('PRUEBA                           VALOR                                     REFERENCIA', x + 200, y + 65)
      doc.setFontSize(12);

      doc.setFont(undefined, 'normal')
      doc.text(['Glicemia', 'Creatinina', 'Urea', 'NUS-BUN ', 'Transaminasas GOT', 'Transaminasas GPT', 'Sodio', 'Cloro', 'Potasio'], x + 200, y + 70, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d1) < 70 || parseFloat(l.d1) > 105)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d1, x + 250, y + 70, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d2) < 0.7 || parseFloat(l.d2) > 1.5)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d2, x + 250, y + 77, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d3) < 15 || parseFloat(l.d3) > 45)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d3, x + 250, y + 84, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d4) < 7 || parseFloat(l.d4) > 18)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d4, x + 250, y + 91, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d5) > 40)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d5, x + 250, y + 99, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d6) > 41)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d6, x + 250, y + 106, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d7) < 135 || parseFloat(l.d7) > 155)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d7, x + 250, y + 114, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d8) < 98 || parseFloat(l.d8) > 106)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d8, x + 250, y + 121, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d9) < 3.4 || parseFloat(l.d9) > 5.6)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d9, x + 250, y + 128, 'left')

      doc.setTextColor(57, 73, 171)

      doc.text(['70 - 105 mg/dl', '0.7 - 1.5 mg/dl', '15 - 45 mg/dl', '7 - 18 mg/dl ', 'hasta 40 UI/L', 'hasta 41 UI/L', '135 - 155 mEq/L', '98 - 106 mEq/L', '3.4 - 5.6 mEq/L'], x + 300, y + 70, 'left')
      doc.setFont(undefined, 'bold')
      doc.text('CONTROL BIMESTRAL', x + 260, y + 140)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(57, 73, 171)
      doc.setLineHeightFactor(1.7);
      doc.text(['Acido Urico', 'Fosfatasa Alcalina', 'calcio', 'Fosforo', 'OBSERVACIONES'], x + 200, y + 145, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d10) < 2.6 || parseFloat(l.d10) > 7.2)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d10, x + 250, y + 145, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d11) > 135)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d11, x + 250, y + 152, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d12) < 8.5 || parseFloat(l.d12) > 10.5)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d12, x + 250, y + 160, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d13) < 2.5 || parseFloat(l.d13) > 4.5)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d13, x + 250, y + 167, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d14, x + 200, y + 179, 'left')
      doc.setTextColor(57, 73, 171)
      doc.text(['2.6 - 7.2 mg/dl', 'adultos Hasta 135 UI/L', '8.5 - 10.5 mg/dl', '2.5 - 4.5 mg/dl'], x + 300, y + 145, 'left')

      doc.setLineHeightFactor(1.5);
      doc.rect(x + 195, y + 185, 155, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 205, y + 190, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 205, y + 195, 'left')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10);
      doc.setLineHeightFactor(1.5)
      doc.text(['Fecha toma de Muestra', 'Hora toma Muestra', 'Fecha Entrega de Resultado'], x + 280, y + 190, 'left')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 330, y + 190, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      //  doc.output('save','HEMODIALISIS M Y B-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      window.open(doc.output('bloburl'), '_blank');

    },
    hemodialisistri(p, l) {
      var doc = new jsPDF('landscape', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 195, 3, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 195, y + 27, 155, 23)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 320, y + 10, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 225, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 022', x + 323, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('HEMODIALISIS TRIMENSUAL', x + 270, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 196, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 268, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 305, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 305, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 337, y + 35, 'center')
      doc.setTextColor(211, 47, 47)
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 195, y + 53, 155, 130)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(211, 47, 47)
      doc.text('', x + 233, y + 55)
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10);
      doc.text('CONTROL TRIMENSUAL', x + 260, y + 60)
      doc.text('PRUEBA                         VALOR                         UNIDAD                   REFERENCIA', x + 200, y + 65)

      doc.setFont(undefined, 'normal')
      doc.text(['Proteinas Totales', 'Albumina', 'Globulina', 'Relacion A/G', 'Trigliceridos', 'Colesterol Total', 'HDL - Col', 'LDL - Col', 'VLDL - Col', 'Hierro', 'Prueba Rapida VIH'], x + 200, y + 70, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d1) < 6.0 || parseFloat(l.d1) > 8.5)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d1, x + 245, y + 70, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d2) < 3.5 || parseFloat(l.d2) > 5.3)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d2, x + 245, y + 74, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d3) < 2.8 || parseFloat(l.d3) > 3.5)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d3, x + 245, y + 78, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d4) < 1.2 || parseFloat(l.d4) > 2.2)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d4, x + 245, y + 82, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d5) < 10 || parseFloat(l.d5) > 160)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d5, x + 245, y + 86, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d6) >= 200)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d6, x + 245, y + 90, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d7) < 35 || parseFloat(l.d7) > 65)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d7, x + 245, y + 94, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d8) > 150)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d8, x + 245, y + 98, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d9) < 2 || parseFloat(l.d9) > 30)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d9, x + 245, y + 102, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d10) < 50 || parseFloat(l.d10) > 170)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d10, x + 245, y + 106, 'left')
      doc.setTextColor(0, 0, 0)
      if (l.d11 != 'NO REACTIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d11, x + 245, y + 110, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text(['g/dl', 'g/dl', 'g/dl', 'g/dl', 'mg/dl', 'mg/dl', 'mg/dl', 'mg/dl', 'mg/dl', 'ug/dl', ''], x + 280, y + 70, 'left')
      doc.setTextColor(57, 73, 171)
      doc.text(['6.0 - 8.5 g/dl', '3.5 - 5.3 g/dl', '2.8 - 3.5 g/dl', '1.2 - 2.2 g/dl', '10 - 160 mmg/dl', 'menor 200 mg/dl', '35 - 65 mg/dl', 'hasta 150 mg/dl', '2 - 30 mg/dl', '50 - 170 ug/dl', 'NO REACTIVO'], x + 310, y + 70, 'left')

      doc.setTextColor(57, 73, 171)

      doc.setFont(undefined, 'bold')
      doc.text('METODO: CAPACIDAD DE FIJACION DE HIERRO', x + 220, y + 120)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(57, 73, 171)
      doc.text(['Capacidad Latente de', '  Fijacion de Hierro', 'Capacidad Total de ', '  Fijacion de Hierro', 'Transferrina'], x + 200, y + 125, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d12) < 140 || parseFloat(l.d12) > 280)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d12, x + 245, y + 125, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d13) < 250 || parseFloat(l.d13) > 450)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d13, x + 245, y + 133, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d14) < 200 || parseFloat(l.d14) > 300)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d14, x + 245, y + 141, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text(['ug/dl', '', 'ug/dl', '', 'mg/dl'], x + 280, y + 125, 'left')
      doc.setTextColor(57, 73, 171)
      doc.text(['140 - 280 ug/dl', '', '250 - 450 ug/dl', '', '200 - 300 mg/dl'], x + 310, y + 125, 'left')

      doc.setFont(undefined, 'bold')
      doc.text('METODO: INMUNOENSAYO DE FLUORESCENCIA (FIA)', x + 220, y + 150)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(57, 73, 171)
      doc.text(['FERRITNA', '', '', 'HEPATITIS B', '', 'OBSERVACIONES'], x + 200, y + 155, 'left')
      doc.setTextColor(0, 0, 0)
      if ((parseFloat(l.d15) < 30 || parseFloat(l.d15) > 350) && p.sexo == 'Masculino')
        doc.setTextColor(255, 0, 0)
      if ((parseFloat(l.d15) < 20 || parseFloat(l.d15) > 250) && p.sexo == 'Femenino')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d15, x + 240, y + 155, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d16) > 15)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d16, x + 240, y + 167, 'left')
      doc.setTextColor(0, 0, 0)
      if (l.d17 == 'POSITIVO')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d17, x + 240, y + 171, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d18, x + 200, y + 180, 'left')
      doc.text(['ng/ml', '', '', 'mlU/dl', ''], x + 280, y + 155, 'left')
      doc.setTextColor(57, 73, 171)
      doc.text(['30-350 ng/ml Varon', '20-250 ng/ml Mujer', '< 5 mlU/ml NEGATIVO', '5-15 mlU/ml Indeterminado', '> 15 mlU/ml POSITIVO'], x + 300, y + 155, 'left')

      doc.rect(x + 195, y + 185, 155, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 205, y + 190, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 205, y + 195, 'left')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10);
      doc.setLineHeightFactor(1.5)
      doc.text(['Fecha toma de Muestra', 'Hora toma Muestra', 'Fecha Entrega de Resultado'], x + 280, y + 190, 'left')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 330, y + 190, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      //  doc.output('save','HEMODIALISIS TRI-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      window.open(doc.output('bloburl'), '_blank');

    },
    miscelaneo(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 023', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('MISCELANEOS - ORINA', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 48, 205, 93)
      doc.setFont(undefined, 'bold')
      doc.setFontSize(12);
      doc.setTextColor(57, 73, 171)
      doc.setLineHeightFactor(2)
      doc.text('                       RESULTADO               VALOR DE REFERENCIA', x + 50, y + 52)
      doc.setFont(undefined, 'normal')
      doc.text(['ORINA EN 24 HORAS', 'CREATININA EN SUERO', 'CREATININA EN ORINA 24', '    HORAS', 'CLEARENCE DE CREATININA', '', 'PROTEINA EN ORINA AL AZAR', 'PROTEINURIA DE 24 HORAS'], x + 8, y + 60)
      doc.setTextColor(0, 0, 0)
      doc.text(l.d1, x + 80, y + 60)
      if (parseFloat(l.d2) < 0.6 || parseFloat(l.d2) > 1.4)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d2, x + 80, y + 70)
      doc.setTextColor(0, 0, 0)
      if ((parseFloat(l.d3) < 1500 || parseFloat(l.d3) > 2500) && p.sexo == 'Masculino')
        doc.setTextColor(255, 0, 0)
      if ((parseFloat(l.d3) < 800 || parseFloat(l.d3) > 1500) && p.sexo == 'Femenino')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d3, x + 80, y + 80)
      doc.setTextColor(0, 0, 0)
      if ((parseFloat(l.d4) < 97 || parseFloat(l.d4) > 137) && p.sexo == 'Masculino')
        doc.setTextColor(255, 0, 0)
      if ((parseFloat(l.d4) < 88 || parseFloat(l.d4) > 128) && p.sexo == 'Femenino')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d4, x + 80, y + 95)
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d5) < 1 || parseFloat(l.d5) > 15)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d5, x + 80, y + 110)
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d6) < 28 || parseFloat(l.d6) > 141)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d6, x + 80, y + 120)
      doc.setTextColor(0, 0, 0)
      doc.text(['VOLUMEN', '0.6 - 1.4 mg/dl', 'Varones 1500 - 2500 mg/24hrs', 'Mujeres 800 - 1500 mg/24hrs ', 'Varones 97 - 137 ml/min', 'Mujeres 88 - 128 ml/min ', '1 - 15 mg/dl', '28 - 141 mg/24hrs'], x + 130, y + 60)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('OBSERVACION', x + 8, y + 125)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d7, x + 8, y + 130)
      doc.setFontSize(10);
      doc.setLineHeightFactor(1.5)

      doc.rect(x + 5, y + 142, 205, 22)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 145)
      doc.setFont(undefined, 'NORMAL')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 8, y + 150)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(57, 73, 171)
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      // doc.output('save','MICSCELANEOS ORINA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      window.open(doc.output('bloburl'), '_blank');
    },

    hierro(p, l) {

      var doc = new jsPDF('landscape', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 195, 3, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 195, y + 27, 155, 23)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 320, y + 10, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 225, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 024', x + 323, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('FIJACION DE HIERRO', x + 270, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 196, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 268, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 305, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 305, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 337, y + 35, 'center')
      doc.setTextColor(211, 47, 47)
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 195, y + 53, 155, 130)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(211, 47, 47)
      doc.text('', x + 233, y + 55)
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10);
      doc.setFontSize(12);
      doc.setTextColor(255, 0, 0)
      doc.text('                   METODO: CAPACIDAD DE FIJACION DE HIERRO', x + 200, y + 60)
      doc.setTextColor(57, 73, 171)
      doc.text('  PRUEBA                     VALOR               UNIDAD                REFERENCIA', x + 200, y + 65)

      doc.setFont(undefined, 'normal')
      doc.text(['Capacidad Latente de ', ' Fijacion de Hierro', 'Capacitacion Total de ', ' Fijacion de Hierro', 'Transferrina'], x + 200, y + 70, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d1) < 140 || parseFloat(l.d1) > 280)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d1, x + 250, y + 70, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d2) < 250 || parseFloat(l.d2) > 450)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d2, x + 250, y + 80, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d3) < 200 || parseFloat(l.d3) > 300)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d3, x + 250, y + 90, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text(['ug/dl', ' ', 'ug/dl', ' ', 'mg/dl'], x + 280, y + 70, 'left')
      doc.text(['140 - 280 ug/dl', ' ', '250 - 450 ug/dl', ' ', '200 - 300 mg/dl'], x + 310, y + 70, 'left')

      doc.setFontSize(11);
      doc.setTextColor(57, 73, 171)
      doc.text('OBSERVACION', x + 200, y + 175, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d4, x + 200, y + 180, 'left')
      doc.setFontSize(11);


      doc.rect(x + 195, y + 185, 155, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 205, y + 190, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 205, y + 195, 'left')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10);
      doc.setLineHeightFactor(1.5)
      doc.text(['Fecha toma de Muestra', 'Hora toma Muestra', 'Fecha Entrega de Resultado'], x + 280, y + 190, 'left')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 330, y + 190, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      //  doc.output('save','FIJACION HIERRO-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
      window.open(doc.output('bloburl'), '_blank');
    },

    uretral(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 022', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('ANALISIS DE SECRECION URETRAL', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 48, 205, 85)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('                                          EXAMEN EN FRESCO', x + 50, y + 52)
      doc.setFont(undefined, 'normal')
      doc.text(['CELULAS EPITELIALES', 'LEUCOCITOS', 'HEMATIES', 'CELULAS CLAVE', 'LEVADURAS', 'PARASITOS', 'BACTERIAS', 'KOH', 'PH', 'OBSERVACIONES'], x + 8, y + 58)
      doc.setTextColor(0, 0, 0)
      doc.text([l.d1, l.d2, l.d3, l.d4, l.d5, l.d6, l.d7, l.d8, l.d9, l.d10], x + 70, y + 58)

      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('                                         TINCION DE GRAM', x + 50, y + 100)
      doc.setFont(undefined, 'normal')
      doc.text(['BACILOS GRAM POSITIVO', 'BACILOS GRAM NEGATIVO', 'COCOS GRAM POSITIVO', 'COCOBACILOS GRAM POSITIVO', 'COCOBACILOS GRAM NEGATIVO', 'ESPORAS E HIFAS MICOTICAS', 'OBSERVACIONES'], x + 8, y + 105)
      doc.setTextColor(0, 0, 0)
      doc.text([l.d11, l.d12, l.d13, l.d14, l.d15, l.d16, l.d17], x + 70, y + 105)

      doc.rect(x + 5, y + 135, 205, 22)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 140)
      doc.setFont(undefined, 'NORMAL')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 8, y + 145)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(57, 73, 171)
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','ANALISIS DE SECRECION URETRAL-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },

    vaginal(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 005', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('ANALISIS DE SECRECION VAGINAL', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 48, 205, 85)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('                                          EXAMEN EN FRESCO', x + 50, y + 52)
      doc.setFont(undefined, 'normal')
      doc.text(['CELULAS EPITELIALES', 'LEUCOCITOS', 'HEMATIES', 'CELULAS CLAVE', 'LEVADURAS', 'PARASITOS', 'BACTERIAS', 'KOH', 'PH', 'OBSERVACIONES'], x + 8, y + 58)
      doc.setTextColor(0, 0, 0)
      doc.text([l.d1, l.d2, l.d3, l.d4, l.d5, l.d6, l.d7, l.d8, l.d9, l.d10], x + 70, y + 58)

      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('                                         TINCION DE GRAM', x + 50, y + 100)
      doc.setFont(undefined, 'normal')
      doc.text(['BACILOS GRAM POSITIVO', 'BACILOS GRAM NEGATIVO', 'COCOS GRAM POSITIVO', 'COCOBACILOS GRAM POSITIVO', 'COCOBACILOS GRAM NEGATIVO', 'ESPORAS E HIFAS MICOTICAS', 'OBSERVACIONES'], x + 8, y + 105)
      doc.setTextColor(0, 0, 0)
      doc.text([l.d11, l.d12, l.d13, l.d14, l.d15, l.d16, l.d17], x + 70, y + 105)

      doc.rect(x + 5, y + 135, 205, 22)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 140)
      doc.setFont(undefined, 'NORMAL')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 8, y + 145)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(57, 73, 171)
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','ANALISIS DE SECRECION VAGINAL-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },

    sanguinea(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 002', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('QUIMICA SANGUINEA', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 48, 205, 77)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('          PRUEBA                      VALOR                   REFERENCIA                  PRUEBA                     VALOR              REFERENCIA', x + 6, y + 52)
      doc.setFont(undefined, 'normal')
      doc.text(['Glicemia', 'Creatinina', 'Urea', 'NUS-BUN', 'Acido Urico', 'Proteinas Totales', 'Albumina', 'Globulina', 'Amilasa', 'Lipasa', 'Bilirrubina Total', 'Bilirrubina Directa', 'Bilirrubina Indirecta', 'CK-MB', 'CPK TOTAL', 'LDH', 'Hierro'], x + 8, y + 58)
      doc.text(['70-105mg/dl', '0.5-1.1mg/dl', '15-45mg/dl', '7-18mg/dl', '1.5-7mg/dl', '6.2-8.5g/dl', '3.5-5.3g/dl', '2.8-3.5g/dl', 'menor a 125UI/L', 'hasta 60 UI/L', 'hasta 1.2 mg/dl', 'hasta 0.3 mg/dl', 'hasta 0.9 mg/dl', '0-25 UI/L', '24-195 UI/L', '200-480 UI/L', '50-170ug/dl'], x + 80, y + 58, 'left')
      doc.text(['Fosfatasa alcalina', 'GGT', 'Transamisas GOT', 'Transamisas GPT', 'LIPIDOGRAMA', 'Trigliceridos', 'Colesterol Total', 'HDL-Col', 'LDL-Col', 'VLDL', 'ELECTROLITOS', 'Sodio', 'Cloro', 'Potasio', 'Calcio', 'Magnesio', 'Fosforo'], x + 110, y + 58, 'left')
      doc.text(['adultos hasta 100UI/L', 'hasta 55 UI/L', 'hasta 40UI/L', 'hasta 41UI/L', '', '10-160mg/dl', 'menor 200mg/dl', '35-65mg/dl', 'hasta 150mg/dl', '2-30 mg/dl', '', '135-155mEq/L', '98-106 mEq/L', '3.4-5.3 mEq/L', '8.5-10.5mg/dl', '1.7-2.4mg/dl', '2.5-4.5mg/dl'], x + 175, y + 58, 'left')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d1) < 70 || parseFloat(l.d1) > 105)
        doc.setTextColor(255, 0, 0)
      if (l.d1 != '')
        doc.text(l.d1 + ' mg/dl', x + 55, y + 58)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d3) < 0.5 || parseFloat(l.d3) > 1.1)
        doc.setTextColor(255, 0, 0)
      if (l.d3 != '')
        doc.text(l.d3 + ' mg/dl', x + 55, y + 62)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d5) < 15 || parseFloat(l.d5) > 45)
        doc.setTextColor(255, 0, 0)
      if (l.d5 != '')
        doc.text(l.d5 + ' mg/dl', x + 55, y + 66)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d7) < 7 || parseFloat(l.d7) > 18)
        doc.setTextColor(255, 0, 0)
      if (l.d7 != '')
        doc.text(l.d7 + ' mg/dl', x + 55, y + 70)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d9) < 1.5 || parseFloat(l.d9) > 7)
        doc.setTextColor(255, 0, 0)
      if (l.d9 != '')
        doc.text(l.d9 + ' mg/dl', x + 55, y + 74)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d10) < 6.2 || parseFloat(l.d10) > 8.5)
        doc.setTextColor(255, 0, 0)
      if (l.d10 != '')
        doc.text(l.d10 + ' g/dl', x + 55, y + 78)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d12) < 3.5 || parseFloat(l.d12) > 5.3)
        doc.setTextColor(255, 0, 0)
      if (l.d12 != '')
        doc.text(l.d12 + ' g/dl', x + 55, y + 82)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d14) < 2.8 || parseFloat(l.d14) > 3.5)
        doc.setTextColor(255, 0, 0)
      if (l.d14 != '')
        doc.text(l.d14 + ' g/dl', x + 55, y + 86)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d16) < 0 || parseFloat(l.d16) > 125)
        doc.setTextColor(255, 0, 0)
      if (l.d16 != '')
        doc.text(l.d16 + ' UI/L', x + 55, y + 90)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d18) < 0 || parseFloat(l.d18) > 60)
        doc.setTextColor(255, 0, 0)
      if (l.d18 != '')
        doc.text(l.d18 + ' UI/L', x + 55, y + 94)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d19) < 0 || parseFloat(l.d19) > 1.2)
        doc.setTextColor(255, 0, 0)
      if (l.d19 != '')
        doc.text(l.d19 + ' mg/dl', x + 55, y + 98)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d21) < 0 || parseFloat(l.d21) > 0.3)
        doc.setTextColor(255, 0, 0)
      if (l.d21 != '')
        doc.text(l.d21 + ' mg/dl', x + 55, y + 102)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d23) < 0 || parseFloat(l.d23) > 0.9)
        doc.setTextColor(255, 0, 0)
      if (l.d23 != '')
        doc.text(l.d23 + ' mg/dl', x + 55, y + 106)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d25) < 0 || parseFloat(l.d25) > 25)
        doc.setTextColor(255, 0, 0)
      if (l.d25 != '')
        doc.text(l.d25 + ' UI/L', x + 55, y + 110)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d33) < 24 || parseFloat(l.d33) > 195)
        doc.setTextColor(255, 0, 0)
      if (l.d33 != '')
        doc.text(l.d33 + ' UI/L', x + 55, y + 114)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d27) < 200 || parseFloat(l.d27) > 480)
        doc.setTextColor(255, 0, 0)
      if (l.d27 != '')
        doc.text(l.d27 + ' UI/L', x + 55, y + 118)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d29) < 50 || parseFloat(l.d29) > 170)
        doc.setTextColor(255, 0, 0)
      if (l.d29 != '')
        doc.text(l.d29 + ' ug/dl', x + 55, y + 122)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d2) < 0 || parseFloat(l.d2) > 100)
        doc.setTextColor(255, 0, 0)
      if (l.d2 != '')
        doc.text(l.d2 + ' UI/L', x + 155, y + 58)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d4) < 0 || parseFloat(l.d4) > 55)
        doc.setTextColor(255, 0, 0)
      if (l.d4 != '')
        doc.text(l.d4 + ' UI/L', x + 155, y + 62)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d6) < 0 || parseFloat(l.d6) > 40)
        doc.setTextColor(255, 0, 0)
      if (l.d6 != '')
        doc.text(l.d6 + ' UI/L', x + 155, y + 66)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d8) < 0 || parseFloat(l.d8) > 41)
        doc.setTextColor(255, 0, 0)
      if (l.d8 != '')
        doc.text(l.d8 + ' UI/L', x + 155, y + 70)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d11) < 10 || parseFloat(l.d11) > 160)
        doc.setTextColor(255, 0, 0)
      if (l.d11 != '')
        doc.text(l.d11 + ' mg/dl', x + 155, y + 78)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d13) < 0 || parseFloat(l.d13) > 200)
        doc.setTextColor(255, 0, 0)
      if (l.d13 != '')
        doc.text(l.d13 + ' mg/dl', x + 155, y + 82)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d15) < 35 || parseFloat(l.d15) > 65)
        doc.setTextColor(255, 0, 0)
      if (l.d15 != '')
        doc.text(l.d15 + ' mg/dl', x + 155, y + 86)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d17) < 0 || parseFloat(l.d17) > 150)
        doc.setTextColor(255, 0, 0)
      if (l.d17 != '')
        doc.text(l.d17 + ' mg/dl', x + 155, y + 90)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d32) <= 2 || parseFloat(l.d32) >= 30)
        doc.setTextColor(255, 0, 0)
      if (l.d32 != '')
        doc.text(l.d32 + ' mg/dl', x + 155, y + 94)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d20) < 135 || parseFloat(l.d20) > 155)
        doc.setTextColor(255, 0, 0)
      if (l.d20 != '')
        doc.text(l.d20 + ' mEq/L', x + 155, y + 102)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d22) < 98 || parseFloat(l.d22) > 106)
        doc.setTextColor(255, 0, 0)
      if (l.d22 != '')
        doc.text(l.d22 + ' mEq/L', x + 155, y + 106)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d24) < 3.4 || parseFloat(l.d24) > 5.3)
        doc.setTextColor(255, 0, 0)
      if (l.d24 != '')
        doc.text(l.d24 + ' mEq/L', x + 155, y + 110)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d26) < 8.5 || parseFloat(l.d26) > 10.5)
        doc.setTextColor(255, 0, 0)
      if (l.d26 != '')
        doc.text(l.d26 + ' mg/dl', x + 155, y + 114)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d28) < 1.7 || parseFloat(l.d28) > 2.4)
        doc.setTextColor(255, 0, 0)
      if (l.d28 != '')
        doc.text(l.d28 + ' mg/dl', x + 155, y + 118)

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d30) < 2.5 || parseFloat(l.d30) > 4.5)
        doc.setTextColor(255, 0, 0)
      if (l.d30 != '')
        doc.text(l.d30 + ' mg/dl', x + 155, y + 122)


      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text('OBSERVACIONES:', x + 6, y + 129)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d31, x + 6, y + 132, 'left')

      doc.rect(x + 5, y + 126, 205, 25)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 139)
      doc.setFont(undefined, 'NORMAL')
      doc.text(l.responsable, x + 8, y + 144)
      doc.setFont(undefined, 'normal')
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 139, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 139, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','QUIMICA SANGUINIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },
    valcien() {
      let total = 0
      if (this.laboratorio.d18 == undefined || this.laboratorio.d18 == '') this.laboratorio.d18 = 0;
      if (this.laboratorio.d20 == undefined || this.laboratorio.d20 == '') this.laboratorio.d20 = 0;
      if (this.laboratorio.d22 == undefined || this.laboratorio.d22 == '') this.laboratorio.d22 = 0;
      if (this.laboratorio.d24 == undefined || this.laboratorio.d24 == '') this.laboratorio.d24 = 0;
      if (this.laboratorio.d26 == undefined || this.laboratorio.d26 == '') this.laboratorio.d26 = 0;
      if (this.laboratorio.d28 == undefined || this.laboratorio.d28 == '') this.laboratorio.d28 = 0;
      if (this.laboratorio.d30 == undefined || this.laboratorio.d30 == '') this.laboratorio.d30 = 0;

      total = parseFloat(this.laboratorio.d18) + parseFloat(this.laboratorio.d20) + parseFloat(this.laboratorio.d22) + parseFloat(this.laboratorio.d24) + parseFloat(this.laboratorio.d26)
        + parseFloat(this.laboratorio.d28) + parseFloat(this.laboratorio.d30)
      if (total != 100) {
        this.$q.notify({
          message: 'No suma el 100 % ',
          icon: 'info',
          color: 'red'
        })
      }
    },
    serologia() {
      var doc = new jsPDF('landscape', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 195, 3, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 195, y + 27, 155, 23)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 320, y + 10, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 225, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 005', x + 323, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('INMUNOENSAYO DE FLUORESCENCIA', x + 270, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA', 'METODO'], x + 196, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text(['ADIEMR PAUL CHAMBI AJATA', 'ADIMER PAUL CHAMBI AJATA A', 'COMPLRETA'], x + 268, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 305, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 305, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text(['9999 ', 'MACULJONO', '11'], x + 337, y + 35, 'center')
      doc.setTextColor(211, 47, 47)
      doc.text('CONTADOR Hematologico MINDRAY BC 5130', x + 260, y + 47, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 195, y + 53, 155, 125)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(211, 47, 47)
      doc.text('', x + 233, y + 55)
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10);
      doc.text('       UNIDAD         TIPO MUESTRA      VALOR REFE', x + 250, y + 60)
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
      ], x + 197, y + 65, 'left')
      doc.setTextColor(0, 0, 0)
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
      ], x + 241, y + 65, 'center')
      doc.setTextColor(57, 73, 171)
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
      ], x + 254, y + 65, 'center')
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
      ], x + 275, y + 65, 'center')
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
      ], x + 315, y + 65, 'center')
      doc.setFontSize(11);


      doc.rect(x + 195, y + 180, 155, 30)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 205, y + 185, 'left')
      doc.setFontSize(10);
      doc.setLineHeightFactor(1.5)
      doc.text(['Fecha toma de Muestra', 'Fecha Entrega de Resultado', 'Hora toma Muestra'], x + 280, y + 185, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      window.open(doc.output('bloburl'), '_blank');
      //           doc.output('save','QUIMICA SANGUINIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },
    hemograma(p, l) {
      var doc = new jsPDF('landscape', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 195, 3, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 195, y + 27, 155, 23)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 320, y + 10, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 225, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 001', x + 323, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('HEMOGRAMA COMPLETO', x + 270, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA', 'METODO'], x + 196, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 268, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 305, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 305, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 337, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.text('CONTADOR Hematologico MINDRAY BC 5130', x + 300, y + 47, 'center')
      //fin datos paciete
      //inicio datos
      doc.rect(x + 195, y + 51, 155, 77)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('REFERENCIA', x + 253, y + 55)
      doc.setFont(undefined, 'normal')
      doc.text(['Globulos rojos', ' ', 'Hemoglobina', ' ', 'Hematocrito', ' ', 'V.E.S.', ' ', 'V.C.M.', ' ', 'Hb.C.M.', ' ', 'C.Hb.C.M.', ' ', 'Globulos Blancos', ' ', 'Plaquetas'], x + 209, y + 60, 'center')
      doc.setTextColor(0, 0, 0)
      if ((parseFloat(l.d1) < 5.1 || parseFloat(l.d1) > 5.7) && p.sexo == 'Masculino')
        doc.setTextColor(255, 0, 0)
      if ((parseFloat(l.d1) < 4.8 || parseFloat(l.d1) > 5.4) && p.sexo == 'Femenino')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d1, x + 226, y + 60, 'center')

      doc.setTextColor(0, 0, 0)
      if ((parseFloat(l.d3) < 151 || parseFloat(l.d3) > 175) && p.sexo == 'Masculino')
        doc.setTextColor(255, 0, 0)
      if ((parseFloat(l.d3) < 141 || parseFloat(l.d3) > 165) && p.sexo == 'Femenino')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d3, x + 226, y + 68, 'center')
      doc.setTextColor(0, 0, 0)

      if ((parseFloat(l.d5) < 0.51 || parseFloat(l.d5) > 0.57) && p.sexo == 'Masculino')
        doc.setTextColor(255, 0, 0)
      if ((parseFloat(l.d5) < 0.46 || parseFloat(l.d5) > 0.53) && p.sexo == 'Femenino')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d5, x + 226, y + 76, 'center')
      doc.setTextColor(0, 0, 0)

      if ((parseFloat(l.d7) < 0 || parseFloat(l.d7) > 15) && p.sexo == 'Masculino')
        doc.setTextColor(255, 0, 0)
      if ((parseFloat(l.d7) < 0 || parseFloat(l.d7) > 20) && p.sexo == 'Femenino')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d7, x + 226, y + 84, 'center')
      doc.setTextColor(0, 0, 0)

      if (parseFloat(l.d9) < 83.0 || parseFloat(l.d9) > 97.0)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d9, x + 226, y + 92, 'center')

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d11) < 27.0 || parseFloat(l.d11) > 31.0)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d11, x + 226, y + 100, 'center')

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d13) < 32.0 || parseFloat(l.d13) > 36.0)
        doc.setTextColor(255, 0, 0)

      doc.text(l.d13, x + 226, y + 108, 'center')

      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d15) < 4.5 || parseFloat(l.d15) > 10.5)
        doc.setTextColor(255, 0, 0)

      doc.text(l.d15, x + 226, y + 116, 'center')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d17) < 150 || parseFloat(l.d17) > 400)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d17, x + 226, y + 125, 'center')

      doc.setTextColor(57, 73, 171)
      doc.text(['x10^(12)/L', ' ', 'g/L', ' ', 'L/L', ' ', 'mm.', ' ', 'ft.', ' ', 'pg.', ' ', '%', ' ', 'x10^(9)L', ' ', 'x10^(9)L'], x + 240, y + 60, 'center')
      doc.setFontSize(6);
      doc.text(['Varon 5.1-5.7x10^12/L', 'Mujer 4.8-5.4x10^12/L'], x + 265, y + 59, 'center')
      doc.text(['Varon 151-175 g/L', 'Mujer 141-165 g/L'], x + 265, y + 67, 'center')
      doc.text(['Varon 0.51-0.57 L/L', 'Mujer 0.46-0.53 L/L'], x + 265, y + 75, 'center')
      doc.text(['Varon 15 mm/hora', 'Mujer 20mm/hora'], x + 265, y + 83, 'center')
      doc.text(['83.0-97.0 ft'], x + 265, y + 92, 'center')
      doc.text(['27.0-31.0 pg.'], x + 265, y + 100, 'center')
      doc.text(['32-36%'], x + 265, y + 108, 'center')
      doc.text(['4.5-10.5x10^(9)/L'], x + 265, y + 116, 'center')
      doc.text(['150-400x10^(9)L'], x + 265, y + 124, 'center')
      doc.setFontSize(10);

      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text('REFERENCIA', x + 325, y + 55)
      doc.setFont(undefined, 'normal')
      doc.text(['Tiempo', 'cuagulacion', 'Tiempo', 'sangria', 'Tiempo', 'Protrombina', '% Actividad', ' ', 'INR', ' ', 'APTT', '', 'Grupo factor', ' ', 'Reticulocitos', ' ', 'IPR'], x + 296, y + 60, 'center')
      doc.setTextColor(0, 0, 0)
      doc.setFontSize(8)
      doc.text(l.d2, x + 320, y + 60, 'center')
      doc.text(l.d4, x + 320, y + 68, 'center')
      doc.text(l.d6, x + 320, y + 76, 'center')
      if (l.d8 != '')
        doc.text(l.d8 + ' %', x + 320, y + 84, 'center')
      doc.text(l.d10, x + 320, y + 92, 'center')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d34) < 22 || parseFloat(l.d34) > 31)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d34, x + 320, y + 100, 'center')
      doc.setTextColor(0, 0, 0)

      doc.text(l.d12, x + 320, y + 108, 'center')
      doc.text(l.d14, x + 320, y + 116, 'center')
      doc.text(l.d16, x + 320, y + 124, 'center')

      //doc.text([l.d2,' ',' ',l.d4,' ',l.d6,' ',l.d8,' ',l.d10,' ',l.d12,' ',l.d14,' ',l.d16,' '],x+315,y+60,'center')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10)

      doc.text(['5-10min', ' ', '1-3min', ' ', '11-14seg', ' ', '65-100%', ' ', '0.97-1.3', ' ', '22-31 seg', '', '', ' ', '0.5-2%'], x + 337, y + 60, 'center')

      doc.rect(x + 195, y + 129, 155, 40)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('VALOR REFERENCIAL', x + 300, y + 133)
      doc.text('RELATIVA               ABSOLUTA               RELATIVA               ABSOLUTA', x + 220, y + 138)
      doc.setFont(undefined, 'nomral')
      doc.text(['Cayados', 'Neutrofilos', 'Eosinofilos', 'Basofilos', 'Linfocitos', 'Monocitos', 'BLASTOS'], x + 209, y + 143, 'center')
      doc.setTextColor(0, 0, 0)
      doc.text([l.d18, l.d20, l.d22, l.d24, l.d26, l.d28, l.d30], x + 225, y + 143, 'center')
      doc.setTextColor(57, 73, 171)
      doc.text(['%', '%', '%', '%', '%', '%', '%'], x + 233, y + 143, 'center')
      doc.setTextColor(0, 0, 0)
      doc.text([l.d19, l.d21, l.d23, l.d25, l.d27, l.d29], x + 256, y + 143, 'center')
      doc.setTextColor(57, 73, 171)
      doc.text(['x10^(9)L', 'x10^(9)L', 'x10^(9)L', 'x10^(9)L', 'x10^(9)L', 'x10^(9)L'], x + 268, y + 143, 'center')
      doc.text(['0-3%', '50-70%', '0-3%', '0-1%', '25-40%', '4-8%'], x + 295, y + 143, 'center')
      doc.text(['0.00-0.35x10^(9)L', '2.50-7.35x10^(9)L', '0.00-0.35x10^(9)L', '0.00-0.15x10^(9)L', '1.25-4.20x10^(9)L', '2.00-8.40x10^(9)L'], x + 327, y + 143, 'center')
      doc.rect(x + 195, y + 170, 155, 20)

      doc.text('MORFOLOGIA DE FROTIS DE SANGRE PERIFERICA', x + 265, y + 173, 'center')
      doc.text(['Serie Roja', '', 'Serie Blanca', 'Serie Plaquetaria'], x + 223, y + 176, 'right')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d31.substring(0, 57), x + 228, y + 176, 'left')
      doc.text(l.d31.substring(57), x + 228, y + 180, 'left')
      doc.text(l.d32, x + 228, y + 185, 'left')
      doc.text(l.d33, x + 228, y + 189, 'left')
      doc.setTextColor(57, 73, 171)
      doc.rect(x + 195, y + 191, 155, 20)

      doc.text('RESPONSABLE', x + 205, y + 195, 'left')
      doc.text(l.responsable, x + 215, y + 200, 'center')
      doc.text(['Fecha de Toma de Muestra', 'Hora de Toma de Muestra:', 'Fecha de Entrega Resultado'], x + 265, y + 195, 'left')
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()

      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 310, y + 195, 'left')
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
      //          doc.output('save','HEMOGRAMA COMPLETO-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },
    ensayo(p, l) {
      var doc = new jsPDF('landscape', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 195, 3, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 195, y + 27, 155, 23)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 320, y + 10, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 225, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 012', x + 323, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('', x + 270, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 196, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 268, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 305, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 305, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 337, y + 35, 'center')
      doc.setTextColor(211, 47, 47)
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 195, y + 51, 155, 130)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(211, 47, 47)
      doc.text('METODO: INMUNOENSAYO DE FLUORESCENCIA (FIA)', x + 233, y + 55)
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10);
      doc.text('UNIDAD   TIPO MUESTRA   VALOR REF', x + 260, y + 60)
      doc.setFontSize(12);

      doc.setFont(undefined, 'normal')
      doc.setFontSize(10);
      doc.text('DIMEROS D', x + 198, y + 65, 'left')
      doc.text('FERRITINA', x + 198, y + 72, 'left')
      doc.text('IL-6', x + 198, y + 80, 'left')
      doc.text('PSA CUANTITATIVO', x + 198, y + 87, 'left')
      doc.text('PCR CUANTITATIVO', x + 198, y + 95, 'left')
      doc.text('TROPONINA I', x + 198, y + 102, 'left')
      doc.text('TROPONINA T', x + 198, y + 108, 'left')
      doc.text('B - HCG', x + 198, y + 114, 'left')
      doc.text('PROCALCITONINA', x + 198, y + 130, 'left')
      doc.text('Anti-HBS', x + 198, y + 152, 'left')
      doc.text('lg M', x + 198, y + 164, 'left')
      doc.text('lg G', x + 198, y + 174, 'left')
      doc.setFontSize(12);
      doc.setTextColor(0, 0, 0)

      if (parseFloat(l.d1) > 500.0)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d1, x + 250, y + 65, 'center')
      doc.setTextColor(0, 0, 0)
      if ((parseFloat(l.d2) < 30 || parseFloat(l.d2) > 350) && p.sexo == 'Masculino')
        doc.setTextColor(255, 0, 0)
      if ((parseFloat(l.d2) < 20 || parseFloat(l.d2) > 250) && p.sexo == 'Femenino')
        doc.setTextColor(255, 0, 0)
      doc.text(l.d2, x + 250, y + 72, 'center')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d3, x + 250, y + 80, 'center')
      if (parseFloat(l.d4) > 4)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d4, x + 250, y + 87, 'center')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d5) > 10)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d5, x + 250, y + 95, 'center')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d6) > 0.11 || parseFloat(l.d6) < 0.0)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d6, x + 250, y + 102, 'center')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d12) < 0 || parseFloat(l.d12) > 17)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d12, x + 250, y + 108, 'center')
      doc.setTextColor(0, 0, 0)


      doc.text(l.d7, x + 250, y + 118, 'center')
      doc.text(l.d8, x + 250, y + 134, 'center')

      if (parseFloat(l.d9) > 15)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d9, x + 250, y + 152, 'center')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d10) > 1.1)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d10, x + 250, y + 164, 'center')
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d11) > 8)
        doc.setTextColor(255, 0, 0)
      doc.text(l.d11, x + 250, y + 174, 'center')
      doc.setTextColor(0, 0, 0)

      doc.text('ng/ml', x + 270, y + 65, 'center')
      doc.text('ng/ml', x + 270, y + 72, 'center')
      doc.text('pg/ml', x + 270, y + 80, 'center')
      doc.text('ng/ml', x + 270, y + 87, 'center')
      doc.text('mg/l', x + 270, y + 95, 'center')
      doc.text('ng/ml', x + 270, y + 102, 'center')
      doc.text('pg/ml', x + 270, y + 108, 'center')
      doc.text('mlU/ml', x + 270, y + 118, 'center')
      doc.text('ng/ml', x + 270, y + 134, 'center')
      doc.text('mlU/ml', x + 270, y + 152, 'center')
      doc.text('IU/ml', x + 270, y + 164, 'center')
      doc.text('IU/ml', x + 270, y + 174, 'center')

      doc.setFontSize(10);
      doc.text('Plasma Citratado', x + 290, y + 65, 'center')
      doc.text('Suero', x + 290, y + 72, 'center')
      doc.text('Suero/plasma', x + 290, y + 80, 'center')
      doc.text('Suero', x + 290, y + 87, 'center')
      doc.text('Sangre Entera', x + 290, y + 95, 'center')
      doc.text('Suero', x + 290, y + 102, 'center')
      doc.text('Suero', x + 290, y + 108, 'center')
      doc.text('Suero', x + 290, y + 118, 'center')
      doc.text('Suero', x + 290, y + 134, 'center')
      doc.text('FIA', x + 290, y + 152, 'center')
      doc.text('FIA', x + 290, y + 164, 'center')
      doc.text('FIA', x + 290, y + 174, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(8);
      doc.setLineHeightFactor(1)
      doc.text(['Hasta 500 ng/ml', '', '30-350 ng/ml Varon', '20-250 ng/ml Mujer', '', '7 pg/ml', '', ' ', 'Menor a 4 ng/ml', '', ' ', '<10 mg/L', '', '0.0 - 0.11 ng/ml', '', 'hasta 17 pg/ml ', 'Mujer No Embarazada < 10 mlU/ml', 'Mujer en postmenopausia <10 mlU/ml', '',
        'PCT < 0.5 Es posible infeccion', '  bacteriana local', 'PCT 0.5 - 2 Posible infeccion', 'PCT 2 -10 Es muy probable ', ' infeccion(sepsia) a menores que se ', 'conozcan otras causas',
        'PCT > 10 Sepsia Bacteriana severa', ' o shock septico', '',
        '< 5 mlU/ml NEGATIVO', '5 - 15 mlU/ml INDETERMINADO', '> 15 mlU/ml POSITIVO', '', '< 0.9 IU/ml NEGATIVO', '0.9 - 1.1 UI/ml INTERMEDIO', '> 1.1 IU/ml POSITIVO', '',
        '< 4 IU/ml NEGATIVO', '4 - 8 UI/ml INTERMEDIO', '> 8 IU/ml POSITIVO', ''
      ], x + 305, y + 65, 'left')
      doc.setLineHeightFactor(1.15)
      doc.setFontSize(10);


      doc.rect(x + 195, y + 184, 155, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 205, y + 189, 'left')
      doc.text(l.responsable, x + 205, y + 193, 'left')
      doc.setFontSize(10);
      doc.text(['Fecha toma de Muestra', 'Hora toma Muestra', 'Fecha Entrega de Resultado'], x + 280, y + 189, 'left')
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 330, y + 189, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','INMUNOENSAYO DE FLUORESCENCIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },
    reserologia(p, l) {
      var doc = new jsPDF('landscape', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 195, 3, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 195, y + 27, 155, 23)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 320, y + 10, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 225, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 011', x + 323, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('', x + 270, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 196, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 268, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 305, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 305, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 337, y + 35, 'center')
      doc.setTextColor(211, 47, 47)
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 195, y + 51, 155, 25)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(211, 47, 47)
      doc.text('PRUEBA RAPIDA ANTIGENOS SARS COV 2', x + 233, y + 55)
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10);
      doc.text('METODO:  INMUNOGRAMATOGRAFIA CUALITATIVA', x + 220, y + 60)
      doc.setFontSize(12);

      doc.setFont(undefined, 'normal')
      doc.setFontSize(10);
      doc.setTextColor(211, 47, 47)
      doc.text(['ANTIGENO', 'SARS COV 2'], x + 250, y + 70, 'left')
      doc.setTextColor(57, 73, 171)
      doc.text(l.d1, x + 285, y + 73, 'left')
      doc.setTextColor(0, 0, 0)
      doc.rect(x + 195, y + 75, 155, 72)

      doc.text('FUNDAMENTO E INTERPRETACION DE LOS RESULTADO', x + 220, y + 80)
      doc.text(['La prueba rapida de Antigeno Nasal puede revelar una Infeccion activa de SARS-COV-2.', 'El test proporciona un resultado de "POSITIVO" o "NEGATIVO"', 'Los antigenos son parte de la estructura del virus, como las proteinas de espiga.'], x + 205, y + 85)
      doc.text(['La muestra de Hisopado nasofaringeo pasa por una linea que detecta antigenos y cambia de color, ', 'durante la prueba el anticuerpo monocianal de raton anti-SARS-CoV-2 en la muestra interactua con ', 'el Anticuerpo monocianal lgG anti-COVID-19 conjugando con particulas de color que forman un ', 'complejo de particulas de antigeno-anticuerpo. Este complejo migra en la menbrana por accion ', 'capilar hasta la linea de prueba, donde aera capturado por el anticuerpo por el anticuerpo monocianal ', 'anti-SARS-CoV-2 de raton. Una linea de prueba coloreada seria visible en la ventana de resultados ', 'si los antigenos del SARS-CoV-2 estan presentes en la muestra.'], x + 205, y + 100)
      doc.text(['Funciona mejor en la etapa inicial con una CARGA VIRAL ALTA y sintomalogia hasta los 10 dias.', 'Los resultados Negativos indica ausencia de Antigenos detectables de SARS-CoV-2 y cuando la ', 'carga viral o la cantidad de antigeno presente se encuentra debajo del limite de deteccion.'], x + 205, y + 130)

      doc.text('Los resultados Psitivos no diferencian entre SARS-COV y SARS-COV-2', x + 205, y + 145)
      doc.rect(x + 195, y + 148, 155, 10)
      doc.text(['NOTA: Las pruebas rapidas para COVID-19 NO SON CONFIRMATORIAS Los Resultados deben ', 'ser interpretados en funcion de la Clinica del paciente y dias de evolucion de la enfermedad'], x + 205, y + 152)

      //doc.text('El test proporciona un resultado de "POSITIVO" o "NEGATIVO"',x+205,y+85)
      doc.setFontSize(10);


      doc.rect(x + 195, y + 160, 155, 10)
      doc.setTextColor(57, 73, 171)
      doc.text('OBSERVACION', x + 200, y + 164)
      doc.setTextColor(0, 0, 0)
      doc.text(l.d2, x + 200, y + 168)
      doc.rect(x + 195, y + 170, 155, 30)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 205, y + 175, 'left')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 205, y + 180, 'left')
      doc.setTextColor(57, 73, 171)
      doc.setFontSize(10);
      doc.text(['Fecha toma de Muestra', 'Hora toma Muestra', 'Fecha Entrega de Resultado'], x + 280, y + 175, 'left')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 325, y + 175, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','RESULTADO SEROLOGIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },
    embarazo(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 23)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 013', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('PRUEBA RAPIDA INMUNOCROMATOGRAFICA', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA', 'METODO'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.text('PRUEBA RAPIDA INMUNOCROMATOGRAFICA', x + 100, y + 47, 'center')
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 51, 205, 50)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('TEST EMBARAZO EN SANGRE', x + 25, y + 60)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d1, x + 100, y + 65, 'center')
      doc.setTextColor(57, 73, 171)
      doc.text('F.U.M.', x + 45, y + 75, 'center')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d2, x + 100, y + 80, 'center')

      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text('OBSERVACIONES:', x + 20, y + 90)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text(l.d3, x + 30, y + 95, 'left')

      doc.rect(x + 5, y + 102, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 110)
      doc.setFont(undefined, 'NORMAL')
      doc.setTextColor(0, 0, 0)
      doc.text(l.responsable, x + 8, y + 115)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(57, 73, 171)
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 110, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 110, 'left')
      doc.setTextColor(0, 0, 0)

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','TEST EMBARAZO-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },
    imprimirlaboratorio(p, l) {
      console.log(l)
      if (l.tipo_id == 1)
        this.hemograma(p, l)
      if (l.tipo_id == 2)
        this.sanguinea(p, l)
      if (l.tipo_id == 3)
        this.orina(p, l)
      if (l.tipo_id == 4)
        this.uretral(p, l)
      if (l.tipo_id == 5)
        this.vaginal(p, l)
      if (l.tipo_id == 6)
        this.heces(p, l)
      if (l.tipo_id == 7)
        this.simple(p, l)
      if (l.tipo_id == 8)
        this.seriado(p, l)
      if (l.tipo_id == 9)
        this.lgmserologia(p, l)
      if (l.tipo_id == 10)
        this.labserologia(p, l)
      if (l.tipo_id == 11)
        this.reserologia(p, l)
      if (l.tipo_id == 12)
        this.ensayo(p, l)
      if (l.tipo_id == 13)
        this.embarazo(p, l)
      if (l.tipo_id == 14)
        this.alcoholemia(p, l)
      if (l.tipo_id == 15)
        this.widal(p, l)
      if (l.tipo_id == 16)
        this.multidrogas(p, l)
      if (l.tipo_id == 17)
        this.glicosilada(p, l)
      if (l.tipo_id == 18)
        this.gasometria(p, l)
      if (l.tipo_id == 19)
        this.hepatitis(p, l)
      if (l.tipo_id == 20)
        this.toxoplasmosis(p, l)
      if (l.tipo_id == 21)
        this.hemodialisis(p, l)

      if (l.tipo_id == 22)
        this.hemodialisistri(p, l)
      if (l.tipo_id == 23)
        this.miscelaneo(p, l)
      if (l.tipo_id == 24)
        this.hierro(p, l)
      if (l.tipo_id == 25)
        this.imprimirCultivo(p, l)
      if (l.tipo_id == 26)
        this.imprimirInmunologia(p, l)
      if (l.tipo_id == 27)
        this.imprimirSanguinea(p, l)
      if (l.tipo_id == 28)
        this.imprimirPlomo(p, l)
      if (l.tipo_id == 31)
        this.imprimirInmunocromatografia(p, l)
      if (l.tipo_id == 32)
        this.imprimirQuimicas(p, l)
      if (l.tipo_id == 33)
        this.imprimirInmunologiaEna(p, l)
      //    console.log(p)
      // console.log(l)
      return false
      var doc = new jsPDF('landscape', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 195, 3, 70, 20)
      let x = 0
      let y = 0
      doc.setDrawColor(120);
      doc.rect(195, 27, 155, 30);
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171);
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 320, y + 10, 'center')
      doc.setTextColor(211, 47, 47);
      doc.text('N Registro CODEDLAB 000045', x + 225, y + 25)
      doc.setTextColor(57, 73, 171);
      doc.text('HEMOGRAMA COMPLETO', x + 280, y + 30, 'center')
      doc.setTextColor(211, 47, 47);
      doc.text('Form. 001', x + 323, y + 30)
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA', 'METODO'], x + 221, y + 33)
      doc.setTextColor(0, 0, 0);
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.paterno + ' ' + l.doctor.materno + ' ' + l.doctor.nombre, l.tipomuestra], x + 275, y + 33, 'center')
      doc.setTextColor(57, 73, 171);
      doc.text('CONTADOR Hematologico MINDRAY BC 5130', x + 305, y + 44, 'center')
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

    },
    imprimirQuimicas(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 17)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 007', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('PLOMO EN SANGRE', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 44, 205, 94)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      // doc.rect(x+75, y+60, 50, 7 )
      // doc.rect(x+75, y+70, 50, 7 )
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d1) > 50)
        doc.setTextColor(255, 0, 0)
      doc.setTextColor(57, 73, 171)
      doc.text('METODO: OXIDASA/PEROXIDASA', x + 30, y + 50)
      doc.text('DETERMINACION DE TOLERANCIA A LA GLUCOSA', x + 30, y + 55)
      // doc.text('RESULTADO',x+80,y+55)
      doc.text('VALOR DE REF', x + 130, y + 55)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)

      doc.text('Glucosa en ayunas', x + 30, y + 65)
      doc.text(l.d1 + 'mg/dl', x + 80, y + 65)
      doc.text('70-105 mg/dl', x + 130, y + 65)

      doc.setTextColor(0, 0, 0)
      doc.text('Glucosa a los 30 min', x + 30, y + 70)
      doc.text(l.d2 + 'mg/dl', x + 80, y + 70)
      doc.text('120-170 mg/dl', x + 130, y + 70)

      doc.setTextColor(0, 0, 0)
      doc.text('Glucosa a los 60 min', x + 30, y + 75)
      doc.text(l.d3 + 'mg/dl', x + 80, y + 75)
      doc.text('100-140 mg/dl', x + 130, y + 75)

      doc.setTextColor(0, 0, 0)
      doc.text('Glucosa a los 90 min', x + 30, y + 80)
      doc.text(l.d4 + 'mg/dl', x + 80, y + 80)
      doc.text('70-110 mg/dl', x + 130, y + 80)


      var chartConfig = {
        type: 'line',
        data: {
          labels: ['', '', '', ''],
          datasets: [{
            label: 'Datos de glucosa',
            data: [parseInt(l.d1), parseInt(l.d2), parseInt(l.d3), parseInt(l.d4)],
            borderColor: 'cyan',
            borderWidth: 2
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      };

// Crear la URL usando QuickChart
      var chartUrl = 'https://quickchart.io/chart?c=' + encodeURIComponent(JSON.stringify(chartConfig));

      var img = new Image();
      img.src = chartUrl;
      img.onload = function () {
        doc.addImage(img, 'JPEG', 25, 85, 100, 50);  // Posición y tamaño ajustables
        window.open(doc.output('bloburl'), '_blank');
      };

      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)


      doc.rect(x + 5, y + 138, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 145)
      doc.setFont(undefined, 'NORMAL')
      doc.text(l.responsable, x + 8, y + 149)
      doc.setFont(undefined, 'normal')
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      //window.open(doc.output('bloburl'), '_blank');
      // window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','ALCOHOLEMIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },
    imprimirInmunocromatografia(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 17)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 007', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('INMUNOCROMATOGRAFIA', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 44, 205, 94)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.rect(x + 75, y + 60, 50, 7)
      doc.rect(x + 75, y + 70, 50, 7)
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d1) > 50)
        doc.setTextColor(255, 0, 0)
      doc.setTextColor(57, 73, 171)
      doc.text('METODO: INMUNOCROMATOGRAFIA', x + 30, y + 50)
      doc.text('INFLUENZA', x + 30, y + 55)
      doc.text('RESULTADO', x + 80, y + 55)
      doc.text('VALOR REFERENCIA', x + 130, y + 55)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text('INFLUENZA A', x + 30, y + 65)
      doc.text(l.d1, x + 80, y + 65)
      doc.text('NEGATIVO', x + 130, y + 65)

      doc.text('INFLUENZA B', x + 30, y + 75)
      doc.text(l.d2, x + 80, y + 75)
      doc.text('NEGATIVO', x + 130, y + 75)
      // doc.text(' METODO DE INMUNOENSAYO DE FLUORESENCIA',x+40,y+55)
      // doc.setTextColor(0,0,0)
      // doc.text(l.d1+' ng/ml',x+90,y+65)
      // doc.setTextColor(0,0,0)
      // doc.setFont(undefined, 'normal')
      // doc.setTextColor(0,0,0)
      //
      // doc.setTextColor(255,0,0)
      // doc.setFont(undefined, 'bold')
      // doc.text('CRITERIO DE VALORACION',x+40,y+85,'center')
      // doc.setTextColor(57,73,171)
      // doc.text('INDICE BIOLOGICO DE EXPOSICION Y NIVELES DE INTERVENCION (IBE)',x+50,y+90)
      // doc.setFont(undefined, 'normal')
      // doc.text('NIVEL NORMAL 1.00 - 100 ng/ml DE PLOMO (CONTROL ANUAL)',x+50,y+95)

      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)


      doc.rect(x + 5, y + 138, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 145)
      doc.setFont(undefined, 'NORMAL')
      doc.text(l.responsable, x + 8, y + 149)
      doc.setFont(undefined, 'normal')
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      //window.open(doc.output('bloburl'), '_blank');
      window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','ALCOHOLEMIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);
    },
    imprimirPlomo(p, l) {
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 17)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 007', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('PLOMO EN SANGRE', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, l.solicitud + ''], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      //fin datos paciete
      //inicio datos
      doc.rect(x + 5, y + 44, 205, 94)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.rect(x + 75, y + 60, 50, 7)
      doc.setTextColor(0, 0, 0)
      if (parseFloat(l.d1) > 50)
        doc.setTextColor(255, 0, 0)
      doc.setTextColor(57, 73, 171)
      doc.text('METODO: Automatizado', x + 30, y + 50)
      doc.text(' METODO DE INMUNOENSAYO DE FLUORESENCIA', x + 40, y + 55)
      doc.setTextColor(0, 0, 0)
      doc.text(l.d1 + ' ng/ml', x + 90, y + 65)
      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)

      doc.setTextColor(255, 0, 0)
      doc.setFont(undefined, 'bold')
      doc.text('CRITERIO DE VALORACION', x + 40, y + 85, 'center')
      doc.setTextColor(57, 73, 171)
      doc.text('INDICE BIOLOGICO DE EXPOSICION Y NIVELES DE INTERVENCION (IBE)', x + 50, y + 90)
      doc.setFont(undefined, 'normal')
      doc.text('NIVEL NORMAL 1.00 - 100 ng/ml DE PLOMO (CONTROL ANUAL)', x + 50, y + 95)

      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)


      doc.rect(x + 5, y + 138, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('RESPONSABLE', x + 6, y + 145)
      doc.setFont(undefined, 'NORMAL')
      doc.text(l.responsable, x + 8, y + 149)
      doc.setFont(undefined, 'normal')
      doc.text(['FECHA DE TOMA DE MUESTRA', 'HORA DE TOMA DE MUESTRA', 'FECHA ENTREGA RESULTADO'], x + 140, y + 145, 'center')
      doc.setTextColor(0, 0, 0)
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"), l.horatoma, moment(l.fechaimp).format("DD-MM-YYYY")], x + 170, y + 145, 'left')

      //$( '#docpdf' ).attr('src', doc.output('datauristring'));
      //window.open(doc.output('bloburl'), '_blank');
      window.open(doc.output('bloburl'), '_blank');
      //            doc.output('save','ALCOHOLEMIA-'+p.nombre+' '+p.paterno+' '+p.materno.pdf);

    },

    getImage(event) {
      this.imagen = event.target.files[0];
    },
    onmodimagen() {
      var data = new FormData();
      data.append('id', this.labmod.id);
      data.append('imagen', this.imagen);
      this.$axios.post(process.env.API + '/updateimagen', data).then(res => {
        // console.log(res.data)
        this.mispacientes()
        this.dialogmodimg = false
        this.resetlabo()
        this.imagen = ''
      })
    },
    createLaboratorio() {
      if (this.doctor.id == undefined) {
        this.$q.dialog({
          title: 'Error',
          message: 'Seleccione un Doctor',
          color: 'red',
        })
        return false
      }

      if (this.tipo.label == 'INMUNOLOGIA') {

        this.laboratorio.tipo_id = this.tipo.id
        this.laboratorio.paciente_id = this.paciente.id
        this.laboratorio.doctor_id = this.doctor.id
        this.laboratorio.user_id = this.$store.state.login.user.id
        this.laboratorio.responsable = this.user
        this.laboratorio.antibiograma = this.detalle
        this.loading = true
        this.$axios.post(process.env.API + '/inmunologia', this.laboratorio).then(res => {
          console.log(res.data)
          this.mispacientes()
          this.dialoglaboratorio = false
          this.resetlabo()
          this.muestras();
          this.loading = false
        })
        return false
      }

      if (this.tipo.label == 'Q SANGUINEA') {

        this.laboratorio.tipo_id = this.tipo.id
        this.laboratorio.paciente_id = this.paciente.id
        this.laboratorio.doctor_id = this.doctor.id
        this.laboratorio.user_id = this.$store.state.login.user.id
        this.laboratorio.responsable = this.user
        this.laboratorio.antibiograma = this.detalle
        this.loading = true
        this.$axios.post(process.env.API + '/sanguinea', this.laboratorio).then(res => {
          console.log(res.data)
          this.mispacientes()
          this.dialoglaboratorio = false
          this.resetlabo()
          this.muestras();
          this.loading = false
        })
        return false
      }

      if (this.tipo.label == 'CULTIVO Y ANTIBIOGRAMA') {

        this.laboratorio.tipo_id = this.tipo.id
        this.laboratorio.paciente_id = this.paciente.id
        this.laboratorio.doctor_id = this.doctor.id
        this.laboratorio.user_id = this.$store.state.login.user.id
        this.laboratorio.responsable = this.user
        this.laboratorio.antibiograma = this.detalle
        this.loading = true
        this.$axios.post(process.env.API + '/cultivo', this.laboratorio).then(res => {
          console.log(res.data)
          this.mispacientes()
          this.dialoglaboratorio = false
          this.resetlabo()
          this.muestras();
          this.loading = false
        })
        return false
      }

      this.laboratorio.tipo_id = this.tipo.id
      this.laboratorio.paciente_id = this.paciente.id
      this.laboratorio.doctor_id = this.doctor.id
      this.laboratorio.user_id = this.$store.state.login.user.id
      this.laboratorio.responsable = this.user
      if (this.tipo.label == 'HEMOGRAMA COMPLETO') {
        if (this.laboratorio.d18 != '' ||
          this.laboratorio.d20 != '' ||
          this.laboratorio.d22 != '' ||
          this.laboratorio.d24 != '' ||
          this.laboratorio.d26 != '' ||
          this.laboratorio.d28 != '' ||
          this.laboratorio.d30 != '') {
          let total = 0
          if (this.es100) {
            this.$q.notify({
              message: 'No suma el 100 % ',
              icon: 'info',
              color: 'red'
            })
            return false;
          }
        }
      }

      var data = new FormData();
      data.append('imagen', this.imagen);
      data.append('tipomuestra', this.laboratorio.tipomuestra)
      data.append('fechatoma', this.laboratorio.fechatoma)
      data.append('horatoma', this.laboratorio.horatoma)
      data.append('solicitud', this.laboratorio.solicitud)
      data.append('d1', this.laboratorio.d1)
      data.append('d2', this.laboratorio.d2)
      data.append('d3', this.laboratorio.d3)
      data.append('d4', this.laboratorio.d4)
      data.append('d5', this.laboratorio.d5)
      data.append('d6', this.laboratorio.d6)
      data.append('d7', this.laboratorio.d7)
      data.append('d8', this.laboratorio.d8)
      data.append('d9', this.laboratorio.d9)
      data.append('d10', this.laboratorio.d10)
      data.append('d11', this.laboratorio.d11)
      data.append('d12', this.laboratorio.d12)
      data.append('d13', this.laboratorio.d13)
      data.append('d14', this.laboratorio.d14)
      data.append('d15', this.laboratorio.d15)
      data.append('d16', this.laboratorio.d16)
      data.append('d17', this.laboratorio.d17)
      data.append('d18', this.laboratorio.d18)
      data.append('d19', this.laboratorio.d19)
      data.append('d20', this.laboratorio.d20)
      data.append('d21', this.laboratorio.d21)
      data.append('d22', this.laboratorio.d22)
      data.append('d23', this.laboratorio.d23)
      data.append('d24', this.laboratorio.d24)
      data.append('d25', this.laboratorio.d25)
      data.append('d26', this.laboratorio.d26)
      data.append('d27', this.laboratorio.d27)
      data.append('d28', this.laboratorio.d28)
      data.append('d29', this.laboratorio.d29)
      data.append('d30', this.laboratorio.d30)
      data.append('d31', this.laboratorio.d31)
      data.append('d32', this.laboratorio.d32)
      data.append('d33', this.laboratorio.d33)
      data.append('d34', this.laboratorio.d34)
      data.append('d35', this.laboratorio.d35)
      data.append('d36', this.laboratorio.d36)
      data.append('d37', this.laboratorio.d37)
      data.append('d38', this.laboratorio.d38)
      data.append('d39', this.laboratorio.d39)
      data.append('d40', this.laboratorio.d40)
      data.append('d41', this.laboratorio.d41)
      data.append('d42', this.laboratorio.d42)
      data.append('d43', this.laboratorio.d43)
      data.append('d44', this.laboratorio.d44)
      data.append('d45', this.laboratorio.d45)
      data.append('responsable', this.laboratorio.responsable)
      data.append('tipo_id', this.laboratorio.tipo_id)
      data.append('paciente_id', this.laboratorio.paciente_id)
      data.append('user_id', this.laboratorio.user_id)
      data.append('doctor_id', this.laboratorio.doctor_id)
      this.loading = true
      this.$axios.post(process.env.API + '/laboratorio', data).then(res => {
        // console.log(res.data)
        this.mispacientes()
        this.dialoglaboratorio = false
        this.resetlabo()
        this.muestras()
        this.loading = false
      })
    },
    modificar(paciente) {
      this.datos2 = paciente
      this.seguro = this.datos2.seguro
      this.seguro.label = this.seguro.nombre
      this.dialog_mod = true
      //console.log(this.datos2)
    },
    frmlaboratorio(paciente) {
      this.paciente = paciente
      this.resetlabo()
      this.dialoglaboratorio = true

    },
    modpaciente() {
      this.datos2.seguro = this.seguro.id
      this.$q.loading.show()
      this.$axios.put(process.env.API + '/paciente/' + this.datos2.id, this.datos2).then(res => {
        this.$q.notify({
          message: 'Se Modificado correctamente',
          color: 'green',
          icon: 'check',
        })
        this.dialog_mod = false
        this.mispacientes()
        this.$q.loading.hide()
      })
    },
    onSubmit() {
      this.dato.seguro = this.seguro.id
      this.loading = true
      this.$axios.post(process.env.API + '/valpaciente', this.dato).then(res => {
        //console.log(res.data.length > 0)
        //return false
        if (res.data.length > 0) {
          this.$q.notify({
            message: 'El paciente existe',
            color: 'red',
            icon: 'info',
          })
          this.loading = false
          return false
        } else {
          this.$q.loading.show()
          this.$axios.post(process.env.API + '/paciente', this.dato).then(res => {
            this.dato = {}
            this.$q.notify({
              message: 'Se registro correctamente',
              color: 'green',
              icon: 'check',
            })
            this.alert = false
            this.mispacientes()
            this.$q.loading.hide()
            this.loading = false

          })
        }
      })


      //   .catch(err=>{
      //   this.$q.loading.hide()
      //   this.$q.notify({
      //     message:err.response.data.message,
      //     color:'red',
      //     icon:'error'
      //   })
      // })
    },
    deleteRow(props) {
      this.datos2 = props;
      //console.log(this.dato2);
      this.$q.dialog({
        title: 'Eliminar Paciente',
        message: 'Esta Seguro de Eliminar ?',
        cancel: true,
      }).onOk(() => {
        this.$q.loading.show()
        this.$axios.delete(process.env.API + '/paciente/' + this.datos2.id).then(res => {
          this.$q.notify({
            message: 'Se elimino correctamente',
            color: 'green',
            icon: 'check'
          })
          this.mispacientes();
        })
      })
    },
    eliminar(props) {
      console.log(props)
      //return false
      this.$q.dialog({
        title: 'Eliminar Formulario',
        message: 'Esta seguro de eliminar?',
        cancel: true,
      }).onOk(() => {
        this.$q.loading.show()
        // console.log('>>>> OK')
        //console.log(props.row)
        if (props.tipo.id == 26) {
          this.$axios.delete(process.env.API + '/inmunologia/' + props.id).then(res => {
            this.dialog_lab = false;
            this.$q.notify({
              message: 'Elimino el registro',
              icon: 'delete',
              color: 'green',
              icon: 'check'
            })
            this.dialoglistlabo = false
            this.consultarLab()
            this.mispacientes()
            this.consultarLab()
          }).finally(() => {
            this.$q.loading.hide()
          })
        }
        this.$axios.delete(process.env.API + '/laboratorio/' + props.id, {
          params: {
            tipo: props.tipoLabo
          }
        }).then(res => {
          this.dialog_lab = false;
          this.$q.notify({
            message: 'Elimino el registro',
            icon: 'delete',
            color: 'green',
            icon: 'check'
          })
          this.dialoglistlabo = false
          this.mispacientes()
          this.consultarLab()
        }).finally(() => {
          this.$q.loading.hide()
        })

      }).onOk(() => {
        // console.log('>>>> second OK catcher')
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })
    },
    listdoctor() {
      this.doctors = [];
      this.$axios.get(process.env.API + '/doctor').then(res => {
        // console.log(res.data);
        res.data.forEach(e => {
          this.doctors.push({label: e.nombre + ' ' + e.paterno + ' ' + e.materno + ' - ' + e.especialidad, value: e.id})
        });
        this.requerido = this.doctors[0];
      })
    },
    listusers() {
      this.usuarios = ['']
      this.$axios.post(process.env.API + '/listuser').then(res => {
        res.data.forEach(element => {
          this.usuarios.push(element.name);
        });
        this.user = this.usuarios[0]
        //console.log(this.usuarios)
      })

    },
    listseguro() {
      this.seguros = []
      this.seguro = {label: ''}
      this.$axios.get(process.env.API + '/listseguro').then(res => {
        res.data.forEach(r => {
          r.label = r.nombre
          this.seguros.push(r);
        });

      })

    },
    mispacientes() {
      this.loading = true
      // this.$q.loading.show()
      this.$axios.get(process.env.API + '/paciente', {
        params: {
          page: this.current,
          search: this.filter
        }
      }).then(res => {
        // this.$q.loading.hide()
        this.pacientes = []
        this.current = res.data.current_page
        this.last_page = res.data.last_page
        res.data.data.forEach(r => {
          let d = r
          // console.log(r)
          //var nacimiento=moment(r.fechanac)
          //this.fechacalculo=moment(r.fechanac)
          //var hoy=moment()
          var anios = this.calcular3(r.fechanac)
          d.tiempo = anios
          d.paciente = r.nombre + ' ' + r.paterno + ' ' + r.materno
          this.pacientes.push(d)
        })
      }).finally(() => {
        this.loading = false
      })
    },
    imprimirCultivo(p, l) {
      let cadena = "<style>\
        .tab1{width:100% }\
        .tab2{width:100%; border:0.5px solid; }\
        .tab3{width:100%; border:0.5px solid; }\
        .img1{width: 300px; height:75px;}\
        .enc1{font-size:14px ; color: blue; text-align:center}\
        .enc2{font-size:18px ; color: blue; text-align:center;font-weight: bold;}\
        footer {\
      position: absolute;\
      bottom: 0;\
      width: 100%;\
      height: 60px;\
      color: blue;  }\
        </style>\
        <table class='tab1'>\
        <tr><td style='width:50%'><img class='img1' src='img/natividad.jpeg' /></td>\
        <td class='enc1'><b>SERVICIO DE LABORATORIO</b> <br> Bolivar N°753 entre Arica e Iquique <br> Telf: 5254721 Fax: 52-83667 <br> Emergencia las 24 horas del dia.<br>\
        <span style='color:red'>Nº Registro CODEDLAB 000045 <br>Form. 025</span></td></tr>\
        </table>"
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      if (l.fechaimp == null || l.fechaimp == undefined) l.fechaimp = moment()
      cadena += "<div class='enc2'>RESULTADO <br> CULTIVO Y ANTIBIOGRAMA</div>\
        <table class='tab2'>\
        <tr><th>PACIENTE: </th><td>" + p.paciente + "</td><th>EDAD: </th><td>" + anio + "</td></tr>\
        <tr><th>REQUERIDO POR: </th><td>" + l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno + "</td><th>SEXO: </th><td>" + p.sexo + "</td></tr>\
        <tr><th>TIPO DE MUESTRA: </th><td>" + l.tipomuestra + "</td><th>N PACIENTE: </th><td>" + l.solicitud + "</td></tr>\
        <tr><th>FECHA DE RECEPCION: </th><td>" + moment(l.fechatoma).format("DD-MM-YYYY") + "</td><th>FECHA ENTREGA: </th><td>" + moment(l.fechaimp).format("DD-MM-YYYY") + "</td></tr>\
        </table>"
      /*
      doc.text('OBSERVACION',x+20,y+245,'left')
      doc.text(l.observacion,x+10,y+240,'left')

      doc.text('RESPONSABLE',x+15,y+260,'left')
      doc.text(l.responsable,x+15,y+265,'left')
      doc.text(['Fecha toma de Muestra','Hora toma Muestra','Fecha Entrega de Resultado'],x+120,y+260,'left')
      doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,moment(l.fechaimp).format("DD-MM-YYYY")],x+170,y+260,'left')
      */
      cadena += "<body style='font-size:12px;padding: 1cm 2cm 1cm 3cm;'><br>"

      if (l.examenDirecto != '' && l.examenDirecto != undefined) {
        cadena += "<b style='font-size:16px;'>EXAMEN DIRECTO</b><br>" + l.examenDirecto + "<br>"
      }
      if (l.tincionGram != '' && l.tincionGram != undefined) {
        cadena += "<br><b style='font-size:16px;'>TINCION GRAM</b><br>" + l.tincionGram + "<br>"
      }
      if (l.microorganizmo != '' && l.microorganizmo != undefined) {
        cadena += "<br><b style='font-size:16px;'>MICROORGANIZMO IDENTIFICADO</b><br>" + l.microorganizmo + "<br>"
      }
      if (l.antibioticos.length > 0) {
        cadena += "<b>ANTIBIOGRAMA</b><br>"
        cadena += "<table class='tab3'><thead><tr><th>ANTIBIOTICO</th><th>INTERPRETACION</th></tr></thead><tbody>"
        l.antibioticos.forEach(r => {
          console.log(r)
          cadena += "<tr><td>" + r.nombre + "</td><td>" + r.pivot.interpretacion + "</td></tr>"
        });
        cadena += "</tbody></table>"
      }
      cadena += "<div>" + l.observacion + "</div>\
      <footer><div style='text-align:center; color:black; '>RESPONSABLE DEL ANALISIS</div><br>CLINICA NATIVIDAD CLINICA DE LA FAMILIA</footer></body>"
      document.getElementById('myelement').innerHTML = cadena
      const d3 = new Printd()
      d3.print(document.getElementById('myelement'))
    },
    imprimirInmunologia2(p, l) {
      console.log(p)
      console.log(l)
      var doc = new jsPDF('P', undefined, 'legal')
      doc.setFont("arial");
      doc.setFontSize(10);
      var img = new Image()
      img.src = 'img/natividad.jpeg'
      doc.addImage(img, 'jpg', 5, 2, 70, 20)
      let x = 0
      let y = 0
      //inicio datos paciete
      doc.setDrawColor(120);
      doc.rect(x + 5, y + 27, 205, 20)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text(['SERVICIO DE LABORATORIO', 'Bolivar N°753 entre Arica e Iquique', 'Telf: 5254721 Fax: 52-83667', 'Emergencia las 24 horas del dia.'], x + 175, y + 8, 'center')
      doc.setTextColor(195, 47, 47)
      doc.text('N Registro CODEDLAB 000045', x + 150, y + 25)
      doc.setTextColor(211, 47, 47)
      doc.text('Form. 002', x + 190, y + 30)
      doc.setTextColor(57, 73, 171)
      doc.text('REPORTE INMUNOLOGIA', x + 100, y + 30, 'center')
      doc.text(['PACIENTE', 'REQUERIDO POR', 'TIPO MUESTRA'], x + 6, y + 35)

      doc.setTextColor(0, 0, 0)
      doc.setFont(undefined, 'normal')
      doc.text([p.paciente, l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno, l.tipomuestra + ''], x + 70, y + 35, 'center')
      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text(['EDAD', 'SEXO'], x + 130, y + 35)
      doc.setTextColor(211, 47, 47)
      doc.text('N PACIENTE', x + 130, y + 43)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)

      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      doc.text([anio + '', p.sexo, (l.solicitud == null ? '' : l.solicitud + '')], x + 160, y + 35, 'center')
      doc.setTextColor(57, 73, 171)


      doc.rect(x + 5, y + 48, 205, 77)
      doc.setFont(undefined, 'bold')
      doc.setTextColor(57, 73, 171)
      doc.text('          ENSAYO                      METODO                   RESULTADO                  UNIDAD                     RANGO              REFERENCIA', x + 6, y + 52)

      // let countAntibiotico = l.antibioticos.length;
      // let promises = [];

      l.antibioticos.forEach((r, i) => {
        doc.setFont(undefined, 'normal');
        doc.setTextColor(0, 0, 0);
        doc.text(r.nombre, x + 10, y + 57 + (i * 12));
        doc.text(r.metodo == null ? '' : r.metodo + '', x + 40, y + 57 + (i * 12));
        doc.text(r.pivot?.resultado, x + 85, y + 57 + (i * 12));
        doc.text(r.unidad, x + 120, y + 57 + (i * 12));
        doc.text(r.rangoMin + '-' + r.rangoMax, x + 150, y + 57 + (i * 12));
        doc.text(r.descripcion + '', x + 185, y + 57 + (i * 12));

        // let promise = new Promise((resolve) => {
        //   doc.html('<div style="border:1px solid black;width: 50px;font-size: 3px;line-height: 0.8;margin-top: '+(i+5)+'px ">dasa'+r.descripcion+'</div>', {
        //     callback: function (doc) {
        //       resolve();
        //     },
        //     x: x + 170,
        //     y: y+57+(i*12),
        //   });
        // });
        //
        // promises.push(promise);
      });

      // Promise.all(promises).then(() => {
      window.open(doc.output('bloburl'), '_blank');
      // });

      doc.setTextColor(57, 73, 171)
      doc.setFont(undefined, 'bold')
      doc.text('OBSERVACIONES:', x + 6, y + 129)
      doc.setFont(undefined, 'normal')
      doc.setTextColor(0, 0, 0)
      doc.text(l.d31 == null ? '' : l.d31 + '', x + 6, y + 132, 'left')
      //
      // doc.rect(x+5, y+126, 205, 25)
      // doc.setFont(undefined, 'bold')
      // doc.setTextColor(57,73,171)
      // doc.text('RESPONSABLE',x+6,y+139)
      // doc.setFont(undefined, 'NORMAL')
      // doc.text(l.responsable,x+8,y+144)
      // doc.setFont(undefined, 'normal')
      // doc.text(['FECHA DE TOMA DE MUESTRA','HORA DE TOMA DE MUESTRA','FECHA ENTREGA RESULTADO'],x+140,y+139,'center')
      // doc.setTextColor(0,0,0)
      // if(l.fechaimp==null || l.fechaimp == undefined ) l.fechaimp =  moment()
      // doc.text([moment(l.fechatoma).format("DD-MM-YYYY"),l.horatoma,moment(l.fechaimp).format("DD-MM-YYYY")],x+170,y+139,'left')
      // setTimeout(() => {
      //   window.open(doc.output('bloburl'), '_blank');
      // }, 1000);
    },
    imprimirInmunologiaEna(p, l) {
      let cadena = "<style>\
        table{margin-bottom:10px;}\
        .tab1{width:100%;font-family: Arial; }\
        .tab2{width:100%; border:0.5px solid; font-size:12px;font-family: Arial;}\
        .tab3{width:100%; border:0.5px solid; font-size:14px;font-family: Arial;}\
        .tab4{width:100%; border:0.5px solid; font-size:14px;font-family: Arial;padding-right:1cm}\
        .img1{width: 200px; height:55px; padding-left:10px;font-family: Arial;}\
        .enc1{font-size:10px ; color: #3949AB; text-align:center;font-family: Arial;}\
        .enc2{font-size:15px ; color: #3949AB; text-align:center;font-weight: bold;font-family: Arial; }\
        footer {position: absolute;bottom: 1cm; width: 100%; height: 60px; color: #3949AB;  }\
        .tab2 th{color: #3949AB; text-align:left}\
        .tab3 thead{color: #3949AB; text-align:center}\
        .tab2 td{ text-align:center}\
        .campo2{color: #D32F2F; text-align:left}\
        </style>\
        <div style='padding-left: 1cm;padding-right: 1cm;padding-top: 0.5cm;padding-bottom: 1cm;'>\
        <table class='tab1'>\
        <tr><td style='width:50%'><img class='img1' src='img/natividad.jpeg' /></td>\
        <td class='enc1'><b>SERVICIO DE LABORATORIO</b> <br> Bolivar N°753 entre Arica e Iquique <br> Telf: 5254721 Fax: 52-83667 <br> Emergencia las 24 horas del dia.<br>\
        <span style='color:red'>Nº Registro CODEDLAB 000045 <br>Form. 025</span></td></tr>\
        </table>"
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      let imp = moment(l.fechaimp).format("DD-MM-YYYY")
      if (l.fechaimp == null || l.fechaimp == undefined) imp = moment().format("DD-MM-YYYY")
      cadena += "<div class='enc2'>RESULTADO <br> INMUNOLOGIA ENA</div>\
        <table class='tab2'>\
        <tr><th>PACIENTE: </th><td>" + p.paciente + "</td><th>EDAD: </th><td>" + anio + "</td></tr>\
        <tr><th>REQUERIDO POR: </th><td>" + l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno + "</td><th>SEXO: </th><td>" + p.sexo + "</td></tr>\
        <tr><th>TIPO DE MUESTRA: </th><td>" + l.tipomuestra + "</td><th style='color: #D32F2F;'>N PACIENTE: </th><td>" + p.id + "</td></tr>\
        </table>"
      cadena += "<body style='font-size:12px;'>"

      cadena += "<table class='tab3'>" +
        "<thead>" +
        "<tr>" +
        "<th style='width: 25%'></th>" +
        "<th style='width: 9%'></th>" +
        "<th style='width: 33%'>RESULTADO</th>" +
        "<th style='width: 33%'>VALOR DE REFERENCIA</th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>" +
        "<tr>" +
        "<td rowspan='6'>ANTIGENO NUCLEAR EXTRAIBLE (PERFIL ENA)</td>" +
        "<td>RNP/Sm</td>" +
        "<td style='text-align: center'>" + l.d1 + "</td>" +
        "<td rowspan='6'>" +
        "NEGATIVO MENOR O IGUAL A 0.9 <br>" +
        "POSITIVO MENOR O IGUAL A 1.1 <br>" +
        "INDETERMINADO 0.91 - 1.09 <br>" +
        "</td>" +
        "</tr>" +
        "<tr>" +
        "<td>SM</td>" +
        "<td style='text-align: center'>" + l.d2 + "</td>" +
        "</tr>" +
        "<tr>" +
        "<td>J0-1</td>" +
        "<td style='text-align: center'>" + l.d3 + "</td>" +
        "</tr>" +
        "<tr>" +
        "<td>SCI-70</td>" +
        "<td style='text-align: center'>" + l.d4 + "</td>" +
        "</tr>" +
        "<tr>" +
        "<td>SS-A</td>" +
        "<td style='text-align: center'>" + l.d5 + "</td>" +
        "</tr>" +
        "<tr>" +
        "<td>SS-B</td>" +
        "<td style='text-align: center'>" + l.d6 + "</td>" +
        "</tr>" +
        "</tbody>" +
        "</table>"

      cadena += "<div style='border-top: 0.5px solid; border-right:  0.5px solid; border-left: 0.5px solid;'></div>\
      <div style='text-align:center; color:black; '>\
        <table class='tab4'><tr><td style='width:50%;color: #3949AB; text-align:left;vertical-align:top'><b>RESPONSABLE DE ANALISIS</b></td><td style='text-align:left'><table><tr><td><b style='color: #3949AB;'>FECHA TOMA DE MUESTRA:</b></td><td>" + moment(l.fechatoma).format("DD-MM-YYYY") + "</td><tr><td><b style='color: #3949AB;'>HORA TOMA DE MUESTRA:</b></td><td>" + l.horatoma + "</td></tr><tr><td><b style='color: #3949AB;'>FECHA ENTREGA:</b></td><td>" + imp + "</td></tr></table><br></td></tr>\
        </table>\
        </div></body></div>"
      document.getElementById('myelement').innerHTML = cadena
      const d3 = new Printd()
      d3.print(document.getElementById('myelement'))
    },
    imprimirInmunologia(p, l) {
      let cadena = "<style>\
        table{margin-bottom:10px;}\
        .tab1{width:100%;font-family: Arial; }\
        .tab2{width:100%; border:0.5px solid; font-size:12px;font-family: Arial;}\
        .tab3{width:100%; border:0.5px solid; font-size:14px;font-family: Arial;}\
        .tab4{width:100%; border:0.5px solid; font-size:14px;font-family: Arial;padding-right:1cm}\
        .img1{width: 200px; height:55px; padding-left:10px;font-family: Arial;}\
        .enc1{font-size:10px ; color: #3949AB; text-align:center;font-family: Arial;}\
        .enc2{font-size:15px ; color: #3949AB; text-align:center;font-weight: bold;font-family: Arial; }\
        footer {position: absolute;bottom: 1cm; width: 100%; height: 60px; color: #3949AB;  }\
        .tab2 th{color: #3949AB; text-align:left}\
        .tab3 thead{color: #3949AB; text-align:center}\
        .tab2 td{ text-align:center}\
        .campo2{color: #D32F2F; text-align:left}\
        </style>\
        <div style='padding-left: 1cm;padding-right: 1cm;padding-top: 0.5cm;padding-bottom: 1cm;'>\
        <table class='tab1'>\
        <tr><td style='width:50%'><img class='img1' src='img/natividad.jpeg' /></td>\
        <td class='enc1'><b>SERVICIO DE LABORATORIO</b> <br> Bolivar N°753 entre Arica e Iquique <br> Telf: 5254721 Fax: 52-83667 <br> Emergencia las 24 horas del dia.<br>\
        <span style='color:red'>Nº Registro CODEDLAB 000045 <br>Form. 025</span></td></tr>\
        </table>"
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      let imp = moment(l.fechaimp).format("DD-MM-YYYY")
      if (l.fechaimp == null || l.fechaimp == undefined) imp = moment().format("DD-MM-YYYY")
      cadena += "<div class='enc2'>RESULTADO <br> INMUNOLOGIA</div>\
        <table class='tab2'>\
        <tr><th>PACIENTE: </th><td>" + p.paciente + "</td><th>EDAD: </th><td>" + anio + "</td></tr>\
        <tr><th>REQUERIDO POR: </th><td>" + l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno + "</td><th>SEXO: </th><td>" + p.sexo + "</td></tr>\
        <tr><th>TIPO DE MUESTRA: </th><td>" + l.tipomuestra + "</td><th style='color: #D32F2F;'>N PACIENTE: </th><td>" + l.solicitud + "</td></tr>\
        </table>"
      cadena += "<body style='font-size:12px;'>"

      if (l.antibioticos.length > 0) {
        cadena += "<b></b>"
        cadena += "<table class='tab3'>" +
          "<thead>" +
          "<tr>" +
          "<th>ENSAYO</th>" +
          "<th>METODO</th>" +
          "<th>RESULTADO</th>" +
          "<th>UNIDAD</th>" +
          "<th>RANGO</th>" +
          "<th>DESCRIPCION</th>" +
          "</tr>" +
          "</thead>" +
          "<tbody>"
        l.antibioticos.forEach(r => {
          console.log(r)
          if (r.categoria == null) r.categoria = ''
          if (r.descripcion == null) r.descripcion = ''
          if (r.unidad == 0) r.unidad = ''
          if (r.metodo == null) r.metodo = ''
          let textcolor = ''
          if (r.rangoMax != null && r.rangoMin != null)
            if (r.pivot.resultado > r.rangoMax || r.pivot.resultado < r.rangoMin)
              textcolor = 'color: red;'
          cadena += "<tr style='text-align:center;'><td style='color: #3949AB;'>" + r.nombre + "</td><td style='color: #3949AB; font-size:10px;'>" + r.metodo + "</td><td style='" + textcolor + "'>" + r.pivot.resultado + "</td><td>" + r.unidad + "</td><td style='color: #3949AB;'>" + (r.rangoMin == null ? '' : r.rangoMin + ' - ') + '' + (r.rangoMax == null ? '' : r.rangoMax) + "</td><td style='color: #3949AB;font-size:10px;'>" + r.descripcion + "</td></tr>"
        });
        cadena += "</tbody></table>"
      }
      cadena += "<div style='border-top: 0.5px solid; border-right:  0.5px solid; border-left: 0.5px solid;'><b style='color: #3949AB;'>OBSERVACION</b><br>" + l.observacion + "</div>\
      <div style='text-align:center; color:black; '>\
        <table class='tab4'><tr><td style='width:50%;color: #3949AB; text-align:left;vertical-align:top'><b>RESPONSABLE DE ANALISIS</b></td><td style='text-align:left'><table><tr><td><b style='color: #3949AB;'>FECHA TOMA DE MUESTRA:</b></td><td>" + moment(l.fechatoma).format("DD-MM-YYYY") + "</td><tr><td><b style='color: #3949AB;'>HORA TOMA DE MUESTRA:</b></td><td>" + l.horatoma + "</td></tr><tr><td><b style='color: #3949AB;'>FECHA ENTREGA:</b></td><td>" + imp + "</td></tr></table><br></td></tr>\
        </table>\
        </div></body></div>"
      document.getElementById('myelement').innerHTML = cadena
      const d3 = new Printd()
      d3.print(document.getElementById('myelement'))
    },
    imprimirSanguinea(p, l) {
      if (l.antibioticos.length == 0) {
        return false
      }
      let anio = ''
      if (p.edad == null || p.edad == undefined || p.edad == '')
        anio = p.tiempo
      else anio = p.edad
      let imp = moment(l.fechaimp).format("DD-MM-YYYY")
      if (l.fechaimp == null || l.fechaimp == undefined) imp = moment().format("DD-MM-YYYY")
      let cont1 = ''
      let cont2 = ''
      let num = 1
      let cad = ''
      l.antibioticos.forEach(r => {
        if (r.categoria == null) r.categoria = ''
        if (r.descripcion == null) r.descripcion = ''
        if (r.unidad == 0) r.unidad = ''
        if (r.metodo == null) r.metodo = ''
        let textcolor = ''
        if (r.rangoMax != null && r.rangoMin != null) {
          if (r.pivot.resultado > r.rangoMax || r.pivot.resultado < r.rangoMin)
            textcolor = 'color: red;'
          cad = "<tr style='text-align:center;'><td style='color: #3949AB;'>" + r.nombre + "</td><td style='" + textcolor + "'>" + r.pivot.resultado + ' ' + r.unidad + "</td><td style='color: #3949AB;'>" + (r.rangoMin == null ? '' : r.rangoMin + ' - ') + '' + (r.rangoMax == null ? '' : r.rangoMax) + " " + r.unidad + "</td></tr>"
        } else {
          cad = "<tr style='text-align:center;'><td style='color: #3949AB;'>" + r.nombre + "</td><td style='" + textcolor + "'>" + r.pivot.resultado + ' ' + r.unidad + "</td><td style='color: #3949AB;'>" + r.descripcion + "</td></tr>"
        }
        if (num % 2 === 0)
          cont2 += cad
        else
          cont1 += cad
        num++

      });
      let cadena = "<style>\
        .tab1{width:100%;font-family: Arial; }\
        .tab2{width:100%; border:0.5px solid; font-size:14px;font-family: Arial;}\
        .tab3{width:100%; border:0.5px solid; font-size:14px;font-family: Arial;}\
        .tab4{width:100%; border:0.5px solid; font-size:14px;font-family: Arial;padding-right:1cm}\
        .tab5{width:100%; font-size:14px;font-family: Arial;}\
        .img1{width: 200px; height:55px; padding-left:10px;font-family: Arial;}\
        .enc1{font-size:10px ; color: #3949AB; text-align:center;font-family: Arial;}\
        .enc2{font-size:15px ; color: #3949AB; text-align:center;font-weight: bold;font-family: Arial; }\
        footer {position: absolute;bottom: 1cm; width: 100%; height: 60px; color: #3949AB;  }\
        .tab2 th{color: #3949AB; text-align:left}\
        .tab3 thead{color: #3949AB; text-align:center}\
        .tab2 td{ text-align:center}\
        .campo2{color: #D32F2F; text-align:left}\
        </style>\
        <div style='padding-left: 1cm;padding-right: 1cm;padding-top: 0.5cm;padding-bottom: 1cm;'>\
        <table class='tab1'>\
        <tr><td style='width:50%'><img class='img1' src='img/natividad.jpeg' /></td>\
        <td class='enc1'><b>SERVICIO DE LABORATORIO</b> <br> Bolivar N°753 entre Arica e Iquique <br> Telf: 5254721 Fax: 52-83667 <br> Emergencia las 24 horas del dia.<br>\
        <span style='color:red'>Nº Registro CODEDLAB 000045 <br>Form. 025</span></td></tr>\
        </table><div class='enc2'>QUIMICA SANGUINEA</div>\
        <table class='tab2'>\
        <tr><th>PACIENTE: </th><td>" + p.paciente + "</td><th>EDAD: </th><td>" + anio + "</td></tr>\
        <tr><th>REQUERIDO POR: </th><td>" + l.doctor.nombre + ' ' + l.doctor.paterno + ' ' + l.doctor.materno + "</td><th>SEXO: </th><td>" + p.sexo + "</td></tr>\
        <tr><th>TIPO DE MUESTRA: </th><td>" + l.tipomuestra + "</td><th style='color: #D32F2F;'>N PACIENTE: </th><td>" + l.solicitud + "</td></tr>\
        </table>"
        + "<table class='tab3'><tr>" +
        "<td style='width:50%'><table class='tab5'><tr><th>PRUEBA</th><th>RESULTADO</th><th>REFERENCIA</th></tr>" + cont1 + "</table></td>" +
        "<td style='width:50%'><table class='tab5'><tr><th>PRUEBA</th><th>RESULTADO</th><th>REFERENCIA</th></tr>" + cont2 + "</table></td></tr></table>"
        + "<br><div style='border:0.5px solid;'><b style='color: #3949AB;'>OBSERVACION</b><br>" + l.observacion + "</div>" +
        "<div style='text-align:center; color:black; '>\
        <table class='tab4'><tr><td style='width:50%;color: #3949AB; text-align:left;vertical-align:top'><b>RESPONSABLE DE ANALISIS</b></td><td style='text-align:left'><table><tr><td><b style='color: #3949AB;'>FECHA TOMA DE MUESTRA:</b></td><td>" + moment(l.fechatoma).format("DD-MM-YYYY") + "</td><tr><td><b style='color: #3949AB;'>HORA TOMA DE MUESTRA:</b></td><td>" + l.horatoma + "</td></tr><tr><td><b style='color: #3949AB;'>FECHA ENTREGA:</b></td><td>" + imp + "</td></tr></table><br></td></tr>\
        </table>\
        </div></div>"
      //console.log(cadena)
      document.getElementById('myelement').innerHTML = cadena
      const d3 = new Printd()
      d3.print(document.getElementById('myelement'))
    }
  }
}
</script>

<style scoped>
canvas {
  background-color: white; /* Fondo blanco */
  width: 400px;
  height: 200px;
}
</style>
