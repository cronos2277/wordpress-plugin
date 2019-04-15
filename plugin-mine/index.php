<?php
/*
Plugin Name: Meu Plugin de Exemplo
Plugin URI: http://webdevforge.com/
Description: Meu primeiro plugin
Author: Adam Skynew
Version: 0.0.1
Author URI: https://webdevforge.com
*/

/*
    Executando acao ao ativar ou desativar plugin, no caso o plugin nao pode ser desativado,
     e exige uma versao minima para ser ativado do wordpress
*/
define('VERSAO_REQUERIDA', '4.8.0'); //Constante informando a versao minima necessaria.
 //A funcao para definir acao de ativacao, primeiro o arquivo aonde contem a funcao e depois o nome da funcao. 
register_activation_hook(__FILE__, 'plugin_ativacao'); 
  
function plugin_ativacao(){ 
  
  global $wp_version; //Aqui importa a variavel global contendo informacoes referente a versao do WP.
 //A funcao version_compare exige 3 parametros, sendo respectivamente: A versao instalada, a versao exigida e um char de comparacao, no caso o menor que foi usado.
  if (version_compare( $wp_version, VERSAO_REQUERIDA, '<' )) { //Essa funcao ela faz a comparacao da versao do WP com a versao exigida no padrao acima declarado.
  	wp_die('É necessária versão ' . VERSAO_REQUERIDA . ' ou superior' ); //Mata o processo caso a versao seja mais antiga que a solicitada
  }
    
}
  
 //A funcao para definir acao de desativacao, primeiro o arquivo aonde contem a funcao e depois o nome da funcao.
register_deactivation_hook(__FILE__, 'plugin_desativacao'); 
 
function plugin_desativacao(){ //No caso essa funcao nao deixa desativar o plugin. 

 wp_die( 'Não permitido desativar' ); //proibe de desativar.

}




/*
    Essa Action eh carregada depois de carregada a barra administrativa,
detalhe a funcao meu logo so exibe o nome da empresa dentro do interface adminstrativa
do wordpress, caso houvesse a necessidade de ser carregado antes, poderia ter usado
wp_before_admin_bar_render como action.
*/ 
add_action('wp_after_admin_bar_render','meuLogo');

function meuLogo(){ //A funcao carregada com base na action acima.
    echo '<h3>Minha Empresa</h3>';
}

//A Action the_post ela modifica a forma com que o post eh exibido, no painel administrativo.
add_filter('the_post', 'add_title');
function add_title($post){ //A funcao the_post ela exige um parametro de entrada, no caso um array
    $post->post_title .= ' ('.$post->comment_status.')';
    return $post;
}

add_action('admin_menu','register_submenu');
function register_submenu(){
    add_submenu_page( //criacao de um submenu
        'options-general.php', //Em qual menu esse submenu vai estar? No caso ele esta dentro do menu config.
        'Plugin MINE Settings', //O Title da pagina quando estiver na pagina de configuracao dele.
        'Mine Settings', //O nome dele dentro do menu escolhido.
        'manage_options', //A permissao necessaria para pode configurar o plugin
        'mine_submenu_plugin', //O slug dele, esse slug sera uma forma de outras funcoes referenciar eles.
        'mine_menu_function' //A funcao de callback contendo o seu funcionamento.
    );

    add_menu_page( //criacao de um menu
        'Plugin MINE Settings', //O Title da pagina quando estiver na pagina de configuracao dele.
        'Mine Settings', //O nome dele dentro do menu escolhido.
        'manage_options', //A permissao necessaria para pode configurar o plugin
        'mine_menu_plugin', //O slug dele, esse slug sera uma forma de outras funcoes referenciar eles.
        'mine_menu_main_function', //A funcao de callback contendo o seu funcionamento.
        'dashicons-lightbulb', /* Aqui eh escolhido um dos varios icones presente no wordpress. Vai aqui para voce escolher um: 
                https://developer.wordpress.org/resource/dashicons/#welcome-view-site escolha um clique nele
                e poste o codigo dele aqui como valor. */
        85 /* A posicao desse menu na barra principal, sendo a posicao dos outros: 2 -> painel, 4 -> separador, 5 -> posts, 10 -> Midia, 15 -> Links,
      20 -> paginas, 25 -> comentarios, 59 -> separador, 60 -> aparencia, 65 -> plugins, 70 usuarios, 75 -> ferramentas 80 -> opcoes, 99 ->separador. */
    );

    do_action('action-mine-plugin',$arg1,$arg2); //Esse comando aqui cria actions, permitindo que o plugin seja extendido e at'e acessado por outro. No caso apenas basta o plugin usar essa action que eu criei.
}

