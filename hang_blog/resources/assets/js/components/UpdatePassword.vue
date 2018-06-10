<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div id="updatepassword" class="col-xs-12 col-sm-6 col-lg-4">
        <form id="msform" autocomplete="off" @submit.prevent="validateBeforeSubmit" v-if="!success" method="post">
          <fieldset>
            <h2 class="fs-title">Update your password</h2>
            <div :class="{'has-error': (error && custom_errors.old_password) || errors.has('old_password')}">
              <label for="old_password">Enter your old password</label>
              <input v-validate="'required|min:6|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$'" name="old_password" id="old_password" type="password" v-model="old_password" placeholder="Old Password" data-vv-as="old password">
              <span class="help-block" v-if="errors.has('old_password')">{{errors.first('old_password')}}</span>
              <span class="help-block" v-if="custom_errors.old_password">{{custom_errors.old_password}}</span>
            </div>

            <div :class="{'has-error': (error && custom_errors.new_password) || errors.has('new_password')}">
              <label for="new_password">Enter your new password</label>
              <input v-validate="'required|min:6|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$'" name="new_password" id="new_password" type="password" v-model="new_password" placeholder="New Password" data-vv-as="new password">
              <span class="help-block" v-if="errors.has('new_password')">{{errors.first('new_password')}}</span>
              <span class="help-block" v-if="custom_errors.new_password">{{custom_errors.new_password}}</span>
            </div>

            <div :class="{'has-error': (error && custom_errors.password_confirmation) || errors.has('password_confirmation')}">
              <label for="password">New password confirm</label>
              <input v-validate="{ required: true, is: new_password }" name="password_confirmation" type="password" placeholder="Password, Again" v-model="password_confirmation" data-vv-as="password confirmation">
              <span class="help-block" v-if="error && custom_errors.password_confirmation">{{custom_errors.password_confirmation}}</span>
              <span class="help-block" v-if="errors.has('password_confirmation')">{{errors.first('password_confirmation')}}</span>
            </div>
          </fieldset>
          <div class="alert alert-danger" v-if="error && !success">
            <p>
              There was an error, unable to complete update your password.
            </p>
          </div> 
          <button type="submit" class="next action-button">Submit</button>
        </form>
        <div class="alert alert-success" v-if="success">
          <p>
            Your password has updated successfull.
          </p>
        </div>
      </div>
    </div>
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
        old_password: '',
        new_password: '',
        password_confirmation: '',
        error: false,
        custom_errors: {},
        success: false
      }
    },

    beforeCreate () {
      if (! this.$store.state.isLogged) {
        this.$router.push('/login')
      }
    },

    methods: {
      validateBeforeSubmit() {
        if (this.errors.items.length <= 0) {
          this.update();
        } else {
          this.error = true;
        }
      },

      update() {
        axios.post(api.update_password, {
          old_password: this.old_password,
          new_password: this.new_password
        }).then(resp => {
          console.log(resp);
          if (resp.data.status == 'error') {
            this.error = true;
            this.custom_errors = resp.data.errors;
          } else if (resp.data.status == 'success') {
            localStorage.setItem('user', JSON.stringify(resp.data.user));
            this.$router.push('/dashboard');
            this.success = true;
          } else {
            this.error = true;
          }
        }).catch(e => {
          console.log(error);
          this.error = true;
          this.custom_errors = error;
        })
      }
    }
  }
</script>

<style scoped>
.row {
  height: 100%;
  background: 
    linear-gradient(rgba(196, 102, 0, 0.6), rgba(155, 89, 182, 0.6));
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

.fs-title {
  font-size: 15px;
  text-transform: uppercase;
  color: #2C3E50;
  margin-bottom: 10px;
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
</style>