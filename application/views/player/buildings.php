<?php view('layout/header', [ 'title' => findById($building_groups, $building_group_id)->name . ' buildings' ]) ?>

<h1><?= findById($building_groups, $building_group_id)->name ?> buildings</h1>
<p>
    <?php foreach ($building_groups as $building_group): ?>
        <a <?= $building_group->id == $building_group_id ? 'class="active"' : '' ?> href="/buildings/<?= slug($building_group->name) ?>"><?= $building_group->name ?></a> &nbsp;
    <?php endforeach ?>
</p>
<table>
    <?php foreach ($buildings as $building): ?>
        <tr>
            <td style="width: 150px;">
                <img width="150" src="/images/buildings/<?= slug(findById($building_groups, $building_group_id)->name) ?>/<?= $building->position ?>.jpg">
            </td>
            <td>
                <h3><?= $building->name ?></h3>
                <?php if ($building->income > 0): ?>
                    <p>Income: <span class="money">$ <?= number_format($building->income) ?> / s</span></p>
                <?php endif ?>
                <?php if ($building->defence > 0): ?>
                    <p>Defence: <span class="defence"><?= number_format($building->defence) ?></span></p>
                <?php endif ?>
            </td>
            <td>Price: <span class="money">$ <?= number_format($building->price) ?></span></td>
            <td><?= number_format($building->amount) ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $building->id ?>">
                    <select class="small" name="amount">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="500">500</option>
                        <?php if (Auth::player()->money >= $building->price): ?>
                            <option value="<?= floor(Auth::player()->money / $building->price) ?>" selected><?= floor(Auth::player()->money / $building->price) ?></option>
                        <?php endif ?>
                    </select>
                    <button class="inline" type="submit" name="action" value="buy">Buy</button>
                </form>
            </td>
            <td>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $building->id ?>">
                    <select class="small" name="amount">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="500">500</option>
                        <?php if ($building->amount > 0): ?>
                            <option value="<?= $building->amount ?>" selected><?= $building->amount ?></option>
                        <?php endif ?>
                    </select>
                    <button class="inline" type="submit" name="action" value="sell">Sell</button>
                </form>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php view('layout/footer') ?>
