<?php 
/*
    Nesse arquivo sao criados um novo tipo de post, ou seja um novo tipo de dados que o wordpress pode processar,
no caso o Post do tipo curso. Aqui eh criado um post do tipo do tipo curso com uma taxionomia hierarquica e nao
hierarquica, junto de uma metabox. 
*/ 

//Para informacoes a respeito de post types custom ou taxionamia, sobre os campos e valores: https://codex.wordpress.org/Function_Reference/register_post_type

//Aqui nessa action init precisamos passar uma funcao de callck para registrar um novo tipo.
add_action('init', 'registrar_custom_post_type'); 
//Aqui a funcao de callback mencionado anteriormente.
function registrar_custom_post_type() {

	$descritivos = [ //esse array eh necessario, pois vai ser passado como parametro.
		'name'			=> 'Cursos', //O nome tipo de post no plural.
		'singular_name' => 'Curso', //O nome tipo de post no singular.
		'add_new'		=> 'Adicionar novo Curso', //O label referente a adicionar um novo atributo nesse.
		'add_new_item' 	=> 'Adicionar Curso', //quando for adicionar um novo item de curso.
		'edit_item' 	=> 'Editar Curso', //O label ao editar o post.
		'new_item' 		=> 'Novo Curso', //Label quando tiver um novo o post.
		'view_item' 	=> 'Ver Curso', //label quando for visualizar o post.
		'search_items' 	=> 'Procurar Cursos', //label quando for procurar esse post
		'not_found' 	=> 'Nenhum Curso encontrado', //mensagem quando nao for encontrado nenhum post desse.
		'not_found_in_trash'	=> 'Nenhum Curso na lixeira', //mensagem quando a lixeira estiver limpa desse tipo de post
		'parent_item_colon'		=> '', //Util apenas se a hierarquia esta ativa, no caso define qual eh o post ou pagina parente.
		'menu_name' 	=> 'Cursos' //Nome a ser mostrado no menu.
	];

	$args = [ //Esse eh o parametro necessario ao registrar o post, o mesmo contem 2 arrays e 3 atributos de parametro, um deles foi informado acima.
		'labels' => $descritivos, //O array informado acima.
		'public' => true,
		'hierarchical' => false, //Se eh hierarquico ou nao, ou nao se ele pode se associar a outros posts.
		'menu_position' => 5, //a posicao dele
		'supports' => ['title', 'editor', 'thumbnail', 'custom-fields', 'revisions'] //que tipo de recurso vai suportar.
	];
//aqui eh feito o registro dele, a funcao contem o id desse tipo de post e o array $arg que foi criado usando 3 atributos(public,hierarchical,menu_positios) e 2 arrays (labels,suport).
	register_post_type('cursos', $args); 
	flush_rewrite_rules();//Aqui eh registrado no menu do wordpress, eh necessario fazer isso, para que o WP identifique o seu post Type custom
}

/*Taxionomia, sao formas de se organiza um novo tipo de post, sendo em hierarquia ou nao, no caso o tecnologia eh hierarquico o tipos nao. */
add_action('init', 'registrar_taxonomia_tecnologia');
function registrar_taxonomia_tecnologia() {
//A estrutura eh semelhante ao criar um novo tipo de post, repare na semelhanca abaixo.
		$descritivos = [
			'name'			=> 'Tecnologias', //Nome plural.
			'singular_name' => 'Tecnologia', //Nome singular.
			'add_new'		=> 'Adicionar nova Tecnologia', //Label ao adicionar novo.
			'add_new_item' 	=> 'Adicionar Tecnologia', //Label do adicionar novo no menu.
			'edit_item' 	=> 'Editar Tecnologia', //Label ao editar no menu.
			'new_item_name' => 'Nova Tecnologia', //Label para o novo item dessa taxionomia.
			'view_item' 	=> 'Ver Tecnologia', //Label ao visualizar.
			'search_items' 	=> 'Procurar Tecnologias', //label ao procurar
			'not_found' 	=> 'Nenhuma Tecnologia encontrada', //label quando nao encontra nada.
			'menu_name' 	=> 'Tecnologias' //Nome no menu.
		];

		$args = [ //diferente do tipo de post, na taxionomia sao passado apenas 3 parametros.
			'labels' 			=> $descritivos, //array com labels.
			'singular_label' 	=> 'Tecnologia', //O Nome
			'hierarchical' 		=> true //se eh hierarquico ou nao.
		];

		register_taxonomy( //aqui eh feito o registro 2 parametros + 1 array.
			'tecnologias', //aqui eh definido o ID dele.
			'cursos', //Aqui o ID do post Type, ao qual ele se referencia.
			$args //array contendo os argumentos, com um array para as labels, um atributo para o nome e um boleano se eh ou nao hierarquico.
		);
//Voce nao precisa reescrever regras no WP como fez com o tipo de post, com as taxionomias, flush_rewrite_rules(); nao se aplica aqui.
}

