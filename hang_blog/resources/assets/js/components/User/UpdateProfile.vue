<template>
  <div class="container-fluid custom-container">
    <profile :nickname-parameter="nickname"></profile>
    <link rel="stylesheet" type="text/css" href="/css/users.css">
    <div class="row justify-content-center">
      <div id="updateprofile" class="col-xs-12 col-sm-6 col-lg-4">
        <form id="msform" autocomplete="off" @submit.prevent="update" v-if="!success" method="post">
          <fieldset>
            <h2 class="fs-title">Update Your Profile</h2>
            <div :class="{'has-error': error && custom_errors.name}">
              <label for="name">Your Full Name</label>
              <input type="text" id="name" class="form-control" v-model="name" required>
              <span v-if="error && custom_errors.name">{{custom_errors.name[0]}}</span>
            </div>

            <div :class="{'has-error': error && custom_errors.nickname}">
              <label for="nickname">Nickname</label>
              <input type="text" id="nickname" class="form-control" v-model="nickname">
              <span v-if="error && custom_errors.nickname">{{custom_errors.nickname[0]}}</span>
            </div>

            <div class="alert alert-danger" v-if="error && !success && ! custom_errors.name && ! custom_errors.nickname">
              <p>
                There was an error, unable to complete update your profile.
              </p>
            </div>
            <button type="submit" class="next action-button">Submit</button>
          </fieldset>
        </form>
      </div>
      <div class="alert alert-success" v-if="success">
        <p>
          Your profile has updated successfull.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import Profile from './Profile'
  import * as api from '../../store/api.js'
  
  export default {
    data(){
      return {
        name: '',
        nickname: '',
        error: false,
        custom_errors: {},
        success: false,
        user: {},
      };
    },

    beforeCreate () {
      if (! this.$store.state.isLogged) {
        this.$router.push('/login')
      }
    },

    created () {
      if (! this.$store.state.isLogged) {
        return;
      } else {
        this.user = JSON.parse(localStorage.getItem('user'));
        if (this.user == null || typeof this.user == 'undefined') {
          axios.get(api.user).then(resp => {
            this.user = resp.data.user;
            this.name = this.user.name;
            this.nickname = this.user.nickname;
          }).catch(error =>{
            console.log(error);
          });
        } else {
          this.name = this.user.name;
          this.nickname = this.user.nickname;
        }
      }
    },

    methods: {
      update() {
        axios.post(api.update_profile, {
          name: this.name == this.user.name ? '' : this.name,
          nickname: this.nickname == this.user.nickname ? '' : this.nickname,
        }).then(resp => {
          if (resp.data.status == 'success') {
            localStorage.setItem('user', JSON.stringify(resp.data.user));
            this.$router.push('/user/' + resp.data.user.nickname);
            this.success = true;
          } else {
            this.error = true;
            this.custom_errors = resp.data.errors;
          }
        }).catch(error => {
          console.log(error);
          this.error = true;
          this.custom_errors = error;
        });               
      },
    },

    components: {
      Profile
    }
  }
</script>

<style scoped>
.custom-container {
  margin-top: 56px;
}
.row {
  background: white;
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
</style>





