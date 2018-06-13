import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import store from './store';
import router from './router';

Vue.use(VueAxios, axios);

axios.defaults.baseURL = "http://localhost:8696/api";
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
