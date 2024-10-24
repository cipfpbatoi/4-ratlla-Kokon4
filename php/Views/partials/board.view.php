<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="board">
    <table>
     <tr>
       <?php if (!$winner ):
        for ($j=1;$j<= $board::COLUMNS ;$j++): ?>
         <td><input type='submit' name='columna' value='<?=$j ?>' /></td>";
      <?php endfor ; else: ?>
        <h1>El guanyador es el jugador <?=  $winner ->getName() ?></h1>
      <?php endif ?>
     </tr>
      <?php for ($i=1;$i<= $board::FILES ;$i++):  ?>
          <tr>
         <?php for ($j=1;$j<= $board::COLUMNS ;$j++):  ?>
             <?php echo match ($board->getSlots()[$i][$j]){
                0 => '<td class="buid"></td>',
                1 => '<td class="player1"></td>',
                2 => '<td class="player2"></td>'};
                endfor ;
            endfor   ?>
         </tr>
    </table>
</div>
</body>
</html>