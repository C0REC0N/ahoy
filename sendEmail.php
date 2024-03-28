<?php
// the message
$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("cg2014@hw.ac.uk, cp202o@hw.ac.uk, mt2023@hw.ac.uk, mn2024@hw.ac.uk, ms2081@hw.ac.uk, omp2000@hw.ac.uk, xs2024@hw.ac.uk",
"Ahoy Account Validation",$msg);
?>