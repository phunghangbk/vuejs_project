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
        <span class="help-block" v-if="error && errors.name">{{errors.name[0]}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && errors.name}">
        <label for="lastName">Your Last Name</label>
        <input type="text" id="lastName" class="form-control" v-model="last_name" required>
        <span class="help-block" v-if="error && errors.name">{{errors.name[0]}}</span>
      </div>

      <div class="form-group" :class="{'has-error': error && errors.email}">
        <label for="email">Email</label>
        <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
        <span class="help-block" v-if="error && errors.email">{{errors.email[0]}}</span>
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
        
      <div class="form-group" :class="{'has-error': error && errors.password}">
        <label for="password">Password</label>
        <input type="password" id="password" class="form-control" v-model="password" required>
        <span class="help-block" v-if="error && errors.password">{{errors.password[0]}}</span>
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
    beforeCreate () {
      if (this.$store.state.isLogged) {
        this.router.push('/dashboard')
      }
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
          if (resp.data.status == 'error') {
            app.error = true;
            app.errors = resp.data.errors;
            console.log(app.errors);
          } else if (resp.data.status == 'success') {
            localStorage.setItem('token', resp.data.token);
            this.$store.commit('LOGIN_USER');
            this.$router.push('/dashboard');
            app.success = true;
          }
        }).catch(error => {
          console.log(error);
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