<?php 
//Aprofundamento: https://codex.wordpress.org/Settings_API 
 
//aqui eh feito o registro de menu.
add_action('admin_menu', 'registrar_menu'); 
 
function registrar_menu() { 
 
add_menu_page( 
  'Página de configuração', 
  'Configurações Plugin', 
  'manage_options', 
  'settings_plug_config', 
  'settings_plug_config_cp', 
  'dashicons-hammer', 
  85 
  ); 
 
} 
//Fim registro de menu. 

 
function settings_plug_config_cp(){//funcao de callback
 
  require_once('page/admin_form.php'); //o include do formulario do Settings API, veja ele para mais informacoes.
 
}

//Voce precisa adicionar essa action abaixo para Comecar a usar a Action API.
add_action('admin_init', 'add_sections_and_fields_cb');

function add_sections_and_fields_cb() { //A funcao de callback, indicada na action acima.
	add_settings_section( //Adiciona a secao de configuracao.
		'section_principal', //identificador unico da secao.
		'Configurações de registro', //O titulo a ser exibido dessa secao.
		'exibe_section_plug_cb', //Funcao de callback a ser executado.
		'PO_dados_registro' //O nome a ser salvo no banco de dados
	);

	register_setting(//funcao para registrar as configuracoes.
		'PO_dados_registro', //Aqui voce deve informar o slug do identificador de registro.
		'PO_dados_registro'//Nome a ser salvo no Banco de Dados.
	);

	add_settings_field(
		'PO_Nome', //Nome do Campo
		'Nome', //Label do campo.
		'input_nome_cb', //funcao de callback.
		'PO_dados_registro',
		'section_principal'
	);

	add_settings_field(
		'PO_Email', //Nome do Campo
		'Email', //Label do campo.
		'input_email_cb',
		'PO_dados_registro',
		'section_principal'
	);

}

function exibe_section_plug_cb() {
	echo "<h2>Dados de registro do Plugin</h2>";
}

function input_nome_cb(){
    //Pega todos os dados de registros no BD, no caso os criado acima.
	$configuracoes = get_option('PO_dados_registro');
    //Verifica se o indice PO_NOME dento do array tem valor, caso nao retorna string vazia.
	$PO_Nome = isset( $configuracoes['PO_Nome'] ) ? $configuracoes['PO_Nome'] : '';

	echo '<input class="regular-text" name="PO_dados_registro[PO_Nome]" value="' . esc_attr($PO_Nome) . '" type="text"';
}

function input_email_cb(){
    //Pega todos os dados de registros no BD, no caso os criado acima.
	$configuracoes = get_option('PO_dados_registro');
//Verifica se o indice PO_NOME dento do array tem valor, caso nao retorna string vazia.
	$PO_Email = isset( $configuracoes['PO_Email'] ) ? $configuracoes['PO_Email'] : '';

	echo '<input class="regular-text" name="PO_dados_registro[PO_Email]" value="' . esc_attr($PO_Email) . '" type="text"';
}
//Ate aqui era o menu configurado acima, daqui pr baixo eh configurado um campo dentro do settings no Wordpress.

//Adiciona configuação a página existente no Wordpress
//chamasse a action
add_action('admin_init', 'add_sections_and_fields_config_cb'); 
 //cria uma funcao de callback, como no exemplo acima.
function add_sections_and_fields_config_cb() { 

/*

    Todas as configuracoes sao usadas usando a settings api, no caso aqui eh para colocar um campo a mais nas configuracoes no menu do wordpress, no caso aqui sera colocado na pagina de settings, mas dentro das opcoes gerais.

*/ 


add_settings_section( 
        'treinaweb_plugin_section', 
        'Opções Treinaweb Plugin', 
        'exibe_section_plug_config_cb', 
        'general' //repare que eh usada o general, o mesmo Slug do arquivo options.php isso eh tudo o que muda com relacao acima
    ); 
 
add_settings_field( 
        'treinaweb_config', 
        'Configuração Treinaweb', 
        'input_treinaweb_config_cb', 
        'general', 
        'treinaweb_plugin_section' 
    ); 
 
register_setting( 
      'general', 
      'treinaweb_config' //aqui eh alterado a forma como salva do BD com realacao acima. 
  ); 
} 
 
function exibe_section_plug_config_cb() { 
	echo '<p class="description">Seção Personalizada</p>'; 
} 
 
function input_treinaweb_config_cb() { 
    $config = get_option( 'treinaweb_config' ); 
 
	echo '<input class="regular-text" name="treinaweb_config" value="' .  $config  . '" type="text">'; 
}















