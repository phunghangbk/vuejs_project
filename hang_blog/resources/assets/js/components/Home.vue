<template>
  <div class="container custom-container">
    <h1>Welcome to Hang Phung's website</h1>
    <div class="row justify-content-center">
      <div class="col-xs-12">
        <div class="avatarImg">
          <img :src="avatar()" width="160" height="160" class="img-fluid">
          <div>
            <span style="text-align: left; margin-top: 10px;">
              Facebook: <a href="https://facebook.com/phunghangbk">https://facebook.com/phunghangbk</a><br>
              E-mail: phunghangbk@gmail.com
            </span>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-xs-2 col-sm-2 col-lg-2">
            <i class="fas fa-briefcase fa-3x"></i>
          </div>
          <div class="col-xs-10 col-sm-10 col-lg-10 information">
            Working at Realworld company
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-xs-2 col-sm-2 col-lg-2">
            <i class="fas fa-graduation-cap fa-3x"></i>
          </div>
          <div class="col-xs-10 col-sm-10 col-lg-10 information">
            Studied at Hanoi University of Science and Technogy
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-xs-2 col-sm-2 col-lg-2">
            <i class="fas fa-home fa-3x"></i>
          </div>
          <div class="col-xs-10 col-sm-10 col-lg-10 information">
            Living in Adachiku, Tokyo, Japan
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-xs-2 col-sm-2 col-lg-2">
            <i class="fas fa-heart fa-3x"></i>
          </div>
          <div class="col-xs-10 col-sm-10 col-lg-10 information">
            Single
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import * as imagePath from '../router/imagePath.js'
  export default {
    methods: {
      avatar() {
        return imagePath.avatarImagePath + 'avatar.jpg'
      },
    }
  }
</script>
<style>
  .custom-container {
    height: 100vh;
    margin-top: 50px;
  }
  h1, div, span {
    font-family: "Libre Baskerville",Georgia,serif;
  }
  .avatarImg > img {
    margin: 20px;
    border-radius: 3px;
    width: 100px;
    height: 100px;
  }
  .avatarImg {
    text-align: left;
  }
  .information {
    text-align: left;
    margin-top: 10px;
  }
  .row {
    margin-top: 15px;
  }
</style>