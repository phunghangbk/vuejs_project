<template>
  <div id="Profile">
    <div class="userInfomation">
      <div class="coverImage"></div>
      <div class="avatarImage"></div>
      <div class="nickname"></div>
    </div>
  </div>
</template>
<script>
  import VueLazyload from 'vue-lazyload'
  import * as api from '../store/api.js'
  import axios from 'axios'

  export default {
    data() {
      return {
        nickname: '',
        avatar_image: '',
        cover_image: '',
        user: [],
        lazyload: {
          error: '../../../../../hang_blog/public/images/profile_default.png',
          loading: '../../../../../hang_blog/public/images/loading.gif'
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
          console.log(resp)
        })
        .catch (error => {

        })
      }
    }
  }
</script>
<style scoped>
  
</style>