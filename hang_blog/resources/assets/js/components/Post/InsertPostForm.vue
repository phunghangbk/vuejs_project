<template>
  <div class="container-fluid custom-container">
    <div class="row justify-content-center">
      <div v-if="!success" class="col-xs-12 col-lg-8">
        <h1>Create Article</h1>
        <div :class="{'has-error': error && custom_errors.title}">
          <div class="title form-group row form-inline">
            <label class="col-sm-2 col-form-label">Title</label>
            <input type="text" class="col-sm-8 title_content form-control" id="title_content" v-model="title">
          </div>
          <span class="help-block" v-if="error && custom_errors.title">{{custom_errors.title[0]}}</span>
        </div>

        <div :class="{'has-error': error && custom_errors.image}">
          <div class="image" v-if="image">
            <img :src="image" class="img-responsive" height="70" width="90">
          </div>
          <div class="form-group row form-inline" @change="onImageChange">
            <label class="col-sm-2 justify-content-center col-form-label">Image</label>
            <label class="uploadImage col-sm-8 col-form-label">
              <input type="file" accept="image/jpeg, image/png" multiple="" size="60" id="headerImg" class="d-none">
            </label>
          </div>
          <span class="help-block" v-if="error && custom_errors.image">{{custom_errors.image[0]}}</span>
        </div>

        <div :class="{'has-error': error && custom_errors.introduction}">
          <div class="introduction form-group row form-inline">
            <label class="col-sm-2 col-form-label">Introduction</label>
            <textarea class="col-sm-8 form-control" id="introduction" v-model="introduction"></textarea>
          </div>
          <span class="help-block" v-if="error && custom_errors.introduction">{{custom_errors.introduction[0]}}</span>
        </div>

        <div :class="{'has-error': error && custom_errors.status}">
          <div class="status form-group row">
            <label class="col-sm-2 col-form-label">Status</label>
            <div class="col-sm-8">
              <div class="form-check form-check-inline">
                <input type="radio" name="status" class="form-check-input" id="statusPublish" value="1" v-model="status">
                <label class="form-check-label" for="statusPublish">Publish</label>
              </div>
              <div class="form-check form-check-inline">
                <input type="radio" name="status" class="form-check-input" id="statusOnlyme" value="2" v-model="status">
                <label class="form-check-label" for="statusOnlyme">Only me</label>
              </div>
            </div>
          </div>
          <span class="help-block" v-if="error && custom_errors.status">{{custom_errors.status[0]}}</span>
        </div>

        <div :class="{'has-error': error && custom_errors.content}">
          <quill-editor class="custom-editor col-xs-12" v-model="content"
                        ref="myQuillEditor"
                        :options="editorOption"
                        @blur="onEditorBlur($event)"
                        @focus="onEditorFocus($event)"
                        @ready="onEditorReady($event)">
          </quill-editor>
          <span class="help-block" v-if="error && custom_errors.content">{{custom_errors.content[0]}}</span>
        </div>

        <div class="col-xs-12 submitButton">
          <button type="button" class="btn btn-success" @click="createPost">Submit</button>
        </div>
        <div v-if="success">
          <span style="color: green;">
            Post article successfully!!
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import * as api from '../../store/api.js'
  import Vue from 'vue'
  import Quill from 'quill'
  import { quillEditor } from 'vue-quill-editor'
  import { ImageResize } from 'quill-image-resize-module'
  import { ImageDrop } from 'quill-image-drop-module'
  import Toasted from 'vue-toasted'
  Quill.register('modules/imageResize', ImageResize)
  Quill.register('modules/imageDrop', ImageDrop)

  import 'quill/dist/quill.core.css'
  import 'quill/dist/quill.snow.css'
  import 'quill/dist/quill.bubble.css'

  Vue.use(Toasted, {
    iconPack : 'material'
  })
  export default {
    components: {
      quillEditor
    },
    data () {
      return {
        user: null,
        content: null,
        title: null,
        status: 1,
        image: null,
        introduction: null,
        success: false,
        error: false,
        custom_errors: [], 
        editorOption: {
          modules: {
            toolbar: [
              ['bold', 'italic', 'underline', 'strike'],
              ['blockquote', 'code-block'],
              [{ 'header': 1 }, { 'header': 2 }],
              [{ 'list': 'ordered' }, { 'list': 'bullet' }],
              [{ 'script': 'sub' }, { 'script': 'super' }],
              [{ 'indent': '-1' }, { 'indent': '+1' }],
              [{ 'direction': 'rtl' }],
              [{ 'size': ['small', false, 'large', 'huge'] }],
              [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
              [{ 'font': [] }],
              [{ 'color': [] }, { 'background': [] }],
              [{ 'align': [] }],
              ['clean'],
              ['link', 'image', 'video']
            ],
            history: {
              delay: 1000,
              maxStack: 50,
              userOnly: false
            },

            imageDrop: true,

            imageResize: {
              displayStyles: {
                backgroundColor: 'black',
                border: 'none',
                color: 'white'
              },
              modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
            }
          }
        }
      }
    },
    beforeCreate() {
      if (! this.$store.state.isLogged) {
        this.$router.push('/login')
      }
    },
    created() {
      this.user = JSON.parse(localStorage.getItem('user'));
    },
    methods: {
      onEditorBlur(quill) {
        console.log('editor blur!', quill)
      },
      onEditorFocus(quill) {
        console.log('editor focus!', quill)
      },
      onEditorReady(quill) {
        console.log('editor ready!', quill)
      },
      onEditorChange({ quill, html, text }) {
        console.log('editor change!', quill, html, text)
        this.content = html
      },
      createPost() {
        axios.post(api.post_create, {
          title: this.title,
          image: this.image,
          introduction: this.introduction,
          status: this.status,
          content: this.content
        })
        .then(resp => {
          if (typeof resp.data.status != 'undefined' && resp.data.status == 'success') {
            this.success = true
            Vue.toasted.show('Post article successfully!!', { 
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
            this.$router.push('/user/' + this.user.nickname);
          } else {
            this.error = true;
            if (typeof resp.data.errors != 'undefined') {
              this.custom_errors = resp.data.errors;
            }
            
            Vue.toasted.show('There was an error, unable to complete post article.', { 
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
          Vue.toasted.show('There was an error, unable to complete post article.', { 
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
          console.log(error.response)
        })
      },
      onImageChange(event) {
        let files = event.target.files || event.dataTransfer.files;
        if (! files.length) {
          return;
        }
        let reader = new FileReader();
        let vm = this;
        reader.onload = (e) => {
          vm["image"] = e.target.result;
        };
        reader.readAsDataURL(files[0]);
      }
    },
    computed: {
      editor() {
        return this.$refs.myQuillEditor.quill
      },
    },
    mounted() {
      console.log('this is current quill instance object', this.editor)
    }
  }
</script>

<style scoped>
  .custom-container {
    margin-top: 56px;
    height: 100vh;
  }

  .custom-editor {
    height: 20rem;
  }

  .title {
    margin-top: 1rem;
  }

  .quill-code {
    border: none;
    height: auto;
    > code {
      width: 100%;
      margin: 0;
      padding: 1rem;
      border: 1px solid #ccc;
      border-top: none;
      border-radius: 0;
      height: 10rem;
      overflow-y: auto;
      resize: vertical;
    }
  }

  .uploadImage {
    width: 100%; 
    height: 50px; 
    font-size: 0px; 
    margin-bottom: 1rem; 
    background-color: #0ebf67; 
    background-position: 50% center;
    border-radius: 3px;
    background-repeat: no-repeat;
    background-image: url(https://abs.twimg.com/a/1498195419/img/t1/highline/empty_state/owner_empty_avatar.png); 
  }

  .help-block {
    color: red;
  }
  h1 {
    font-family: "Libre Baskerville",Georgia,serif;
  }
</style>