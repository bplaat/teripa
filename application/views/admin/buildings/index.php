<?php view('layout/header', [ 'title' => 'Admin' ]) ?>

<?php view('admin/navbar') ?>

<h2>Buildings (<a href="/admin/buildings/create">Create</a>)</h2>
<table>
    <tr>
        <th>#</th>
        <th>Group</th>
        <th>Position</th>
        <th>Name</th>
        <th>Price</th>
        <th>Income</th>
        <th>Defence</th>
        <th></th>
    </tr>

    <?php foreach ($buildings as $building): ?>
        <tr>
            <td><?= $building->id ?></td>
            <td><?= findById($building_groups, $building->building_group_id)->name ?></td>
            <td><?= $building->position ?></td>
            <td><?= $building->name ?></td>
            <td><span class="money">$ <?= number_format($building->price) ?></span></td>
            <td><span class="money">$ <?= number_format($building->income) ?> / s</span></td>
            <td><span class="defence"><?= number_format($building->defence) ?></span></td>
            <td>
                <a href="/admin/buildings/<?= $building->id ?>/edit">Edit</a> &nbsp;
                <a href="/admin/buildings/<?= $building->id ?>/delete">Delete</a>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php view('layout/footer') ?>
