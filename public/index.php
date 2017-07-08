<?php
function dd(...$data)
{
    foreach ($data as $d) {
        die(dump($d));
    }
}

// Hijri conversion function
// COPYRIGHT 2002 BY KHALED MAMDOUH www.vbzoom.com
// Updated, and added Islamic names of months by Samir Greadly
// xushi at xushi dot homelinux dot org

function Hijri($GetDate)
{
//    dump($GetDate);
    
    $TDays = round(strtotime($GetDate) / (60 * 60 * 24));
    $HYear = round($TDays / 354.37419);
    $Remain = $TDays - ($HYear * 354.37419);
    $HMonths = round($Remain / 29.531182);
    $HDays = $Remain - ($HMonths * 29.531182);
    $HYear = $HYear + 1389;
    $HMonths = $HMonths + 10;
    $HDays = $HDays + 23;
    
    // If the days is over 29, then update month and reset days
    if ($HDays > 29.531188 and round($HDays) != 30) {
        $HMonths = $HMonths + 1;
        $HDays = Round($HDays - 29.531182);
    } else {
        $HDays = Round($HDays);
    }
    
    // If months is over 12, then add a year, and reset months
    if ($HMonths > 12) {
        $HMonths = $HMonths - 12;
        $HYear = $HYear + 1;
    }
    
    return [$HDays, $HMonths, $HYear];
}

// end of Hijri Conversion function
?>
<?php
/* Hijri-Gregorian Calendar v.1.0. by Tayeb Habib
http://redacacia.wordpress.com tayeb.habib@gmail.com
a special thanks to KHALED MAMDOUH www.vbzoom.com
for Hijri Conversion function
*/

//set here font, background etc for the calendar
$fontfamily = isset($fontfamily) ? $fontfamily : "Verdana";
$defaultfontcolor = isset($defaultfontcolor) ? $defaultfontcolor : "#000000";
$defaultbgcolor = isset($defaultbgcolor) ? $defaultbgcolor : "#E0E0E0";
$defaultwbgcolor = isset($defaultwbgcolor) ? $defaultwbgcolor : "#F5F4D3";
$todayfontcolor = isset($todayfontcolor) ? $todayfontcolor : "#000000";
$todaybgcolor = isset($todaybgcolor) ? $todaybgcolor : "#F2BFBF";
$monthcolor = isset($monthcolor) ? $monthcolor : "#000000";
$relfontsize = isset($relfontsize) ? $relfontsize : "1";
$cssfontsize = isset($cssfontsize) ? $cssfontsize : "7pt";

// obtain month, today date etc
$month = (isset($month)) ? $month : date("n", time());
$monthnames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
];
$textmonth = $monthnames[$month - 1];
$year = (isset($year)) ? $year : date("Y", time());
$today = (isset($today)) ? $today : date("j", time());
$today = ($month == date("n", time())) ? $today : 32;

// The Names of Hijri months
$mname = [
    "Muharram",
    "Safar",
    "Rabi'ul Awal",
    "Rabi'ul Akhir",
    "Jamadil Awal",
    "Jamadil Akhir",
    "Rajab",
    "Sha'ban",
    "Ramadhan",
    "Shawwal",
    "Zul Qida",
    "Zul Hijja",
];
// End of the names of Hijri months

// Setting how many days each month has
if ((($month < 8) && ($month % 2 == 1)) || (($month > 7) && ($month % 2 ==
            0))
) {
    $days = 31;
}
if ((($month < 8) && ($month % 2 == 0)) || (($month > 7) && ($month % 2 ==
            1))
) {
    $days = 30;
}

//checking leap year to adjust february days
if ($month == 2) {
    $days = (date("L", time())) ? 29 : 28;
}

$dayone = date("w", mktime(1, 1, 1, $month, 1, $year));
$daylast = date("w", mktime(1, 1, 1, $month, $days, $year));
$middleday = intval(($days - 1) / 2);

//checking the hijri month on beginning of gregorian calendar
$date_hijri = date("$year-$month-1");

list ($HDays, $HMonths, $HYear) = Hijri($date_hijri);

$smon_hijridone = $mname[$HMonths - 1];
$syear_hijridone = $HYear;

//checking the hijri month on end of gregorian calendar
$date_hijri = date("$year-$month-$days");
list ($HDays, $HMonths, $HYear) = Hijri($date_hijri);
$smon_hijridlast = $mname[$HMonths - 1];
$syear_hijridlast = $HYear;


//checking the hijri month on middle of gregorian calendar
$date_hijri = date("$year-$month-$middleday");
list ($HDays, $HMonths, $HYear) = Hijri($date_hijri);
$smon_hijridmiddle = $mname[$HMonths - 1];
$syear_hijridmiddle = $HYear;


// checking if there's a span of a year
if ($syear_hijridone == $syear_hijridlast) {
    $syear_hijridone = "";
}
//checking if span of month is only one or two or three hijri months

if (($smon_hijridone == $smon_hijridmiddle) AND ($smon_hijridmiddle == $smon_hijridlast)) {
    $smon_hijri = "<div style=\"color:red\">" . $smon_hijridone . "&nbsp;" . $syear_hijridlast . "</div>";
}

if (($smon_hijridone == $smon_hijridmiddle) AND ($smon_hijridmiddle != $smon_hijridlast)) {
    $smon_hijri = "<div style=\"color:red\">" . $smon_hijridone . "&nbsp;" . $syear_hijridone . "-" . $smon_hijridlast . "&nbsp;" . $syear_hijridlast . "</div>";
}


