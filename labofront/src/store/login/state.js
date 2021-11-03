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
    boolhemograma:false,
    boolorina:false,
    boolsanguinia:false,
    booluretral:false,
    boolvaginal:false,
    boolhece:false,
    boolsimple:false,
    boolseriado:false,
    boolserologia:false,
    boollabserologia:false,
    boolreserologia:false,
    boolensayo:false

  }
}
