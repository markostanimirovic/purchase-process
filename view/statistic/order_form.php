<?php
$title = 'Statistika: Narudžbenica';

ob_start();
?>
    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4">Statistika: Narudžbenica</h1>
        </div>
    </div>

<?php
$header = ob_get_clean();
ob_flush();
ob_start();
?>
    <div class="container col-md-7">
        <div id="container">
        </div>
    </div>

<?php
$content = ob_get_clean();
ob_flush();
ob_start();
?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js">
    </script>
    <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart']});
    </script>
    <script language="JavaScript">
        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Stanje');
            data.addColumn('number', 'Percentage');
            data.addRows([
                ['U pripremi', <?= $saved; ?>],
                ['Poslate', <?= $sent; ?>],
                ['Stornirane', <?= $reversed; ?>],
                ['Odobrene', <?= $approved; ?>],
                ['Odbijene', <?= $canceled; ?>]
            ]);
            var options = {
                'width': 1100,
                'height': 400,
                colors: ['#00366E', '#00448B', '#0059B6', '#007DFF', '#1085FF'],
                chartArea: {left: '20%', top: 0, width: "50%", height: "100%"}
            };
            var chart = new google.visualization.PieChart(document.getElementById('container'));
            chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(drawChart);
    </script>
<?php
$javascript = ob_get_clean();
ob_flush();
ob_start();
?>

<?php
$css = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params,
    array('title' => $title, 'header' => $header, 'content' => $content, 'javascript' => $javascript, 'css' => $css)));