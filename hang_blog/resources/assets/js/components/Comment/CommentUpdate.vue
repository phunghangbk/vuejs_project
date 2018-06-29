<template>
  <div>
    <div v-if="canUpdate" id="updateButton" class="btn" @click="showForm">
      <i class="fas fa-pencil-alt"></i>
      Update
    </div>
    <div v-if="loaded" id="commentFormUpdate" class="container-fluid custom-container" style="display: none;">
      <div class="row justify-content-center">
        <div class="col-xs-12 col-sm-10 col-lg-8">
          <div>
            <hr>
          </div>
          <div :class="{'has-error': error}">
            <quill-editor class="custom-editor col-xs-12" v-model="content"
                          ref="myQuillEditor"
                          :options="editorOption">
            </quill-editor>
            <span class="help-block" v-if="error">An error has occurred</span>
          </div>
          <div class="sendButton">
            <button type="button" class="btn btn-success" @click="update" :disabled="! $store.state.isLogged">Send</button>
            <button type="button" class="btn btn-secondary" @click="cancle">Cancle</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import Vue from 'vue'
  import axios from 'axios'
  import * as api from '../../store/api.js'
  import Quill from 'quill'
  import { quillEditor } from 'vue-quill-editor'
  import Toasted from 'vue-toasted'

  import 'quill/dist/quill.core.css'
  import 'quill/dist/quill.snow.css'
  import 'quill/dist/quill.bubble.css'

  Vue.use(Toasted, {
    iconPack : 'material'
  })

  export default {
    name: 'CommentUpdate',
    props: {
      commentId: {
        required: true
      }
    },
    data() {
      return {
        loaded: false,
        canUpdate: false,
        content: '',
        comment: null,
        error: false,
        success: false,
        message: '',
        loaded: false,
        editorOption: {
          modules: {
            toolbar: [
              ['bold', 'italic', 'underline', 'strike'],
              ['blockquote', 'code-block'],
              [{ 'indent': '-1' }, { 'indent': '+1' }],
              [{ 'direction': 'rtl' }],
              [{ 'size': ['small', false, 'large', 'huge'] }],
              [{ 'font': [] }],
              [{ 'color': [] }, { 'background': [] }],
              [{ 'align': [] }],
              ['clean'],
            ],
            history: {
              delay: 1000,
              maxStack: 50,
              userOnly: false
            },
          }
        }
      }
    },
    created() {
      this.canUpdateComment()
      this.fetchData()
    },
    methods: {
      update() {
        axios.post(api.comment_update + this.commentId, {
          content: this.content
        })
        .then (resp => {
          if (resp.data.status = "success") {
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
      fetchData() {
        axios.get(api.comments+this.commentId)
        .then (resp => {
          this.content = resp.data.comment.content
          this.loaded = true
        })
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
      showForm() {
        $(function() {
          $('#commentFormUpdate').slideToggle('fast')
        })
      },
      cancle() {
        $(function() {
          $('#commentFormUpdate').hide()
        })
      }
    },
    components: {
      quillEditor
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
</style>