<template>
  <div>
    <div class="alert alert-danger" v-if="error && !success">
      <p>
        There was an error, unable to complete update your password.
      </p>
    </div> 
    <div class="alert alert-success" v-if="success">
      <p>
        Your password has updated successfull.
      </p>
    </div>

    <form autocomplete="off" @submit.prevent="validateBeforeSubmit" v-if="!success" method="post">
      <div class="form-group" :class="{'has-error': (error && custom_errors.old_password) || errors.has('old_password')}">
        <label for="old_password">Enter your old password</label>
        <input v-validate="'required|min:6|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$'" name="old_password" id="old_password" type="password" class="form-control" v-model="old_password" placeholder="Old Password">
        <span class="help-block" v-if="errors.has('old_password')">{{errors.first('old_password')}}</span>
      </div>

      <div class="form-group" :class="{'has-error': (error && custom_errors.new_password) || errors.has('new_password')}">
        <label for="new_password">Enter your new password</label>
        <input v-validate="'required|min:6|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$'" name="new_password" id="new_password" type="password" class="form-control" v-model="new_password" placeholder="New Password">
        <span class="help-block" v-if="errors.has('new_password')">{{errors.first('new_password')}}</span>
      </div>

      <div class="form-group" :class="{'has-error': (error && custom_errors.password_confirmation) || errors.has('password_confirmation')}">
        <label for="password">New password confirm</label>
        <input v-validate="{ required: true, is: new_password }" name="password_confirmation" type="password" class="form-control" placeholder="Password, Again" data-vv-as="password" v-model="password_confirmation">
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