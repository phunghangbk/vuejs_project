import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import Home from './components/Home.vue';
import Dashboard from './components/Dashboard.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import Logout from './components/Logout.vue';
import store from './store';
import Profile from './components/Profile.vue';
import UpdateProfile from './components/UpdateProfile.vue';
import UpdatePassword from './components/UpdatePassword.vue';
import UserVerify from './components/UserVerify.vue';
import VerificationEmail from './components/VerificationEmail.vue';


Vue.use(VueRouter);
Vue.use(VueAxios, axios);

axios.defaults.baseURL = "http://localhost:8696/api";
const router = new VueRouter({
  base: '/',
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/register',
      name: 'register',
      component: Register,
      meta: {
        auth: false
      }
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: {
        auth: false
      }
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: Dashboard,
      meta: {
        auth: true
      }
    },
    {
      path: '/logout',
      name: 'logout',
      component: Logout
    },
    {
      path: '/profile',
      name: 'profile',
      component: Profile,
      meta: {
        auth: true
      }
    },
    {
      path: '/update_profile',
      name: 'update_profile',
      component: UpdateProfile,
      meta: {
        auth: true
      }
    },
    {
      path: '/update_password',
      name: 'update_password',
      component: UpdatePassword,
      meta: {
        auth: true
      }
    },
    {
      path: '/user_verify',
      name: 'user_verify',
      component: UserVerify,
      meta: {
        auth: false
      }
    },
    {
      path: '/user/verify/:token',
      name: 'verification_email',
      component: VerificationEmail,
      props: true,
      meta: {
        auth: false
      }
    }
  ]
});
router.beforeEach((to, from, next) => {
  if (to.meta.auth) {
    if (store.state.isLogged) {
      return next();
    } else {
      return next('/login');
    }
  }
  return next();
})

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.interceptors.request.use(config => {
  config.headers['Authorization'] = 'Bearer '+ localStorage.getItem('token');
  return config
}, error => {
  return Promise.reject(error)
});

Vue.router = router
App.router = Vue.router
App.store = store
new Vue(App).$mount('#app');
