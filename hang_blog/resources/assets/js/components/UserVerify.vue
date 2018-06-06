<template>
  <div>
    <div>
      <h2>Thankyou!</h2>
    </div>
    <div>
      We've sent an email to you {{user.email}}
      Please click to the link in that message 
      to active you account
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      user: null
    },
    created() {
      this.user = JSON.parse(localStorage.getItem('user'));
    }
  }
</script>