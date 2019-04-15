<form method="post" action="">
	<h1>Configurações do plugin</h1>
    <!--
        wp_nounce_field('o slug ou o nome da pagina','o nome do campo oculto, preste atencao aqui que deve ser o mesmo nome na funcao check_admin_referer'),
    deve ser colocado fora da table, antes da table no caso. Essa medida visa proteger o site contra o CSRF que eh uma falsificacao da validacao de sites, no caso com isso estaremos garantindo que qualquer alteracao que vai ao banco de dados vai se submeter as regras do WP. e que apenas esse formulario validado vai pode alterar no banco de dados e nao um outro falsificado que mande uma requisicao igual a esse.
    -->

    <?php wp_nonce_field('mine_menu_plugin','campo_nounce'); ?>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">
				<label for="tablecell">Nome: </label>
			</th>
			<td>
				<input type="type" name="nome" class="regular-text" value="<?= esc_attr(get_option('PO_Nome', '')) ?>">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="tablecell">Email: </label>
			</th>
			<td>
				<input type="type" name="email" class="regular-text" value="<?= esc_attr(get_option('PO_Email', '')) ?>">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="tablecell">API key: </label>
			</th>
			<td>
				<input type="type" name="apikey" class="regular-text" value="<?= esc_attr(get_option('PO_ApiKey', '')) ?>">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="tablecell">Tipo de licença: </label>
			</th>
			<td>
				<select name="licenca">
					<option value="basica" <?= selected( esc_attr(get_option('PO_Licenca')), 'basica') ?>>Básica</option>
					<option value="intermediaria" <?= selected( esc_attr(get_option('PO_Licenca')), 'intermediaria') ?>>Intermediária</option>
					<option value="avancada" <?= selected( esc_attr(get_option('PO_Licenca'), 'avancada')) ?>>Avançada</option>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="tablecell"></label>
			</th>
			<td>
				<input class="button-primary" type="submit" name="PO_submit" value="Salvar">
			</td>
		</tr>
		
	</table>
    <!--
        O scape_attr() ele escapa caracteres, na exibicao evitando com que se abre brechas para usar esses inputs como bucha de canhao para executar scripts maliciosos, enquanto que o sanitize ele escapa na entrada, esse faz isso na saida do banco de dados.
mais informacoes, sanitize: https://developer.wordpress.org/reference/functions/sanitize_text_field/
esc_attr: https://developer.wordpress.org/reference/functions/esc_attr/

nounce fields: https://codex.wordpress.org/Function_Reference/wp_nonce_field
    -->

</form>

