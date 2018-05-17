<template>
  <div id="Profile">
    <div class="userInfomation">
      <div class="coverImage">
        <img v-show="loaded" v-lazy="{src: getImg(cover_image, 'cover_images'), loading: lazyload.loading, error: lazyload.error}" />
      </div>
      <div class="avatarImage">
        <img v-show="loaded" v-lazy="{src: getImg(avatar_image, 'avatar_images'), loading: lazyload.loading, error: lazyload.error}" />
      </div>
      <div class="nickname">
        {{nickname}}
      </div>
    </div>
  </div>
</template>
<script>
  import Vue from 'vue'
  import VueLazyload from 'vue-lazyload'
  import * as api from '../store/api.js'
  import axios from 'axios'

  Vue.use(VueLazyload)
  export default {
    data() {
      return {
        nickname: '',
        avatar_image: '',
        cover_image: '',
        loaded: false,
        lazyload: {
          error: '/images/profile_default.png',
          loading: '/images/loading.gif'
        },
      }
    },
    created() {
      this.fetchUserData();
    },
    methods: {
      fetchUserData() {
        axios.get(api.user)
        .then(resp => {
          this.nickname = resp.data.user.nickname ? resp.data.user.nickname : '';
          this.avatar_image = resp.data.user.avatar_image ? resp.data.user.avatar_image : '';
          this.cover_image = resp.data.user.cover_image ? resp.data.user.cover_image : '';
          this.loaded = true;
        })
        .catch (error => {
          console.log(error)
        })
      },
      getImg(name, type) {
        return type + '/' + name;
      },
    }
  }
</script>
<style scoped>
  
</style>