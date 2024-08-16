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
    <form id="safp-filter-form" method="GET">
        <select name="marca" id="safp-marca">
            <option value="">Selecciona una marca</option>
            <?php foreach ($marcas_array as $marca) : ?>
                <option value="<?php echo esc_attr($marca->slug); ?>"><?php echo esc_html($marca->name); ?></option>
            <?php endforeach; ?>
        </select>

        <select name="modelo" id="safp-modelo">
            <option value="">Selecciona un modelo</option>
            <?php foreach ($modelos_array as $modelo) : ?>
                <option value="<?php echo esc_attr($modelo->slug); ?>"><?php echo esc_html($modelo->name); ?></option>
            <?php endforeach; ?>
        </select>

        <select name="ano" id="safp-ano">
            <option value="">Selecciona un año</option>
            <?php foreach ($años_array as $año) : ?>
                <option value="<?php echo esc_attr($año->slug); ?>"><?php echo esc_html($año->name); ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <div id="safp-filtered-results">
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
                echo '<ul class="safp-products">';
                while ($query->have_posts()) {
                    $query->the_post();
                    global $product;

                    $price = $product->get_price_html();
                    $rating = $product->get_average_rating();
                    $image = get_the_post_thumbnail($product->get_id(), 'thumbnail');
                    $add_to_cart_url = $product->add_to_cart_url();
                    ?>
                    <li class="safp-product-item">
                        <a href="<?php the_permalink(); ?>" class="safp-product-link">
                            <?php echo $image; ?>
                            <h2 class="safp-product-title"><?php the_title(); ?></h2>
                            <?php if ($rating) : ?>
                                <div class="safp-product-rating">
                                    <?php echo wc_get_rating_html($rating); ?>
                                </div>
                            <?php endif; ?>
                            <div class="safp-product-price">
                                <?php echo $price; ?>
                            </div>
                            <a href="<?php echo esc_url($add_to_cart_url); ?>" class="safp-add-to-cart-button">Añadir al carrito</a>
                        </a>
                    </li>
                    <?php
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
