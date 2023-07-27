<?php
require_once 'backend/sdbh.php';
$dbh = new sdbh();
?>
<html>
<head>
	<link
			crossorigin='anonymous'
			href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'
			rel='stylesheet'
	>
	<link
			href='style_form.min.css'
			rel='stylesheet'
	/>
	<link
			href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css'
			rel='stylesheet'
	>
	<script
			crossorigin='anonymous'
			src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'
	></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js'></script>
	<link
			href='form.css'
			rel='stylesheet'
	/>
	<link
			href="assets/css/style.css"
			rel="stylesheet"
	/>
</head>
<body>
<div class="container">
	<div class="row row-header">
		<div class="col-12">
			<img
					src="assets/img/logo.png"
					alt="logo"
					class='logo'
			/>
			<h1>Прокат</h1>
		</div>
	</div>
	<div class='row row-body'>
		<h2>Поля, помеченные звёздочкой <strong class='red'>*</strong></h2>
	</div>
	<div class='row row-body'>
		<div class='col-3'>
			<span class='header-text'>Форма обратной связи</span>
			<i class='bi bi-activity'></i>
		</div>
		<div class='col-9'>
			<form
					id='form'
					method='POST'
			>
				<label
						class='form-label'
						for='product'
				>Выберите продукт:<strong class='red'>*</strong></label>
				<select
						class='form-select'
						id='product'
						name='product'
				>
					<!--
					a:4:{s:27:"детское кресло";i:300;s:19:"Мойка авто";i:600;s:32:"видеорегистратор";i:100;s:18:"антирадар";i:0;}
					-->
                    <?= "<option value='0'>Выберите продукт</option>" ?>

                    <?php
                    $query = "SELECT * FROM `a25_products`";
                    $res = $dbh->get_all_assoc($dbh->query_exc($query));
                    foreach ($res as $item) {
                        $query = "SELECT * FROM `a25_tarifs` WHERE `products_id` = " . $item['ID'];
                        $tarifsRes = $dbh->get_all_assoc($dbh->query_exc($query));
                        if (sizeof($tarifsRes) === 0) { ?>
                            <?= "<option value='" . $item['ID'] . "'>" . $item['NAME'] . " за " . $item['PRICE'] . "</option>" ?>
                        <?php } else {
                            foreach ($tarifsRes as $tarif) { ?>
                                <?= "<option value='" . $item['ID'] . "," . $tarif['id'] . "'>" . $item['NAME'] . " за " . $tarif['pricePerDay'] . "/день</option>" ?>
                                <?php
                            }
                        }
                    } ?>
				</select>
                <?= '0' === $_POST['product']? "<label class='red' for='product'>Обязательное поле, выберите из списка</label><br>": ""?>

				<label
						class='form-label'
						for='customRange'
				>Количество дней:<strong class='red'>*</strong></label>
				<input
						class='form-control'
						id='customRange'
						name='days'
						max='31'
						min='1'
						type='text'
						placeholder='1-31 дней'
				>
                <?= strlen($_POST['days']) === 0? "<label class='red' for='customRange'>Обязательное поле, напишите, на сколько хотите взять в прокат</label><br>": ""?>

				<label
						class='form-label'
						for='customRange'
				>Дополнительно:</label>

                <?php
                $serviceQuery = "SELECT * FROM `a25_services`";
                $serviceRes = $dbh->get_all_assoc($dbh->query_exc($serviceQuery));
                foreach ($serviceRes as $item) {
                    ?>
                    <?= "
<div class='form-check'>
					<input
							class='form-check-input'
							id='flexCheckChecked" . $item['id'] . "'
							name='service[]'
							type='checkbox'
							value='" . $item['id'] . "'
					>
					<label
							class='form-check-label'
							for='flexCheckChecked" . $item['id'] . "'
					>
						" . $item['name'] . " за " . $item['tarif'] . "/день
					</label>
				</div>" ?>
                    <?php
                } ?>
				<button
						class='btn btn-primary'
						type='submit'
				>Рассчитать
				</button>
			</form>
            <?php
            $result = null;
            if ((isset($_POST['product']) && isset($_POST['days']) && isset($_POST['service']))) {
                $result = [
                    'days' => 0,
                    'cost' => 0,
                    'services' => [

                    ]
                ];
                $productForm = explode(',', $_POST['product']);
                $product = $productForm[0];
                $tarif_id = isset($productForm[1]) ? $productForm[1] : 0;
                $daysForm = $_POST['days'];
                $serviceForm = $_POST['service'];
                $serviceQuery = "SELECT * FROM `a25_services` WHERE `id` IN (";
                $tarifRes = $dbh->get_all_assoc($dbh->query_exc($query));
                $price = $daysForm;
                $productQuery = "SELECT * FROM `a25_products` WHERE `ID` = " . $product;
                $productRes = $dbh->get_all_assoc($dbh->query_exc($productQuery));
                $product = $productRes[0];
                if ($tarif_id !== 0) {
                    $tarifQuery = "SELECT * FROM `a25_tarifs` WHERE `products_id` = " . $product['ID'] . " AND `id` = " . $tarif_id;
                    $tarifRes = $dbh->get_all_assoc($dbh->query_exc($tarifQuery));
                    $tarif = $tarifRes[0];
                    $result['services'][] = $product['NAME'] . " на ". $daysForm." дней = " . $tarif['pricePerDay'] * $daysForm;
                    $price *= $tarif['pricePerDay'];
                } else {
                    $result['services'][] = $product['NAME'] . " = " . $product['PRICE'];
                    $price = $product['PRICE'];
                }
                for ($i = 0; $i < sizeof($serviceForm); $i++) {
                    $serviceQuery .= $i + 1 === sizeof($serviceForm) ? $serviceForm[$i] . ")" : $serviceForm[$i] . ", ";
                }
                $serviceRes = $dbh->get_all_assoc($dbh->query_exc($serviceQuery));

                foreach ($serviceRes as $service) {
                    $price += $service['tarif'] * $daysForm;
                    $result['services'][] = $service['name'] . " за " . $service['tarif'] . "/день на ". $daysForm ." = " . $service['tarif'] * $daysForm;
                }
                $result['days'] = $daysForm;
                $result['cost'] = $price;

            }
            if (isset($result)) {
				foreach ($result['services'] as $item) { ?>
					<?=  "<h6>".$item."</h6><br>" ?>
				<?php }
                echo "<h3>За " . $result['days'] . " дней вы заплатите : " . $result['cost'] . "</h3>";

            }?>

		</div>
	</div>
</div>
</body>
</html>