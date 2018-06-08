<template>

</template>

<script>
  import axios from 'axios'
  import * as api from '../store/api.js'

  export default {
    props: {
      token: {
        required: true
      }
    },
    data() {
      return {
        user: null,
        token: '',
        authentication_token: ''
      }
    },
    methods: {
      verificate() {
        axios.post(api.verificate_email, {
          token: this.token
        })
        .then(resp => {
          console.log(resp)
        })
        .catch(error => {
          console.log(error)
        });
      }
    }
  }
</script>