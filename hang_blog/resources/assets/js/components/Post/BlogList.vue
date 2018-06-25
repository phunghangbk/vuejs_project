<template>
  <div id="blogList">
    <div class="container">
      <div class="row justify-content-around">
        <div class="col-xs-12 col-lg-6 col-sm-6 postContent" style="padding: 0px 10px;" v-for="item in list">
          <a v-if="loaded" :href="detailURL(item.post_id)">
            <div class="image">
              <img v-show="loaded" v-lazy="{src: getImg(item.image), loading: lazyload.loading, error: lazyload.error}" height="500" width="350" class="img-fluid" />
            </div>
            <div class="title">{{item.title}}</div>
            <div class="introduction">
              {{item.introduction}}
            </div>
          </a>
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
        is_busy: false
      }
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
</style>