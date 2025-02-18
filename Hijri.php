<?php
////////
class Hijri {
    private $gregorianDate;
    private $hijriDate;

    public function __construct($gregorianDate) {
        $this->gregorianDate = $gregorianDate;
        $this->convertToHijri();
    }

    private function convertToHijri() {
        $gregorianDateArray = explode(' ', $this->gregorianDate);
        $dateArray = explode('-', $gregorianDateArray[0]);
        $year = $dateArray[0];
        $month = $dateArray[1];
        $day = $dateArray[2];

        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        $jd = $this->gregorianToJD($year, $month, $day);
        $hijriDate = $this->jdToHijri($jd);

        $this->hijriDate = array(
            'year' => $hijriDate[0],
            'month' => $hijriDate[1],
            'day' => $hijriDate[2]
        );
    }

    private function gregorianToJD($year, $month, $day) {
        $a = floor((14 - $month) / 12);
        $y = $year + 4800 - $a;
        $m = $month + 12 * $a - 3;
        $jd = $day + floor((153 * ($m + 1)) / 5) + 365 * $y + floor($y / 4) - floor($y / 100) + floor($y / 400) - 32045;
        return $jd;
    }

    private function jdToHijri($jd) {
        $jd = $jd - 2451626;
        $y = floor($jd / 365.24219);
        $jd = $jd - floor($y * 365.24219);
        $m = floor(($jd + 0.5) / 30.61);
        $daying = floor($jd + 0.5) - floor($m * 30.61);
        //for aban
        if($m == 9 && $daying <= 9){
        $d = floor($jd + 0.5) - floor($m * 30.61) + 1;
        }else{
        $d = floor($jd + 0.5) - floor($m * 30.61);
        }
        return array($y + 1379, $m, $d);
    }

    private function convertToShamsi() {
        $gregorianDateArray = explode(' ', $this->gregorianDate);
        $dateArray = explode('-', $gregorianDateArray[0]);
        $year = $dateArray[0];
        $month = $dateArray[1];
        $day = $dateArray[2];

        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        $jd = jdfromdate($year, $month, $day);
        $shamsiDate = $this->jdToShamsi($jd);

        // Check if the month is between 7 (Tir) and 12 (Esfand)
        if ($shamsiDate[1] >= 7 && $shamsiDate[2] <= 9) {
            $shamsiDate[2] += 1; // Add one day
        }

        $this->shamsiDate = array(
            'year' => $shamsiDate[0],
            'month' => $shamsiDate[1],
            'day' => $shamsiDate[2]
        );
    }

    public function getHijriDate() {
        return $this->hijriDate;
    }

}

function getMiladitoShamsi($datemiladi) {
    $gregorianDate = $datemiladi;
    $a = date('Y-m-d', strtotime($gregorianDate)); 
    $hijri = new Hijri($a);
    $hijriDate = $hijri->getHijriDate();
    $formattedHijriDate = sprintf('%d/%d/%d', $hijriDate['year'], $hijriDate['month'], $hijriDate['day']);
    return $formattedHijriDate;
}
?>




<!-- 
require 'Hijri.php';
$gregorianDate = getMiladitoShamsi($row['datecrt0']);
echo $gregorianDate; 
-->
