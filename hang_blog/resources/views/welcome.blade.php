<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Laravel</title>
</head>
<body>
  <div class="container">
    <div id="app"></div>
  </div>
  <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>