/*
    Voce tambem pode usar as funcoes rapidas, nesse caso se usa todos os parametros acima exceto o primeiro, uma vez que cada funcao
    ja tem por configurado em um menu determinado, por exemplo add_theme_page() <- adiciona o menu de configuracao na pagina de tema.
    add_options_page(), ou seja add_[o local aonde voce quer por]_page();
    No caso essa funcao poderia ser tambem:
    add_options_page(        
        'Plugin MINE Settings', //O Title da pagina quando estiver na pagina de configuracao dele.
        'Mine Settings', //O nome dele dentro do menu escolhido.
        'manage_options', //A permissao necessaria para pode configurar o plugin
        'mine_menu_plugin', //O slug dele, esse slug sera uma forma de outras funcoes referenciar eles.
        'mine_menu_function' //A funcao de callback contendo o seu funcionamento.
    );
*/
function mine_menu_function(){ //Essa eh a funcao de call back da register submenu, programe aqui o conteudo da pagina de configuracao.
    echo "Configuracao de Plugin";
    opt_test_func();
}

 function options_plug_config_cp(){ //Essa funcao vai escrever no banco de dados, esses valores. A API Option eh explicada melhor abaixo.
       if (isset($_POST['PO_submit'])) { //Verifica se tem valor no post.
            /*
            (primeiro parametro eh a pagina, ou seja no caso o valor get da page na url, mas na maioria dos casos eh o slug mesmo, como eh o caso
              aqui, no caso o slug eh usado como o nome da pagina na maiora das vezes, o segundo parametro eh o campo nounce definido la no formulario,
                mais informacoes no gui.php, essa funcao ela interrompe o script em caso de um ataque CSRF 
            */
            check_admin_referer('mine_menu_plugin','campo_nonce'); //Protege contra ataques CSRF, melhor explicado no gui.php
            $nome     = sanitize_text_field($_POST['nome']); //o sanitize ele limpa um campo de texto.
            $email    = sanitize_email($_POST['email']); //o sanitize ele limpa um campo de email nesse caso.
            $apiKey   = sanitize_text_field($_POST['apikey']); //o sanitize ele limpa um campo de texto.
            $licenca  = sanitize_text_field($_POST['licenca']); //o sanitize ele limpa um campo de texto.
            //o update_option aqui ele escreve ou atualiza um novo atributo com valor no banco de dados.
            update_option('PO_Nome',    $nome); 
            update_option('PO_Email',   $email);
            update_option('PO_ApiKey',  $apiKey);
            update_option('PO_Licenca', $licenca);

            /*
                O sanitize ele limpa as strings, evitando assim uma sql injection, no casoseria um mecanismo de defesa contra isso.
    toda vez que uma pessoa tentar digitar como entrada de dados algo do tipo "'or 1=1" isso evita com que a query seja executada
    como um comando ao banco de dados, o escapando e transformando em algo "'\or 1\=1" por exemplo e evitando com que seja processado.
            */
        }
    }
  


function mine_menu_main_function(){ //Essa eh a funcao de call back da register menu, programe aqui o conteudo da pagina de configuracao.
    options_plug_config_cp(); //chama a funcao acima.
    include_once('page/gui.php'); //inclui um arquivo externo chamado 'gui.php'.
}

