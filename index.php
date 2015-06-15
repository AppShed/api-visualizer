<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Appshed visualizer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container theme-showcase">
        <div class="page-header">
            <h1>Visualizer</h1>
        </div>
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Put API URL and set update interval</h3>
                </div>
                <div class="panel-body">

                    <div class="col-sm-8">
                        <input type="text" class="form-control api-url-input" placeholder="API URL" value="http://appshed-ext-storage.igor.ekreative.com/app_dev.php/api/04e9b308-0dd9-11e5-8b53-5254005af43f" />
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="text" class="form-control interval-input" placeholder="Interval" title="Run interval" value="30" />

                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default start-btn">Start</button>
                                <button type="button" class="btn btn-default run-btn">Run!</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row table-wrapper"></div>
    </div>

    <script>

        var execution = false;
        var timerId = 0;

        function run() {
            var apiUrlInput = jQuery('.api-url-input');
            var url = apiUrlInput.val();
            if (url) {
                jQuery.post(url, {}, function(data) {
                    if (data.length) {
                        var thead = Object.keys(data[0]);
                        var result = '<table class="table table-striped"><thead><tr>';
                        for (key in thead) {
                            result = result + '<th>' + thead[key] + '</th>';
                        }
                        result = result + '</tr></thead><tbody>';
                        for (i in data) {
                            result = result + '<tr>';
                            for (key in thead) {
                                result = result + '<td '+ ((data[i][thead[key]] == null) ? ('class="text-muted"') : ('')) +'>' + data[i][thead[key]] + '</td>';
                            }
                            result = result + '</tr>';
                        }
                        result = result + '</tbody></table>';
                    } else {
                        //empty result
                        result = '<h3>Empty result</h3>';
                    }
                    jQuery('.table-wrapper').html(result);

                }, 'json');
            } else {
                apiUrlInput.focus();
            }
        }

        function toggleRun(btn) {
            if (execution) {
                jQuery(btn).html('Start!');
                clearInterval(timerId);
                execution = false;
            } else {
                jQuery(btn).html('Stop!');
                run();
                var interval = parseInt(jQuery('.interval-input').val());
                timerId = setInterval(function() {
                    run();
                }, interval * 1000);
                execution = true;
            }
        }

        jQuery(document).ready(function() {
            jQuery('.run-btn').unbind('click').click(function() {
                run();
            });

            jQuery('.start-btn').unbind('click').click(function() {
                toggleRun(this);
            });
        });
    </script>
</body>
</html>