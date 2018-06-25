<template>
  <div class="container-fluid custom-container">
    <link rel="stylesheet" type="text/css" href="/css/users.css">
    <div class="row justify-content-center">
      <div id="resetpasswordform" class="col-xs-12 col-sm-6 col-lg-4">
        <form id="msform" autocomplete="off" @submit.prevent="sendResetMail" v-if="!success" method="post">
          <fieldset>
            <h2 class="fs-title">Password Reset</h2>
            <div :class="{'has-error':error && custom_errors.email}">
              <label for="old_password">Enter you email address</label>
              <input name="email" type="email" v-model="email" placeholder="user@example.com">
              <span class="help-block" v-if="custom_errors.email">{{custom_errors.email}}</span>
            </div>
          </fieldset>
          <div class="alert alert-danger" v-if="error && !success">
            <p>
              {{message}}
            </p>
          </div> 
          <button type="submit" class="next action-button">Submit</button>
        </form>
        <div class="alert alert-success" v-if="success">
          <p>
            We've sent to an mail to {{email}}. Please check and click to the link in the message to reset your password.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import * as api from '../../store/api.js'

  export default {
    data() {
      return {
        email: null,
        success: false,
        error: false,
        custom_errors: {},
        message: 'There was an error, unable to send email to your email address.'
      }
    },
    beforeCreate () {
      if (this.$store.state.isLogged) {
        this.user = JSON.parse(localStorage.getItem('user'));
        this.$router.push('/user/' + this.user.nickname);
      }
    },
    methods: {
      sendResetMail() {
        axios.post(api.reset_password, {
          email: this.email
        })
        .then(resp => {
          console.log(resp);
          if (resp.data.status == 'success') {
            this.success = true;
          } else {
            this.error = true;
            if (typeof resp.data.errors != 'undefined') {
              this.custom_errors = resp.data.errors;
            }
            this.message = resp.data.msg;
          }
        })
        .catch(error => {
          this.error = true;
          console.log(error);
        })
      }
    }
  }
</script>

<style scoped>
.custom-container {
  margin-top: 56px;
  height: 100vh;
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