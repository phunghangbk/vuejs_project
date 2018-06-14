<template>
  <div class="container-fluid custom-container">
    <link href="/css/editor.css" type="text/css" rel="stylesheet"/>
    <div class="row justify-content-center">
      <div class="container">
          <div class="row">
            <div class="col-lg-12 nopadding">
              <textarea id="txtEditor"></textarea> 
            </div>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
  var editor = require("../../custom_js/editor.js");
  export default {
    data() {
      return {

      }
    },
    mounted() {
      this.editor();
    },
    methods: {
      editor() {
        console.log($("#txtEditor"));
        $("#txtEditor").Editor();
      }
    }
  }
</script>

<style scoped>

  .custom-container {
    margin-top: 70px;
  }
</style>