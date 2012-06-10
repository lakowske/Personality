<form method="post" action="{$scriptBasePath}/signup">
  <table class="signup">
    <tbody>
      <tr><th class="header">Sign up:</th></tr>
      <tr><td><small>Email:</small></td></tr>
      <tr><td><input class="signup_email" name="email"></td></tr>
      <tr><td><small>Username:</small></td></tr>
      <tr><td><input class="signup_input" name="username" value="{$username}"></td></tr>
      <tr><td><small>Password:</small></td></tr>
      <tr><td><input class="signup_input" type="password" name="password"></td></tr>
      <tr><td><input class="signup_input" type="submit" name="Signup" value="Sign up"></td></tr>
    </tbody>
  </table>
</form>
