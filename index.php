<?php

function str2color($str) {
    $code = dechex(crc32($str));
    $code = substr($code, 0, 6);
    return '#'.$code;
}


$t_disabled = ['gwen001/github-search','gwen001/s3-buckets-finder','gwen001/pentest-tools','gwen001/github-subdomains'];


$t_data = json_decode( file_get_contents('repositories.json'), true );
ksort($t_data);
// var_dump($t_data);

$start_date_ts = null;
$end_date_ts = time();
foreach( $t_data as $repo=>$t_repo ) {
    foreach( $t_repo as $n_page=>$t_stars ) {
        foreach( $t_stars as $star ) {
            $ts = strtotime($star);
            if( is_null($start_date_ts) || $ts<$start_date_ts ) {
                $start_date_ts = $ts;
            }
        }
    }
}
$start_date_ts = 1483228800; // 2017-01-01
$start_date_ts = 1491004800; // 2017-04-01

$t_dates = [];
$start_date_m = date('m',$start_date_ts);
$start_date_y = date('Y',$start_date_ts);
$start_date = date('Ym',$start_date_ts);
$end_date = date('Ym',$end_date_ts);
// var_dump($start_date);
// var_dump($end_date);

for( $m=0,$d=(int)$start_date ; $d<(int)$end_date ; $m++ ) {
    $d = (int)date( 'Ym', mktime(0,0,0,$start_date_m+$m,1,$start_date_y) );
    $dd = date( 'Y-m', mktime(0,0,0,$start_date_m+$m,1,$start_date_y) );
    $t_dates[] = $dd;
}
// var_dump($t_dates);

$t_repo_stats = [];

foreach( $t_data as $repo=>$t_repo ) {
    if( !isset($t_repo_stats[$repo]) ) {
        $t_repo_stats[$repo] = [];
    }
    foreach( $t_repo as $n_page=>$t_stars ) {
        foreach( $t_stars as $star ) {
            $ts = strtotime($star);
            $d = date('Y-m',$ts);
            if( !isset($t_repo_stats[$repo][$d]) ) {
                $t_repo_stats[$repo][$d] = 0;
            }
            $t_repo_stats[$repo][$d]++;
        }
    }
}
// var_dump($t_repo_stats);

$t_series = [];

foreach( $t_data as $repo=>$t_repo ) {
    $serie = [];
    $serie['data'] = [];
    $serie['name'] = str_replace('gwen001/','',$repo);
    $serie['color'] = str2color($repo);
    if( in_array($repo,$t_disabled) ) {
        $serie['visible'] = false;
    }
    $n = 0;
    foreach( $t_dates as $d ) {
        if( isset($t_repo_stats[$repo][$d]) ) {
            $n += $t_repo_stats[$repo][$d];
        }
        $serie['data'][] = $n;
    }
    $t_series[] = $serie;
}
// var_dump($t_series);





$start_date_ts = mktime(0,0,0,date('m'),date('d')-30,date('Y'));

$t_dates30 = [];
$start_date_d = date('d',$start_date_ts);
$start_date_m = date('m',$start_date_ts);
$start_date_y = date('Y',$start_date_ts);
$start_date = date('Ymd',$start_date_ts);
$end_date = date('Ymd',$end_date_ts);
// var_dump($start_date);
// var_dump($end_date);

for( $m=0,$d=(int)$start_date ; $d<(int)$end_date ; $m++ ) {
    $d = (int)date( 'Ymd', mktime(0,0,0,$start_date_m,$start_date_d+$m,$start_date_y) );
    $dd = date( 'Y-m-d', mktime(0,0,0,$start_date_m,$start_date_d+$m,$start_date_y) );
    $t_dates30[] = $dd;
}
// var_dump($t_dates30);

$t_repo_stats30 = [];

foreach( $t_data as $repo=>$t_repo ) {
    if( !isset($t_repo_stats30[$repo]) ) {
        $t_repo_stats30[$repo] = [];
    }
    foreach( $t_repo as $n_page=>$t_stars ) {
        foreach( $t_stars as $star ) {
            $ts = strtotime($star);
            $d = date('Y-m-d',$ts);
            if( !isset($t_repo_stats30[$repo][$d]) ) {
                $t_repo_stats30[$repo][$d] = 0;
            }
            $t_repo_stats30[$repo][$d]++;
        }
    }
}
// var_dump($t_repo_stats30);

$t_series30 = [];

foreach( $t_data as $repo=>$t_repo ) {
    $serie = [];
    $serie['data'] = [];
    $serie['name'] = str_replace('gwen001/','',$repo);
    $serie['color'] = str2color($repo);
    // if( in_array($repo,$t_disabled) ) {
    //     $serie['visible'] = false;
    // }
    $n = 0;
    foreach( $t_dates30 as $d ) {
        if( isset($t_repo_stats30[$repo][$d]) ) {
            $n = $t_repo_stats30[$repo][$d];
        } else {
            $n = 0;
        }
        $serie['data'][] = $n;
    }
    $t_series30[] = $serie;
}
// var_dump($t_series30);



?>


<html>

<head>
    <script src="highcharts.js"></script>
</head>

<body>
    <h1>Repositories stars</h1>

    <div id="overall_progress" style="width:1500px;height:700px;"></div>

    <script type="text/javascript">
    Highcharts.chart('overall_progress', {
        chart: {
            type: 'spline'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'GitHub stargazer'
        },
        subtitle: {
            text: 'Overall progress'
        },
        xAxis: {
            categories: <?php echo json_encode($t_dates); ?>,
            crosshair: true,
            // title: {
            //     enabled: true,
            //     text: 'n stars'
            // }
        },
        yAxis: {
            min: 0,
            title: {
                enabled: true,
                text: 'n stars'
            }
        },
        legend: {
            enabled: true
        },
        plotOptions: {
            series: {
                marker: {
                    enabled: false,
                }
            }
        },
        tooltip: {
            enabled: true,
            // shared: true
        },
        series: <?php echo json_encode($t_series); ?>
    });
    </script>

    <div id="daily_progress" style="width:1500px;height:700px;"></div>
    <script type="text/javascript">
    Highcharts.chart('daily_progress', {
        chart: {
            type: 'column'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Repositories stars'
        },
        subtitle: {
            text: 'Daily progress'
        },
        xAxis: {
            categories: <?php echo json_encode($t_dates30); ?>,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                enabled: true,
                text: 'n stars'
            }
        },
        legend: {
            enabled: true
        },
        tooltip: {
            enabled: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: <?php echo json_encode($t_series30); ?>
    });

    </script>

</body>

</html>
