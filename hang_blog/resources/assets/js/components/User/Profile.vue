<template>
  <div class="row justify-content-center">
    <div id="Profile" class="col-xs-12 col-sm-10 col-lg-10">
      <div class="userInfomation">
        <div class="coverImage">
          <img v-show="loaded" v-lazy="{src: getImg(cover_image, 'cover_images'), loading: lazyload.loading, error: lazyload.error}" class="coverimg img-fluid" />
          <div v-if="is_signedin" class="cover_camera_icon">
            <label class="coverLabel" @change="onCoverImageChange">
              <i class="fa fa-camera fa-2x">
                <input type="file" name="coverImage" id="coverImage" class="d-none">
              </i>
            </label>
          </div>
        </div>
        <div class="avatarImage">
          <img v-show="loaded" v-lazy="{src: getImg(avatar_image, 'avatar_images'), loading: lazyload.loading, error: lazyload.error}" class="avatarimg img-fluid" />
          <div v-if="is_signedin" class="avatar_camera_icon">
            <label class="avatarLabel" @change="onAvartaImageChange">
              <i class="fa fa-camera">
                <input type="file" name="avatarImage" id="avatarImage" class="d-none">
              </i>
            </label>
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
  import axios from 'axios'

  Vue.use(VueLazyload)
  export default {
    name: 'Profile',
    props: {
      nicknameParameter: {
        required: true
      }
    },
    data() {
      return {
        nickname: '',
        avatar_image: '',
        cover_image: '',
        avatarTemp: '',
        coverTemp: '',
        loaded: false,
        change_avatar: false,
        change_cover: false,
        lazyload: {
          error: '/images/profile_default.png',
          loading: '/images/loading.gif'
        },
        is_signedin: false,
        custom_errors: {},
        user: null,
      }
    },

    created() {
      this.is_signedin = this.$store.state.isLogged;
      this.fetchUserData();
    },

    methods: {
      fetchUserData() {
        axios.get(api.user+'?nickname='+this.nicknameParameter).then(resp => {
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
        return '/' + type + '/' + name;
      },

      update_avatar() {
        axios.post(api.update_profile, {
          avatar_image: this.change_avatar ? this.avatarTemp : '',
        }).then(resp => {
          this.change_avatar = false;
          if (resp.data.status == 'success') {
            this.user = resp.data.user;
            localStorage.setItem('user', JSON.stringify(this.user));
            this.avatar_image = this.user.avatar_image;
            this.success = true;
          } else {
            this.error = true;
            this.custom_errors = resp.data.errors;
            console.log(this.custom_errors);
            alert('An error has occurred. Cannot complete update your avatar.');
          }
        }).catch(error => {
          this.error = true;
          console.log(error);
          alert('An error has occurred. Cannot complete update your avatar.');
        });               
      },

      update_cover() {
        axios.post(api.update_profile, {
          cover_image: this.change_cover ? this.coverTemp : ''
        }).then(resp => {
          this.change_cover = false;
          if (resp.data.status == 'success') {
            this.user = resp.data.user;
            localStorage.setItem('user', JSON.stringify(this.user));
            this.cover_image = this.user.cover_image;
            console.log(this.cover_image);
            this.success = true;
          } else {
            this.error = true;
            this.custom_errors = resp.data.errors;
            alert('An error has occurred. Cannot complete update your avatar.');
          }
        }).catch(error => {
          console.log(error);
          this.error = true;
          alert('An error has occurred. Cannot complete update your avatar.');
        });               
      },

      onAvartaImageChange(event) {
        let files = event.target.files || event.dataTransfer.files;
        if (! files.length) {
          return;
        }
        this.change_avatar = true;
        this.createImage(files[0], 'avatarTemp', this.update_avatar);
      },

      onCoverImageChange(event) {
        let coverfiles = event.target.files || event.dataTransfer.files;
        if (! coverfiles.length) {
          return;
        }
        this.change_cover = true;
        this.createImage(coverfiles[0], 'coverTemp', this.update_cover);
        this.update_cover();
      },

      createImage(file, field, callback) {
        let reader = new FileReader();
        let vm = this;
        reader.onload = (e) => {
          vm[field] = e.target.result;
          callback();
        };
        reader.readAsDataURL(file);
      },
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