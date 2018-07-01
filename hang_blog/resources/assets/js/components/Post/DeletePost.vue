<template>
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Do you want delete this article?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Warnning: Cannot restore after delete action.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal" @click="deletePost">Delete</button>
        </div>
      </div>
    </div>
  </div>
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
    created() {
      console.log(this.postId)
    },
    methods: {
      deletePost() {
        if (! this.$store.state.isLogged) {
          this.$router.push('/login')
        } else {
          axios.post(api.post_delete + this.postId)
          .then (resp => {
            if (typeof resp.data.status != 'undefined' && resp.data.status == 'success') {
              this.$emit('changeAfterDeletePost', this.postId)
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