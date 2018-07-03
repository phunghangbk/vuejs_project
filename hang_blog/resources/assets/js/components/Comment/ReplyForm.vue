<template>
  <div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCenterTitle">Reply for comment #{{commentId}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row parentComment">
            <div v-if="user" class="col-2 avatarImg">
              <a :href="'/user/'+user.nickname"><img :src="avatar(user.avatar_image)" class="avatarimg img-fluid"></a>
            </div>
            <div class="col-10 info" style="text-align: left;">
              <div v-if="user" class="col-sm-6 col-lg-6 col-xs-12 userName">
                <span><b style="font-weight: 700; font-size: 15px;">{{user.name}}</b></span>
              </div>
              <div v-if="createdAt" class="col-sm-6 col-lg-6 col-xs-12 commentTime">
                <span><time>{{createdAt}}</time></span>
              </div>
            </div>
          </div>
          <div v-if="content" class="commentContent row" v-html="content.replace(/(?:\r\n|\r|\n)/g, '<br />')"></div>
          <comment :post-id="postId" :parent-id="commentId" :is-reply="true"></comment>
        </div>
      </div>
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
    props: {
      commentId: {
        required: true
      },
      postId: {
        required: true
      },
      content: {
        required: false
      },
      createdAt: {
        required: false
      },
      user: {
        required: false
      }
    },
    methods: {
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
  .commentContent {
    margin-left: 100px;
    margin-top: 10px;
    margin-bottom: 10px;
    text-align: left;
  }
</style>