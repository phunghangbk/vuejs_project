<template>
  <div v-if="$store.state.isLogged" class="likeButton btn" :class="{'btn-primary': liked}" @click="iLikeIt">
    <i class="fa fa-thumbs-o-up"></i> 
    <span>
      {{ likesCount }}
    </span>
  </div>
</template>
<script>
  import Vue from 'vue'
  import axios from 'axios'
  import * as api from '../../store/api.js'

  export default {
    name: 'LikeComment',
    props: {
      commentId: {
        required: true
      },
      likes: {
        required: true
      }
    },
    data() {
      return {
        liked: false,
        likesCount: 0,
      }
    },

    created() {
      this.getLikesCount()
      this.checkLiked()
    },
    methods: {
      iLikeIt() {
        if (! this.$store.state.isLogged) {
          this.$router.push('/login')
        } else {
          axios.post(api.comment_like, {
            commentId: this.commentId
          })
          .then(resp => {
            if (typeof resp.data.liked != 'undefined') {
              this.liked = resp.data.liked
            }
            this.liked ? this.likesCount++ : this.likesCount--
          });
        }
      },
      getLikesCount() {
        this.likesCount = typeof this.likes != 'undefined' ? this.likes.length : 0
      },
      checkLiked() {
        if (typeof this.likes != 'undefined') {
          for (var i = 0; i < this.likes.length; i++) {
            if (JSON.parse(localStorage.getItem('user')).user_id == this.likes[i].user_id) {
              this.liked = true
            }
          }
        }
      }
    }
  }
</script>
<style scoped>
  .likeButton {
    box-shadow: rgba(0, 0, 0, 0.117647) 0px 1px 6px;
  }
</style>