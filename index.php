<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            background-color: rgba(0, 0, 0, 0.7)
        }

        .form-pesquisa {
            display: flex;
            justify-content: center;
            width: 500px;
            margin: 0 auto 50px;
        }

        .form-pesquisa select {
            font-size: 12px;
            font-family: sans-serif;
            font-weight: 700;
            color: #444;
            line-height: 1.3;
            padding: .6em 1.4em .5em .8em;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            margin: 0;
            border: 1px solid #aaa;
            box-shadow: 0 1px 0 1px rgba(0, 0, 0, .04);
            border-radius: .5em;
            -moz-appearance: none;
            -webkit-appearance: none;
            appearance: none;
            background-color: #fff;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'),
                linear-gradient(to bottom, #ffffff 0%, #e5e5e5 100%);
            background-repeat: no-repeat, repeat;
            background-position: right .7em top 50%, 0 0;
            background-size: .65em auto, 100%;
        }

        .form-pesquisa select:hover {
            border-color: #888;
        }


        .form-pesquisa button {
            display: block;
            font-size: 12px;
            font-family: sans-serif;
            font-weight: 700;
            color: #444;
            line-height: 1.3;
            padding: .6em 1.4em .5em .8em;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            margin: 0;
            border: 1px solid #aaa;
            box-shadow: 0 1px 0 1px rgba(0, 0, 0, .04);
            border-radius: .5em;
            -moz-appearance: none;
            -webkit-appearance: none;
            appearance: none;
        }

        .form-pesquisa button:hover {
            background-color: #e5e5e5;
            border-color: #888;
        }


        .atletas {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .atleta {
            display: flex;
            flex-flow: column;
            align-items: center;
            padding: 20px;
            min-width: 200px;
            max-width: 200px;
            text-align: center;
            flex-grow: 1;
            margin: 0 20px 20px;
            background-color: red;
            background-image:
                radial-gradient(yellow,
                    #f06d06);
            box-shadow: 5px 5px 10px black;
        }

        .atleta * {
            margin: 0 0 10px;
        }
    </style>
</head>

<body>
    <p></p>
    <?php


$url = "https://api.cartolafc.globo.com/atletas/mercado";

$options = array(
    'http' => 
      array(
        'method' => 'GET', 
        'user_agent' => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)', 
        'timeout' => 1
        )
);

$context = stream_context_create($options);
$file = file_get_contents($url, false, $context);
$cartola = json_decode($file, true);
$clubes = [];
foreach($cartola['clubes'] as $clube){
	//array_push($clubes, $clube);
	$clubes[$clube['id']] = $clube;
}
function buscaClube($id){
	global $clubes;	
	foreach($clubes as $clube){
		if($clube['id'] !== $id) continue;
		return $clube['nome'];
		
		if($clube['id'] === $id){
			return $clube['nome'];
		}
	}
	return null;
}

$clube_id = null;
if(isset($_POST['clube']))
	$clube_id = $_POST['clube'];
print "<div class='form-pesquisa'><form method='POST' action='index.php'><select name='clube'>";
print "<option value='-1'> Selecione</option>";
	foreach($clubes as $clube){
		$selecionado = '';
		if($clube['id'] == $clube_id) $selecionado = 'selected';
		print "<option value='{$clube['id']}' {$selecionado}>{$clube['nome']}</option>";
	}
	print "</select><button type='submit'>Pesquisar</button></form> </div>";
print "<div class='atletas'>";



foreach($cartola['atletas'] as $atleta){
	if(!empty($clube_id) && $clube_id != -1 && $clube_id != $atleta['clube_id']) continue;
	
	$foto = "https://s.glbimg.com/es/sde/f/2019/06/20/cf0dbe27f075457db8f6dabc6fb405d3_140x140.png";
	
	if(!empty($atleta['foto']))
		$foto = str_replace("FORMATO", "140x140", $atleta['foto']);

	print "<div class='atleta'>";
	print "<img src='{$foto}'/>";
	print "<strong>{$atleta['nome']}</strong>";
	print "<small>".buscaClube($atleta['clube_id'])."</small>";
	print "</div>";
	
}
print "</div>";
?>
</body>

</html>
