<template>
  <div>
    <div class="alert alert-danger" v-if="error && !success">
      <p>
        There was an error, unable to complete update your profile.
      </p>
    </div> 
    <div class="alert alert-success" v-if="success">
      <p>
        Your profile has updated successfull.
      </p>
    </div>
    <form autocomplete="off" @submit.prevent="update" v-if="!success" method="post">
      <div class="form-group" :class="{'has-error': error && errors.name}">
        <label for="name">Your Full Name</label>
        <input type="text" id="name" class="form-control" v-model="name" required>
        <span class="help-block" v-if="error && errors.name">{{errors.name[0]}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && errors.nickname}">
        <label for="nickname">Nickname</label>
        <input type="text" id="nickname" class="form-control" v-model="nickname">
        <span class="help-block" v-if="error && errors.nickname">{{errors.nickname[0]}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && errors.avarta_image}" @change="onAvartaImageChange">
        <label for="avartaImage">Avarta Image</label>
        <div class="col-md-3" v-if="avatar_image">
          <img :src="avatar_image" class="img-responsive" height="70" width="90">
        </div>
        <input type="file" id="avartaImage" class="form-control">
        <span class="help-block" v-if="error && errors.avatar_image">{{errors.avatar_image[0]}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && errors.cover_image}" @change="onCoverImageChange">
        <label for="coverImage">Cover Image</label>
        <div class="col-md-3" v-if="cover_image">
          <img :src="cover_image" class="img-responsive" height="70" width="90">
        </div>
        <input type="file" id="coverImage" class="form-control">
        <span class="help-block" v-if="error && errors.cover_image">{{errors.cover_image[0]}}</span>
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
  </div>
</template>

<script>
  import axios from 'axios'
  import * as api from '../store/api.js'
  export default {
    data(){
      return {
        name: '',
        nickname: '',
        avatar_image: null,
        cover_image: null,
        error: false,
        errors: {},
        success: false,
        user: {},
        change_avatar: false,
        change_cover: false
      };
    },

    beforeCreate () {
      if (! this.$store.state.isLogged) {
        this.$router.push('/login')
      }
    },

    created () {
      this.user = JSON.parse(localStorage.getItem('user'));
      if (this.user == null || typeof this.user == 'undefined') {
        axios.get(api.user).then(resp => {
          this.user = resp.data.user;
          this.name = this.user.name;
          this.nickname = this.user.nickname;
          this.avatar_image = '/avatar_images/' + this.user.avatar_image;
          this.cover_image = '/cover_images/' + this.user.cover_image;
        }).catch(error =>{
          console.log(error);
        });
      } else {
        this.name = this.user.name;
        this.nickname = this.user.nickname;
        this.avatar_image = '/avatar_images/' + this.user.avatar_image;
        this.cover_image = '/cover_images/' + this.user.cover_image;
      }
    },

    methods: {
      update() {
        axios.post(api.update_profile, {
          name: this.name == this.user.name ? '' : this.name,
          nickname: this.nickname == this.user.nickname ? '' : this.nickname,
          avatar_image: this.change_avatar ? this.avatar_image : '',
          cover_image: this.change_cover ? this.cover_image : ''
        }).then(resp => {
          console.log(resp);
          if (resp.data.status == 'error') {
            this.error = true;
            this.errors = resp.data.errors;
          } else if (resp.data.status == 'success') {
            localStorage.setItem('user', JSON.stringify(resp.data.user));
            this.$router.push('/dashboard');
            this.success = true;
          }
        }).catch(error => {
          console.log(error);
          this.error = true;
          this.errors = error;
        });               
      },
      onAvartaImageChange(event) {
        let files = event.target.files || event.dataTransfer.files;
        this.change_avatar = true;
        if (!files.length) {
          return;
        }
        this.createImage(files[0], 'avatar_image');
      },
      onCoverImageChange(event) {
        let coverfiles = event.target.files || event.dataTransfer.files;
        this.change_cover = true;
        if (!coverfiles.length) {
          return;
        }
        this.createImage(coverfiles[0], 'cover_image');
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







