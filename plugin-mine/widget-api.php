<?php 
//Mais informacoes sobre a classe widget: https://codex.wordpress.org/Widgets_API

/*Aqui eh criado um widget, nesse caso o widget eh criado extendendo uma outra classe.
*/ 

//Essa aqui eh a action necessaria. Como toda action exige uma sttring informando a acao qeu vai ser adicionada e outra com a funcao de callback.
add_action('widgets_init', 'registrar_widget');
 
function registrar_widget() { //A referida funcao de callback.
//para registrar um novo widget, voce usa essa funcao, passando o nome da classe dela como parametro.
	register_widget('SocialMediaWidget');

}

//Aqui eh a classe do nosso widget, essa classe deve extender da WP_Widget e ter obrigatoriamente que passar parametros pelo construtor pai e ter pelo menos o metodo form e widget para que funcione direito.
class SocialMediaWidget extends WP_Widget
{
	
	function __construct()
	{ //No construtor eh chamado o construtor pai, porem passando 3 parametros.
		parent::__construct(
			'social_media_widget', //o ID.
			'Social Media', //O nome do Widget.
//E por fim um array contendo a descricao dela.
			['description' => 'Widget permite criar link para redes sociais']
		);
	}

/*  Nesse metodo eh feito a escrita dos dados, quando o usuario altera no admin do wordpress algum dado, essa
    eh o metodo que lida com isso, no caso ele verifica se existe um alguma nulidade, caso exista retorna uma string vazia
    A variavel passada na assinatura do metodo, como eh o caso do $dados, ela representa os dados modificados quando o usuario
    da um submit no admin do wordpress. Analise o arquivo importado nesse metodo para mais informacoes. Ressaltando contudo, que
    esse metodo ele eh disparado quando o usuario clica em submit do wordpress.
*/
	public function form( $dados ) 
	{
		$titulo 		= isset($dados['titulo']) ? $dados['titulo'] : '';
		$titulo_link 	= isset($dados['titulo_link']) ? $dados['titulo_link'] : '';
		$link 			= isset($dados['link']) ? $dados['link'] : '';


		require('page/widget_form.php');//arquivo importado, leia para mais informacoes.
	}

    /*Esse metodo esta relacionado com a exibicao do widget na pagina, que os internautas acessam.
     Esse metodo recebe dois parametros em sua assinatura, o primeiro sao as configuracoes e o segundo
     os dados propriamente dito. 
    */
	public function widget($conf, $dados) 
	{
		echo $conf['before_widget']; //Refere-se a tag antes do widget.

		if ( ! empty($dados['titulo'])) { //Verifica nulidade do titulo.
    //Aqui eh o titulo, no caso sera criado um titulo do widget integrado com o tema, gracas a esse array nesse indice que faz com que o wordpress retorne o titulo com todas as classes e configuracoes do tema. 
			echo $conf['before_title']; //importante para se integrar ao tema.
            //Voce precisa do filtro para exibir o titulo integrado ao tema, no caso a funcao abaixo faz exatamente isso.
			echo apply_filters('widget_title', $dados['titulo']); //apply_filters('atributo ao qual voce quer passar o filtro',o dado a ser filtrado);

			echo $conf['after_title']; //importante para se integrar ao tema.
		}
//o printf faz a formatacao, substituindo o primeiro %s, pelo primeiro valor apos a virgula e o segundo %s pelo segundo valor apos a virgula.
		if ( ! empty($dados['titulo_link']) && ! empty($dados['link'])) { //verifica nulidade do conteudo do widget.
			printf('<a href="%s" target="_blank">%s</a>', $dados['link'], $dados['titulo_link']);
		}

		echo $conf['after_widget']; //refere-se a tag apos o widget

/*Detalhe o indice before e after widget, assim como after e before title ele permite uma maior integracao com o tema. No caso quando usado ele cria as tags html usando as regras do tema, logo eh recomendado o seu uso, caso o contrario nao estara dentro do padrao do tema. */

	}

    //Esse metodo nao eh obrigatorio, ele eh acionado, quando se faz uma alteracao, para editar o widget, porem ele eh executado antes do metodo form e contem 2 arrays diferentes desse metodo, ele pode util para sanatizar os dados antes da execucao do form por exemplo, ou para submete-lo a uma expressao regular antes de modificar os dados por exemplo, ele exige um retorno, no caso se atender todas as requisitos, voce pode retornar os dados informados pelo usuario, senao vai o antigo por exemplo.
	public function update($dados_novos, $dados_antigos)
	{
//Esse metodo tem dois arrays, o primeiro sao os dados novos, o segundo os dados que ja estavam la.
// strip_tags() escapa tags html, ou seja limpa de tags html.
		$dados_novos['titulo'] = strip_tags($dados_novos['titulo']);
		$dados_novos['titulo_link'] = strip_tags($dados_novos['titulo_link']);
		$dados_novos['link'] = strip_tags($dados_novos['link']);

		return $dados_novos; //Esse metodo precisa de um retorno, repare que ele sanitiza os dados e depois retorna os novos dados informados no admin do WP.
	}


}
















