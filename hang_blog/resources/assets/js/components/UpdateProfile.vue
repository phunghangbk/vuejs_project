<template>
  <div class="container-fluid">
    <profile></profile>
    <link rel="stylesheet" type="text/css" href="/css/users.css">
    <div class="row justify-content-center">
      <div id="updateprofile" class="col-xs-12 col-sm-6 col-lg-4">
        <form id="msform" autocomplete="off" @submit.prevent="update" v-if="!success" method="post">
          <fieldset>
            <h2 class="fs-title">Update Your Profile</h2>
            <div :class="{'has-error': error && custom_errors.name}">
              <label for="name">Your Full Name</label>
              <input type="text" id="name" class="form-control" v-model="name" required>
              <span class="help-block" v-if="error && custom_errors.name">{{custom_errors.name[0]}}</span>
            </div>

            <div :class="{'has-error': error && custom_errors.nickname}">
              <label for="nickname">Nickname</label>
              <input type="text" id="nickname" class="form-control" v-model="nickname">
              <span class="help-block" v-if="error && custom_errors.nickname">{{custom_errors.nickname[0]}}</span>
            </div>

            <div class="alert alert-danger" v-if="error && !success">
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
  import * as api from '../store/api.js'
  import Profile from './Profile'

  export default {
    data(){
      return {
        name: '',
        nickname: '',
        avatar_image: null,
        cover_image: null,
        error: false,
        custom_errors: {},
        success: false,
        user: {},
        change_avatar: false,
        change_cover: false
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
            this.avatar_image = '/avatar_images/' + this.user.avatar_image;
            this.cover_image = '/cover_images/' + this.user.cover_image;
          }).catch(error =>{
            console.log(error);
          });
        } else {
          this.name = this.user.name;
          this.nickname = this.user.nickname;
          this.avatar_image = '/avatar_images/' + this.user.avatar_image;
          this.cover_image = '/cover_images/' + this.user.cover_image;
        }
      }
    },

    methods: {
      update() {
        axios.post(api.update_profile, {
          name: this.name == this.user.name ? '' : this.name,
          nickname: this.nickname == this.user.nickname ? '' : this.nickname,
          avatar_image: this.change_avatar ? this.avatar_image : '',
          cover_image: this.change_cover ? this.cover_image : ''
        }).then(resp => {
          console.log(resp);
          if (resp.data.status == 'error') {
            this.error = true;
            this.custom_errors = resp.data.errors;
          } else if (resp.data.status == 'success') {
            localStorage.setItem('user', JSON.stringify(resp.data.user));
            this.$router.push('/dashboard');
            this.success = true;
          }
        }).catch(error => {
          console.log(error);
          this.error = true;
          this.custom_errors = error;
        });               
      },
      onAvartaImageChange(event) {
        let files = event.target.files || event.dataTransfer.files;
        this.change_avatar = true;
        if (!files.length) {
          return;
        }
        this.createImage(files[0], 'avatar_image');
      },
      onCoverImageChange(event) {
        let coverfiles = event.target.files || event.dataTransfer.files;
        this.change_cover = true;
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
    },
    components: {
      Profile
    }
  }
</script>

<style scoped>
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

.image {
  float: left;
  text-align: left;
  width: 100%;
}
</style>





