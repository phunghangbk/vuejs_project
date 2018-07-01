<template>
  <div class="modal fade" id="modalUpdateCenter" tabindex="-1" role="dialog" aria-labelledby="modalUpdateCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalUpdateCenterTitle">Update Comment #{{commentId}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row parentComment">
            <div v-if="user" class="col-2 avatarImg">
              <img :src="avatar(user.avatar_image)" class="avatarimg img-fluid">
            </div>
            <div class="col-10 info" style="text-align: left;">
              <div v-if="user" class="col-sm-6 col-lg-6 col-xs-12 userName">
                <span><b style="font-weight: 700; font-size: 15px;">{{user.name}}</b></span>
              </div>
              <div v-if="createdAt" class="col-sm-6 col-lg-6 col-xs-12 commentTime">
                <span><time>{{createdAt}}</time></span>
              </div>
            </div>
          </div>
          <div v-if="content" :class="{'has-error': error}" style="margin-top: 1rem;">
            <div class="form-group">
              <textarea class="form-control" id="commentContent" rows="3" v-model="uContent"></textarea>
              <span class="help-block"  v-if="error">An error has occurred</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success" data-dismiss="modal" @click="update" :disabled="! $store.state.isLogged">Send</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import Vue from 'vue'
  import axios from 'axios'
  import * as api from '../../store/api.js'
  import * as imagePath from '../../router/imagePath.js'
  import Toasted from 'vue-toasted'

  Vue.use(Toasted, {
    iconPack : 'material'
  })

  export default {
    name: 'CommentUpdate',
    props: {
      commentId: {
        required: true
      },
      content: {
        required: false
      },
      createdAt: {
        required: false
      },
      user: {
        required: false
      }
    },
    data() {
      return {
        canUpdate: false,
        comment: null,
        error: false,
        success: false,
        message: '',
        loaded: false,
        uContent: ''
      }
    },
    created() {
      console.log(this.commentId)
    },
    watch: {
      content() {
        this.uContent = this.content;
      }
    },
    methods: {
      update() {
        axios.post(api.comment_update + this.commentId, {
          content: this.uContent
        })
        .then (resp => {
          if (resp.data.status = "success") {
            this.$bus.$emit('changeAfterUpdate', this.commentId, resp.data.comment)
            this.success = true
            Vue.toasted.show('Update comment success!!', { 
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
          Vue.toasted.show('Cannot update your comment. Try later!', { 
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
      },
      avatar(fileName) {
        return imagePath.avatarImagePath + fileName
      },
      canUpdateComment() {
        axios.get(api.canUpdateComment, {
          params: {
            commentId: this.commentId
          }
        })
        .then (resp => {
          this.canUpdate = resp.data.canUpdateComment
        })
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
.info {
  padding-top: 7px;
}
</style>