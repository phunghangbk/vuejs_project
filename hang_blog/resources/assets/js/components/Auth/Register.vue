<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div id="registration" class="col-xs-12 col-sm-6 col-lg-4">
        <form id="msform" autocomplete="off" @submit.prevent="validateBeforeSubmit" v-if="!success" method="post">
          <ul id="progressbar">
            <li class="active">Account Setup</li>
            <li>Send Email Verification</li>
            <li>Vericate Email Address</li>
          </ul>
          <fieldset>
            <h2 class="fs-title">Create your account</h2>
            <h3 class="fs-subtitle">This is step 1</h3>

            <div :class="{'has-error': error && custom_errors.name}">
              <label for="name">Your Full Name</label>
              <input type="text" id="name" v-model="name" placeholder="Name" required>
              <span class="help-block" v-if="error && custom_errors.name">{{custom_errors.name[0]}}</span>
            </div>

            <div :class="{'has-error': error && custom_errors.email}">
              <label for="email">Email</label>
              <input type="email" id="email" placeholder="user@example.com" v-model="email" required>
              <span class="help-block" v-if="error && custom_errors.email">{{custom_errors.email[0]}}</span>
            </div>

            <div :class="{'has-error': error && custom_errors.nickname}">
              <label for="nickname">Nickname</label>
              <input type="text" id="nickname" v-model="nickname" placeholder="Nickname">
              <span class="help-block" v-if="error && custom_errors.nickname">{{custom_errors.nickname[0]}}</span>
            </div>

            <div :class="{'has-error': error && custom_errors.avarta_image}" @change="onAvartaImageChange">
              <span class="label">Avatar Image</span>
              <span>
                <div class="image" v-if="avatar_image">
                  <img :src="avatar_image" class="img-responsive" height="70" width="90">
                </div>
                <label class="uploadImage">
                  <input type="file" accept="image/jpeg, image/png" multiple="" size="60" id="avartaImage">
                </label>
                <span class="help-block" v-if="error && custom_errors.avatar_image">{{custom_errors.avatar_image[0]}}</span>
              </span>
              
            </div>

            <div :class="{'has-error': error && custom_errors.cover_image}" @change="onCoverImageChange">
              <span class="label">Cover Image</span>
              <span>
                <div class="image" v-if="cover_image">
                  <img :src="cover_image" class="img-responsive" height="70" width="90">
                </div>
                <label class="uploadImage">
                  <input type="file" id="coverImage">
                </label>
                <span class="help-block" v-if="error && custom_errors.cover_image">{{custom_errors.cover_image[0]}}</span>
              </span>
            </div>
              
            <div :class="{'has-error': (error && custom_errors.password) || errors.has('password')}">
              <label for="password">Password</label>
              <input v-validate="'required|min:6|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$'" name="password" id="password" type="password" v-model="password" placeholder="Password">
              <span class="help-block" v-if="errors.has('password')">{{errors.first('password')}}</span>
            </div>

            <div :class="{'has-error': (error && custom_errors.password_confirmation) || errors.has('password_confirmation')}">
              <label for="password">Password Confirm</label>
              <input v-validate="{ required: true, is: password }" name="password_confirmation" type="password" placeholder="Password, Again" data-vv-as="password" v-model="password_confirmation">
              <span class="help-block" v-if="error && custom_errors.password_confirmation">{{custom_errors.password_confirmation[0]}}</span>
              <span class="help-block" v-if="errors.has('password_confirmation')">{{errors.first('password_confirmation')}}</span>
            </div>

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
            <button type="submit" class="next action-button">Submit</button>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</template>

