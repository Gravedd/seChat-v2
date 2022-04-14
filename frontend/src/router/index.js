import { createRouter, createWebHistory } from 'vue-router'
import IndexView from "@/views/IndexView";
import store from '@/store';

//Защита маршрутов
const ifAuthenticated = (to, from, next) => {
  if (store.getters.authstatus) {
    next()
    return
  }
  next('/login')
}
const ifNotAuthenticated = (to, from, next) => {
  if (!store.getters.authstatus) {
    next()
    return
  }
  next('/myprofile')
}

let name = 'seChat';
const routes = [
    //Главная страница
  {
    path: '/',
    name: 'index',
    meta: { title: 'Главная' },
    component: IndexView
  },
    //Авторизация
  {
    path: '/login',
    name: 'login',
    meta: { title: 'Авторизация' },
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import('../views/LoginView.vue')
  },
    //Профиль пользователя
  {
    path: '/profile/:id',
    name: 'profile',
    meta: { title: 'Профиль пользователя' },
    props: true,
    beforeEnter: ifAuthenticated,
    component: () => import('../views/ProfileView.vue')
  },
    //Личный профиль
  {
    path: '/myprofile',
    name: 'myprofile',
    meta: { title: 'Мой профиль' },
    beforeEnter: ifAuthenticated,
    component: () => import('../views/MyprofileView.vue')
  },
    //Чат с пользователем
  {
    path: '/dialogues/:userid',
    name: 'chat',
    meta: { title: 'Диалог' },
    beforeEnter: ifAuthenticated,
    component: () => import('../views/ChatView.vue')
  },
  //Список диалогов
  {
    path: '/dialogues/',
    name: 'dialogues',
    meta: { title: 'Диалоги' },
    beforeEnter: ifAuthenticated,
    component: () => import('../views/DialoguesView.vue'),
  },


  {
    path: '/search',
    name: 'search',
    meta: { title: 'Поиск' },
    beforeEnter: ifAuthenticated,
    component: () => import('../views/SearchView.vue')
  },

    //Ошибка 404
  { path: '/:pathMatch(.*)*', meta: { title: '404' }, component: () => import('../views/NotFound.vue') }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})


//Названия страниц
router.beforeEach(async (to, from, next) => {
  const { title } = to.meta;
  const brand = "seChat";
  document.title = `${title ? title + " | " : ""}${brand}`;
  next();
});
export default router
