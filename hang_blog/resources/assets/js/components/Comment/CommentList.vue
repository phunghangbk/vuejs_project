<template>
  <div id="commentList">
    <div class="container custom-container" v-for="comment in comments">
      <div class="row justify-content-center">
        <div class="leftColumn col-xs-2">
          
        </div>
        <div class="rightColumn col-xs-10">
          
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

  export default {
    name: 'CommentList',
    props: {
      postId: {
        required: true
      }
    },
    data() {
      lazyload: {
        error: '/images/profile_default.png',
        loading: '/images/loading.gif'
      },
      comments: []
    },
    methods: {
      fetchData() {
        axios.get(api.postcomments, {
          params: {
            postId: this.postId
          }
        })
        .then (resp => {
          console.log(resp)
          this.comments = resp.data.comments
        })
      }
    }
  }
</script>
<style scoped>
  
</style>