function opt_test_func(){ //Essa funcao eh chamada por um submenu e nao o menu principal.
    //Essas informacoes sao exibidas no submenu dentro das configuracoes.
    echo "<h1>Página de configuração do plugin (menu principal), dados referente ao Wordpress e plugin</h1>";

	echo "<h3>Informações bloginfo</h3>";
	echo bloginfo('name') . "<br>"; //retorna o nome do blog
	echo bloginfo('description') . "<br>"; //retorna a descricao.
	echo bloginfo('url') . "<br>"; //retorna a url
	echo bloginfo('language') . "<br>"; //retorna o idioma configurado pelo usuario.
	echo bloginfo('version') . "<br>"; //retorna a versao.

	echo "<h3>Informações de constantes</h3>";
	echo WP_CONTENT_DIR . "<br>"; //O diretorio completo do sistema ex: /public_html/
	echo WP_CONTENT_URL . "<br>";// a url ate a pasta aonde esta o arquivo.
	echo WP_PLUGIN_DIR . "<br>"; //diretorio do sistema da pasta plugins
	echo WP_PLUGIN_URL . "<br>"; //url ate a pasta de plugins

	echo "<h3>Informações de funções de plugin</h3>";
	echo plugin_basename(__DIR__) . "<br>"; //o nome da pasta de plugin
	echo plugin_basename(__FILE__) . "<br>"; //o nome da pasta do plugin/o arquivo que esta executando esse script. 
	echo plugin_dir_url(__FILE__) . "<br>"; //url completa ate a pasta do plugin.
	echo plugin_dir_path(__FILE__) . "<br>"; //diretorio do sistema ate a pasta do plugin

	echo "<h3>Informações de funções do WordPress</h3>";
	echo admin_url() . "<br>"; //retorna a url ate a pasta admin.
	echo site_url() . "<br>"; //retorna a url ate o site.
	print_r(wp_upload_dir()); //retorna um array referente a pasta de upload

    //o add_option precisa de dois parametros, o atributo a ser salvo no banco de dados e o valor.
    add_option('myAttribute','MyValue');
    add_option('myAttribute2','MyValue');
    //O get_option ele retorna o valor do atributo, no caso voce passa o atributo e ele retorna o valor.
    echo '<br>'.get_option('myAttribute');
    //quando o get_option recebe dois parametros, ele usa o segundo como valor, caso nao encontre esse atributo no banco de dados.
    echo '<br>'.get_option('myAttr2','Valor a ser exibido quando nao existe');
    //o update_ele atualiza um valor no banco de dados, repare tambem que eh possivel gravar um array.
    update_option('myAttribute',['MyValue1','MyValue2','MyValue3','MyValue4']);
    //essa funcao deleta um atributo.
    delete_option('myAttribute2');

    //Transient API registra dados de maneira temporaria.
    //essa funcao registra dados de maneira temporaria, no caso atributo, valor, quantidade de milissegundos que vai ficar registrado.
    set_transient("atributo",'meu transient valor',60*3);    
    //pega o valor salvo pelo set_transient, essa funcao retorna false quando esta vazia.
    echo '<br>'.get_transient("atributo");
    //apaga o valor salvo pelo transient.
    delete_transient("atributo");
}

include('settings-api.php'); //importando a settings api.
include('custom-post.php'); //Nesse arquivo contem todas as informacoes, referentes a custom post, como metabox, taxionomia, e etc...
include('dashboard-widget.php'); //Nesse arquivos contem o dashbord widgets. O "curso registrado que aparece nos detalhes do curso no menu principal."
include('widget-api.php'); //Nesse arquivo contem o widget criado.
include('shortcode-api.php');//Nesse arquivo contem os Shortcodes.
include('traducao.php'); //Esse arquivo refere-se a internacionalizacao.
//O arquivo wpdb-dbdelta eh um plugin independente, devido a um erro que da ao integrar a este, o arquivo eh: wpdb-dbdelta.php
