<template>
  <div class="modal fade" id="deleteModalComment" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Do you want delete this comment?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Warnning: Cannot restore after delete action.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal" @click="deleteAction">Delete</button>
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
    name: 'CommentDelete',
    props: {
      commentId: {
        required: true
      }
    },
    data() {
      return {
        canDelete: false
      }
    },
    methods: {
      canDeleteComment() {
        axios.get(api.canDeleteComment, {
          params: {
            commentId: this.commentId
          }
        })
        .then (resp => {
          this.canDelete = resp.data.canDeleteComment
        })
      },
      deleteAction() {
        axios.post(api.comment_delete + this.commentId)
        .then (resp => {
          if (typeof resp.data.status != 'undefined' && resp.data.status == "success") {
            this.$bus.$emit('changeAfterDeleteBus', this.commentId)
            this.$bus.$emit('changeCommentCount', -1)
            Vue.toasted.show('Comment has deleted', { 
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
            Vue.toasted.show('There was an error, unable update comment.', { 
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
      }
    }
  }
</script>
<style scoped>
  
</style>