add_action('init', 'registrar_taxonomia_tipo');
function registrar_taxonomia_tipo() {

		$descritivos = [
			'name'			=> 'Tipos',
			'singular_name' => 'Tipo',
			'add_new'		=> 'Adicionar novo Tipo',
			'add_new_item' 	=> 'Adicionar Tipo',
			'edit_item' 	=> 'Editar Tipo',
			'new_item_name' => 'Novo Tipo',
			'view_item' 	=> 'Ver Tipo',
			'search_items' 	=> 'Procurar Tipos',
			'not_found' 	=> 'Nenhum Tipo encontrado',
			'menu_name' 	=> 'Tipos'
		];

		$args = [
			'labels' 			=> $descritivos,
			'singular_label' 	=> 'Tipo',
			'hierarchical' 		=> false
		];

		register_taxonomy(
			'tipos',
			'cursos',
			$args
		);

//Repare funciona extamente igual como a taxionomia criada acima, porem esse nao eh hierarquico, compare o Tecnologia e o Tipo para ver a diferenca na gui do wordpress na pratica, entre o modelo hierarquico e nao hierarquico.

}

//Para resumir custom post type => seria algo como um novo tipo de post. Taxionomia seria uma especie de atibuto que voce poe nesses custom posts, do tipo filho de quem, pai de quem, ou atributos de identidade mesmo.



//Aqui eh adiciona um metabox, aquela caixinha a direita, como por exemplo a publish quando voce cria um post ou pagina. Aqui faremos uma custom dessa.
add_action('add_meta_boxes', 'add_metabox_carga_horaria'); //essa eh a action e ela pede uma funcao de callback.
function add_metabox_carga_horaria() {
//funcao para adicionar a metabox.
	add_meta_box(
		'carga_horaria', //aqui eh informado o ID.
		'Carga Horária', //O nome.
		'mb_carga_horaria_cb', //funcao de callback.
		'cursos', //o post type ao qual ela ira operar.
		'side', //a posicao ao ele fica.
		'default'//a prioridade da execucao.
	);

}

function mb_carga_horaria_cb() {//Essa eh a funcao de callback informado acima.
	
	global $post; //Essa eh a variavel global que contem um array de posts.
    //essa funcao ela busca no banco de dados, pelo post id: get_post_meta(Variavel POST_ID,o nome da variavel, 'true' caso voce queira que retorna apenas o primeiro valor encontrado, aqui eh o caso de um true, se nao omite isso ou coloca false ali.).
	$carga_horaria = get_post_meta($post->ID, 'carga_horaria', true);

	echo '<label for="carga_horaria">Carga Horária: </label>';
	echo '<input type="text" name="carga_horaria" id="carga_horaria" value="' . esc_attr($carga_horaria) . '" />';

}
//para interceptar a criacao de post e salva-lo usamos essa action, no caso ela cria esse post no banco de dados, quando o valor do metabox for atualizado.
add_action('save_post', 'save_carga_curso'); //nome da action + funcao callback

function save_carga_curso() { //funcao de callback como mencionado acima.

	global $post; //pegando a variavel global post

	$carga_horaria = sanitize_text_field($_POST['carga_horaria']); //sanatizando ela.

	update_post_meta($post->ID, 'carga_horaria', $carga_horaria); //escrevendo ela no banco de dados.
//Todo esse processo ocorre apenas quando os valores na metabox sao atualizados.

}
























