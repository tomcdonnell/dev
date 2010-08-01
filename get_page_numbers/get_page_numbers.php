<?php
$numbersA = array();
$numbersB = array();

for ($i = 1; $i < 215; $i += 4)
{
   $numbersA[] = $i;
   $numbersA[] = $i + 1;
   $numbersB[] = $i + 2;
   $numbersB[] = $i + 3;
}

echo 'Set A: ', implode(',', $numbersA), "\n";
echo 'Set B: ', implode(',', $numbersB), "\n";
?>
