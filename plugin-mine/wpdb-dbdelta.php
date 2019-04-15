<?php 
 
/* 
 * Plugin Name: wpdb e dbdelta 
 * Plugin URI: http://www.treinaweb.com.br/ 
 * Description: Criacao de Banco de dados exemplo. 
 * Author: ADAM SKYNEW
 * Version: 1.0.0 
 * Author URI: http://www.treinaweb.com.br/ 
 * License: GPL2+ 
 */

//aprofundamento a respeito do WPDB: https://codex.wordpress.org/Class_Reference/wpdb

//registra a action para criacao de tabelas. No caso esta sendo passado como parametro o proprio arquivo e uma funcao de callback.
register_activation_hook( __FILE__ , 'criar_tabelas');


function criar_tabelas() {
//Voce precisa chamar essa variavel global, ela eh um objeto, no caso usamos ela para criar uma tabela com o prefixo do wordpress.  
	global $wpdb;

	$nome_tabela = $wpdb->prefix . 'aluno'; //No caso aqui ele pega o prefixo para criar a tabela com o nome de aluno.

	$sql = 'CREATE TABLE ' . $nome_tabela . '(
		cod_aluno INT NOT NULL,
		nome VARCHAR(100) NOT NULL,
		email VARCHAR(100) NOT NULL,
		endereco VARCHAR(150) NOT NULL
	);'; //O SQL para a criacao 
    //Existe uma funcao que cria tabelas no banco de dados, primeiro eh preciso importar o arquivo
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql); //Essa eh a funcao, ela executa a query e caso a tabela ja existe ele analisa e ve se a tabela esta igual a definida na query.

}
//Registro de menu.
add_action('admin_menu', 'registrar_submenu');

function registrar_submenu() {
	add_menu_page(
		'Página de configuração',
		'WPDB Delta',
		'manage_options',
		'menu_slug_config',
		'menu_slug_config_cb',
		'dashicons-hammer',
		9
	);
}
//Fim de registro de menu.
function menu_slug_config_cb() {

	global $wpdb;
    //simplesmente executa uma query, nao eh o mais seguro mas eh o mais simples.
	$wpdb->query('INSERT INTO wp_aluno (cod_aluno, nome, email, endereco) VALUES (1, "Wagner", "wag@gmail.com", "Paulista, 11100")');
    //repare que esse metodo tambem aceita uma String SQL devidamente escapada e formatada a fim de evitar sql injection.
	 $wpdb->query(//o metodo em si.
	 	$wpdb->prepare(//dentro do metodo voce passa uma string aonde nos valores a serem substituido voce coloca %d para inteiro e %s para textos.
	 		'INSERT INTO wp_aluno (cod_aluno, nome, email, endereco) VALUES (%d, "%s", "%s", "%s")',
	 		[2, 'Bruna', 'bru@gmail.com', 'consolação 122'] //depois da string formada voce coloca os valores a serem substituido nas strings aonde esta os % em ordem, como esta na string
	 	)
	 );
//metodo $wpdb->insert('String com o nome da tabela',[array com atributos => valor],[array com a formtacao dos atributos]), essa funcao ela ja sanitiza os dados eh a melhor forma de insirir dados.
	 $wpdb->insert( //metodo insert do wordpress.
	 	'wp_aluno', //aqui uma string com o nome da tabela
	 	[ //'atributo' => 'valor' 
	 		'cod_aluno' => 3,
	 		'nome'		=> 'Rose',
	 		'email'		=> 'rose@gmail.com',
	 		'endereco'	=> 'rua teste'
	 	],
	 	['%d', '%s', '%s', '%s'] //Parametros para insercao, no caso ali esta a primeira como inteiro e as outras 3 como string
	 );
        //o metodo get_results ele retorna mais de uma linha, o segundo parametro eh opicional. No caso esse metodo eh usada quando o resultado tem mais de uma linha
	 $resultado = $wpdb->get_results('SELECT * FROM wp_aluno', OBJECT_K);
	 var_dump($resultado);

	// OBJECT – Objeto com arrays que representam cada linha com índices numéricos;
	// OBJECT_K – Objeto com array que representa cada linha com índice associativo; (Valido apenas para o get_results, pois diferente do get_row, o get results eh um array.
	// ARRAY_A – Array com índices de linhas e colunas associativos;
	// ARRAY_N – Array com índices de linhas e colunas numéricos.

//O metodo retorna apenas uma linha, no caso a linha especificada pelo terceiro parametro, os dois primeiros funcionam igual a funcao get_results e o terceiro parametro informa a linha que deve ser retornada, a comecar pela linha zero, no caso essa retorna a segunda posicao, apesar de estar como 1.
	 $resultado = $wpdb->get_row('SELECT * FROM wp_aluno', OBJECT, 1);
	 var_dump($resultado);

//Repare tambem que o get_row tambem pode pegar dados formatados, afim de evitar sql injection.
	 $resultado = $wpdb->get_row(
	 	$wpdb->prepare(
	 		'SELECT * FROM wp_aluno WHERE cod_aluno = %d', 3
	 	)
	 );//repare que no get_row ali tem os valores com %d, no caso aquele %d sera substituido pelo 3. 
	 var_dump($resultado);

	 $wpdb->update(//Aqui eh feito o update
	 	'wp_aluno', //O nome da tabela
	 	[
	 		'nome' => 'Bruna Silva', //os atributos a serem colocados
	 		'email' => 'bruna123@gmail.com' //os atributos a serem colocados
	 	],
	 	[
	 		'cod_aluno' => 2 //aqui entra a clausura where, no caso seria algo do tipo where cod_aluno = 2;
	 	],
	 	['%s', '%s'], //formatacao dos novos dados, como ambos sao strings, se usa o %s. 
	 	['%d'] //formatacao do tipo de dado da 'clausura where', no caso la eh um inteiro.
	 );

//metodo para deletar.
	$wpdb->delete( 
		'wp_aluno', //nome da tabela.
		[
			'cod_aluno' => 3 //clausura where.
		],
		['%d'] //tipo do atributo que esta sendo usado la, no caso um inteiro.
	);


}

/*metodo $wpdb->update('nome da tabela,ARRAY['campo' => 'novo valor'],'ARRAY['atributo do where' => valor do where], 'ARRAY['TIPO de dados que vao ser inseridos'],ARRAY["tipo de dados da parte responsavel pela clausura where"]'), essa funcao eh a mais recomendada pois sanitiza os dados, caso haja a insercao por parte do usuario.*/

/* Metodo: $wpdb->delete('nome da tabela',ARRAY['atributo do where' => 'valor do where'],ARRAY['tipo de dados que esta lidando.'])*/

/* Metodo: $wpdb->prepare('sql query','Valor a ser tratado 1',,'Valor a ser tratado 2',,'Valor a ser tratado 3',etc... )*/

/*o %d refere-se a inteiro, %s a strings, no caso isso ajuda para saber aonde que o usuario ira inserir os dados, para que seja feito a sanitizacao do mesmo.*/
//Isso pode ajudar a entender as referencias % das strings: https://tutorials.webencyclop.com/c-language/c-format-specifiers/





















