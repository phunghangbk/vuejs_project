<template>
  <div id="commentForm" class="container-fluid custom-container">
    <div class="row justify-content-center">
      <div class="col-xs-12 col-sm-10 col-lg-8">
        <div :class="{'has-error': error}">
          <div class="form-group">
            <textarea class="form-control" id="commentContent" rows="3" v-model="content"></textarea>
            <span class="help-block"  v-if="error">An error has occurred</span>
          </div>
        </div>
        <div class="sendButton">
          <button type="button" class="btn btn-success" @click="create" :disabled="! $store.state.isLogged">Send</button>
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
    name: 'Comment',
    props: {
      postId: {
        required: true
      },
      parentId: {
        required: false
      },
      isReply: {
        required: false
      }
    },
    data() {
      return {
        content: '',
        error: false,
        success: false,
        message: '',
      }
    },
    methods: {
      create() {
        if (! this.$store.state.isLogged) {
          Vue.toasted.show('Please loggin to comment.', { 
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
          return;
        } else {
          axios.post(api.comment_create, {
            postId: this.postId,
            parentId: this.parentId ? this.parentId : null,
            content: this.content
          })
          .then(resp => {
            if (typeof resp.data.status != 'undefined' && resp.data.status == 'success') {
              this.content = '';
              this.$bus.$emit('changeAfterCreateComment', resp.data.comment, this.isReply)
              this.$bus.$emit('changeCommentCount', 1)
              this.success = true
              Vue.toasted.show('Your comment has sent successfully!!', { 
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
              Vue.toasted.show('An error has occurred. Cannot send your comment.', { 
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
          .catch(error => {
            this.error = true
            Vue.toasted.show('An error has occurred. Cannot send your comment.', { 
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
      },
    },
    components: {
    }
  }
</script>

<style scoped>
hr {
  border: none;
  height: 4px;
  color: #28a745;
  background-color: #28a745;
}

h2, .sendComment {
  font-size: 28px;
  font-family: "Libre Baskerville",Georgia,serif;
  font-weight: 700;
}

.sendButton {
  margin-top: .5rem;
}
#commentForm {
  margin-top: 1rem;
}
</style>