<template>
  <div id="app">
    <div class="header">
      <nav>
        <ul class="header_right">
          <li>
            <router-link :to="{ name: 'home' }">
              <div class="button_home">
                Home
              </div>
            </router-link>
          </li>
          <li v-if="! $store.state.isLogged">
            <router-link :to="{ name: 'login' }">
              <div class="button_login">
                Login
              </div>
            </router-link>
          </li>                    
          <li v-if="! $store.state.isLogged">
            <router-link :to="{ name: 'register' }">
              <div class="button_register">
                Register
              </div>
            </router-link>
          </li>
          <li v-if="$store.state.isLogged">
            <router-link :to="{ name: 'logout' }">
              <div class="button_logout">
                Logout
              </div>
            </router-link>
          </li>
        </ul>
      </nav>
    </div>
    <div class="panel-body">
      <router-view></router-view>
    </div>
  </div>
</template>

<style scoped>
  #app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    min-height: 100%;
  }
  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    border-bottom: 2px solid #E5E5E5;
    background-color: #fff;
    font-size: 15px;
    font-size: 1.5rem;
    z-index: 50;
  }

  html, body {
    height: 100%;
    margin: 0;
    padding: 40px 0 0 0;
  }

  #footer {
    padding: 0 0 10px 0;;
    background-color: #807E7F;
    text-align: center;
    color: #fff;
  }

  #footer .inner {
    width: 80%;
    margin: 0 auto;
  }

  #app .header > a > img {
    display: block;
    margin: 10px 0 5px 150px;
    float: left;
    width: 30%;
  }

  #app .header .header_right {
    float: right;
    display: flex;
    flex-direction: row;
    margin: 5px 150px 0 5px;
    width: 30%;
    list-style: none;
  }

  #app .header .header_right > li > a > div {
    margin: 0 5px 0 0;
    padding: 10px;
    width: 100px;
    border-radius: 3px;
    cursor: pointer;
    background: #0ebf67;
    transition: all .5s ease;
    border-radius: 3px;
  }
  .button_home:hover, .button_login:hover, .button_register:hover, .button_logout:hover {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
  }
  #app .header .header_right > li > a {
    font-size: 15px;
    font-family: 'Roboto', sans-serif;
    text-transform: uppercase;
    text-align: center;
    letter-spacing: 4px;
    color: white;
    text-decoration: none;
  }
  @media only screen and (max-width: 768px) {
    #app .header .header_right {
      float: unset;
      width: 100%;
      display: flex;
      justify-content: center;
    }
    #app .header > a > img {
      display: block;
      margin: 10px 0 5px 0px;
      float: left;
      width: 100%;
    }
    #app .header .header_right > li > a > div {
      margin: 0 5px 0 0;
      max-width: 100px;
      min-width: 50px;
      height: 20px;
      padding: 10px 20px 20px 20px;
      border-radius: 3px;
      cursor: pointer;
      background: #0ebf67;
      transition: all .5s ease;
      border-radius: 3px;
    }
    #app .header .header_right > li > a {
      font-size: 50%;
    }
  }
</style>