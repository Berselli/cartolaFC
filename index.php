<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
