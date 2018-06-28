<template>
  <div id="commentList">
    <div class="container custom-container">
      <div class="row justify-content-center">
        <div class="leftColumn">
          
        </div>
        <div class="rightColumn">
          
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
      }
    }
  }
</script>
<style scoped>
  
</style>