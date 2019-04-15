<?php 

//Shortcode simples. Use a como [EXEMPLO][/EXEMPLO] para usar-la em post ou pages.
//Esse eh o metodo para adicionar shortcode simples ou complexa.
add_shortcode('EXEMPLO', 'tw_teste_cb'); //add_shortcode('O nome do shortcode', 'A funcao de callback');

//A funcao de callback deve ter 3 atributos: O atibutos, Conteudo e a tag, sendo o exemplo abaixo:
//[TAG atributo=valor_atributo]Conteudo[/TAG]
function tw_teste_cb($atributo, $conteudo, $tag) {

	var_dump($atributo);

	echo "<br>" .$conteudo;

	echo "<br>[".$tag."]";

}



//Shortcode complexas. Use a como [CEP][/CEP] para usar-la em post ou pages.
//ShortCode, no caso aqui sera adicionada uma shortcode com o nome de CEP, no formato [CEP]SEU CEP[/CEP], retornando o endereco.
//Essa funcao cria uma shortcode, semelhante a simples a forma de criar a complexa eh parecida.
add_shortcode('CEP', ['Endereco', 'getEndereco']); //Sao dois parametros: add_shortcode(O nome da shortCode, ['A Classe','Metodo statico que vai executar']) 


class Endereco //A classe, repare que todos os metodos sao estaticos.
{
	//O metodo que eh informado na add_shortcode, deve ter como assinatura dois arrays, o primeiro com os atributos e o segundo com o conteudo.
	static function getEndereco($attr, $content)
	{

		$content = $content == '' ? '01311200' : $content; //Operador ternario, se nulo pega o cep do treinaweb.

		$json = self::getJson($content); //pega o json atraves de uma requisicao curl. 

		$array = self::getArray($json); //Transforma o json em objeto e nao array como diz a funcao.

		extract($array); //a funcao extract, transforma cada indice de array em uma variavel, porem recebe um array como parametro.

		echo $address . ", " . $district . " - " . $city . " - " . $state; //Essas variaveis foram criadas com a funcao acima.
	}

	static function getJson($content)
	{
		  // Inicia o cURL (uma transação HTTP) passando o CEP recebido 
		  $recurso = curl_init("http://apps.widenet.com.br/busca-cep/api/cep/{$content}.json"); 
		 
		  // Definido o que receber de conteúdo (GET) 
		  curl_setopt($recurso, CURLOPT_RETURNTRANSFER, true); 
		 
		  // Executa e obtém o resultado 
		  $resultado = curl_exec($recurso); 
		 
		  // Encerra a conexão (para liberar da memória) 
		  curl_close($recurso); 

		  return $resultado;
	}

	static function getarray($json)
	{
		  return  (array) json_decode($json); //a funcao decode transforma um json em objeto, porem com o cast retorna um array.
	}

}




