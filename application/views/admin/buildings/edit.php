<?php view('layout/header', [ 'title' => 'Admin' ]) ?>

<?php view('admin/navbar') ?>

<form method="post" enctype="multipart/form-data">
    <h2>Edit a building</h2>
    <div>
        <label for="image">Image:</label>
        <img src="/images/buildings/<?= slug(findById($building_groups, $building->building_group_id)->name) ?>/<?= $building->position ?>.jpg">
        <input type="file" id="image" name="image">
    </div>
    <div>
        <label for="building_group_id">Building group:</label>
        <select id="building_group_id" name="building_group_id" autofocus required>
            <?php foreach ($building_groups as $building_group): ?>
                <option value="<?= $building_group->id ?>"<?= $building_group->id == $building->building_group_id ? ' selected' : '' ?>><?= $building_group->name ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div>
        <label for="position">Position:</label>
        <input type="number" id="position" name="position" value="<?= $building->position ?>" required>
    </div>
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $building->name ?>" required>
    </div>
    <div>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?= $building->price ?>" required>
    </div>
    <div>
        <label for="income">Income:</label>
        <input type="number" id="income" name="income" value="<?= $building->income ?>" required>
    </div>
    <div>
        <label for="defence">Defence:</label>
        <input type="number" id="defence" name="defence" value="<?= $building->defence ?>" required>
    </div>
    <div>
        <button type="submit">Save</button>
    </div>
</form>

<?php view('layout/footer') ?>
