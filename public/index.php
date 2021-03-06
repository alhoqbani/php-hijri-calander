<?php
/* Hijri-Gregorian Calendar v.1.0. by Tayeb Habib
http://redacacia.wordpress.com tayeb.habib@gmail.com
a special thanks to KHALED MAMDOUH www.vbzoom.com
for Hijri Conversion function
Updated by Hamoud Alhoqbani, h.alhoqbani@gmail.com 2017
*/

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

// For debugging
function dd(...$data)
{
    foreach ($data as $d) {
        die(var_dump($d));
    }
}

// The Names of Gregorian months
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

$arabicDays = [
    'الأحد',
    'الإثنين',
    'الثلاثاء',
    'الأربعاء',
    'الخميس',
    'الجمعة',
    'السبت',
];

$year = isset($_GET['year']) ? $_GET['year'] : null;
$monthName = isset($_GET['month']) ? $_GET['month'] : null;

if ($key = array_search($monthName, $monthnames)) {
    $month = ++$key;
}

// obtain month, today date etc
$currentMonth = date("n", time());
$month = (isset($month)) ? $month : $currentMonth;
$textmonth = $monthnames[$month - 1];

$currentYear = date("Y", time());
$year = (isset($year)) ? $year : $currentYear;

$today = (isset($today)) ? $today : date("j", time());
$today = ($month == $currentMonth && $year == $currentYear) ? $today : 32;

// Get current Date String
$date_hijri = date("$currentYear-$currentMonth-$today");
list ($HDays, $HMonths, $HYear) = Hijri($date_hijri);

$currentDayIndex = date('w', time());
$DateStringHijri = $arabicDays[$currentDayIndex] . " $HDays-$HMonths-$HYear هـ ";
$DateStringGregorian = date('l F j, Y', time());

// Setting how many days each month has
if ((($month < 8) && ($month % 2 == 1)) || (($month > 7) && ($month % 2 == 0))) {
    $days = 31;
}
if ((($month < 8) && ($month % 2 == 0)) || (($month > 7) && ($month % 2 == 1))) {
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
    $smon_hijri = "{$smon_hijridone} {$syear_hijridlast}";
}

if (($smon_hijridone == $smon_hijridmiddle) AND ($smon_hijridmiddle != $smon_hijridlast)) {
    $smon_hijri = "{$smon_hijridone} {$syear_hijridone} - {$smon_hijridlast} {$syear_hijridlast}";
}

if (($smon_hijridone != $smon_hijridmiddle) AND ($smon_hijridmiddle == $smon_hijridlast)) {
    $smon_hijri = "{$smon_hijridone} {$syear_hijridone} - {$smon_hijridlast} {$syear_hijridlast}";
}

if (($smon_hijridone != $smon_hijridmiddle) AND ($smon_hijridmiddle != $smon_hijridlast)) {
    $smon_hijri = "{$smon_hijridone} {$syear_hijridone} - {$smon_hijridmiddle} - {$smon_hijridlast} {$syear_hijridlast}";
}

// next part of code generates calendar
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hijri Calnder for <?= $textmonth . "&nbsp;" . $year ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <p>Change the month:</p>
            <form class="form-inline" action="<?= $_SERVER['PHP_SELF'] ?>" method="get" role="form">
                <div class="form-group form-group-sm">
                    <label for="year" class="col-sm-1 control-label input-sm">Year</label>
                    <div class="col-sm-2">
                        <select class="form-control input-sm" id="year" name="year">
                            <?php foreach (range($currentYear - 5, $currentYear + 5) as $yearOption) : ?>
                                <option value="<?= $yearOption ?>" <?= $yearOption == $year ? 'selected' : '' ?>><?= $yearOption ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <label for="month" class="col-sm-1 control-label input-sm">Month</label>
                    <div class="col-sm-3">
                        <select class="form-control input-sm" id="month" name="month">
                            <?php foreach ($monthnames as $monthOption) : ?>
                                <option value="<?= $monthOption ?>" <?= $textmonth == $monthOption ? 'selected' : '' ?>><?= $monthOption ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary btn-xs">Select</button>
                    </div>
                    <div class="col-sm-2">
                        <?php if ($month != $currentMonth || $year != $currentYear) { ?>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-success btn-xs pull-left">current
                                month</a>
                        <?php } ?>
                    </div>

                </div> <!-- .form-group -->

            </form>
        </div> <!-- .col-md-4 -->
    </div> <!-- .row -->

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-condensed">
                <caption class="caption">PHP Dynamic Hijri Calandar</caption>
                <thead>
                <tr class="header-row warning">
                    <th class="header-cell date-string-gregorian" colspan="2">
                        <span>Today is <?= $DateStringGregorian ?></span>
                    </th>
                    <th class="header-cell" colspan="3">
                        <span class="month-name-gregorian"><?= $textmonth . "&nbsp;" . $year ?></span>
                        <br>
                        <span class="month-name-hijri"><?= $smon_hijri ?></span>
                    </th>
                    <th class="header-cell date-string-hijri" colspan="2">
                        <span>اليوم هو <?= $DateStringHijri ?></span>
                    </th>
                </tr>
                <tr class="week-days-row warning">
                    <th class="week-days-cell">
                        <div>
                            S
                        </div>
                    </th>
                    <th class="week-days-cell">
                        <div>
                            M
                        </div>
                    </th>
                    <th class="week-days-cell">
                        <div>
                            T
                        </div>
                    </th>
                    <th class="week-days-cell">
                        <div>
                            W
                        </div>
                    </th>
                    <th class="week-days-cell">
                        <div>
                            T
                        </div>
                    </th>
                    <th class="week-days-cell">
                        <div>
                            F
                        </div>
                    </th>
                    <th class="week-days-cell">
                        <div>
                            S
                        </div>
                    </th>
                </tr>
                </thead>

                <tbody>
                <?php // Span
                if ($dayone != 0) {
                    $span1 = $dayone;
                }
                $span2 = (6 - $daylast != 0) ? 6 - $daylast : 0;
                ?>
                
                <?php for ($i = 1; $i <= $days; $i++) : ?>
                    <?php
                    $dayofweek = date("w", mktime(1, 1, 1, $month, $i, $year));
                    
                    if ($i == 1 || $dayofweek == 0) : ?>
                        <tr class="date-row">
                    <?php endif; ?>
                    <?php if ($dayone && $i == 1) : ?>
                        <td class="empty-cell" colspan="<?= $dayone ?>">
                            &nbsp;
                        </td>
                    <?php endif; ?>

                    <td class=" date-cell <?= $today == $i ? 'danger today' : 'active' ?>">
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
                        <span class="date-gregorian"><?= $i ?></span>
                        <br>
                        <span class="date-hijri text-danger"><?= $HDays ?></span>
                    </td>
                    <?php if ($i == $days) { ?>
                        <?php if ($span2 > 0) { ?>
                            <td class="empty-cell" colspan="<?= $span2 ?>">
                                &nbsp;
                            </td>
                        <?php } ?>
                    <?php } ?>
                    
                    <?php if ($dayofweek == 6 || $i == $days): ?>
                        </tr>
                    <?php endif; ?>
                <?php endfor; ?>
                </tbody>
            </table>
        </div> <!-- .col-md-12 -->
    </div> <!-- .row -->
</div> <!-- .container -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script src="js/script.js"></script>

</body>
</html>