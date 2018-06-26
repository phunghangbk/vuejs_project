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
          <div v-if="loaded && is_signedin" class="btn deleteButton" data-toggle="modal" :data-target="'#deleteModal' + index">
            <i class="fas fa-trash"></i>
            Delete
          </div>
          <delete-post ref="deletePostComponent" :post-id="item.post_id"></delete-post>
          <div class="modal fade" :id="'deleteModal'+index" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                <button type="button" class="btn btn-primary" data-dismiss="modal" @click="deletePost(index)">Delete</button>
              </div>
            </div>
          </div>
        </div>
        </div>
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
        is_signedin: false
      }
    },
    created() {
      if (this.$store.state.isLogged) {
        this.is_signedin = true
      }
    },
    components: {
      DeletePost
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
      scroll (list) {
        $(window).scroll(() => {
          let bottomOfWindow = $(window).scrollTop() + $(window).height() >= $('#blogList').height()
          if (bottomOfWindow && ! this.stop) {
            if (this.is_busy == true){
              return false
            }

            if (this.stop == true){
              return false
            }

            this.is_busy = true
            axios.get(api.post_list + '?nickname=' + this.nickname + '&page=' + this.page)
            .then(resp => {
              console.log(resp.data.list)
              if (typeof resp.data.status != 'undefined' && resp.data.status == 'success') {
                this.loaded = true
                if (resp.data.list.current_page < resp.data.list.last_page) {
                  this.page = this.page + 1
                } else {
                  this.stop = true
                }
                for (let i = 0; i < resp.data.list.data.length; i++) {
                  list.push(resp.data.list.data[i])
                }
              } else {
                if (typeof resp.data.errors != 'undefined') {
                  this.custom_errors = resp.data.errors
                }
              }
              this.is_busy = false
            });
          }
        });
      },
      getImg(name) {
        return '/post/images/' + name;
      },
      detailURL(post_id) {
        return '/user/' + this.nickname + '/post/' + post_id;
      },
      deletePost(index) {
        console.log(this.$refs)
        this.$refs.deletePostComponent[index].delete()
      }
    },
    mounted() {
      $(document).ready(() => {
        this.scroll(this.list);
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

  .updateButton, .deleteButton {
    box-shadow: rgba(0, 0, 0, 0.117647) 0px 1px 6px;
  }

  .updateButton:hover, .deleteButton:hover {
    background-color: #11902e;
  }
</style>