<?php
if (!defined('ABSPATH')) {
    exit;
}

function search_advanced_filter_shortcode($atts) {
    // Obtener términos de cada taxonomía
    $marcas = get_terms(array(
        'taxonomy' => 'marca',
        'hide_empty' => false,
    ));
    $modelos = get_terms(array(
        'taxonomy' => 'modelo',
        'hide_empty' => false,
    ));
    $años = get_terms(array(
        'taxonomy' => 'ano',
        'hide_empty' => false,
    ));

    // Verificar si get_terms no devolvió errores y los términos están en forma de objetos
    if (is_array($marcas) && !empty($marcas) && isset($marcas[0]->slug)) {
        $marcas_array = $marcas;
    } else {
        $marcas_array = [];
    }

    if (is_array($modelos) && !empty($modelos) && isset($modelos[0]->slug)) {
        $modelos_array = $modelos;
    } else {
        $modelos_array = [];
    }

    if (is_array($años) && !empty($años) && isset($años[0]->slug)) {
        $años_array = $años;
    } else {
        $años_array = [];
    }

    // HTML del formulario de selección
    ob_start();
    ?>
    <form id="search-advanced-filter-form" method="GET">
        <select name="marca" id="marca">
            <option value="">Selecciona una marca</option>
            <?php foreach ($marcas_array as $marca) : ?>
                <option value="<?php echo esc_attr($marca->slug); ?>"><?php echo esc_html($marca->name); ?></option>
            <?php endforeach; ?>
        </select>

        <select name="modelo" id="modelo">
            <option value="">Selecciona un modelo</option>
            <?php foreach ($modelos_array as $modelo) : ?>
                <option value="<?php echo esc_attr($modelo->slug); ?>"><?php echo esc_html($modelo->name); ?></option>
            <?php endforeach; ?>
        </select>

        <select name="ano" id="ano">
            <option value="">Selecciona un año</option>
            <?php foreach ($años_array as $año) : ?>
                <option value="<?php echo esc_attr($año->slug); ?>"><?php echo esc_html($año->name); ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <div id="filtered-results">
        <?php
        if (isset($_GET['marca']) || isset($_GET['modelo']) || isset($_GET['ano'])) {
            // Parámetros de la consulta
            $args = array(
                'post_type' => 'product',
                'tax_query' => array(
                    'relation' => 'AND',
                ),
            );

            if (!empty($_GET['marca'])) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'marca',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['marca']),
                );
            }

            if (!empty($_GET['modelo'])) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'modelo',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['modelo']),
                );
            }

            if (!empty($_GET['ano'])) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'ano',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['ano']),
                );
            }

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                echo '<ul>';
                while ($query->have_posts()) {
                    $query->the_post();
                    echo '<li>' . get_the_title() . '</li>';
                }
                echo '</ul>';
                wp_reset_postdata();
            } else {
                echo 'No se encontraron productos.';
            }
        }
        ?>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('search_advanced_filter', 'search_advanced_filter_shortcode');
