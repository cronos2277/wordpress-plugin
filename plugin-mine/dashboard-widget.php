<?php
//Aprofundamento: https://codex.wordpress.org/Dashboard_Widgets_API

//A Action para adicionar essa dashboard width.
add_action('wp_dashboard_setup', 'curso_dashboard_widget');

function curso_dashboard_widget() { //a callback da action acima.

	wp_add_dashboard_widget( //essa funcao registra uma nova dashboar widget, ela aceita 3 parametros.
		'wg_curso_dashboard_widget', //Esse eh o ID.
		'Detalhes dos cursos', //Esse eh o nome a ser exibido na home do wordpress.
		'wg_curso_dashboard_widget_cb' //A funcao de callback.
	);

}

function wg_curso_dashboard_widget_cb() { //A funcao de callback da wp_add_dashboard_widget();
	
	$cursos = new WP_Query([ //Essa classe quando instanciada ela filtra os cursos.
		'post_type' => 'cursos', //Aqui esta pegando apenas o post do tipo cursos.
		'posts_per_page' => 3 //No maximo 3, no caso os 3 ultimos.
	]);

	while ($cursos->have_posts()) { //Array no padrao Iterator verificando se tem post nesse indice.
		
		$cursos->the_post(); //ele avanca ao proximo indice do array, tanto esse the_post() como o have_posts() sao metodos.

?>
	<a href="<?= the_permalink()//retorna o link do post atual do laco. ?>" title="<?= the_title() ?>">
		<?= the_title()//retorna o titulo do post do post atual do laco. ?>
	</a>
	<br/>

<?php
	}

	$total = new WP_Query([
		'post_type' => 'cursos'
	]);

	echo "Cursos Cadastrados: " . $total->post_count; //<-- Post count retorna a quantidade total dos posts, no caso como nao existe limite limites como a anterior, logo mostra todos.

}
