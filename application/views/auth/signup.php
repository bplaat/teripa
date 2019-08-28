<?php view('layout/header', [ 'title' => 'Sign up' ]) ?>

<form method="post">
    <h1>Sign up</h1>
    <div style="display: flex; margin: 16px 0;">
        <div style="flex: 1; padding-right: 16px;">
            <label style="margin-top: 0;"for="first_name">First name:</label>
            <input style="margin-bottom: 0;"type="text" id="first_name" name="first_name" autofocus required>
        </div>
        <div style="flex: 1; padding-left: 16px;">
            <label style="margin-top: 0;"for="last_name">Last name:</label>
            <input style="margin-bottom: 0;" type="text" id="last_name" name="last_name" required>
        </div>
    </div>
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div>
        <label for="email">Email address:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="confirm_password">Confirm password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <div>
        <button type="submit">Sign up</button>
    </div>
</form>

<?php view('layout/footer') ?>
