<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div id="verification_email" class="col-xs-12 col-sm-6 col-lg-4">
        <div class="content">
          <ul id="progressbar">
            <li class="active">Account Setup</li>
            <li class="active">Send Email Verification</li>
            <li class="active">Vericate Email Address</li>
          </ul>
          <div class="inline">
            <h2 class="fs-title">Verificate your account</h2>
            <h3 class="fs-subtitle">This is step 3</h3>
            <div v-if="error">
              <span class="error">
                {{message}}
              </span>
            </div>
            <div v-if="success">
              <div class="welcome">
                Welcome to Hang's Blog!!
              </div>
              <div class="sentence">
                Now you can login to your account 
                <router-link :to="{ name: 'login' }" class="nav-link">
                  Login
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'

  export default {
    props: {
      token: {
        required: true
      }
    },
    data() {
      return {
        user: null,
        authentication_token: '',
        error: false,
        success: false,
        message: ''
      }
    },
    created() {
      axios.get(api.verificate_email+this.token)
        .then(resp => {
          console.log(resp)
          if (typeof resp.data.status != 'undefined' 
            && resp.data.status == 'success') {
            this.success = true;
            this.message = resp.data.message;
            if (resp.data.authentication_token != null) {
              localStorage.setItem('token', resp.data.authentication_token);
            }
          } else {
            this.error = true;
            this.message = resp.data.message;
          }
        })
        .catch(error => {
          this.error = true;
          this.message = 'An error occurred. Cannot complete verification your email address.';
        });
      }
    }
</script>

<style scoped>
.row {
  height: 100%;
  background: 
    linear-gradient(rgba(196, 102, 0, 0.6), rgba(155, 89, 182, 0.6));
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
.content {
  margin: 50px auto;
  text-align: center;
  position: relative;
  margin-bottom:50%;
}

.inline {
  background: white;
  border: 0 none;
  border-radius: 3px;
  box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
  padding: 20px 30px;
  box-sizing: border-box;
  width: 100%;
  position: relative;
}
/*progressbar*/
#progressbar {
  margin-bottom: 30px;
  overflow: hidden;
  /*CSS counters to number the steps*/
  counter-reset: step;
  padding-left: 30px;
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
  width: 100%;
  height: 2px;
  background: white;
  position: absolute;
  left: -50%;
  top: 9px;
  z-index: -1; /*put it behind the numbers*/
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
</style>