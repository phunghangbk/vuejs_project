<template>
  <div>
    <div class="alert alert-danger" v-if="error && !success">
      <p>
        There was an error, unable to complete registration.
      </p>
    </div>
    <div class="alert alert-success" v-if="success">
      <p>
        Registration completed. You can now <router-link :to="{name: 'login'}">sign in.</router-link>
      </p>
    </div>
    <form autocomplete="off" @submit.prevent="validateBeforeSubmit" v-if="!success" method="post">
      <div class="form-group" v-bind:class="{'has-error': error && custom_errors.name}">
        <label for="name">Your Full Name</label>
        <input type="text" id="name" class="form-control" v-model="name" placeholder="Name" required>
        <span class="help-block" v-if="error && custom_errors.name">{{custom_errors.name[0]}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && custom_errors.email}">
        <label for="email">Email</label>
        <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
        <span class="help-block" v-if="error && custom_errors.email">{{custom_errors.email[0]}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && custom_errors.nickname}">
        <label for="nickname">Nickname</label>
        <input type="text" id="nickname" class="form-control" v-model="nickname" placeholder="Nickname">
        <span class="help-block" v-if="error && custom_errors.nickname">{{custom_errors.nickname[0]}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && custom_errors.avarta_image}" @change="onAvartaImageChange">
        <label for="avartaImage">Avarta Image</label>
        <div class="col-md-3" v-if="avatar_image">
          <img :src="avatar_image" class="img-responsive" height="70" width="90">
        </div>
        <input type="file" id="avartaImage" class="form-control">
        <span class="help-block" v-if="error && custom_errors.avatar_image">{{custom_errors.avatar_image[0]}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && custom_errors.cover_image}" @change="onCoverImageChange">
        <label for="coverImage">Cover Image</label>
        <div class="col-md-3" v-if="cover_image">
          <img :src="cover_image" class="img-responsive" height="70" width="90">
        </div>
        <input type="file" id="coverImage" class="form-control">
        <span class="help-block" v-if="error && custom_errors.cover_image">{{custom_errors.cover_image[0]}}</span>
      </div>
        
      <div class="form-group" :class="{'has-error': (error && custom_errors.password) || errors.has('password')}">
        <label for="password">Password</label>
        <input v-validate="'required|min:6|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$'" name="password" id="password" type="password" class="form-control" v-model="password" placeholder="Password">
        <span class="help-block" v-if="errors.has('password')">{{errors.first('password')}}</span>
      </div>

      <div class="form-group" :class="{'has-error': (error && custom_errors.password_confirmation) || errors.has('password_confirmation')}">
        <label for="password">Password Confirm</label>
        <input v-validate="{ required: true, is: password }" name="password_confirmation" type="password" class="form-control" placeholder="Password, Again" data-vv-as="password" v-model="password_confirmation">
        <span class="help-block" v-if="error && custom_errors.password_confirmation">{{custom_errors.password_confirmation[0]}}</span>
        <span class="help-block" v-if="errors.has('password_confirmation')">{{errors.first('password_confirmation')}}</span>
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
  </div>
</template>

<script> 
  import axios from 'axios'
  import * as api from '../store/api.js'
  import VeeValidate from 'vee-validate'
  import Vue from 'vue'

  Vue.use(VeeValidate);

  export default {
    data() {
      return {
        name: '',
        email: '',
        nickname: '',
        avatar_image: null,
        cover_image: null,
        password: '',
        password_confirmation: '',
        error: false,
        custom_errors: {},
        success: false
      };
    },
    beforeCreate () {
      if (this.$store.state.isLogged) {
        this.$router.push('/dashboard')
      }
    },
    methods: {
      register() {
        axios.post(api.register, {
          name: this.name,
          email: this.email,
          nickname: this.nickname,
          password: this.password,
          avatar_image: this.avatar_image,
          cover_image: this.cover_image
        }).then(resp => {
          if (resp.data.status == 'error') {
            this.error = true;
            this.custom_errors = resp.data.errors;
            console.log(this.custom_errors);
          } else if (resp.data.status == 'success') {
            localStorage.setItem('token', resp.data.token);
            localStorage.setItem('user', JSON.stringify(resp.data.user));
            this.$store.commit('LOGIN_USER');
            this.$router.push('/dashboard');
            this.success = true;
          } else {
            this.error = true;
          }
        }).catch(error => {
          console.log(error);
          this.error = true;
          this.custom_errors = error;
        });               
      },
      onAvartaImageChange(event) {
        let files = event.target.files || event.dataTransfer.files;
        if (! files.length) {
          return;
        }
        this.createImage(files[0], 'avatar_image');
      },
      onCoverImageChange(event) {
        let coverfiles = event.target.files || event.dataTransfer.files;
        if (! coverfiles.length) {
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
      },
      validateBeforeSubmit() {
        if (this.errors.items.length <= 0) {
          this.register();
        } else {
          this.error = true;
        }
      }
    }
  }
</script>