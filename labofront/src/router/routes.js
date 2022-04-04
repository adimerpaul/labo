import Index from "../pages/Index";
import Login from "../pages/Login";
// import Local from "../pages/Local";
import User from "pages/User";
import Doctor from "pages/Doctor";
import Historial from "pages/Historial";
import Pacientes from "pages/Pacientes";
import Porcaducar from "pages/Porcaducar";
import Reactivo from "pages/Reactivo";
import Seguros from "pages/Seguros";

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: Index },
      { path: 'login', component: Login},
      { path: 'user', component: User,meta:{requiresAuth: true}},
      { path: 'doctor', component: Doctor,meta:{requiresAuth: true}},
      { path: 'historial', component: Historial,meta:{requiresAuth: true}},
      { path: 'pacientes', component: Pacientes,meta:{requiresAuth: true}},
      { path: 'porcaducar', component: Porcaducar,meta:{requiresAuth: true}},
      { path: 'reactivo', component: Reactivo,meta:{requiresAuth: true}},
      { path: 'seguros', component: Seguros,meta:{requiresAuth: true}},
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
