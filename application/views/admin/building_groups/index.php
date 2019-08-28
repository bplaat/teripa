<?php view('layout/header', [ 'title' => 'Admin' ]) ?>

<?php view('admin/navbar') ?>

<h2>Building groups (<a href="/admin/building_groups/create">Create</a>)</h2>
<table>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th></th>
    </tr>

    <?php foreach ($building_groups as $building_group): ?>
        <tr>
            <td><?= $building_group->id ?></td>
            <td style="width: 800px;"><a href="/buildings/<?= slug($building_group->name) ?>"><?= $building_group->name ?></a></td>
            <td>
                <a href="/admin/building_groups/<?= $building_group->id ?>/edit">Edit</a> &nbsp;
                <a href="/admin/building_groups/<?= $building_group->id ?>/delete">Delete</a>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php view('layout/footer') ?>
