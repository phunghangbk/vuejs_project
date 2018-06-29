<template>
  <div id="commentList">
    <div class="container custom-container" v-for="comment in comments">
      <div class="row justify-content-center">
        <div>
          <hr>
        </div>
        <div class="leftColumn col-lg-4 col-sm-12 col-xs-12">
          <img v-if="loaded" :src="avatar(comment.user.avatar_image)" class="avatarimg img-fluid">
        </div>
        <div class="info">
          <div><span class="userName">{{comment.user.name}}</span></div>
          <div><span class="commentTime">{{comment.created_at}}</span></div>
        </div>
        <div class="rightColumn col-lg-8 col-sm-12 col-xs-12">
          <div>
            <hr>
          </div>
          <div v-if="loaded" class="commentContent" v-html="comment.content">
          </div>
          <div>
            <hr>
          </div>
        </div>
      </div>
    </div>
    <div class="replyButton">
      <comment :post-id="postId" :parent-id="parentId"></comment>
    </div>
  </div>
</template>
<script>
  import Vue from 'vue'
  import axios from 'axios'
  import * as api from '../../store/api.js'
  import * as imagePath from '../../router/imagePath.js'
  import Comment from './Comment'

  export default {
    name: 'Reply',
    props: {
      postId: {
        required: true
      },
      parentId: {
        required: true
      }
    },
    data() {
      return {
        comments: [],
        loaded: false,
        replyCount: 0,
        countLoaded: false
      }
    },
    created() {
      this.fetchData()
    },
    methods: {
      fetchData() {
        axios.get(api.commentFindByParent, {
          params: {
            parentId: this.parentId
          }
        })
        .then (resp => {
          console.log(resp)
          this.comments = resp.data.comments
          this.loaded = true
        })
      },
      avatar(fileName) {
        return imagePath.avatarImagePath + fileName
      },
    },
    components: {
      Comment
    }
  }
</script>
<style scoped>
  .avatarimg {
    background-color: #fff;
    border-radius: 2px;
    display: block;
    height: 160px;
    position: relative;
    width: 160px;
    border: 4px solid #fff;
    z-index: 10;
  }
</style>