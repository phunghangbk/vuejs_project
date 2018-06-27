<template>
  
</template>
<script>
  import Vue from 'vue'
  import axios from 'axios'
  import * as api from '../../store/api.js'
  import Toasted from 'vue-toasted'

  Vue.use(Toasted, {
    iconPack : 'material'
  })
  export default {
    name: 'DeletePost',
    props: {
      postId: {
        required: true
      }
    },
    data() {
      return {
        error: false,
        success: false,
        error_message: ''
      }
    },

    methods: {
      delete() {
        if (! this.$store.state.isLogged) {
          this.$router.push('/login')
        } else {
          axios.post(api.post_delete + this.postId)
          .then (resp => {
            console.log(resp)
            if (typeof resp.data.status != 'undefined' && resp.data.status == 'success') {
              this.success = true;
              Vue.toasted.show('Delete article successfully!!', { 
                theme: "bubble", 
                position: "top-center", 
                duration : 5000,
                type: 'success',
                icon: 'check',
                action: {
                  text: 'Close',
                    onClick : (e, toastObject) => {
                      toastObject.goAway(0);
                    }
                }
              })
            } else {
              this.error = true
              Vue.toasted.show(resp.data.errors['error'], { 
                theme: "bubble", 
                position: "top-center", 
                duration : 5000,
                type: 'error',
                icon: 'error',
                action: {
                  text: 'Close',
                    onClick : (e, toastObject) => {
                      toastObject.goAway(0);
                    }
                }
              })
            }
          })
          .catch (error => {
            this.error = true
            Vue.toasted.show('An error has occurred. Cannot delete this article', { 
                theme: "bubble", 
                position: "top-center", 
                duration : 5000,
                type: 'error',
                icon: 'error',
                action: {
                  text: 'Close',
                    onClick : (e, toastObject) => {
                      toastObject.goAway(0);
                    }
                }
              })
          })
        }
      }
    }
  }
</script>