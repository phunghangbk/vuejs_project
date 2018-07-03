<template>
  <div id="userList" class="container-fluid custom-container">
    <h1>User List</h1>
    <div class="row justify-content-center">
      <table class="col-xs-12 col-lg-8 col-sm-10 table table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Avatar</th>
            <th scope="col">Name</th>
            <th scope="col">Nickname</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in userList">
              <th><a :href="'/user/'+user.nickname">{{user.user_id }}</a></th>
              <th><a :href="'/user/'+user.nickname"><img v-lazy="{src: avatar(user.avatar_image), loading: lazyload.loading, error: lazyload.error}" class="avatarimg img-fluid"></a></th>
              <th><a :href="'/user/'+user.nickname">{{user.name}}</a></th>
              <th><a :href="'/user/'+user.nickname">{{user.nickname}}</a></th>
          </tr>
        </tbody>
      </table>

      <pagination 
      :total-pages="totalPages"
      :current-page="pageOne.currentPage"
      :items-per-page="pageOne.itemsPerPage"
      @pagechanged="onPagechanged"
      class="col-xs-12 col-lg-8 col-sm-10"
    ></pagination>
    </div>
  </div>
</template>
<script>
  import Vue from 'vue'
  import VueLazyload from 'vue-lazyload'
  import axios from 'axios'
  import * as api from '../../store/api.js'
  import Pagination from '../../components/Pagination'
  import * as imagePath from '../../router/imagePath.js'
  export default {
    name: 'UserList',
    data() {
      return {
        lazyload: {
        error: '/images/profile_default.png',
        loading: '/images/loading.gif'
        },
        userList: [],
        pageOne: {
          currentPage: 1,
          itemsPerPage: 20
        },
        totalPages: null,

      }
    },
    created() {
      this.fetchData(1)
    },
    methods: {
      fetchData(page) {
        axios.get(api.users, {
          params: {
            page: page
          }
        })
        .then (resp => {
          console.log(resp.data)
          this.userList = resp.data.userList.data;
          this.totalPages = resp.data.userList.last_page
        })
      },
      onPagechanged(pageNum) {
        this.pageOne.currentPage = pageNum
        this.fetchData(pageNum)
      },
      avatar(fileName) {
        return imagePath.avatarImagePath + fileName
      }
    },
    components: {
      Pagination
    }
  }
</script>
<style scoped>
  .avatarimg {
    border-radius: 140px;
    height: 50px;
    width: 50px;
  }
</style>