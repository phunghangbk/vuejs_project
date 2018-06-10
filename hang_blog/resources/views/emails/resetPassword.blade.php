<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
</head>
 
<body>
  <h2>Reset Password</h2>
  <br/>
  You are receiving this email because we received a password reset request for your account.
  <br/>
  Click to the following link to reset your password
  <a href="{{url('password/reset', $token)}}">Reset Password</a>

  If you did not request a password reset, no further action is required.
</body>
</html>