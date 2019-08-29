<?php view('layout/header', [ 'title' => findById($unit_groups, $unit_group_id)->name . ' units' ]) ?>

<h1><?= findById($unit_groups, $unit_group_id)->name ?>  units</h1>
<p>
    <?php foreach ($unit_groups as $unit_group): ?>
        <a <?= $unit_group->id == $unit_group_id ? 'class="active"' : '' ?> href="/units/<?= slug($unit_group->name) ?>"><?= $unit_group->name ?></a> &nbsp;
    <?php endforeach ?>
</p>
<table>
    <?php foreach ($units as $unit): ?>
        <tr>
            <td style="width: 150px;">
                <img width="150" src="/images/units/<?= slug(findById($unit_groups, $unit_group_id)->name) ?>/<?= $unit->position ?>.jpg">
            </td>
            <td>
                <h3><?= $unit->name ?></h3>
                <p>Attack: <span class="attack"><?= number_format($unit->attack) ?></span></p>
                <p>Defence: <span class="defence"><?= number_format($unit->defence) ?></span></p>
            </td>
            <td>Price: <span class="money">$ <?= number_format($unit->price) ?></span>
            <td><?= number_format($unit->amount) ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $unit->id ?>">
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
                        <?php if (Auth::player()->money >= $unit->price): ?>
                            <option value="<?= floor(Auth::player()->money / $unit->price) ?>"><?= floor(Auth::player()->money / $unit->price) ?></option>
                        <?php endif ?>
                    </select>
                    <button class="inline" type="submit" name="action" value="buy">Buy</button>
                </form>
            </td>
            <td>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $unit->id ?>">
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
                        <?php if ($unit->amount > 0): ?>
                            <option value="<?= $unit->amount ?>"><?= $unit->amount ?></option>
                        <?php endif ?>
                    </select>
                    <button class="inline" type="submit" name="action" value="sell">Sell</button>
                </form>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php view('layout/footer') ?>
