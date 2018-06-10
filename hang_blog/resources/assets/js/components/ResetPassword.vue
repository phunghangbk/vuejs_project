<template>
  <div class="container-fluid">
    <link rel="stylesheet" type="text/css" href="/css/users.css">
    <div class="row justify-content-center">
      <div id="resetpassword" class="col-xs-12 col-sm-6 col-lg-4">
        <div class="inline">
          <h2 class="fs-title">Verificate your account</h2>

          <div v-if="invalid_user">
            Your account is invalid!
          </div>
          <div v-if="! invalid_user && error">
            An error is occurred. Cannot reset your password.
          </div>

          <div v-if="success && ! reset_success" class="reset_form">
            <form id="msform" autocomplete="off" @submit.prevent="reset" method="post">
              <fieldset>
                <div :class="{'has-error': (reset_error && custom_errors.password) || errors.has('password')}">
                  <label for="password">Password</label>
                  <input v-validate="'required|min:6|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$'" name="password" id="password" type="password" v-model="password" placeholder="New Password" data-vv-as="password">
                  <span class="help-block" v-if="errors.has('password')">{{errors.first('password')}}</span>
                  <span class="help-block" v-if="custom_errors.password">{{custom_errors.password}}</span>
                </div>

                <div :class="{'has-error': (reset_error && custom_errors.password_confirmation) || errors.has('password_confirmation')}">
                  <label for="password">New password confirm</label>
                  <input v-validate="{ required: true, is: password }" name="password_confirmation" type="password" placeholder="Password, Again" v-model="password_confirmation" data-vv-as="password confirmation">
                  <span class="help-block" v-if="reset_error && custom_errors.password_confirmation">{{custom_errors.password_confirmation}}</span>
                  <span class="help-block" v-if="errors.has('password_confirmation')">{{errors.first('password_confirmation')}}</span>
                </div>
              </fieldset>
              <div class="alert alert-danger" v-if="reset_error && !reset_success">
                <p>
                  There was an error, unable to complete reset your password.
                </p>
              </div> 
              <div class="alert alert-success" v-if="reset_success">
                <p>
                  Your password has reseted successfull.
                </p>
              </div>
              <button type="submit" class="next action-button">Submit</button>
            </form>
          </div>
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
    props: {
      token: {
        required: true
      }
    },
    data() {
      return {
        user: null,
        error: false,
        success: false,
        invalid_user: false,
        reset_error: false,
        reset_success: false,
        custom_errors: {},
        password: null,
        password_confirmation: null,
      }
    },
    created() {
      axios.get(api.reset_password+this.token)
      .then(resp => {
        console.log(resp);
        if (resp.data.status == 'success' && resp.data.user != null) {
          this.success = true;
          this.user = resp.data.user;
        } else {
          this.error = true;
          this.invalid_user = true;
        }
      })
      .catch(e => {
        this.error = true;
        console.log(e);
      })
    },

    methods: {
      reset() {
        axios.post(api.reset_action, {
          email: this.user.email,
          password: this.password
        })
        .then(resp => {
          console.log(resp);
          if (resp.data.status == 'success') {
            this.reset_success = true;
          } else {
            this.reset_error = true;
            this.custom_errors = resp.data.errors;
          }
        })
        .catch(e => {
          this.reset_error = true;
          console.log(e);
        })
      }
    }
  }
</script>

<style scoped>
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

.fs-title {
  margin-top: 30px;
}
</style>