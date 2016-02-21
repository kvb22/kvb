<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<table>
    <tr>
        <th>Пол</th><th>Количество</th>
    </tr>
    <? foreach( $arResult['persons'] as $k=>$v): ?>
        <tr>
            <td><?=($v['gender'] == 'f' ? 'Ж':'М')?></td><td><?=$v['cnt']?></td>
        </tr>
    <?endforeach;?>
</table>

