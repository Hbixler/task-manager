import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: import.meta.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'task-dashboard',
      component: () => import('../views/TaskDashboard.vue')
    },
    {
      path: '/search',
      name: 'task-search',
      // route level code-splitting
      component: () => import('../views/TaskSearch.vue')
    }
  ]
})

export default router
