<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Synchronisation</title>
</head>
<body>
    <label for="ip">Ip serveur ftp</label>
    <input type="text" id="ip">
    <button id="sync" onclick="sync()">Synchroniser projet</button>
    <div id="syncMessage"></div>
    <script src="jquery-3.4.1.min.js"></script>
    <script>
        function sync() {
            $.ajax({
                url: "updateProject.php",
                method: 'GET',
                data: `ip=${$("#ip").val()}`,
                success: function (result) {
                    $(`#syncMessage`).css("color", "green");
                    $(`#syncMessage`).html(result);
                    $(`#syncMessage`).show().delay(1000).fadeOut();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
    </script>
</body>
</html>