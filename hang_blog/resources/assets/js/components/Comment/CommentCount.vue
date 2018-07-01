<template>
  <div id="commentsCount" class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-xs-12 col-sm-10 col-lg-8">
        <div>
          <hr>
        </div>
        <div>
          <span v-if="countLoaded" class="commentCount">
            <h2>
              {{commentsCount}} Comments
            </h2>
          </span>
        </div>
        <div>
          <hr>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import Vue from 'vue'
  import axios from 'axios'
  import * as api from '../../store/api.js'
  export default {
    name: 'CommentCount',
    props: {
      postId: {
        required: true
      }
    },
    data() {
      return {
        commentsCount: 0,
        countLoaded: false,
      }
    },
    created() {
      this.getCommentCount()
    },
    methods: {
      getCommentCount() {
        axios.get(api.postCommentsCount, {
          params: {
            postId: this.postId
          }
        })
        .then(resp => {
          if (typeof resp.data.count != 'undefined') {
            this.commentsCount = resp.data.count
            this.countLoaded = true
          }
        })
      }
    },
    mounted() {
      this.$bus.$on('changeCommentCount', (count) => {
        if (count == 1) {
          this.commentsCount++
        } else if (count == -1) {
          this.commentsCount--
        }
      })
    }
  }
</script>
<style scoped>
  h2 {
    font-family: "Libre Baskerville",Georgia,serif;
  }
</style>