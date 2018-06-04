<template>
  <div>
    <div class="alert alert-danger" v-if="infoError">
      <p>There was an error, unable to sign in with those credentials.</p>
    </div>
    <form autocomplete="off" @submit.prevent="login" method="post">
      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" class="form-control" v-model="password" required>
      </div>
      <button type="submit" class="btn btn-default">Sign in</button>
    </form>
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
        password: ''
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
          console.log(response.data);
          if (response.data.status == 'error') {
            this.infoError = true;
          } else {
            localStorage.setItem('token', response.data.token);
            localStorage.setItem('user', JSON.stringify(response.data.user));
            this.$store.commit('LOGIN_USER');
            this.$router.push('/dashboard');
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