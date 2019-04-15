<!-- Todas as requisicoes quando usado a Settings API deve ser feito para essa pagina 'options.php' via post-->
<form method="post" action="options.php">
	
	<table class="form-table">
		<?php do_settings_sections('PO_dados_registro'); //exibe a sessao criada na add_settings_section(), repare que o nome eh um atributo que informado la, veja o arquivo settings-api.php no diretorio raiz do plugin para mais informacao. ?>
		<?php settings_fields('PO_dados_registro');//Recebe o conjunto de configuracoes com os campos previamente configurado. ?>
		<?php submit_button(); //Essa funcao retorna o submit buttom custumizado pelo WP. ?>
	</table>

</form>
