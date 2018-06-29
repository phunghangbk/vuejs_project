<template>
  <div id="commentForm" class="container-fluid custom-container">
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
    name: 'Comment',
    props: {
      postId: {
        required: true
      },
      parentId: {
        required: false
      }
    },
    data() {
      return {
        content: '',
        error: false,
        success: false,
        message: '',
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
            console.log(resp)
            if (typeof resp.data.status != 'undefined' && resp.data.status == 'success') {
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