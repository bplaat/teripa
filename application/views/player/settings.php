<?php view('layout/header', [ 'title' => 'Settings' ]) ?>

<form method="post" action="/change_details">
    <h1>Change details</h1>
    <div>
        <label>Avatar:</label>
        <p>Change your avatar on <a href="https://en.gravatar.com/" target="_blank">Gravatar.com</a></p>
    </div>
    <div style="display: flex; margin: 16px 0;">
        <div style="flex: 1; padding-right: 16px;">
            <label style="margin-top: 0;" for="first_name">First name:</label>
            <input style="margin-bottom: 0;" type="text" id="first_name" name="first_name" value="<?= Auth::player()->first_name ?>" autofocus required>
        </div>
        <div style="flex: 1; padding-left: 16px;">
            <label style="margin-top: 0;" for="last_name">Last name:</label>
            <input style="margin-bottom: 0;" type="text" id="last_name" name="last_name" value="<?= Auth::player()->last_name ?>" required>
        </div>
    </div>
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?= Auth::player()->username ?>" required>
    </div>
    <div>
        <label for="email">Email address:</label>
        <input type="email" id="email" name="email" value="<?= Auth::player()->email ?>" required>
    </div>
    <div>
        <button type="submit">Change details</button>
    </div>
</form>

<form method="post" action="/change_password">
    <h1>Change password</h1>
    <div>
        <label for="old_password">Old password:</label>
        <input type="password" id="old_password" name="old_password" required>
    </div>
    <div>
        <label for="new_password">New password:</label>
        <input type="password" id="new_password" name="new_password" required>
    </div>
    <div>
        <label for="confirm_new_password">Confirm new password:</label>
        <input type="password" id="confirm_new_password" name="confirm_new_password" required>
    </div>
    <div>
        <button type="submit">Change password</button>
    </div>
</form>

<h1>Sessions</h1>
<?php foreach ($sessions as $session): ?>
    <p>
        Name: <code><?= $session->session ?></code><br>
        Created on: <?= $session->created_at ?><br>
        Expires on: <?= $session->expires_at ?><br>
        <?php if ($session->session == $_COOKIE[SESSION_COOKIE_NAME]): ?>
            <b>Current session</b>
        <?php else: ?>
            <a href="/revoke_session?session=<?= $session->session ?>">Revoke session</a>
        <?php endif ?>
    </p>
<?php endforeach ?>

<?php view('layout/footer') ?>
