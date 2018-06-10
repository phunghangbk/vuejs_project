<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div id="login" class="col-xs-12 col-sm-6 col-lg-4">
        <form id="msform" autocomplete="off" @submit.prevent="login" method="post">
          <ul id="progressbar">
          </ul>
          <fieldset>
            <h2 class="fs-title">Login</h2>
            <div>
              <label for="email">E-mail</label>
              <input type="email" id="email" placeholder="user@example.com" v-model="email" required>
            </div>
            <div>
              <label for="password">Password</label>
              <input type="password" id="password" v-model="password" required>
            </div>
            <div class="alert-danger" v-if="infoError">
              <p>{{message}}</p>
            </div>
            <button type="submit" class="action-button">Sign in</button>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import * as api from '../store/api.js'

  export default {
    name: 'login',
    data () {
      return {
        loader: false,
        infoError: false,
        email: '',
        password: '',
        message: 'There was an error, unable to sign in with those credentials.'
      }
    },
    beforeCreate () {
      if (this.$store.state.isLogged) {
        this.$router.push('/dashboard')
      }
    },
    methods: {
      login () {
        this.loader = true
        this.infoError = false
        axios.post(api.login, {
          email: this.email,
          password: this.password
        }).then((response) => {
          if (response.data.status == 'success') {
            localStorage.setItem('token', response.data.token);
            localStorage.setItem('user', JSON.stringify(response.data.user));
            this.$store.commit('LOGIN_USER');
            this.$router.push('/dashboard');
          } else {
            this.infoError = true;
            this.message = response.data.msg;
          }
        }, () => {
          this.infoError = true
          this.loader = false
          this.password = ''
        })
      }
    }
  }
</script>

<style scoped>
  @import url(https://fonts.googleapis.com/css?family=Montserrat);
  .row {
    height: 100%;
    background: 
      linear-gradient(rgba(196, 102, 0, 0.6), rgba(155, 89, 182, 0.6));
  }

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

  /*Hide all except first fieldset*/
  #msform fieldset:not(:first-of-type) {
    display: none;
  }
  /*inputs*/
  #msform input, #msform textarea {
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

  body {
    font-family: montserrat, arial, verdana;
  }

  label {
    float: left;
    font-size: 13px;
    text-transform: uppercase;
    color: #2C3E50;
    margin: 5px;
  }

  #progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    /*CSS counters to number the steps*/
    counter-reset: step;
    padding-left: 30px;
    padding-top: 20px;
    width: 100%;
  }
  .alert-danger {
    text-align: left;
    font-size: 13px;
    color: red;
  }
</style>