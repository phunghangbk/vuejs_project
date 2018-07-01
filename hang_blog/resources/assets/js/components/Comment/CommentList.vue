<template>
  <div id="commentList" class="col-xs-12 col-lg-8 col-sm-10">
    <comment-count :post-id="postId"></comment-count>
    <div class="row" v-for="comment in comments">
      <div class="col-2">
        <img v-if="loaded" :src="avatar(comment.user.avatar_image)" class="avatarimg img-fluid">
      </div>
      <div class="col-10">
        <div class="info row justify-content-around">
          <div class="col-sm-6 col-lg-6 col-xs-12 userName">
            <span><b style="font-weight: 700; font-size: 15px;">{{comment.user.name}}</b></span>
          </div>
          <div class="col-sm-6 col-lg-6 col-xs-12 commentTime">
            <span><time>{{comment.created_at}}</time></span>
          </div>
        </div>
        <div v-if="loaded" class="commentContent row" v-html="comment.content.replace(/(?:\r\n|\r|\n)/g, '<br />')">
        </div>
        <div class="updateDelete d-flex flex-row">
          <div class="btn" data-toggle="modal" data-target="#modalUpdateCenter" @click="updateComment(comment)">
            <i class="fas fa-pencil-alt"></i>
            Update
          </div>
          <div class="btn deleteButton" data-toggle="modal" data-target="#deleteModalComment" @click="deleteComment(comment.comment_id)">
            <i class="fas fa-trash"></i>
            Delete
          </div>
          <like-comment :comment-id="comment.comment_id"></like-comment>
          <div class="btn replyButton" data-toggle="modal" data-target="#replyModal" @click="replyComment(comment)">
            <i class="fa fa-reply"></i>Reply
          </div>
        </div>
        <reply :post-id="postId" :parent-id="comment.comment_id" @deleteReply="deleteComment" @udpateReply="updateComment"></reply>
      </div>
      <div>
      </div>
    </div>
    <comment-delete :comment-id="commentId"></comment-delete>
    <reply-form :comment-id="commentId" :user="user" :content="content" :created-at="createdAt" :post-id="postId"></reply-form>
    <comment-update :comment-id="commentId" :user="user" :content="content" :created-at="createdAt" @changeAfterUpdate="updateAfterUpdate"></comment-update>
  </div>
</template>
<script>
  import Vue from 'vue'
  import axios from 'axios'
  import * as api from '../../store/api.js'
  import * as imagePath from '../../router/imagePath.js'
  import Reply from './Reply' 
  import CommentUpdate from './CommentUpdate'
  import CommentDelete from './CommentDelete'
  import LikeComment from '../Like/LikeComment'
  import CommentCount from './CommentCount'
  import ReplyForm from './ReplyForm'

  export default {
    name: 'CommentList',
    props: {
      postId: {
        required: true
      }
    },
    data() {
      return {
        comments: [],
        loaded: false,
        commentId: null,
        canDelete: false,
        comment: null,
        content: null,
        user: null,
        createdAt: null
      }
    },
    created() {
      this.fetchData()
    },
    methods: {
      deleteComment(commentId) {
        this.commentId = commentId
        $('#deleteModalComment').modal('show')
      },
      fetchData() {
        axios.get(api.postcomments, {
          params: {
            postId: this.postId
          }
        })
        .then (resp => {
          this.comments = resp.data.comments
          this.loaded = true
        })
      },
      filterComments(commentId) {
        let comment = this.comments.filter(function (item) {
          return item.comment_id == commentId
        })
        return comment.length > 0 ? comment[0] : null
      },
      avatar(fileName) {
        return imagePath.avatarImagePath + fileName
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
      replyClick(commentId) {
        $(function() {
          $('#replyList'+commentId).slideToggle('fast')
          $('#replyButton'+commentId).text(function(_, txt) {
            let ret=''
            if (txt == 'Reply') {
              ret = 'Hide'
            } else {
              ret = 'Reply'
            }
            return ret
          }) 

          return false;
        })
      },
      replyComment(comment) {
        this.commentId = comment.comment_id
        this.content = comment.content
        this.user = comment.user
        this.createdAt = comment.created_at
      },

      updateComment(comment) {
        this.commentId = comment.comment_id
        this.content = comment.content
        this.user = comment.user
        this.createdAt = comment.created_at
      },

      showForm(commentId) {
        $(function() {
          $('#commentFormUpdate' + commentId).slideToggle('fast')
        })
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
      this.$bus.$on('changeAfterCreateComment', (comment, isReply) => {
        if (! isReply) {
          this.comments.push(comment)
        }
      })
      this.$bus.$on('changeAfterUpdate', (commentId, comment) => {
        this.updateAfterUpdate(commentId, comment)
      })
    },
    components: {
      Reply, CommentUpdate, CommentDelete, LikeComment, CommentCount, ReplyForm
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
  .replyButton {
    text-decoration: none;
    color: #495057;
  }
  .commentContent {
    padding-top: 10px;
    padding-bottom: 10px;
  }
  @media only screen and (max-width: 768px) {
    #commentList > div > div > div.info  > div.col-sm-6.col-lg-6.col-xs-12.commentTime {
      text-align: left;
    }
  }
</style>