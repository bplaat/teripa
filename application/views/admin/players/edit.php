<?php view('layout/header', [ 'title' => 'Admin' ]) ?>

<?php view('admin/navbar') ?>

<form method="post">
    <h2>Edit a player</h2>
    <div style="display: flex; margin: 16px 0;">
        <div style="flex: 1; padding-right: 16px;">
            <label style="margin-top: 0;" for="first_name">First name:</label>
            <input style="margin-bottom: 0;" type="text" id="first_name" name="first_name" value="<?= $player->first_name ?>" autofocus required>
        </div>
        <div style="flex: 1; padding-left: 16px;">
            <label style="margin-top: 0;" for="last_name">Last name:</label>
            <input style="margin-bottom: 0;" type="text" id="last_name" name="last_name" value="<?= $player->last_name ?>" required>
        </div>
    </div>
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?= $player->username ?>" required>
    </div>
    <div>
        <label for="email">Email address:</label>
        <input type="email" id="email" name="email" value="<?= $player->email ?>" required>
    </div>
    <div>
        <label for="money">Money:</label>
        <input type="number" id="money" name="money" value="<?= $player->money ?>" required>
    </div>
    <div>
        <button type="submit">Save</button>
    </div>
</form>

<?php view('layout/footer') ?>