<script> 
  import axios from 'axios'
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
          console.log(resp);
          if (resp.data.status == 'error') {
            this.error = true;
            this.custom_errors = resp.data.errors;
            console.log(this.custom_errors);
          } else if (resp.data.status == 'success') {
            localStorage.setItem('user', JSON.stringify(resp.data.user));
            this.$router.push('/user_verify');
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

<style scoped>
@import url(https://fonts.googleapis.com/css?family=Montserrat);
* {margin: 0; padding: 0;}

.row {
  height: 100%;
  background: 
    linear-gradient(rgba(196, 102, 0, 0.6), rgba(155, 89, 182, 0.6));
}

body {
  font-family: montserrat, arial, verdana;
}
/*form styles*/
#msform {
  margin: 50px auto;
  text-align: center;
  position: relative;
}
#msform fieldset {
  background: white;
  border: 0 none;
  border-radius: 3px;
  box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
  padding: 20px 30px;
  box-sizing: border-box;
  width: 100%;
  position: relative;
}
#msform fieldset:not(:first-of-type) {
  display: none;
}
/*inputs*/
#msform input, #msform textarea, #msform > fieldset > div > div.uploadImage {
  padding: 15px;
  border: 1px solid #ccc;
  border-radius: 3px;
  margin-bottom: 10px;
  width: 100%;
  box-sizing: border-box;
  font-family: montserrat;
  color: #2C3E50;
  font-size: 13px;
}

#msform input[type="file"] {
  display: none;
}
/*buttons*/
#msform .action-button {
  width: 100px;
  background: #27AE60;
  font-weight: bold;
  color: white;
  border: 0 none;
  border-radius: 1px;
  cursor: pointer;
  padding: 10px 5px;
  margin: 10px 5px;
}
#msform .action-button:hover, #msform .action-button:focus {
  box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
}
.image {
  float: left;
  text-align: left;
  width: 100%;
}
/*headings*/
.fs-title {
  font-size: 15px;
  text-transform: uppercase;
  color: #2C3E50;
  margin-bottom: 10px;
}
.fs-subtitle {
  font-weight: normal;
  font-size: 13px;
  color: #666;
  margin-bottom: 20px;
}
/*progressbar*/
#progressbar {
  margin-bottom: 30px;
  overflow: hidden;
  counter-reset: step;
  padding-top: 20px;
  width: 100%;
}
#progressbar li {
  color: white;
  list-style-type: none;
  text-transform: uppercase;
  font-size: 9px;
  width: 33.33%;
  float: left;
  position: relative;
}
#progressbar li:before {
  content: counter(step);
  counter-increment: step;
  width: 20px;
  line-height: 20px;
  display: block;
  font-size: 10px;
  color: #333;
  background: white;
  border-radius: 3px;
  margin: 0 auto 5px auto;
}
/*progressbar connectors*/
#progressbar li:after {
  content: '';
  width: 89%;
  height: 2px;
  background: white;
  position: absolute;
  left: -43%;
  top: 9px;
}
#progressbar li:first-child:after {
  /*connector not needed before the first step*/
  content: none; 
}
/*marking active/completed steps green*/
/*The number of the step and the connector before it = green*/
#progressbar li.active:before,  #progressbar li.active:after{
  background: #27AE60;
  color: white;
}

label {
  float: left;
  padding: 5px;
  font-size: 13px;
  text-transform: uppercase;
  color: #2C3E50;
}

span {
  float: left;
  font-size: 13px;
  color: red;
  width: 100%;
  display: flex;
}

.alert-danger {
  text-align: left;
  font-size: 13px;
  color: red;
}

.uploadImage {
  width: 100%; 
  height: 50px; 
  font-size: 0px; 
  margin-bottom: 1rem; 
  background-color: #0ebf67; 
  background-image: url(https://abs.twimg.com/a/1498195419/img/t1/highline/empty_state/owner_empty_avatar.png); 
  background-position: 50% center;
  border-radius: 3px;
  background-repeat: no-repeat;
}

#msform > fieldset > div > span.label {
  float: left;
  padding: 5px;
  font-size: 13px;
  text-transform: uppercase;
  color: #2C3E50;
}
</style>