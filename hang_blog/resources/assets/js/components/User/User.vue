<template>
  <div class="container-fluid custom-container">
    <profile :nickname-parameter="nickname"></profile>
    <div class="row justify-content-center">
      <div class="blogList">
        <blog-list :nickname="nickname"></blog-list>
      </div>
    </div>
  </div>
</template>

<script>
  import Profile from './Profile'
  import BlogList from '../Post/BlogList'
  export default {
    props: {
      nickname: {
        required: true
      }
    },

    components: {
      Profile, BlogList
    }
  }
</script>

<style scoped>
  
</style>