<?php
function returnAdminTableOrderHTML($ol, $i)
{
    echo ""
?>
    <tr>
        <td class="align-middle"><?= $i + 1 ?></td>
        <td class="align-middle"><a href="index.php?page=glove&id=<?= $ol->id_glove ?>"><img class='img-fluid' src="assets/img/gloves/thumbnail/<?= $ol->img ?>" alt="<?= $ol->name_glove ?>"></a></td>
        <td class="align-middle"><a class="text-secondary" href="index.php?page=glove&id=<?= $ol->id_glove ?>"><?= $ol->name_glove ?></a></td>
        <td class="align-middle"><?= $ol->name_cat ?></td>
        <td class="align-middle"><?= $ol->name_brand ?></td>
        <td class="align-middle">
            <div class="d-flex align-items-center justify-content-center">
                <span class="palette border align-bottom" style="background-color:<?= $ol->color_name ?>!important;"></span>
                <span class="capitalize ms-2 lead align-bottom"><?= $ol->color_name ?></span>
            </div>
        </td>
        <td class="align-middle"><?= $ol->size ?> <?= $ol->measure == "OZ" ? $ol->measure : "" ?></td>
        <td class="align-middle">$<?= $ol->price ?></td>
        <td class="align-middle"><?= $ol->quantity ?></td>
        <td class="align-middle">$<?= (+$ol->quantity) * (+$ol->price) ?></td>
    </tr>
<?php } ?>