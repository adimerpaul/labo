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
}
