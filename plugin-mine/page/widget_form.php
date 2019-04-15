<!--
    Os metodos get_field_id() e get_field_name, eles sao declarados na classe pai desse widget e as suas funcoes sao criar um name e um ID unico e integrado com a classe, no caso para que possa ser usados no metodo form(), widget() e update() se faz necessario gerar um id e um name por eles. Lembrando que essa pagina e um include da nossa classe widget, entao o 'this' esta fazendo referencia a essa propria classe que criamos. O esc_attr() ele escapa os campos vindos do banco de dados.
-->
<p>
	<label for="<?= $this->get_field_id('titulo')//Esse metodo retorna um id unico para esse campo. ?>">Título</label>
	<input type="text" name="<?= $this->get_field_name('titulo')//Esse metodo retorna um name unico para esse campo. ?>" id="<?= $this->get_field_id('titulo') ?>" 
			class="widefat" value="<?= esc_attr($titulo) ?>">
</p>
<p>
	<label for="<?= $this->get_field_id('titulo_link') ?>">Título do Link</label>
	<input type="text" name="<?= $this->get_field_name('titulo_link') ?>" id="<?= $this->get_field_id('titulo_link') ?>" 
			class="widefat" value="<?= esc_attr($titulo_link) ?>">
</p>
<p>
	<label for="<?= $this->get_field_id('link') ?>">Link</label>
	<input type="text" name="<?= $this->get_field_name('link') ?>" id="<?= $this->get_field_id('link') ?>" 
			class="widefat" value="<?= esc_attr($link) ?>">
</p>
