/*
export function someMutation (state) {
}
*/
export function auth_request(state){
  state.status = 'loading'
}
export function auth_success(state, data){
  state.status = 'success'
  state.token = data.token
  state.user = data.user
  state.booluser=data.user.permisos.find(permiso=>permiso.id===1)!=undefined
  state.booldoctor=data.user.permisos.find(permiso=>permiso.id===2)!=undefined
  state.boolpacientes=data.user.permisos.find(permiso=>permiso.id===3)!=undefined
  state.boolhistorial=data.user.permisos.find(permiso=>permiso.id===4)!=undefined
  state.boolreactivo=data.user.permisos.find(permiso=>permiso.id===5)!=undefined
  state.boolporcaducar=data.user.permisos.find(permiso=>permiso.id===6)!=undefined
  state.boolhemograma=data.user.permisos.find(permiso=>permiso.id===7)!=undefined
  state.boolorina=data.user.permisos.find(permiso=>permiso.id===8)!=undefined
  state.boolsanguinia=data.user.permisos.find(permiso=>permiso.id===9)!=undefined
  state.booluretral=data.user.permisos.find(permiso=>permiso.id===10)!=undefined
  state.boolvaginal=data.user.permisos.find(permiso=>permiso.id===11)!=undefined
  state.boolhece=data.user.permisos.find(permiso=>permiso.id===12)!=undefined
  state.boolsimple=data.user.permisos.find(permiso=>permiso.id===13)!=undefined
  state.boolseriado=data.user.permisos.find(permiso=>permiso.id===14)!=undefined
  state.boolserologia=data.user.permisos.find(permiso=>permiso.id===15)!=undefined
  state.boollabserologia=data.user.permisos.find(permiso=>permiso.id===16)!=undefined
  state.boolreserologia=data.user.permisos.find(permiso=>permiso.id===17)!=undefined
  state.boolensayo=data.user.permisos.find(permiso=>permiso.id===18)!=undefined
}
export function   auth_error(state){
  state.status = 'error'
}
export function salir(state){
  state.status = ''
  state.token = ''
  state.user = ''
  state.booluser=false
  state.booldoctor=false
  state.boolpacientes=false
  state.boolhistorial=false
  state.boolreactivo=false
  state.boolporcaducar=false
  state.boolhemograma=false
  state.boolorina=false
  state.boolsanguinia=false
  state.booluretral=false
  state.boolvaginal=false
  state.boolhece=false
  state.boolsimple=false
  state.boolseriado=false
  state.boolserologia=false
  state.boollabserologia=false
  state.boolreserologia=false
  state.boolensayo=false
}