if (($smon_hijridone != $smon_hijridmiddle) AND ($smon_hijridmiddle == $smon_hijridlast)) {
    $smon_hijri = "<div style=\"color:red\">" . $smon_hijridone . "&nbsp;" . $syear_hijridone . "-" . $smon_hijridlast . "&nbsp;" . $syear_hijridlast . "</div>";
}

if (($smon_hijridone != $smon_hijridmiddle) AND ($smon_hijridmiddle != $smon_hijridlast)) {
    $smon_hijri = "<div style=\"color:red\">" . $smon_hijridone . "&nbsp;" . $syear_hijridone . "-" . "-" . $smon_hijridmiddle . "-" . $smon_hijridlast . "&nbsp;" . $syear_hijridlast . "</div>";
}

// next part of code generates calendar
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Bootstrap 101 Template</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
              integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
              crossorigin="anonymous">
        <link rel="stylesheet" href="/css/style.css">


        <style>
            .days-name {
                background-color: <?= $defaultwbgcolor ?>;
                font-family: <?= $fontfamily ?>"
                text-align: center;
                width: 10%;
                font-weight: bold;
                text-align: center;
            }

        </style>
    </head>

    <body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table border="1" cellpadding="0" cellspacing="0" width="100%"
                       bgcolor='white'
                       valign='top'>
                    <tr>
                        <td bgcolor="#C6D4E5" colspan="7" align="center">
                            <div style="color:<?= $monthcolor ?>">
                                <b><?=
                                    $textmonth . "&nbsp;" . $year . "<br />" . $smon_hijri
                                    ?></b>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="days-name">
                            <div>
                                <b> S </b>
                            </div>
                        </td>
                        <td class="days-name">
                            <div>
                                <b> M </b>
                            </div>
                        </td>
                        <td class="days-name">
                            <div>
                                <b> T </b>
                            </div>
                        </td>
                        <td class="days-name">
                            <div>
                                <b> W </b>
                            </div>
                        </td>
                        <td class="days-name">
                            <div>
                                <b> T </b>
                            </div>
                        </td>
                        <td class="days-name">
                            <div>
                                <b> F </b>
                            </div>
                        </td>
                        <td class="days-name">
                            <div>
                                <b> S </b>
                            </div>
                        </td>
                    </tr>
                    
                    <?php
                    if ($dayone != 0) {
                        $span1 = $dayone;
                    }
                    if (6 - $daylast != 0) {
                        $span2 = 6 - $daylast;
                    }
                    ?>
                    
                    <?php for ($i = 1; $i <= $days; $i++) : ?>
                        <?php
                        $dayofweek = date("w", mktime(1, 1, 1, $month, $i, $year));
                        
                        $width = "14%";
                        
                        if ($dayofweek == 0 || $dayofweek == 6) {
                            $width = "15%";
                        }
                        if ($i == $today) {
                            $fontcolor = $todayfontcolor;
                            $bgcellcolor = $todaybgcolor;
                        }
                        if ($i != $today) { // Don't need this
                            $fontcolor = $defaultfontcolor;
                            $bgcellcolor = $defaultbgcolor;
                        }
                        $x = strlen($i);
                        if ($x == 1) {
                            $b = "0" . $i;
                        }
                        if ($x == 2) {
                            $b = $i;
                        }
                        $x = strlen($month);
                        if ($x == 1) {
                            $c = "0" . $month;
                        }
                        if ($x == 2) {
                            $c = $month;
                        }
                        $data = $year . "-" . $c . "-" . $b;
                        
                        if ($i == 1 || $dayofweek == 0) : $condition = '$i = 1' ?>
                            <tr bgcolor="<?= $defaultbgcolor ?>">
                        <?php endif; ?>
                        <?php if ($span1 > 0 && $i == 1) : ?>
                            <td align='left' bgcolor='#999999' colspan="<?= $span1 ?>">
                                &nbsp;
                            </td>
                        <?php endif; ?>

                        <td bgcolor="<?= $bgcellcolor ?>" valign="middle" align="center"
                            width="<?= $width ?>">
                            <div style="color:<?= $fontcolor ?>; font-family: <?= $fontfamily ?>">
                                <?php
                                $date_hijri = date("$year-$month-$i");
                                list ($HDays, $HMonths, $HYear) = Hijri($date_hijri);
                                
                                // For last day of hijri month
                                if ($HDays == 30) {
                                    
                                    $i = $i + 1;
                                    $date_hijri = date("$year-$month-$i");
                                    list ($HDays, $HMonths, $HYear) = Hijri($date_hijri);
                                    if ($HDays == 2) {
                                        $HDays = 1;
                                    } else {
                                        $HDays = 30;
                                    }
                                    $i = $i - 1;
                                } ?>

                                <span><?= $i ?></span><br><span style="color: red"><?= $HDays ?></span>
                        </td>
                        <?php if ($i == $days): ?>
                            <?php if ($span2 > 0) ?>
                                <td align="left" bgcolor="#999999" colspan="<?= $span2 ?>">
                                &nbsp;
                            </td>
                        <?php endif; ?>
                        
                        <?php if ($dayofweek == 6 || $i == $days): ?>
                            </tr>
                        <?php endif; ?>
                    <?php endfor; // Hamoud End for loop?>

                </table>
            </div> <!-- .col-md-12 -->
        </div> <!-- .row -->
    </div> <!-- .container -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <script src="/js/script.js"></script>
    </body>
    </html>


<?php
$ano = str_replace("20", "", $year);

$x = strlen($today);
if ($x == 1) {
    $b = "0" . $today;
}
if ($x == 2) {
    $b = $today;
}
//echo $b;
$x = strlen($month);
if ($x == 1) {
    $c = "0" . $month;
}
if ($x == 2) {
    $c = $month;
}
//echo $c;

$data = $year . $c . $b;
?>