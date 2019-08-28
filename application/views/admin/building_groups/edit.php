<?php view('layout/header', [ 'title' => 'Admin' ]) ?>

<?php view('admin/navbar') ?>

<form method="post">
    <h2>Edit a building group</h2>
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $building_group->name ?>" autofocus required>
    </div>
    <div>
        <button type="submit">Save</button>
    </div>
</form>

<?php view('layout/footer') ?>
