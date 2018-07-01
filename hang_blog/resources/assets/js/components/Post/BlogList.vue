<template>
  <div id="blogList">
    <div class="container">
      <div class="row justify-content-around">
        <div class="col-xs-12 col-lg-6 col-sm-6 postContent" style="padding: 0px 10px;" v-for="(item, index) in list">
          <a v-if="loaded" :href="detailURL(item.post_id)">
            <div class="image">
              <img v-show="loaded" v-lazy="{src: getImg(item.image), loading: lazyload.loading, error: lazyload.error}" height="500" width="350" class="img-fluid" />
            </div>
            <div class="title">{{item.title}}</div>
            <div class="introduction">
              {{item.introduction}}
            </div>
          </a>
          <div v-if="loaded && is_signedin" class="btn updateButton">
            <a :href="'/post/' + item.post_id + '/update'">
              <i class="fas fa-pencil-alt"></i>
              Update
            </a>
          </div>
          <div v-if="loaded && is_signedin" class="btn deleteButton" data-toggle="modal" data-target="deleteModal" @click="deletePost(item.post_id)">
            <i class="fas fa-trash"></i>
            Delete
          </div>
          <like :post-id="item.post_id"></like>          
        </div>
        <delete-post :post-id="postId" @changeAfterDeletePost="updatePostList"></delete-post>
      </div>
    </div>
  </div>
</template>

<script>
  import Vue from 'vue'
  import VueLazyload from 'vue-lazyload'
  import axios from 'axios'
  import * as api from '../../store/api.js'
  import infiniteScroll from 'vue-infinite-scroll'
  import Like from '../Like/Like'
  import DeletePost from './DeletePost'
  Vue.use(infiniteScroll)

  export default {
    name: 'BlogList',
    props: {
      nickname: {
        required: true
      }
    },
    data() {
      return {
        list: [],
        stop: false,
        custom_errors: [],
        lazyload: {
          error: '/images/post_image.jpg',
          loading: '/images/loading.gif'
        },
        loaded: false,
        page: 1,
        is_busy: false,
        is_signedin: false,
        user: null,
        temp_user: null,
        postId: null
      }
    },
    created() {
      this.getUserInfo()
    },
    components: {
      Like, DeletePost
    },
    methods: {
      infiniteHandler() {
        axios.get(api.post_list + '?nickname=' + this.nickname + '&page=' + this.page)
        .then(resp => {
          if (typeof resp.data.status != 'undefined' && resp.data.status == 'success') {
            this.loaded = true
            if (resp.data.list.current_page < resp.data.list.last_page) {
              this.page = this.page + 1
            } else {
              this.stop = true
            }
            for (let i = 0; i < resp.data.list.data.length; i++) {
              this.list.push(resp.data.list.data[i])
            }
          } else {
            if (typeof resp.data.errors != 'undefined') {
              this.custom_errors = resp.data.errors
            }
          }
        })
        .catch(error => {
          this.error = true
          console.log(error.response)
        })
      },
      fetchData() {
        if (this.is_busy == true){
          return false
        }

        if (this.stop == true){
          return false
        }

        this.is_busy = true
        axios.get(api.post_list + '?nickname=' + this.nickname + '&page=' + this.page)
        .then(resp => {
          if (typeof resp.data.status != 'undefined' && resp.data.status == 'success') {
            this.loaded = true
            if (resp.data.list.current_page < resp.data.list.last_page) {
              this.page = this.page + 1
            } else {
              this.stop = true
            }
            for (let i = 0; i < resp.data.list.data.length; i++) {
              this.list.push(resp.data.list.data[i])
            }
          } else {
            if (typeof resp.data.errors != 'undefined') {
              this.custom_errors = resp.data.errors
            }
          }
          this.is_busy = false
        });
      },
      scroll() {
        $(window).scroll(() => {
          let bottomOfWindow = $(window).scrollTop() + $(window).height() + 10 >= $('#blogList').height()
          if (bottomOfWindow && ! this.stop) {
            this.fetchData()
          }
        });
      },
      getImg(name) {
        return '/post/images/' + name;
      },
      detailURL(post_id) {
        return '/user/' + this.nickname + '/post/' + post_id;
      },
      
      getUserInfo() {
        axios.get(api.user+'?nickname='+this.nickname)
        .then (resp => {
          if (resp.data.user) {
            this.user = resp.data.user
            if (this.$store.state.isLogged) {
              this.temp_user = JSON.parse(localStorage.getItem('user'))
              if (this.user.user_id == this.temp_user.user_id) {
                this.is_signedin = true
              }
            }
          }
        })
        .catch (error => {
          console.log(error)
        })
      },
      deletePost(postId) {
        this.postId = postId
        $(function () {
          $('#deleteModal').modal('show')
        })
      },
      filterComments(postId) {
        let post = this.list.filter(function (item) {
          return item.post_id == postId
        })
        return post.length > 0 ? post[0] : null
      },
      updatePostList(postId) {
        let deletedPost = this.filterComments(postId)
        if (deletedPost) {
          let index = $.inArray(deletedPost, this.list)
          if (index != -1) {
            this.list.splice(index, 1)
          }
        }
      }
    },
    mounted() {
      this.fetchData()
      $(document).ready(() => {
        this.scroll();
      })
    }
  }
</script>

<style scoped>
  .title {
    font-size: 24px;
    line-height: 1.2125em;
    color: #666;
    font-family: "Libre Baskerville",Georgia,serif;
    font-weight: 700;
    margin-bottom: 10px;
    margin-top: 10px;
  }

  .postContent {
    margin-top: 1rem;
  }

  .postContent {
    box-shadow: rgba(0, 0, 0, 0.117647) 0px 1px 6px, rgba(0, 0, 0, 0.117647) 0px 1px 4px;
    transition: .15s all ease-in-out;
  }
  .postContent:hover {
    background-color: #7AB66E;
  }
  .postContent > a {
    color: #666;
    text-decoration: none;
    padding: 12px;
    font-size: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .updateButton > a, .deleteButton > a {
    text-decoration: none;
    color: #6c757d;
  }

  .updateButton, .deleteButton, .likeButton {
    box-shadow: rgba(0, 0, 0, 0.117647) 0px 1px 6px;
  }

  .updateButton:hover, .deleteButton:hover {
    background-color: #11902e;
  }
</style>