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
    <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
      <div class="form-group" v-bind:class="{'has-error': error && errors.name}">
        <label for="firstName">Your First Name</label>
        <input type="text" id="firstName" class="form-control" v-model="first_name" required>
        <span class="help-block" v-if="error && errors.name">{{errors.name}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && errors.name}">
        <label for="lastName">Your Last Name</label>
        <input type="text" id="lastName" class="form-control" v-model="last_name" required>
        <span class="help-block" v-if="error && errors.name">{{errors.name}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && errors.email}">
        <label for="email">Email</label>
        <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
        <span class="help-block" v-if="error && errors.name">{{errors.name}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && errors.name}">
        <label for="nickname">Nickname</label>
        <input type="text" id="nickname" class="form-control" v-model="nickname">
        <span class="help-block" v-if="error && errors.name">{{errors.name}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && errors.name}" @change="onAvartaImageChange">
        <label for="avartaImage">Avarta Image</label>
        <input type="file" id="avartaImage" class="form-control">
        <span class="help-block" v-if="error && errors.name">{{errors.name}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && errors.name}" @change="onCoverImageChange">
        <label for="coverImage">Cover Image</label>
        <input type="file" id="coverImage" class="form-control">
        <span class="help-block" v-if="error && errors.name">{{errors.name}}</span>
      </div>
        
      <div class="form-group" :class="{'has-error': error && errors.name}">
        <label for="password">Password</label>
        <input type="password" id="password" class="form-control" v-model="password" required>
        <span class="help-block" v-if="error && errors.name">{{errors.name}}</span>
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
  </div>
</template>

<script> 
  import axios from 'axios'
  export default {
    data(){
      return {
        first_name: '',
        last_name: '',
        email: '',
        nickname: '',
        avatar_image: null,
        cover_image: null,
        password: '',
        error: false,
        errors: {},
        success: false
      };
    },
    methods: {
      register() {
        var app = this
        axios.post('/auth/register', {
          first_name: app.first_name,
          last_name: app.last_name,
          email: app.email,
          nickname: app.nickname,
          password: app.password,
          avatar_image: app.avatar_image,
          cover_image: app.cover_image
        }).then(resp => {
          if (resp.status=='200') {
            app.success = true;
          } else {
            app.error = true;
            app.errors = resp.response.data.errors;
          }
        }).catch(error => {
          app.error = true;
          app.errors = error;
        });               
      },
      onAvartaImageChange(event) {
        let files = event.target.files || event.dataTransfer.files;
        if (!files.length) {
          return;
        }
        this.createImage(files[0], 'avatar_image');
      },
      onCoverImageChange(event) {
        let coverfiles = event.target.files || event.dataTransfer.files;
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