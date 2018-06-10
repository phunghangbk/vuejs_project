<template>
  <div class="row justify-content-center">
    <div id="Profile" class="col-xs-12 col-sm-10 col-lg-10">
      <div class="userInfomation">
        <div class="coverImage">
          <img v-show="loaded" v-lazy="{src: getImg(cover_image, 'cover_images'), loading: lazyload.loading, error: lazyload.error}" class="coverimg img-fluid" />
          <div class="cover_camera_icon">
            <i class="fa fa-camera fa-2x" @click="coverUpload">
            </i>
            <input type="file" name="coverImage" id="coverImage" class="d-none">
          </div>
        </div>
        <div class="avatarImage">
          <img v-show="loaded" v-lazy="{src: getImg(avatar_image, 'avatar_images'), loading: lazyload.loading, error: lazyload.error}" class="avatarimg img-fluid" />
          <div class="avatar_camera_icon">
            <i class="fa fa-camera" @click="avatarUpload"></i>
            <input type="file" name="avatarImage" id="avatarImage" class="d-none">
          </div>
        </div>
        <div class="nickname_wrap">
          <div class="nickname">
          <span class="nickname_in_profile">
            {{nickname}}
          </span>
        </div>
        </div>
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
    name: 'Profile',

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

      coverUpload() {
        $("#coverImage").click();
      },

      avatarUpload() {
        $("#avatarImage").click();
      },

      createImage(file, field) {
        let reader = new FileReader();
        let vm = this;
        reader.onload = (e) => {
          vm[field] = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    }
  }
</script>
<style scoped>
  .row {
    background: white;
  }

  .coverImage {
    display: block;
    position: relative;
    height: 315px;
    overflow: hidden;
    text-decoration: none;
  }

  .coverimg {
    min-width: 100%;
    min-height: 100%;
    position: absolute;
    left: 0;
    top: 0;
  }

  .avatarImage {
    box-shadow: none;
    left: 15px;
    margin-top: 0;
    top: -143px;
    background: rgba(0, 0, 0, .3);
    border-radius: 3px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .07);
    display: block;
    left: 23px;
    margin-top: -121px;
    padding: 1px;
  }

  .avatarimg {
    background-color: #fff;
    border-radius: 2px;
    display: block;
    height: 160px;
    position: relative;
    width: 160px;
    border: 4px solid #fff;
  }

  .nickname {
    font-family: Helvetica, Arial, sans-serif;
    font-size: 24px;
    font-weight: 500;
    line-height: 30px;
    max-width: 275px;
    position: relative;
  }

  .nickname_in_profile {

  }

  .nickname_wrap {
    bottom: 70px;
    left: 201px;
    position: absolute;
    color: white;
  }

  .cover_camera_icon {
    position: absolute;
    color: white;
    margin-left: 9px;
  }

  i.fa.fa-camera:hover {
    color: #28a745;
    cursor: pointer;
  }

  .avatar_camera_icon {
    margin-left: 8px;
    position:  absolute;
    top: 200px;
    color: white;
  }
</style>