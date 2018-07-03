<template>
  <div id="reply">
    <div v-if="replyCount > 0" class="replyCount">
      <i class="fa fa-arrow-circle-right"></i>
      {{replyCount}} Replies
    </div>
    <div :id="'replyList' + parentId" class="replyList">
      <div class="row replyItem" v-for="comment in comments">
          <div class="col-2">
            <a :href="'/user/'+comment.user.nickname"><img :src="avatar(comment.user.avatar_image)" class="avatarimg img-fluid"></a>
          </div>
          <div class="col-10">
            <div class="info row justify-content-around">
              <div class="col-sm-6 col-lg-6 col-xs-12 userName"><span><b style="font-weight: 700; font-size: 15px;">{{comment.user.name}}</b></span></div>
              <div class="col-sm-6 col-lg-6 col-xs-12 commentTime"><span><time>{{comment.created_at}}</time></span></div>
            </div>
            <div class="commentContent row justify-content-start" v-html="comment.content.replace(/(?:\r\n|\r|\n)/g, '<br />')">
            </div>
            <div class="updateDelete row">
              <div class="btn btn-link" data-toggle="modal" data-target="#modalUpdateCenter" @click="updateComment(comment)">
                <i class="fas fa-pencil-alt"></i>
                Update
              </div>
              <div class="btn deleteButton btn-link" data-toggle="modal" data-target="#deleteModalComment" @click="deleteComment(comment.comment_id)">
                <i class="fas fa-trash"></i>
                Delete
              </div>
              <like-comment :likes="comment.likes" :comment-id="comment.comment_id"></like-comment>
            </div>
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
  import LikeComment from '../Like/LikeComment'

  export default {
    name: 'Reply',
    props: {
      postId: {
        required: true
      },
      parentId: {
        required: true
      },
      comments: {
        required: true
      }
    },
    data() {
      return {
        replyCount: 0,
        commentId: null,

      }
    },
    created() {
      this.replyCount = this.comments ? this.comments.length : 0
    },
    methods: {
      avatar(fileName) {
        return imagePath.avatarImagePath + fileName
      },
      deleteComment(commentId) {
        this.$emit('deleteReply', commentId)
      },
      filterComments(commentId) {
        let comment = this.comments.filter(function (item) {
          return item.comment_id == commentId
        })
        return comment.length > 0 ? comment[0] : null
      },
      updateAfterUpdate(commentId, comment) {
        let updatedComment = this.filterComments(commentId)
        if (updatedComment) {
          let index = $.inArray(updatedComment, this.comments)
          if (index != -1) {
            this.comments.splice(index, 1, comment)
          }
        }
      },
      updateAfterCreate(comment) {
        this.comments.push(comment);
      },
      updateComment(comment) {
        this.$emit('udpateReply', comment)
      },

    },
    watch: {
      comments() {
        this.replyCount = this.comments.length
      }
    },
    mounted() {
      this.$bus.$on('changeAfterDeleteBus', (commentId) => {
        let deletedComment = this.filterComments(commentId)
        if (deletedComment) {
          let index = $.inArray(deletedComment, this.comments)
          if (index != -1) {
            this.comments.splice(index, 1)
          }
        }
      })

      this.$bus.$on('changeAfterCreateComment', (comment, isReply, parentId) => {
        if (isReply && parentId == this.parentId) {
          this.comments.push(comment)
        }
      })

      this.$bus.$on('changeAfterUpdate', (commentId, comment) => {
        this.updateAfterUpdate(commentId, comment)
      })
    },
    components: {
      Comment, LikeComment
    }
  }
</script>
<style scoped>
  .avatarimg {
    border-radius: 50px;
    height: 50px;
    width: 50px;
  }
  .userName {
    font-family: "Libre Baskerville",Georgia,serif;
  }
  .commentTime {
    color: #303030;
    font-size: 15px;
    font-family: "Libre Baskerville",Georgia,serif;
  }
  .comment {
    width: 100%;
  }
  .userName {
    text-align: left;
  }
  .commentTime {
    text-align: right;
  }
  .commentContent {
    text-align: left;
  }
  .commentContent {
    padding-top: 10px;
    padding-bottom: 10px;
  }
  .info {
    padding-top: 7px;
  }
  .reply {
    padding-top: 10px; 
    padding-bottom: 10px;
  }
  .replyList {
    margin-top: 10px;
  }
  .replyItem {
    border: solid 1px #e6ecf0;
    border-radius: 3px;
    padding: 10px 10px 10px 10px;
    margin-top: .5rem;
    box-shadow: rgba(0, 0, 0, 0.117647) 0px 1px 6px, rgba(0, 0, 0, 0.117647) 0px 1px 4px;
    transition: .15s all ease-in-out;
  }
  .replyCount  {
    text-align: left;
    color: green;
  }
  div.btn {
    font-family: "Libre Baskerville",Georgia,serif;
  }
  @media only screen and (max-width: 768px) {
    .replyList > div > div > div.info  > div.col-sm-6.col-lg-6.col-xs-12.commentTime {
      text-align: left;
    }
  }
</style>