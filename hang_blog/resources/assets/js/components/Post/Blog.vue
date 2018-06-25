<template>
  <div id="blog">
    <profile :nickname-parameter="nickname"></profile>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xs-12 col-sm-10 col-lg-8">
          <div class="row justify-content-center">
            <div v-if="loaded" class="title col-xs-12">
              {{post.title}}
            </div>
          </div>
          <div>
            <hr>
          </div>
          <div class="row justify-content-center">
            <div v-if="loaded" class="image col-xs-12">
              <img :src="getImg(post.image)" height="500" width="350" class="img-fluid">
            </div>
          </div>
          <div>
            <hr>
          </div>
          <div class="row justify-content-center">
            <div v-if="loaded" class="content col-xs-12" v-html="post.content">
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
  import Profile from '../User/Profile'

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
          console.log(resp)
          this.loaded = true
          this.post = resp.data.post[0]
        })
        .catch (error => {
          console.log(error)
        })
      },
      getImg(name) {
        return '/post/images/' + name;
      }
    },
    components: {
      Profile
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
</style>