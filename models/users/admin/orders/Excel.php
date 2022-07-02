<?php
include "../../../../config/routes.php";
// Includes
include_once MODELS . "/Helpers.php";
include_once CONFIG . "/connection.php";
include_once "../Admin.php";

$orderID = isset($_GET['id']) ? $_GET['id'] : "";

if (!$orderID) {
    header("Location: index.php?page=index");
}

$isOrder = returnOrderDetailInfo($orderID);
if (!isset($isOrder) || empty($isOrder[0])) {
    header("Location: index.php?page=index");
}

list($order_lines, $u, $count) = $isOrder;
$order_lines = (array)$order_lines;
$u = (array)$u;
list($first_name, $last_name, $email_user, $id_user) = array_values($u);

$excel = "<div style='padding: 30px 50px'>";
$excel .= "<h3>Full name: ".$first_name. " " . $last_name ."</h3>";
$excel .= "<h3>Email: ".$email_user. "</h3>";
$excel .= "<h3>Order ID: ". $orderID . "</h3>";
$excel .= "<table style='width: 80%; border: 1px solid; margin: 0 auto;'>
<thead>
<tr>
    <td style='padding: 5px 8px' >Glove ID</td>
    <td style='padding: 5px 8px'>Name</td>
    <td style='padding: 5px 8px'>Category</td>
    <td style='padding: 5px 8px'>Brand</td>
    <td style='padding: 5px 8px'>Color</td>
    <td style='padding: 5px 8px'>Size</td>
    <td style='padding: 5px 8px'>Price</td>
    <td style='padding: 5px 8px'>Quantity</td>
    <td style='padding: 5px 8px'>Total</td>
</tr>
</thead>
<tbody>
";
foreach ($order_lines as $i => $ol) {
    $ol = (array)$ol;
    list($date, $name_glove, $id_glove, $name_brand, $name_cat, $price, $color_name, $img, $size, $measure, $quantity) = array_values($ol);
    $total = $quantity * $price;
    $size = $size . " " . ($measure == 'OZ' ? $measure : '');
    $excel .= "<tr>
    <td style='border: 1px solid; padding: 5px 8px'>" . $id_glove . "</td>
    <td style='border: 1px solid; padding: 5px 8px'>" . $name_glove . "</td>
    <td style='border: 1px solid; padding: 5px 8px'>" . $name_cat . "</td>
    <td style='border: 1px solid; padding: 5px 8px'>" . $name_brand . "</td>
    <td style='border: 1px solid; padding: 5px 8px'>" . $color_name . "</td>
    <td style='border: 1px solid; padding: 5px 8px'>" .  $size . "</td>
    <td style='border: 1px solid; padding: 5px 8px'>" . $price . "</td>
    <td style='border: 1px solid; padding: 5px 8px'>" . $quantity . "</td>
    <td style='border: 1px solid; padding: 5px 8px'>" . $total . "</td>
</tr>";
}
$excel .= "</tbody>
</table></div>";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=order-detail.xls");
display($excel);
