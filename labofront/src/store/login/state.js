export default function () {
  return {
    status: '',
    token: localStorage.getItem('tokenlabo') || '',
    user : {},
    pedido:[],
    booluser:false,
    booldoctor:false,
    boolpacientes:false,
    boolhistorial:false,
    boolreactivo:false,
    boolporcaducar:false,
  }
}
