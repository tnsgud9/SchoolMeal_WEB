<?php
/**
* meal_api.php
* Created: Tuesday, Jan 30, 2018
* 
* Juneyoung KANG <juneyoung@juneyoung.kr>
* Gyoha High School
*
* Creates a today school meal JSON file from the NEIS webpage.
* Github : https://github.com/Juneyoung-Kang/school-meal/
*
* How to use?
* http://juneyoung.kr/api/school-meal/meal_api_today.php?countryCode=stu.goe.go.kr&schulCode=J100004922&insttNm=교하고등학교&schulCrseScCode=4&schMmealScCode=2
* 
* For more information, visit github and see README.md
*
* Licensed under The MIT License
*/

error_reporting(0);                           // error reporting disable
header("Content-type: application/json; charset=UTF-8");        // json type and UTF-8 encoding

require "simple_html_dom.php";                // use 'simple_html_dom.php'

$countryCode = $_GET['countryCode'];          // local office of education website
$schulCode =  $_GET['schulCode'];             // school code
$insttNm = $_GET['insttNm'];                  // school name
$schulCrseScCode = $_GET['schulCrseScCode'];  // school levels code
$schMmealScCode = $_GET['schMmealScCode'];    // meal kinds code
$time = time();
$time=strtotime("+1 day", $time);
$tomorrow = date("Y.m.d", strtotime("+9 hours", $time));
$schYmd =  $tomorrow;

$MENU_URL = "sts_sci_md01_001.do";            // view weekly table

// url for today
$URL="http://" . $countryCode . "/" . $MENU_URL . "?schulCode=" . $schulCode . "&insttNm=" . urlencode( $insttNm ) . "&schulCrseScCode=" . $schulCrseScCode . "&schMmealScCode=" . $schMmealScCode . "&schYmd=" . $schYmd;

// DOMDocument
$dom=new DOMDocument;

// load HTML file 
$html=$dom->loadHTMLFile($URL);
$dom->preserveWhiteSpace=false;

// get elements by tag name
$table=$dom->getElementsByTagName('table');
$tbody=$table->item(0)->getElementsByTagName('tbody');
$rows=$tbody->item(0)->getElementsByTagName('tr');
$cols=$rows->item(1)->getElementsByTagName('td');

// reset date format
$schYmd=str_replace(".", "-", $schYmd);

// get day
$day=date('w', strtotime($schYmd));

// check blank has values
if($cols->item($day)->nodeValue==null){
    echo '';
}else{
    $final=$cols->item($day)->nodeValue;
}
//http://localhost/meal_api_tomorrow.php?countryCode=stu.sen.go.kr&schulCode=B100000593&insttNm=송파공업고등학교&schulCrseScCode=4&schMmealScCode=2
// replace unnecessary characters

$final=preg_replace("/[0-9]/", "1", $final);
$final=str_replace(".", "1", $final);
$final=str_split($final);
for($i=1;$i<count($final);$i++)
{
    if($final[$i-1]==1)
    {
        if($final[$i]!=1)
            $final[$i-1]="\n";
        else
            $final[$i-1]="";
    }
}
$final[count($final)-1]="";

$final=implode($final);
// change code number to text
if($schulCrseScCode==1){
    $schulCrseScCode="유치원";
}
if($schulCrseScCode==2){
    $schulCrseScCode="초등학교";
}
if($schulCrseScCode==3){
    $schulCrseScCode="중학교";
}
if($schulCrseScCode==4){
    $schulCrseScCode="고등학교";
}
if($schMmealScCode==1){
    $schMmealScCode="조식";
}
if($schMmealScCode==2){
    $schMmealScCode="중식";
}
if($schMmealScCode==3){
    $schMmealScCode="석식";
}

if($final==null&&$final=="")
{
    echo "내일 급식 정보가 없습니다.!";
    $final="내일 급식 정보가 없습니다.!";
}

// array
$array = array(
    '교육청 코드' => $countryCode,
    '학교 코드' => $schulCode,
    '학교 명' => $insttNm,
    '학교 종류' => $schulCrseScCode,
    '급식 종류' => $schMmealScCode,
    '날짜' => $schYmd,
    '메뉴' => $final
);
$fp = fopen('meal_tomorrow.json', 'w');
fwrite($fp, json_encode($array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
fclose($fp);
echo $final;
//Header("Location:../index.html")

?>

