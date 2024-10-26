<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Benvingut al joc 4EnRatlla</h1>
    
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <label for="nom">Nom del jugador</label>
        <input type="text" name="nom" id="nom" required>

        <label for="color">Color</label>
        <select name="color" id="color" required>
            <option value="vermell">Vermell</option>
            <option value="verd">Verd</option>
        </select>

     <label for="mode">Mode de joc:</label>
    <div class="radio-group">   
    <label for="modeAmigo">Jugar amb un amic</label>
    <input type="radio" name="mode" value="amigo" id="modeAmigo" checked>
    </div>
    <div class="radio-group">
    <label for="modeMaquina">Jugar amb la màquina</label>
    <input type="radio" name="mode" value="maquina" id="modeMaquina">
    </div>

        <div id="jugador2Container" style="display: none;">
            <label for="nomJugador2">Nom del segon jugador</label>
            <input type="text" name="nomJugador2" id="nomJugador2">
        </div>

        <input type="submit" value="Jugar">
    </form>

    <script>
        const modeAmigo = document.getElementById('modeAmigo');
        const modeMaquina = document.getElementById('modeMaquina');
        const jugador2Container = document.getElementById('jugador2Container');

        modeAmigo.addEventListener('change', function() {
            jugador2Container.style.display = 'block';
        });

        modeMaquina.addEventListener('change', function() {
            jugador2Container.style.display = 'none';
        });
    </script>
</body>
</html>
