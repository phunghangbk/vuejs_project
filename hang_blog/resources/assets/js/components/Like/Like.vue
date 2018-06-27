<template>
  <div v-if="$store.state.isLogged" class="likeButton btn" :class="{'btn-primary': liked && checkLikedLoaded}" @click="iLikeIt">
    <i class="fa fa-thumbs-o-up"></i> 
    <span v-if="likesCountLoaded">
      {{ likesCount }}
    </span>
  </div>
</template>
<script>
  import Vue from 'vue'
  import axios from 'axios'
  import * as api from '../../store/api.js'

  export default {
    name: 'Like',
    props: {
      postId: {
        required: true
      }
    },
    data() {
      return {
        liked: false,
        likesCount: 0,
        likesCountLoaded: false,
        checkLikedLoaded: false
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
          axios.post(api.post_like, {
            postId: this.postId
          })
          .then(resp => {
            console.log(resp)
            if (typeof resp.data.liked != 'undefined') {
              this.liked = resp.data.liked
            }
            this.liked ? this.likesCount++ : this.likesCount--
          });
        }
      },
      getLikesCount() {
        axios.get(api.post_likesCount, {
          params: {
            postId: this.postId
          }
        })
        .then (resp => {
          if (typeof resp.data.count != 'undefined') {
            this.likesCount = resp.data.count
          }
          this.likesCountLoaded = true
        })
      },
      checkLiked() {
        axios.get(api.post_checkLiked, {
          params: {
            postId: this.postId
          }
        })
        .then (resp => {
          if (typeof resp.data.liked != 'undefined') {
            this.liked = resp.data.liked
          }
          this.checkLikedLoaded = true
        })
      }
    }
  }
</script>
<style scoped>
  .likeButton {
    box-shadow: rgba(0, 0, 0, 0.117647) 0px 1px 6px;
  }
</style>