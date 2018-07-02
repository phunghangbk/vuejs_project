<template>
  <div id="blog">
    <profile :nickname-parameter="nickname"></profile>
    <div class="container-fluid custom-container">
      <div class="row justify-content-center">
        <div v-if="!is_error" class="col-xs-12 col-sm-10 col-lg-8">
          <div class="row justify-content-end">
            <div v-if="loaded" class="dropdown d-flex flex-row">
              <div data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin: .5rem;">
                <i class="fas fa-cog" id="dropdownMenu"></i>
                Setting
              </div>
              <like :post-id="post_id"></like>
              <div class="dropdown-menu" aria-labelledby="dropdownMenu">
                <a :href="'/post/' + post_id + '/update'" class="dropdown-item">Update</a>
                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteModal">Delete</a>
              </div>
              <delete-post ref="deletePostComponent" :post-id="post_id"></delete-post>
            </div>
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
          </div>
        
          <div>
            <hr>
          </div>
          <div class="row justify-content-center">
            <div v-if="loaded" class="title col-xs-12">
              <h1>
                {{post.title}}
              </h1>
            </div>
          </div>
          <div>
            <hr>
          </div>
          <div class="row justify-content-center">
            <div v-if="loaded" class="image col-xs-12">
              <img v-lazy="{src: getImg(post.image), loading: lazyload.loading, error: lazyload.error}" class="img-fluid">
            </div>
          </div>
          <div>
            <hr>
          </div>
          <div class="row">
            <div v-if="loaded" style="text-align: left;" class="content col-xs-12" v-html="post.content">
            </div>
          </div>
        </div>
        <div v-if="is_error">
          <span class="help-block">
            {{error}}
          </span>
        </div>
        <comment-list v-if="post" :comments="post.comments" :post-id="post_id"></comment-list>
      </div>
      <div><hr></div>
      <h1>
        Sent your comment
      </h1>
      <comment :post-id="post_id"></comment>
    </div>
  </div>
</template>

<script>
  import Vue from 'vue'
  import VueLazyload from 'vue-lazyload'
  import axios from 'axios'
  import * as api from '../../store/api.js'
  import Profile from '../User/Profile'
  import DeletePost from './DeletePost'
  import Like from '../Like/Like'
  import Comment from '../Comment/Comment'
  import CommentList from '../Comment/CommentList'

  export default {
    name: 'Blog',
    props: {
      nickname: {
        required: true
      },
      post_id: {
        required: true
      }
    },
    data() {
      return {
        post: null,
        loaded: false,
        lazyload: {
          error: '/images/post_image.jpg',
          loading: '/images/loading.gif'
        },
        error: '',
        is_error: false
      }
    },
    created() {
      this.fetchData()
    },
    methods: {
      fetchData() {
        axios.get(api.post + this.post_id, {
          params: {
            nickname: this.nickname
          }
        })
        .then (resp => {
          console.log(resp.data.post[0].comments)
          if (typeof resp.data.status != 'undefined' && resp.data.status == 'success') {
            this.loaded = true
            this.post = resp.data.post[0]
          } else {
            this.is_error = true
            if (typeof resp.data.errors != 'undefined' && resp.data.errors['post_id']) {
              this.error += resp.data.errors['post_id'] + '\n';
            }

            if (typeof resp.data.errors != 'undefined' && resp.data.errors['error']) {
              this.error += resp.data.errors['error'] + '\n';
            }
          }
        })
        .catch (error => {
          this.is_error = true
          this.error = 'An error has occured. Cannot load this post. Please try later.';
        })
      },
      getImg(name) {
        return '/post/images/' + name;
      },

      deletePost() {
        this.$refs.deletePostComponent.delete()
      }
    },
    components: {
      Profile, DeletePost, Like, Comment, CommentList
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

.title {
  font-family: "Libre Baskerville",Georgia,serif;
  font-size: 28px;
  line-height: 1.25;
  margin-top: 10px;
}

.custom-container {
  min-height: 100%;
}
span {
  color: red;
}
h1 {
  font-family: "Libre Baskerville",Georgia,serif;
}
</style>