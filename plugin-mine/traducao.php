<?php 
 //Essa eh a action para registrar uma funcao de callback para internacionalizacao.
add_action('plugins_loaded', 'registrar_dominio');

function registrar_dominio() {//A funcao de callback
    //Essa funcao aceita 3 parametros, porem o segundo eh inutil pois foi descontinuado, apenas coloque falso.
    //Primeiro parametro refere-se ao arquivo 'mo'. A ser carregado, esse arquivo deve seguir um padrao de nome, explicado mais abaixo.
    //Segundo se o caminho para acesso ao arquivo eh relativo ou absoluto, is absolute? None = false, descontinuado. Parece que eh possivel carregar um arquivo em outra URL.
    //o terceiro parametro o diretorio que contem os arquivos de traducao.
    //Mais informacao: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
	load_plugin_textdomain('traducao-tw', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

//registrar submenu.
add_action('admin_menu', 'registrar_submenu_internacionalizacao'); 
 
function registrar_submenu_internacionalizacao() { 
 
add_menu_page( 
  'Página Traduzida', 
  'Página Traduzida', 
  'manage_options', 
  'menu_plug_traducao', 
  'menu_plug_traducao_cp', 
  'dashicons-hammer', 
  9 
  ); 
 
 
} 
//fim do registrar submenu.
 
function menu_plug_traducao_cp(){ 

	echo "<h1>";
//Essa funcao '_e()' ela da um echo no texto, se ela encontrar a traducao no arquivo MO, ela printa a traducao, caso nao, ela printa o texto da funcao.
//Mais detalhes: https://codex.wordpress.org/Function_Reference/_e
	_e('Hello World', 'traducao-tw');

	echo "</h1>";
//a funcao '__()' eh parecida com a '_e()', porem ela nao da echo e retorna a string, Mais detalhes: https://codex.wordpress.org/Function_Reference/_2
	$string = __('This plugin is in English', 'traducao-tw');

	echo "<p>" . $string . '</p>';


}

/*
    Voc^e precisa de um arquivo MO para isso, sendo esse um arquivo compilado de um arquivo PO(esse codigo fonte).
    Voce pode criar um arquivo MO nesse site: https://localise.biz/free/poeditor
   No campo aonde voc^e faz o upload do arquivo PO, voce pode arrastar o arquivo PHP pra la e ir arrumando as traducoes, string por string no php.
ele disponibiliza o PO que eh o codigo fonte e o MO que eh o compilado. Para mais informacao para arquivo MO: https://pt.wikipedia.org/wiki/Gettext

*/

