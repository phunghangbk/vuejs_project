<template>
  <div id="userverify">
    <div class="content">
      <ul id="progressbar">
        <li>Account Setup</li>
        <li class="active">Send Email Verification</li>
        <li>Vericate Email Address</li>
      </ul>
      <div class="inline">
        <div class="thankyou">
          <h2>Thankyou for registration!</h2>
        </div>
        <div class="sentence">
          We've sent an email to {{user.email}}.
          Please click to the link in that message 
          to active your account.
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        user: null
      }
    },
    created() {
      this.user = JSON.parse(localStorage.getItem('user'));
    }
  }
</script>

<style scoped>
* {margin: 0; padding: 0;}

#userverify {
  height: 100%;
  background: 
  linear-gradient(rgba(196, 102, 0, 0.6), rgba(155, 89, 182, 0.6));
}

.content {
  width: 400px;
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
  margin: 0 10%;
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

.thankyou {
  margin-bottom: 10px;
}
</style>