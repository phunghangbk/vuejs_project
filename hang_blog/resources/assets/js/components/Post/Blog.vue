<template>
  <div id="blog">
    <profile :nickname-parameter="nickname"></profile>
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
        post: []
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
        })
        .catch (error => {

        })
      }
    },
    components: {
      Profile
    }
  }
</script>

<style>
  
</style>