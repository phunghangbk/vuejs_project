<template>
  <div id="reply">
    <div :id="'replyList' + parentId" class="replyList">
      <div class="row replyItem" v-for="comment in comments">
          <div class="col-2">
            <img v-if="loaded" :src="avatar(comment.user.avatar_image)" class="avatarimg img-fluid">
          </div>
          <div class="col-10">
            <div class="info row justify-content-around">
              <div class="col-sm-6 col-lg-6 col-xs-12 userName"><span><b style="font-weight: 700; font-size: 15px;">{{comment.user.name}}</b></span></div>
              <div class="col-sm-6 col-lg-6 col-xs-12 commentTime"><span><time>{{comment.created_at}}</time></span></div>
            </div>
            <div v-if="loaded" class="commentContent row justify-content-start" v-html="comment.content.replace(/(?:\r\n|\r|\n)/g, '<br />')">
            </div>
            <div class="updateDelete row">
              <div class="btn" data-toggle="modal" data-target="#modalUpdateCenter" @click="updateComment(comment)">
                <i class="fas fa-pencil-alt"></i>
                Update
              </div>
              <div class="btn deleteButton" data-toggle="modal" data-target="#deleteModalComment" @click="deleteComment(comment.comment_id)">
                <i class="fas fa-trash"></i>
                Delete
              </div>
              <like-comment :comment-id="comment.comment_id"></like-comment>
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
      }
    },
    data() {
      return {
        comments: [],
        loaded: false,
        replyCount: 0,
        countLoaded: false,
        commentId: null,

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
          this.comments = resp.data.comments
          this.loaded = true
        })
      },
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
  }
  @media only screen and (max-width: 768px) {
    .replyList > div > div > div.info  > div.col-sm-6.col-lg-6.col-xs-12.commentTime {
      text-align: left;
    }
  }
</style>