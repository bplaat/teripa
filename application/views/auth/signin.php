<?php view('layout/header', [ 'title' => 'Sign in' ]) ?>

<form method="post">
    <h1>Sign in</h1>
    <div>
        <label for="login">Username or email address:</label>
        <input type="text" id="login" name="login" autofocus required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <button type="submit">Sign in</button>
    </div>
</form>

<?php view('layout/footer') ?>
